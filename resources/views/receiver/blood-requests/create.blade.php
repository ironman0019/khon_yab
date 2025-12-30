@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('receiver.Create Blood Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('receiver.New Blood Request') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('receiver.Fill in the details below to create a blood request') }}</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('receiver.blood-requests.store') }}"
                      x-data="{ 
                          cities: @js($cities),
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

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('receiver.Blood Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
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
                                <option value="A" {{ old('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('blood_type') == 'O' ? 'selected' : '' }}>O</option>
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
                                <option value="positive" {{ old('rh_factor') == 'positive' ? 'selected' : '' }}>{{ __('receiver.Positive') }}</option>
                                <option value="negative" {{ old('rh_factor') == 'negative' ? 'selected' : '' }}>{{ __('receiver.Negative') }}</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('rh_factor')" class="mt-2" />
                        </div>

                        <!-- Number of Bags -->
                        <div>
                            <x-input-label for="number_of_bags" :value="__('receiver.Number of Bags')" />
                            <x-text-input 
                                id="number_of_bags" 
                                name="number_of_bags" 
                                type="number" 
                                min="1"
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('number_of_bags')" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('number_of_bags')" class="mt-2" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('receiver.Patient Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Patient Name -->
                        <div>
                            <x-input-label for="patient_name" :value="__('receiver.Patient Name')" />
                            <x-text-input 
                                id="patient_name" 
                                name="patient_name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('patient_name')" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('patient_name')" class="mt-2" />
                        </div>

                        <!-- Patient Age -->
                        <div>
                            <x-input-label for="patient_age" :value="__('receiver.Patient Age')" />
                            <x-text-input 
                                id="patient_age" 
                                name="patient_age" 
                                type="number" 
                                min="0"
                                max="150"
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('patient_age')" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('patient_age')" class="mt-2" />
                        </div>

                        <!-- Request Reason -->
                        <div class="md:col-span-2">
                            <x-input-label for="request_reason" :value="__('receiver.Request Reason')" />
                            <x-textarea 
                                id="request_reason" 
                                name="request_reason" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                rows="3"
                                required
                            >{{ old('request_reason') }}</x-textarea>
                            <x-input-error :messages="$errors->get('request_reason')" class="mt-2" />
                        </div>

                        <!-- Contact Number -->
                        <div>
                            <x-input-label for="contact_number" :value="__('receiver.Contact Number')" />
                            <x-text-input 
                                id="contact_number" 
                                name="contact_number" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('contact_number', $receiver->mobile_number ?? '')" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                        </div>

                        <!-- Medical Center -->
                        <div>
                            <x-input-label for="medical_center" :value="__('receiver.Medical Center')" />
                            <x-text-input 
                                id="medical_center" 
                                name="medical_center" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('medical_center')" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('medical_center')" class="mt-2" />
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
                                x-on:change="fetch('/api/cities?province_id=' + $event.target.value)
                                    .then(response => response.json())
                                    .then(data => cities = data)
                                    .catch(() => cities = [])"
                                required
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
                                    <option :value="city.id" x-text="city.name"></option>
                                </template>
                            </x-select>
                            <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <x-input-label for="notes" :value="__('receiver.Notes')" />
                        <x-textarea 
                            id="notes" 
                            name="notes" 
                            class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            rows="3"
                        >{{ old('notes') }}</x-textarea>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('receiver.Additional notes (optional)') }}</p>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex {{ $isRtl ? 'flex-row-reverse justify-start' : 'justify-end' }} gap-4">
                        <a href="{{ route('receiver.blood-requests.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('receiver.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('receiver.Submit Request') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

