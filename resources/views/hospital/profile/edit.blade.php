@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('hospital.Edit Hospital Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('hospital.Edit Hospital Profile') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('hospital.Update your hospital information') }}</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 text-green-700 dark:text-green-400 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route($updateRoute ?? 'hospital.profile.update') }}"
                      x-data="{ 
                          cities: @js($cities),
                          init() {
                              @if(old('province_id', $hospitalUser->province_id))
                                  fetch('/api/cities?province_id={{ old('province_id', $hospitalUser->province_id) }}')
                                      .then(response => response.json())
                                      .then(data => this.cities = data)
                                      .catch(() => {});
                              @endif
                          }
                      }">
                    @csrf
                    @method('PATCH')

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('hospital.Hospital Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Hospital Name -->
                        <div>
                            <x-input-label for="hospital_name" :value="__('hospital.Hospital Name')" />
                            <x-text-input 
                                id="hospital_name" 
                                name="hospital_name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('hospital_name', $hospitalUser->hospital_name)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('hospital_name')" class="mt-2" />
                        </div>

                        <!-- Hospital Code -->
                        <div>
                            <x-input-label for="hospital_code" :value="__('hospital.Hospital Code')" />
                            <x-text-input 
                                id="hospital_code" 
                                name="hospital_code" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('hospital_code', $hospitalUser->hospital_code)" 
                            />
                            <x-input-error :messages="$errors->get('hospital_code')" class="mt-2" />
                        </div>

                        <!-- License Number -->
                        <div>
                            <x-input-label for="license_number" :value="__('hospital.License Number')" />
                            <x-text-input 
                                id="license_number" 
                                name="license_number" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('license_number', $hospitalUser->license_number)" 
                            />
                            <x-input-error :messages="$errors->get('license_number')" class="mt-2" />
                        </div>

                        <!-- Contact Person Name -->
                        <div>
                            <x-input-label for="contact_person_name" :value="__('hospital.Contact Person Name')" />
                            <x-text-input 
                                id="contact_person_name" 
                                name="contact_person_name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('contact_person_name', $hospitalUser->contact_person_name)" 
                            />
                            <x-input-error :messages="$errors->get('contact_person_name')" class="mt-2" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('hospital.Contact Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Mobile Number -->
                        <div>
                            <x-input-label for="mobile_number" :value="__('hospital.Mobile Number')" />
                            <x-text-input 
                                id="mobile_number" 
                                name="mobile_number" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('mobile_number', $hospitalUser->mobile_number)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <x-input-label for="phone_number" :value="__('hospital.Phone Number')" />
                            <x-text-input 
                                id="phone_number" 
                                name="phone_number" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('phone_number', $hospitalUser->phone_number)" 
                            />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('hospital.Location Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Province -->
                        <div>
                            <x-input-label for="province_id" :value="__('hospital.Province')" />
                            <x-select 
                                id="province_id" 
                                name="province_id" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                x-on:change="fetch('/api/cities?province_id=' + $event.target.value)
                                    .then(response => response.json())
                                    .then(data => cities = data)
                                    .catch(() => cities = [])"
                                required
                            >
                                <option value="">{{ __('hospital.Select Province') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ old('province_id', $hospitalUser->province_id) == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('province_id')" class="mt-2" />
                        </div>

                        <!-- City -->
                        <div>
                            <x-input-label for="city_id" :value="__('hospital.City')" />
                            <x-select 
                                id="city_id" 
                                name="city_id" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('hospital.Select City') }}</option>
                                <template x-for="city in cities" :key="city.id">
                                    <option :value="city.id" x-text="city.name" :selected="city.id == {{ old('city_id', $hospitalUser->city_id) }}"></option>
                                </template>
                            </x-select>
                            <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <x-input-label for="address" :value="__('hospital.Address')" />
                            <x-textarea 
                                id="address" 
                                name="address" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                rows="3"
                                required
                            >{{ old('address', $hospitalUser->address) }}</x-textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex {{ $isRtl ? 'flex-row-reverse justify-start' : 'justify-end' }} gap-4">
                        <a href="{{ route('hospital.dashboard.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('hospital.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('hospital.Update Profile') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

