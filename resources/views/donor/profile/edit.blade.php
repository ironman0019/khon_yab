@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('donor.Edit Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('donor.Edit Donor Profile') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('donor.Update your personal information') }}</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 text-green-700 dark:text-green-400 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('donor.profile.update') }}"
                      x-data="{ 
                          cities: @js($cities),
                          init() {
                              @if(old('province_id', $donor->province_id))
                                  fetch('/api/cities?province_id={{ old('province_id', $donor->province_id) }}')
                                      .then(response => response.json())
                                      .then(data => this.cities = data)
                                      .catch(() => {});
                              @endif
                          }
                      }">
                    @csrf
                    @method('PATCH')

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('donor.Personal Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Full Name -->
                        <div>
                            <x-input-label for="full_name" :value="__('donor.Full Name')" />
                            <x-text-input 
                                id="full_name" 
                                name="full_name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                value="{{ old('full_name', $donor->user->full_name ?? Auth::user()->full_name) }}" 
                            />
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>

                        <!-- Mobile Number -->
                        <div>
                            <x-input-label for="mobile_number" :value="__('donor.Mobile Number')" />
                            <x-text-input 
                                id="mobile_number" 
                                name="mobile_number" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('mobile_number', $donor->mobile_number)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
                        </div>

                        <!-- Age -->
                        <div>
                            <x-input-label for="age" :value="__('donor.Age')" />
                            <x-text-input 
                                id="age" 
                                name="age" 
                                type="number" 
                                min="18"
                                max="120"
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('age', $donor->age)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('age')" class="mt-2" />
                        </div>

                        <!-- Gender (Read-only) -->
                        <div>
                            <x-input-label for="gender" :value="__('donor.Gender')" />
                            <x-text-input 
                                id="gender" 
                                name="gender" 
                                type="text" 
                                class="block mt-1 w-full border-gray-300 bg-gray-100 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-400"
                                :value="$donor->gender" 
                                disabled
                            />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('donor.This field cannot be changed') }}</p>
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('donor.Location Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Province -->
                        <div>
                            <x-input-label for="province_id" :value="__('donor.Province')" />
                            <x-select 
                                id="province_id" 
                                name="province_id" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                                x-on:change="
                                    if ($event.target.value) {
                                        fetch('/api/cities?province_id=' + $event.target.value)
                                            .then(response => response.json())
                                            .then(data => {
                                                cities = data;
                                                document.getElementById('city_id').value = '';
                                            })
                                            .catch(() => cities = []);
                                    } else {
                                        cities = [];
                                    }
                                "
                            >
                                <option value="">{{ __('donor.Select Province') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ old('province_id', $donor->province_id) == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('province_id')" class="mt-2" />
                        </div>

                        <!-- City -->
                        <div>
                            <x-input-label for="city_id" :value="__('donor.City')" />
                            <x-select 
                                id="city_id" 
                                name="city_id" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('donor.Select City') }}</option>
                                <template x-for="city in cities" :key="city.id">
                                    <option :value="city.id" x-text="city.name" :selected="city.id == '{{ old('city_id', $donor->city_id) }}'"></option>
                                </template>
                            </x-select>
                            <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <x-input-label for="address" :value="__('donor.Address')" />
                            <textarea 
                                id="address" 
                                name="address" 
                                rows="3"
                                class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                required
                            >{{ old('address', $donor->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('donor.Blood Information (Read-only)') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- National Code (Read-only) -->
                        <div>
                            <x-input-label for="national_code" :value="__('donor.National Code')" />
                            <x-text-input 
                                id="national_code" 
                                name="national_code" 
                                type="text" 
                                class="block mt-1 w-full border-gray-300 bg-gray-100 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-400"
                                :value="$donor->national_code" 
                                disabled
                            />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('donor.This field cannot be changed') }}</p>
                        </div>

                        <!-- Blood Type (Read-only) -->
                        <div>
                            <x-input-label for="blood_type" :value="__('donor.Blood Type')" />
                            <x-text-input 
                                id="blood_type" 
                                name="blood_type" 
                                type="text" 
                                class="block mt-1 w-full border-gray-300 bg-gray-100 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-400"
                                :value="$donor->blood_type" 
                                disabled
                            />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('donor.This field cannot be changed') }}</p>
                        </div>

                        <!-- RH Factor (Read-only) -->
                        <div>
                            <x-input-label for="rh_factor" :value="__('donor.RH Factor')" />
                            <x-text-input 
                                id="rh_factor" 
                                name="rh_factor" 
                                type="text" 
                                class="block mt-1 w-full border-gray-300 bg-gray-100 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-400"
                                :value="$donor->rh_factor" 
                                disabled
                            />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('donor.This field cannot be changed') }}</p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center {{ $isRtl ? 'flex-row-reverse justify-start' : 'justify-end' }} gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('donor.dashboard.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('donor.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('donor.Update Profile') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

