@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Edit Blood Request') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Update blood request information') }}</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('admin.blood-request-management.update', $bloodRequest) }}"
                      x-data="{ 
                          cities: [],
                          init() {
                              @if(old('province_id', $bloodRequest->province_id))
                                  fetch('/api/cities?province_id={{ old('province_id', $bloodRequest->province_id) }}')
                                      .then(response => response.json())
                                      .then(data => {
                                          cities = data;
                                          @if(old('city_id', $bloodRequest->city_id))
                                              document.getElementById('city_id').value = {{ old('city_id', $bloodRequest->city_id) }};
                                          @endif
                                      })
                                      .catch(() => {});
                              @endif
                          }
                      }">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Patient Name -->
                        <div>
                            <x-input-label for="patient_name" :value="__('admin.Patient Name')" />
                            <x-text-input 
                                id="patient_name" 
                                name="patient_name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('patient_name', $bloodRequest->patient_name)" 
                                required 
                                autofocus 
                            />
                            <x-input-error :messages="$errors->get('patient_name')" class="mt-2" />
                        </div>

                        <!-- Patient Age -->
                        <div>
                            <x-input-label for="patient_age" :value="__('admin.Patient Age')" />
                            <x-text-input 
                                id="patient_age" 
                                name="patient_age" 
                                type="number" 
                                min="0"
                                max="150"
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('patient_age', $bloodRequest->patient_age)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('patient_age')" class="mt-2" />
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
                                <option value="A" {{ old('blood_type', $bloodRequest->blood_type) == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('blood_type', $bloodRequest->blood_type) == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('blood_type', $bloodRequest->blood_type) == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('blood_type', $bloodRequest->blood_type) == 'O' ? 'selected' : '' }}>O</option>
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
                                <option value="positive" {{ old('rh_factor', $bloodRequest->rh_factor) == 'positive' ? 'selected' : '' }}>{{ __('admin.Positive') }}</option>
                                <option value="negative" {{ old('rh_factor', $bloodRequest->rh_factor) == 'negative' ? 'selected' : '' }}>{{ __('admin.Negative') }}</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('rh_factor')" class="mt-2" />
                        </div>

                        <!-- Number of Bags -->
                        <div>
                            <x-input-label for="number_of_bags" :value="__('admin.Number of Bags')" />
                            <x-text-input 
                                id="number_of_bags" 
                                name="number_of_bags" 
                                type="number" 
                                min="1"
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('number_of_bags', $bloodRequest->number_of_bags)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('number_of_bags')" class="mt-2" />
                        </div>

                        <!-- Medical Center -->
                        <div>
                            <x-input-label for="medical_center" :value="__('admin.Medical Center')" />
                            <x-text-input 
                                id="medical_center" 
                                name="medical_center" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('medical_center', $bloodRequest->medical_center)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('medical_center')" class="mt-2" />
                        </div>

                        <!-- Contact Number -->
                        <div>
                            <x-input-label for="contact_number" :value="__('admin.Contact Number')" />
                            <x-text-input 
                                id="contact_number" 
                                name="contact_number" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('contact_number', $bloodRequest->contact_number)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
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
                                    <option value="{{ $province->id }}" {{ old('province_id', $bloodRequest->province_id) == $province->id ? 'selected' : '' }}>
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
                                    <option :value="city.id" x-text="city.name" :selected="city.id == {{ old('city_id', $bloodRequest->city_id) }}"></option>
                                </template>
                            </x-select>
                            <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Request Reason -->
                    <div class="mt-6">
                        <x-input-label for="request_reason" :value="__('admin.Request Reason')" />
                        <textarea 
                            id="request_reason" 
                            name="request_reason" 
                            rows="4"
                            class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                            required
                        >{{ old('request_reason', $bloodRequest->request_reason) }}</textarea>
                        <x-input-error :messages="$errors->get('request_reason')" class="mt-2" />
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <x-input-label for="notes" :value="__('admin.Notes (Optional)')" />
                        <textarea 
                            id="notes" 
                            name="notes" 
                            rows="3"
                            class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                            placeholder="{{ __('admin.Add any additional notes...') }}"
                        >{{ old('notes', $bloodRequest->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center {{ $isRtl ? 'justify-start' : 'justify-end' }} gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.blood-request-management.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Update Request') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

