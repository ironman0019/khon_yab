@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Add Blood Test Results') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Record test results for donation record #:id', ['id' => $donationRecord->id]) }}</p>
            </div>

            <!-- Donation Record Info -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4 mb-6">
                <p class="text-sm text-blue-900 dark:text-blue-300 {{ $isRtl ? 'text-right' : 'text-left' }}">
                    <strong>{{ __('admin.Donor') }}:</strong> {{ $donationRecord->donor->user->full_name }} ({{ $donationRecord->donor->blood_type }}{{ $donationRecord->donor->rh_factor == 'positive' ? '+' : '-' }}) |
                    <strong>{{ __('admin.Donation Date') }}:</strong> {{ $donationRecord->donation_date->format('Y-m-d') }} |
                    <strong>{{ __('admin.Amount') }}:</strong> {{ $donationRecord->amount_ml }} ml
                </p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('admin.blood-donation-management.test.store', $donationRecord) }}">
                    @csrf

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Test Results') }}</h2>
                    <div class="space-y-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- HIV Test -->
                        <div>
                            <x-input-label for="hiv_result" :value="__('admin.HIV Test Result')" />
                            <div class="mt-2 flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-6">
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        name="hiv_result" 
                                        value="0"
                                        {{ old('hiv_result', '0') == '0' ? 'checked' : '' }}
                                        class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600"
                                        required
                                    >
                                    <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-700 dark:text-gray-300">{{ __('admin.Negative') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        name="hiv_result" 
                                        value="1"
                                        {{ old('hiv_result') == '1' ? 'checked' : '' }}
                                        class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600"
                                    >
                                    <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-700 dark:text-gray-300">{{ __('admin.Positive') }}</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('hiv_result')" class="mt-2" />
                        </div>

                        <!-- HBV Test -->
                        <div>
                            <x-input-label for="hbv_result" :value="__('admin.HBV Test Result')" />
                            <div class="mt-2 flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-6">
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        name="hbv_result" 
                                        value="0"
                                        {{ old('hbv_result', '0') == '0' ? 'checked' : '' }}
                                        class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600"
                                        required
                                    >
                                    <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-700 dark:text-gray-300">{{ __('admin.Negative') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        name="hbv_result" 
                                        value="1"
                                        {{ old('hbv_result') == '1' ? 'checked' : '' }}
                                        class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600"
                                    >
                                    <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-700 dark:text-gray-300">{{ __('admin.Positive') }}</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('hbv_result')" class="mt-2" />
                        </div>

                        <!-- HCV Test -->
                        <div>
                            <x-input-label for="hcv_result" :value="__('admin.HCV Test Result')" />
                            <div class="mt-2 flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-6">
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        name="hcv_result" 
                                        value="0"
                                        {{ old('hcv_result', '0') == '0' ? 'checked' : '' }}
                                        class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600"
                                        required
                                    >
                                    <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-700 dark:text-gray-300">{{ __('admin.Negative') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        name="hcv_result" 
                                        value="1"
                                        {{ old('hcv_result') == '1' ? 'checked' : '' }}
                                        class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600"
                                    >
                                    <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-700 dark:text-gray-300">{{ __('admin.Positive') }}</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('hcv_result')" class="mt-2" />
                        </div>

                        <!-- Syphilis Test -->
                        <div>
                            <x-input-label for="syphilis_result" :value="__('admin.Syphilis Test Result')" />
                            <div class="mt-2 flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-6">
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        name="syphilis_result" 
                                        value="0"
                                        {{ old('syphilis_result', '0') == '0' ? 'checked' : '' }}
                                        class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600"
                                        required
                                    >
                                    <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-700 dark:text-gray-300">{{ __('admin.Negative') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        name="syphilis_result" 
                                        value="1"
                                        {{ old('syphilis_result') == '1' ? 'checked' : '' }}
                                        class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600"
                                    >
                                    <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-700 dark:text-gray-300">{{ __('admin.Positive') }}</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('syphilis_result')" class="mt-2" />
                        </div>

                        <!-- Malaria Test -->
                        <div>
                            <x-input-label for="malaria_result" :value="__('admin.Malaria Test Result')" />
                            <div class="mt-2 flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-6">
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        name="malaria_result" 
                                        value="0"
                                        {{ old('malaria_result', '0') == '0' ? 'checked' : '' }}
                                        class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600"
                                        required
                                    >
                                    <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-700 dark:text-gray-300">{{ __('admin.Negative') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        name="malaria_result" 
                                        value="1"
                                        {{ old('malaria_result') == '1' ? 'checked' : '' }}
                                        class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600"
                                    >
                                    <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-700 dark:text-gray-300">{{ __('admin.Positive') }}</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('malaria_result')" class="mt-2" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Test Information') }}</h2>
                    <div class="space-y-6 mb-6">
                        <!-- Test Date -->
                        <div>
                            <x-input-label for="test_date" :value="__('admin.Test Date')" />
                            <x-text-input 
                                id="test_date" 
                                name="test_date" 
                                type="date" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('test_date', now()->format('Y-m-d'))" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('test_date')" class="mt-2" />
                        </div>

                        <!-- Test Logs -->
                        <div>
                            <x-input-label for="test_logs" :value="__('admin.Test Logs (Optional)')" />
                            <textarea 
                                id="test_logs" 
                                name="test_logs" 
                                rows="4"
                                class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                placeholder="{{ __('admin.Additional notes about the test results...') }}"
                            >{{ old('test_logs') }}</textarea>
                            <x-input-error :messages="$errors->get('test_logs')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Optional: Add any additional notes or observations about the test results. A summary log will be automatically generated.') }}</p>
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                        <p class="text-sm text-yellow-800 dark:text-yellow-300 {{ $isRtl ? 'text-right' : 'text-left' }}">
                            <strong>{{ __('admin.Note') }}:</strong> {{ __('admin.If any test result is positive, the blood will be automatically marked as unsafe and discarded from inventory.') }}
                        </p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center {{ $isRtl ? 'flex-row-reverse justify-start' : 'justify-end' }} gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.blood-donation-management.show', $donationRecord) }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Save Test Results') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

