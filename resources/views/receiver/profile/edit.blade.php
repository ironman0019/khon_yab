@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('receiver.Edit Receiver Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('receiver.Edit Receiver Profile') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('receiver.Update your receiver profile information') }}</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 text-green-700 dark:text-green-400 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('receiver.profile.update') }}"
                      x-data="{ 
                          cities: @js($cities),
                          init() {
                              @if(old('province_id', $receiver->province_id))
                                  fetch('/api/cities?province_id={{ old('province_id', $receiver->province_id) }}')
                                      .then(response => response.json())
                                      .then(data => this.cities = data)
                                      .catch(() => {});
                              @endif
                          }
                      }">
                    @csrf
                    @method('PATCH')

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('receiver.Personal Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Full Name -->
                        <div>
                            <x-input-label for="full_name" :value="__('receiver.Full Name')" />
                            <x-text-input 
                                id="full_name" 
                                name="full_name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                value="{{ old('full_name', $receiver->user->full_name ?? Auth::user()->full_name) }}" 
                            />
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>

                        <!-- Mobile Number -->
                        <div>
                            <x-input-label for="mobile_number" :value="__('receiver.Mobile Number')" />
                            <x-text-input 
                                id="mobile_number" 
                                name="mobile_number" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('mobile_number', $receiver->mobile_number)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
                        </div>

                        <!-- Age -->
                        <div>
                            <x-input-label for="age" :value="__('receiver.Age')" />
                            <x-text-input 
                                id="age" 
                                name="age" 
                                type="number" 
                                min="0"
                                max="120"
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('age', $receiver->age)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('age')" class="mt-2" />
                        </div>

                        <!-- Gender -->
                        <div>
                            <x-input-label for="gender" :value="__('receiver.Gender')" />
                            <x-select 
                                id="gender" 
                                name="gender" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('receiver.Select Gender') }}</option>
                                <option value="male" {{ old('gender', $receiver->gender) == 'male' ? 'selected' : '' }}>{{ __('receiver.Male') }}</option>
                                <option value="female" {{ old('gender', $receiver->gender) == 'female' ? 'selected' : '' }}>{{ __('receiver.Female') }}</option>
                                <option value="other" {{ old('gender', $receiver->gender) == 'other' ? 'selected' : '' }}>{{ __('receiver.Other') }}</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('receiver.Location Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Province -->
                        <div>
                            <x-input-label for="province_id" :value="__('receiver.Province')" />
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
                                <option value="">{{ __('receiver.Select Province') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ old('province_id', $receiver->province_id) == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('province_id')" class="mt-2" />
                        </div>

                        <!-- City -->
                        <div>
                            <x-input-label for="city_id" :value="__('receiver.City')" />
                            <x-select 
                                id="city_id" 
                                name="city_id" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('receiver.Select City') }}</option>
                                <template x-for="city in cities" :key="city.id">
                                    <option :value="city.id" x-text="city.name" :selected="city.id == '{{ old('city_id', $receiver->city_id) }}'"></option>
                                </template>
                            </x-select>
                            <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <x-input-label for="address" :value="__('receiver.Address')" />
                            <textarea 
                                id="address" 
                                name="address" 
                                rows="3"
                                class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                required
                            >{{ old('address', $receiver->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('receiver.Blood Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- National Code -->
                        <div>
                            <x-input-label for="national_code" :value="__('receiver.National Code')" />
                            <x-text-input 
                                id="national_code" 
                                name="national_code" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('national_code', $receiver->national_code)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('national_code')" class="mt-2" />
                        </div>

                        <!-- Blood Type -->
                        <div>
                            <x-input-label for="blood_type" :value="__('receiver.Blood Type')" />
                            <x-select 
                                id="blood_type" 
                                name="blood_type" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('receiver.Select Blood Type') }}</option>
                                <option value="A" {{ old('blood_type', $receiver->blood_type) == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('blood_type', $receiver->blood_type) == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('blood_type', $receiver->blood_type) == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('blood_type', $receiver->blood_type) == 'O' ? 'selected' : '' }}>O</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('blood_type')" class="mt-2" />
                        </div>

                        <!-- RH Factor -->
                        <div>
                            <x-input-label for="rh_factor" :value="__('receiver.RH Factor')" />
                            <x-select 
                                id="rh_factor" 
                                name="rh_factor" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('receiver.Select RH Factor') }}</option>
                                <option value="positive" {{ old('rh_factor', $receiver->rh_factor) == 'positive' ? 'selected' : '' }}>{{ __('receiver.Positive') }}</option>
                                <option value="negative" {{ old('rh_factor', $receiver->rh_factor) == 'negative' ? 'selected' : '' }}>{{ __('receiver.Negative') }}</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('rh_factor')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center {{ $isRtl ? 'flex-row-reverse justify-start' : 'justify-end' }} gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('receiver.dashboard.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('receiver.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('receiver.Update Profile') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

