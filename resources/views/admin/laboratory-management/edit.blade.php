@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Edit Laboratory') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Update hospital user information') }}</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('admin.laboratory-management.update', $laboratory) }}"
                      x-data="{ 
                          cities: [],
                          init() {
                              @if(old('province_id', $laboratory->province_id))
                                  fetch('/api/cities?province_id={{ old('province_id', $laboratory->province_id) }}')
                                      .then(response => response.json())
                                      .then(data => {
                                          cities = data;
                                          @if(old('city_id', $laboratory->city_id))
                                              document.getElementById('city_id').value = {{ old('city_id', $laboratory->city_id) }};
                                          @endif
                                      })
                                      .catch(() => {});
                              @endif
                          }
                      }">
                    @csrf
                    @method('PUT')

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
                                :value="old('full_name', $laboratory->user->full_name)" 
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
                                :value="old('email', $laboratory->user->email)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password (Optional) -->
                        <div>
                            <x-input-label for="password" :value="__('admin.Password')" />
                            <x-text-input 
                                id="password" 
                                name="password" 
                                type="password" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                placeholder="{{ __('admin.Leave blank to keep current password') }}"
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.Leave blank to keep current password') }}</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Laboratory Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Hospital Name -->
                        <div>
                            <x-input-label for="laboratory_name" :value="__('admin.Hospital Name')" />
                            <x-text-input 
                                id="laboratory_name" 
                                name="laboratory_name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('laboratory_name', $laboratory->laboratory_name)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('laboratory_name')" class="mt-2" />
                        </div>

                        <!-- Hospital Code -->
                        <div>
                            <x-input-label for="laboratory_code" :value="__('admin.Hospital Code')" />
                            <x-text-input 
                                id="laboratory_code" 
                                name="laboratory_code" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('laboratory_code', $laboratory->laboratory_code)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('laboratory_code')" class="mt-2" />
                        </div>

                        <!-- Contact Person Name -->
                        <div>
                            <x-input-label for="contact_person_name" :value="__('admin.Contact Person Name')" />
                            <x-text-input 
                                id="contact_person_name" 
                                name="contact_person_name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('contact_person_name', $laboratory->contact_person_name)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('contact_person_name')" class="mt-2" />
                        </div>

                        <!-- License Number -->
                        <div>
                            <x-input-label for="license_number" :value="__('admin.License Number')" />
                            <x-text-input 
                                id="license_number" 
                                name="license_number" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('license_number', $laboratory->license_number)"
                            />
                            <x-input-error :messages="$errors->get('license_number')" class="mt-2" />
                        </div>

                        <!-- Mobile Number -->
                        <div>
                            <x-input-label for="mobile_number" :value="__('admin.Mobile Number')" />
                            <x-text-input 
                                id="mobile_number" 
                                name="mobile_number" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('mobile_number', $laboratory->mobile_number)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <x-input-label for="phone_number" :value="__('admin.Phone Number')" />
                            <x-text-input 
                                id="phone_number" 
                                name="phone_number" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('phone_number', $laboratory->phone_number)"
                            />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
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
                                    <option value="{{ $province->id }}" {{ old('province_id', $laboratory->province_id) == $province->id ? 'selected' : '' }}>
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
                                    <option :value="city.id" x-text="city.name" :selected="city.id == {{ old('city_id', $laboratory->city_id) }}"></option>
                                </template>
                            </x-select>
                            <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('admin.Status')" />
                            <x-select 
                                id="status" 
                                name="status" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="0" {{ old('status', $laboratory->status) == 0 ? 'selected' : '' }}>{{ __('admin.Pending') }}</option>
                                <option value="1" {{ old('status', $laboratory->status) == 1 ? 'selected' : '' }}>{{ __('admin.Active') }}</option>
                                <option value="2" {{ old('status', $laboratory->status) == 2 ? 'selected' : '' }}>{{ __('admin.Inactive') }}</option>
                                <option value="3" {{ old('status', $laboratory->status) == 3 ? 'selected' : '' }}>{{ __('admin.Verified') }}</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
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
                        >{{ old('address', $laboratory->address) }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center {{ $isRtl ? 'justify-start' : 'justify-end' }} gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.laboratory-management.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Update Laboratory') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

