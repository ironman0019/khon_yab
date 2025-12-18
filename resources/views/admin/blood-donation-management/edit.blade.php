@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Edit Blood Donation Record') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Update donation record information') }}</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('admin.blood-donation-management.update', $donationRecord) }}"
                      x-data="{ 
                          cities: [],
                          init() {
                              @if(old('province_id', $donationRecord->province_id))
                                  fetch('/api/cities?province_id={{ old('province_id', $donationRecord->province_id) }}')
                                      .then(response => response.json())
                                      .then(data => {
                                          cities = data;
                                          @if(old('city_id', $donationRecord->city_id))
                                              document.getElementById('city_id').value = {{ old('city_id', $donationRecord->city_id) }};
                                          @endif
                                      })
                                      .catch(() => {});
                              @endif
                          }
                      }">
                    @csrf
                    @method('PUT')

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Donation Information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Donor -->
                        <div>
                            <x-input-label for="donor_id" :value="__('admin.Donor')" />
                            <x-select 
                                id="donor_id" 
                                name="donor_id" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('admin.Select Donor') }}</option>
                                @foreach($donors as $donor)
                                    <option value="{{ $donor->id }}" {{ old('donor_id', $donationRecord->donor_id) == $donor->id ? 'selected' : '' }}>
                                        {{ $donor->user->full_name }} ({{ $donor->blood_type }}{{ $donor->rh_factor == 'positive' ? '+' : '-' }})
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('donor_id')" class="mt-2" />
                        </div>

                        <!-- Donation Type -->
                        <div>
                            <x-input-label for="donation_type" :value="__('admin.Donation Type')" />
                            <x-select 
                                id="donation_type" 
                                name="donation_type" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('admin.Select Type') }}</option>
                                <option value="0" {{ old('donation_type', $donationRecord->donation_type) == '0' ? 'selected' : '' }}>{{ __('admin.Whole Blood') }}</option>
                                <option value="1" {{ old('donation_type', $donationRecord->donation_type) == '1' ? 'selected' : '' }}>{{ __('admin.Plasma') }}</option>
                                <option value="2" {{ old('donation_type', $donationRecord->donation_type) == '2' ? 'selected' : '' }}>{{ __('admin.Platelets') }}</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('donation_type')" class="mt-2" />
                        </div>

                        <!-- Amount (ml) -->
                        <div>
                            <x-input-label for="amount_ml" :value="__('admin.Amount (ml)')" />
                            <x-text-input 
                                id="amount_ml" 
                                name="amount_ml" 
                                type="number" 
                                min="1"
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('amount_ml', $donationRecord->amount_ml)" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('amount_ml')" class="mt-2" />
                        </div>

                        <!-- Donation Date -->
                        <div>
                            <x-input-label for="donation_date" :value="__('admin.Donation Date')" />
                            <x-text-input 
                                id="donation_date" 
                                name="donation_date" 
                                type="date" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('donation_date', $donationRecord->donation_date->format('Y-m-d'))" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('donation_date')" class="mt-2" />
                        </div>

                        <!-- Expiration Date -->
                        <div>
                            <x-input-label for="expiration_date" :value="__('admin.Expiration Date')" />
                            <x-text-input 
                                id="expiration_date" 
                                name="expiration_date" 
                                type="date" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('expiration_date', $donationRecord->expiration_date->format('Y-m-d'))" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('expiration_date')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('admin.Status')" />
                            <x-select 
                                id="status" 
                                name="status" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="0" {{ old('status', $donationRecord->status) == '0' ? 'selected' : '' }}>{{ __('admin.Test Pending') }}</option>
                                <option value="1" {{ old('status', $donationRecord->status) == '1' ? 'selected' : '' }}>{{ __('admin.Safe') }}</option>
                                <option value="2" {{ old('status', $donationRecord->status) == '2' ? 'selected' : '' }}>{{ __('admin.Unsafe') }}</option>
                                <option value="3" {{ old('status', $donationRecord->status) == '3' ? 'selected' : '' }}>{{ __('admin.Discarded') }}</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Donation Location') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Province -->
                        <div>
                            <x-input-label for="province_id" :value="__('admin.Province')" />
                            <x-select 
                                id="province_id" 
                                name="province_id" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
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
                                    <option value="{{ $province->id }}" {{ old('province_id', $donationRecord->province_id) == $province->id ? 'selected' : '' }}>
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
                            >
                                <option value="">{{ __('admin.Select City') }}</option>
                                <template x-for="city in cities" :key="city.id">
                                    <option :value="city.id" x-text="city.name"></option>
                                </template>
                            </x-select>
                            <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <x-input-label for="notes" :value="__('admin.Notes')" />
                        <textarea 
                            id="notes" 
                            name="notes" 
                            rows="4"
                            class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                        >{{ old('notes', $donationRecord->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Report and additional information about the donation') }}</p>
                    </div>

                    <!-- Related Inventory -->
                    @if($donationRecord->bloodInventory->isNotEmpty())
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Related Inventory Entry') }}</h3>
                            @foreach($donationRecord->bloodInventory as $inventory)
                                <p class="text-sm text-blue-700 dark:text-blue-400 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    <a href="{{ route('admin.inventory-management.show', $inventory) }}" class="hover:underline">
                                        {{ __('admin.Bag ID') }}: {{ $inventory->bag_id }}
                                    </a>
                                </p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="flex items-center {{ $isRtl ? 'flex-row-reverse justify-start' : 'justify-end' }} gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.blood-donation-management.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Update Record') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

