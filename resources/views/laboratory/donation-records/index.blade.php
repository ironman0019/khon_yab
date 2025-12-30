@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('laboratory.Blood Donation Records') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('laboratory.Manage blood donations recorded by your laboratory') }}</p>
                </div>
                <a href="{{ route('laboratory.donation-records.create') }}" 
                   class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('laboratory.Create Donation Record') }}
                </a>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <form method="GET" action="{{ route('laboratory.donation-records.index') }}" class="flex flex-col gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('laboratory.Search') }}</label>
                            <x-text-input 
                                id="search"
                                name="search" 
                                type="text" 
                                class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                value="{{ request('search') }}"
                                placeholder="{{ __('laboratory.Search by donor name, email, or record ID...') }}"
                            />
                        </div>

                        <!-- Donor Filter -->
                        <div>
                            <label for="donor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('laboratory.Donor') }}</label>
                            <x-select 
                                id="donor_id"
                                name="donor_id" 
                                class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">{{ __('laboratory.All Donors') }}</option>
                                @foreach($donors as $donor)
                                    <option value="{{ $donor->id }}" @selected(request('donor_id') == $donor->id)>
                                        {{ $donor->user->full_name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('laboratory.Status') }}</label>
                            <x-select 
                                id="status"
                                name="status" 
                                class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">{{ __('laboratory.All Statuses') }}</option>
                                <option value="0" @selected(request('status') == '0')>{{ __('laboratory.Test Pending') }}</option>
                                <option value="1" @selected(request('status') == '1')>{{ __('laboratory.Safe') }}</option>
                                <option value="2" @selected(request('status') == '2')>{{ __('laboratory.Unsafe') }}</option>
                                <option value="3" @selected(request('status') == '3')>{{ __('laboratory.Discarded') }}</option>
                            </x-select>
                        </div>

                        <!-- Donation Type Filter -->
                        <div>
                            <label for="donation_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('laboratory.Donation Type') }}</label>
                            <x-select 
                                id="donation_type"
                                name="donation_type" 
                                class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">{{ __('laboratory.All Types') }}</option>
                                <option value="0" @selected(request('donation_type') == '0')>{{ __('laboratory.Whole Blood') }}</option>
                                <option value="1" @selected(request('donation_type') == '1')>{{ __('laboratory.Plasma') }}</option>
                                <option value="2" @selected(request('donation_type') == '2')>{{ __('laboratory.Platelets') }}</option>
                            </x-select>
                        </div>

                        <!-- Province Filter -->
                        <div>
                            <label for="province_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('laboratory.Province') }}</label>
                            <x-select 
                                id="province_id"
                                name="province_id" 
                                class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">{{ __('laboratory.All Provinces') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" @selected(request('province_id') == $province->id)>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>

                        <!-- Donation Date From -->
                        <div>
                            <label for="donation_date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('laboratory.From Date') }}</label>
                            <x-text-input 
                                id="donation_date_from"
                                name="donation_date_from" 
                                type="date" 
                                class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                value="{{ request('donation_date_from') }}"
                            />
                        </div>

                        <!-- Donation Date To -->
                        <div>
                            <label for="donation_date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('laboratory.To Date') }}</label>
                            <x-text-input 
                                id="donation_date_to"
                                name="donation_date_to" 
                                type="date" 
                                class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                value="{{ request('donation_date_to') }}"
                            />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('laboratory.Filter') }}
                        </button>
                        <a href="{{ route('laboratory.donation-records.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('laboratory.Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Donation Records Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('laboratory.ID') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('laboratory.Donor') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('laboratory.Type') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('laboratory.Amount') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('laboratory.Donation Date') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('laboratory.Status') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('laboratory.Location') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-left' : 'text-right' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('laboratory.Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($donationRecords as $record)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            #{{ $record->id }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $record->donor->user->full_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $record->donor->user->email }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $donationTypes = [
                                                0 => __('laboratory.Whole Blood'),
                                                1 => __('laboratory.Plasma'),
                                                2 => __('laboratory.Platelets'),
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                            {{ $donationTypes[$record->donation_type] ?? __('laboratory.Unknown') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $record->amount_ml }} ml
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $record->donation_date->format('Y-m-d') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusLabels = [
                                                0 => __('laboratory.Test Pending'),
                                                1 => __('laboratory.Safe'),
                                                2 => __('laboratory.Unsafe'),
                                                3 => __('laboratory.Discarded'),
                                            ];
                                            $statusColors = [
                                                0 => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                1 => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                                2 => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                                3 => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$record->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                                            {{ $statusLabels[$record->status] ?? __('laboratory.Unknown') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $record->province->name ?? '-' }}
                                        </div>
                                        @if($record->city)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $record->city->name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap {{ $isRtl ? 'text-left' : 'text-right' }} text-sm font-medium">
                                        <div class="flex {{ $isRtl ? 'justify-start' : 'justify-end' }} gap-2">
                                            <a href="{{ route('laboratory.donation-records.show', $record) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                               title="{{ __('laboratory.View') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('laboratory.donation-records.print', $record) }}" 
                                               target="_blank"
                                               class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 transition-colors"
                                               title="{{ __('laboratory.Print Receipt') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('laboratory.donation-records.edit', $record) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors"
                                               title="{{ __('laboratory.Edit') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" 
                                                  action="{{ route('laboratory.donation-records.destroy', $record) }}" 
                                                  class="inline"
                                                  onsubmit="return confirm('{{ __('laboratory.Are you sure you want to delete this donation record?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                        title="{{ __('laboratory.Delete') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('laboratory.No donation records found') }}</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('laboratory.Get started by creating a new donation record.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($donationRecords->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $donationRecords->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

