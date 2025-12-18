@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Add Blood Inventory Entry') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Create a new blood inventory entry') }}</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('admin.inventory-management.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Bag ID -->
                        <div>
                            <x-input-label for="bag_id" :value="__('admin.Bag ID')" />
                            <x-text-input 
                                id="bag_id" 
                                name="bag_id" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('bag_id')" 
                                required 
                                autofocus 
                            />
                            <x-input-error :messages="$errors->get('bag_id')" class="mt-2" />
                        </div>

                        <!-- Blood Donation Record ID -->
                        <div>
                            <x-input-label for="blood_donation_record_id" :value="__('admin.Blood Donation Record ID')" />
                            <x-text-input 
                                id="blood_donation_record_id" 
                                name="blood_donation_record_id" 
                                type="number" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('blood_donation_record_id')" 
                                required 
                                placeholder="{{ __('admin.Enter donation record ID') }}"
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.ID of the blood donation record') }}</p>
                            <x-input-error :messages="$errors->get('blood_donation_record_id')" class="mt-2" />
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

                        <!-- Province -->
                        <div>
                            <x-input-label for="province_id" :value="__('admin.Province')" />
                            <x-select 
                                id="province_id" 
                                name="province_id" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
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

                        <!-- Entry Date -->
                        <div>
                            <x-input-label for="entry_date" :value="__('admin.Entry Date')" />
                            <x-text-input 
                                id="entry_date" 
                                name="entry_date" 
                                type="date" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('entry_date', now()->format('Y-m-d'))" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('entry_date')" class="mt-2" />
                        </div>

                        <!-- Expiration Date -->
                        <div>
                            <x-input-label for="expiration_date" :value="__('admin.Expiration Date')" />
                            <x-text-input 
                                id="expiration_date" 
                                name="expiration_date" 
                                type="date" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('expiration_date')" 
                                required 
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Must be after entry date') }}</p>
                            <x-input-error :messages="$errors->get('expiration_date')" class="mt-2" />
                        </div>
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
                        >{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center {{ $isRtl ? 'justify-start flex-row-reverse' : 'justify-end' }} gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.inventory-management.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Create Entry') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

