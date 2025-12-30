@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Create Receiver') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Add a new blood receiver to the system') }}</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('admin.receiver-management.store') }}"
                      x-data="{ 
                          cities: [],
                          init() {
                              @if(old('province_id'))
                                  fetch('/api/cities?province_id={{ old('province_id') }}')
                                      .then(response => response.json())
                                      .then(data => this.cities = data)
                                      .catch(() => {});
                              @endif
                          }
                      }">
                    @csrf

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.User Information') }}</h2>
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Full Name -->
                        <div class="mb-6">
                            <x-input-label for="full_name" :value="__('admin.Full Name')" />
                            <x-text-input 
                                id="full_name" 
                                name="full_name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('full_name')" 
                                required 
                                autofocus 
                            />
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <x-input-label for="email" :value="__('admin.Email')" />
                            <x-text-input 
                                id="email" 
                                name="email" 
                                type="email" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('email')" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <x-input-label for="password" :value="__('admin.Password')" />
                            <x-text-input 
                                id="password" 
                                name="password" 
                                type="password" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required 
                            />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('admin.Confirm Password')" />
                            <x-text-input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required 
                            />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Receiver Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mobile Number -->
                        <div>
                            <x-input-label for="mobile_number" :value="__('admin.Mobile Number')" />
                            <x-text-input 
                                id="mobile_number" 
                                name="mobile_number" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('mobile_number')" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
                        </div>

                        <!-- National Code -->
                        <div>
                            <x-input-label for="national_code" :value="__('admin.National Code')" />
                            <x-text-input 
                                id="national_code" 
                                name="national_code" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('national_code')" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('national_code')" class="mt-2" />
                        </div>

                        <!-- Age -->
                        <div>
                            <x-input-label for="age" :value="__('admin.Age')" />
                            <x-text-input 
                                id="age" 
                                name="age" 
                                type="number" 
                                min="18"
                                max="100"
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('age')" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('age')" class="mt-2" />
                        </div>

                        <!-- Gender -->
                        <div>
                            <x-input-label for="gender" :value="__('admin.Gender')" />
                            <x-select 
                                id="gender" 
                                name="gender" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('admin.Select Gender') }}</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('admin.Male') }}</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('admin.Female') }}</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>{{ __('admin.Other') }}</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        <!-- Province -->
                        <div>
                            <x-input-label for="province_id" :value="__('admin.Province')" />
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
                                <option value="">{{ __('admin.Select Province') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('province_id')" class="mt-2" />
                        </div>

                        <!-- City -->
                        <div>
                            <x-input-label for="city_id" :value="__('admin.City')" />
                            <x-select 
                                id="city_id" 
                                name="city_id" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('admin.Select City') }}</option>
                                <template x-for="city in cities" :key="city.id">
                                    <option :value="city.id" x-text="city.name"></option>
                                </template>
                            </x-select>
                            <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
                        </div>

                        <!-- Blood Type -->
                        <div>
                            <x-input-label for="blood_type" :value="__('admin.Blood Type')" />
                            <x-select 
                                id="blood_type" 
                                name="blood_type" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('admin.Select Blood Type') }}</option>
                                <option value="A" {{ old('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('blood_type')" class="mt-2" />
                        </div>

                        <!-- RH Factor -->
                        <div>
                            <x-input-label for="rh_factor" :value="__('admin.RH Factor')" />
                            <x-select 
                                id="rh_factor" 
                                name="rh_factor" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('admin.Select RH Factor') }}</option>
                                <option value="positive" {{ old('rh_factor') == 'positive' ? 'selected' : '' }}>{{ __('admin.Positive') }}</option>
                                <option value="negative" {{ old('rh_factor') == 'negative' ? 'selected' : '' }}>{{ __('admin.Negative') }}</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('rh_factor')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-6">
                        <x-input-label for="address" :value="__('admin.Address')" />
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="3"
                            class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                            required
                        >{{ old('address') }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center {{ $isRtl ? 'justify-start' : 'justify-end' }} gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.receiver-management.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Create Receiver') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

