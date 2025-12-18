<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" 
          x-data="{ 
              userType: {{ old('user_type', 0) }}, 
              cities: [],
              hospitalCities: [],
              init() {
                  @if(old('province_id'))
                      fetch('/api/cities?province_id={{ old('province_id') }}')
                          .then(response => response.json())
                          .then(data => this.cities = data)
                          .catch(() => {});
                  @endif
                  @if(old('hospital_province_id'))
                      fetch('/api/cities?province_id={{ old('hospital_province_id') }}')
                          .then(response => response.json())
                          .then(data => this.hospitalCities = data)
                          .catch(() => {});
                  @endif
              }
          }">
        @csrf

        <!-- Full Name -->
        <div>
            <x-input-label for="full_name" :value="__('auth.Full Name')" />
            <x-text-input id="full_name" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                          type="text" 
                          name="full_name" 
                          :value="old('full_name')" 
                          required 
                          autofocus 
                          autocomplete="name" />
            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('auth.Email')" />
            <x-text-input id="email" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required 
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- User Type -->
        <div class="mt-4">
            <x-input-label for="user_type" :value="__('auth.User Type')" />
            <x-select id="user_type" 
                      name="user_type" 
                      class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                      x-model="userType"
                      required>
                <option value="0" {{ old('user_type', '') == '0' ? 'selected' : '' }}>{{ __('auth.User') }}</option>
                <option value="1" {{ old('user_type', '') == '1' ? 'selected' : '' }}>{{ __('auth.Donor') }}</option>
                <option value="2" {{ old('user_type', '') == '2' ? 'selected' : '' }}>{{ __('auth.Hospital User') }}</option>
            </x-select>
            <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
        </div>

        <!-- Donor Fields (Conditional) -->
        <div x-show="userType == 1" x-transition style="display: none;">
            <!-- Mobile Number -->
            <div class="mt-4">
                <x-input-label for="mobile_number" :value="__('auth.Mobile Number')" />
                <x-text-input id="mobile_number" 
                              class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                              type="text" 
                              name="mobile_number" 
                              :value="old('mobile_number')" 
                              x-bind:required="userType == 1"
                              autocomplete="tel" />
                <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
            </div>

            <!-- National Code -->
            <div class="mt-4">
                <x-input-label for="national_code" :value="__('auth.National Code')" />
                <x-text-input id="national_code" 
                              class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                              type="text" 
                              name="national_code" 
                              :value="old('national_code')" 
                              x-bind:required="userType == 1" />
                <x-input-error :messages="$errors->get('national_code')" class="mt-2" />
            </div>

            <!-- Age -->
            <div class="mt-4">
                <x-input-label for="age" :value="__('auth.Age')" />
                <x-text-input id="age" 
                              class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                              type="number" 
                              name="age" 
                              :value="old('age')" 
                              min="18"
                              max="100"
                              x-bind:required="userType == 1" />
                <x-input-error :messages="$errors->get('age')" class="mt-2" />
            </div>

            <!-- Gender -->
            <div class="mt-4">
                <x-input-label for="gender" :value="__('auth.Gender')" />
                <x-select id="gender" 
                          name="gender" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                          x-bind:required="userType == 1">
                    <option value="">{{ __('auth.Select Gender') }}</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('auth.Male') }}</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('auth.Female') }}</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>{{ __('auth.Other') }}</option>
                </x-select>
                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
            </div>

            <!-- Province -->
            <div class="mt-4">
                <x-input-label for="province_id" :value="__('auth.Province')" />
                <x-select id="province_id" 
                          name="province_id" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                          x-bind:required="userType == 1"
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
                          ">
                    <option value="">{{ __('auth.Select Province') }}</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </x-select>
                <x-input-error :messages="$errors->get('province_id')" class="mt-2" />
            </div>

            <!-- City -->
            <div class="mt-4">
                <x-input-label for="city_id" :value="__('auth.City')" />
                <x-select id="city_id" 
                          name="city_id" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                          x-bind:required="userType == 1">
                    <option value="">{{ __('auth.Select City') }}</option>
                    <template x-for="city in cities" :key="city.id">
                        <option :value="city.id" x-text="city.name"></option>
                    </template>
                </x-select>
                <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
            </div>

            <!-- Address -->
            <div class="mt-4">
                <x-input-label for="address" :value="__('auth.Address')" />
                <textarea id="address" 
                          name="address" 
                          class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                          rows="3"
                          x-bind:required="userType == 1">{{ old('address') }}</textarea>
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <!-- Blood Type -->
            <div class="mt-4">
                <x-input-label for="blood_type" :value="__('auth.Blood Type')" />
                <x-select id="blood_type" 
                          name="blood_type" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                          x-bind:required="userType == 1">
                    <option value="">{{ __('auth.Select Blood Type') }}</option>
                    <option value="A" {{ old('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ old('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="AB" {{ old('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                    <option value="O" {{ old('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                </x-select>
                <x-input-error :messages="$errors->get('blood_type')" class="mt-2" />
            </div>

            <!-- RH Factor -->
            <div class="mt-4">
                <x-input-label for="rh_factor" :value="__('auth.RH Factor')" />
                <x-select id="rh_factor" 
                          name="rh_factor" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                          x-bind:required="userType == 1">
                    <option value="">{{ __('auth.Select RH Factor') }}</option>
                    <option value="positive" {{ old('rh_factor') == 'positive' ? 'selected' : '' }}>{{ __('auth.Positive') }}</option>
                    <option value="negative" {{ old('rh_factor') == 'negative' ? 'selected' : '' }}>{{ __('auth.Negative') }}</option>
                </x-select>
                <x-input-error :messages="$errors->get('rh_factor')" class="mt-2" />
            </div>
        </div>

        <!-- Hospital User Fields (Conditional) -->
        <div x-show="userType == 2" x-transition style="display: none;">
            <!-- Hospital Name -->
            <div class="mt-4">
                <x-input-label for="hospital_name" :value="__('auth.Hospital Name')" />
                <x-text-input id="hospital_name" 
                              class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                              type="text" 
                              name="hospital_name" 
                              :value="old('hospital_name')" 
                              x-bind:required="userType == 2" />
                <x-input-error :messages="$errors->get('hospital_name')" class="mt-2" />
            </div>

            <!-- Hospital Code -->
            <div class="mt-4">
                <x-input-label for="hospital_code" :value="__('auth.Hospital Code')" />
                <x-text-input id="hospital_code" 
                              class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                              type="text" 
                              name="hospital_code" 
                              :value="old('hospital_code')" 
                              x-bind:required="userType == 2" />
                <x-input-error :messages="$errors->get('hospital_code')" class="mt-2" />
            </div>

            <!-- Mobile Number -->
            <div class="mt-4">
                <x-input-label for="hospital_mobile_number" :value="__('auth.Mobile Number')" />
                <x-text-input id="hospital_mobile_number" 
                              class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                              type="text" 
                              name="hospital_mobile_number" 
                              :value="old('hospital_mobile_number')" 
                              x-bind:required="userType == 2"
                              autocomplete="tel" />
                <x-input-error :messages="$errors->get('hospital_mobile_number')" class="mt-2" />
            </div>

            <!-- Phone Number -->
            <div class="mt-4">
                <x-input-label for="hospital_phone_number" :value="__('auth.Phone Number')" />
                <x-text-input id="hospital_phone_number" 
                              class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                              type="text" 
                              name="hospital_phone_number" 
                              :value="old('hospital_phone_number')" 
                              autocomplete="tel" />
                <x-input-error :messages="$errors->get('hospital_phone_number')" class="mt-2" />
            </div>

            <!-- Province -->
            <div class="mt-4">
                <x-input-label for="hospital_province_id" :value="__('auth.Province')" />
                <x-select id="hospital_province_id" 
                          name="hospital_province_id" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                          x-bind:required="userType == 2"
                          x-on:change="
                              if ($event.target.value) {
                                  fetch('/api/cities?province_id=' + $event.target.value)
                                      .then(response => response.json())
                                      .then(data => {
                                          hospitalCities = data;
                                          document.getElementById('hospital_city_id').value = '';
                                      })
                                      .catch(() => hospitalCities = []);
                              } else {
                                  hospitalCities = [];
                              }
                          ">
                    <option value="">{{ __('auth.Select Province') }}</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" {{ old('hospital_province_id') == $province->id ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </x-select>
                <x-input-error :messages="$errors->get('hospital_province_id')" class="mt-2" />
            </div>

            <!-- City -->
            <div class="mt-4">
                <x-input-label for="hospital_city_id" :value="__('auth.City')" />
                <x-select id="hospital_city_id" 
                          name="hospital_city_id" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                          x-bind:required="userType == 2">
                    <option value="">{{ __('auth.Select City') }}</option>
                    <template x-for="city in hospitalCities" :key="city.id">
                        <option :value="city.id" x-text="city.name"></option>
                    </template>
                </x-select>
                <x-input-error :messages="$errors->get('hospital_city_id')" class="mt-2" />
            </div>

            <!-- Address -->
            <div class="mt-4">
                <x-input-label for="hospital_address" :value="__('auth.Address')" />
                <textarea id="hospital_address" 
                          name="hospital_address" 
                          class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                          rows="3"
                          x-bind:required="userType == 2">{{ old('hospital_address') }}</textarea>
                <x-input-error :messages="$errors->get('hospital_address')" class="mt-2" />
            </div>

            <!-- License Number -->
            <div class="mt-4">
                <x-input-label for="license_number" :value="__('auth.License Number')" />
                <x-text-input id="license_number" 
                              class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                              type="text" 
                              name="license_number" 
                              :value="old('license_number')" />
                <x-input-error :messages="$errors->get('license_number')" class="mt-2" />
            </div>

            <!-- Contact Person Name -->
            <div class="mt-4">
                <x-input-label for="contact_person_name" :value="__('auth.Contact Person Name')" />
                <x-text-input id="contact_person_name" 
                              class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                              type="text" 
                              name="contact_person_name" 
                              :value="old('contact_person_name')" 
                              x-bind:required="userType == 2" />
                <x-input-error :messages="$errors->get('contact_person_name')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('auth.Password')" />
            <x-text-input id="password" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                          type="password"
                          name="password"
                          required 
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('auth.Confirm Password')" />
            <x-text-input id="password_confirmation" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                          type="password"
                          name="password_confirmation" 
                          required 
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition-colors" 
               href="{{ route('login') }}">
                {{ __('auth.Already registered?') }}
            </a>

            <x-primary-button class="bg-red-600 hover:bg-red-700 focus:ring-red-500 dark:bg-red-700 dark:hover:bg-red-600">
                {{ __('auth.Register') }}
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>