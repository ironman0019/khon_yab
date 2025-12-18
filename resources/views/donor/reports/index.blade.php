@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Donation Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Donation Reports') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Detailed statistics about your donations') }}</p>
                </div>
                <a href="{{ route('donor.dashboard.index') }}" 
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                    {{ __('Back to Dashboard') }}
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Donations -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Donations') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($statistics['total_donations']) }}</p>
                        </div>
                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Amount') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($statistics['total_amount_ml']) }} ml</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Average Amount -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Average Amount') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($statistics['average_amount_ml'], 0) }} ml</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Donations This Year -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('This Year') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($statistics['donations_this_year']) }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Breakdown Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Donations by Status -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Donations by Status') }}</h2>
                    <div class="space-y-3">
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Test Pending') }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $statistics['donations_by_status']['pending'] }}</span>
                        </div>
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Safe') }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $statistics['donations_by_status']['safe'] }}</span>
                        </div>
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Unsafe') }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $statistics['donations_by_status']['unsafe'] }}</span>
                        </div>
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Discarded') }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $statistics['donations_by_status']['discarded'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Donations by Type -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Donations by Type') }}</h2>
                    <div class="space-y-3">
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Whole Blood') }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $statistics['donations_by_type']['whole_blood'] }}</span>
                        </div>
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Plasma') }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $statistics['donations_by_type']['plasma'] }}</span>
                        </div>
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Platelets') }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $statistics['donations_by_type']['platelets'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Additional Information') }}</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Last Donation Date') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                            {{ $statistics['last_donation_date'] ? $statistics['last_donation_date']->format('Y-m-d') : __('Never') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Next Eligible Date') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                            {{ $statistics['next_eligible_date'] ? $statistics['next_eligible_date']->format('Y-m-d') : __('Now') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Days Until Next Donation') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                            {{ $statistics['days_until_next'] !== null ? $statistics['days_until_next'] : 0 }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Can Donate Now') }}</dt>
                        <dd class="mt-1">
                            @if($statistics['can_donate_now'])
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ __('Yes') }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    {{ __('No') }}
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Donations Last Year') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                            {{ number_format($statistics['donations_last_year']) }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>

