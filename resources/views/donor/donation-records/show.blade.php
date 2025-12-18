@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Donation Record Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Donation Record #:id', ['id' => $donation_record->id]) }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('View complete details of your donation record') }}</p>
                </div>
                <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2">
                    @if($donation_record->status == 0 && $donation_record->submitted_by_donor)
                        <form method="POST" action="{{ route('donor.donation-records.cancel', $donation_record) }}" 
                              onsubmit="return confirm('{{ __('Are you sure you want to cancel this donation request?') }}');">
                            @csrf
                            @method('POST')
                            <button type="submit" 
                                    class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                {{ __('Cancel Request') }}
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('donor.donation-records.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        {{ __('Back to List') }}
                    </a>
                </div>
            </div>

            <!-- Donation Record Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Donation Information') }}</h2>
                </div>
                
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Record ID -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Record ID') }}</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">#{{ $donation_record->id }}</dd>
                        </div>

                        <!-- Donation Type -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Donation Type') }}</dt>
                            <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                @php
                                    $donationTypes = [
                                        0 => __('Whole Blood'),
                                        1 => __('Plasma'),
                                        2 => __('Platelets'),
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $donationTypes[$donation_record->donation_type] ?? __('Unknown') }}
                                </span>
                            </dd>
                        </div>

                        <!-- Amount -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Amount') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ number_format($donation_record->amount_ml) }} ml</dd>
                        </div>

                        <!-- Status -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Status') }}</dt>
                            <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                @php
                                    $statusLabels = [
                                        0 => __('Test Pending'),
                                        1 => __('Safe'),
                                        2 => __('Unsafe'),
                                        3 => __('Discarded'),
                                    ];
                                    $statusColors = [
                                        0 => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        1 => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        2 => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        3 => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$donation_record->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ $statusLabels[$donation_record->status] ?? __('Unknown') }}
                                </span>
                            </dd>
                        </div>

                        <!-- Donation Date -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Donation Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donation_record->donation_date->format('Y-m-d') }}</dd>
                        </div>

                        <!-- Expiration Date -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Expiration Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donation_record->expiration_date->format('Y-m-d') }}</dd>
                        </div>

                        <!-- Location -->
                        @if($donation_record->province || $donation_record->city)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Location') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    @if($donation_record->city)
                                        {{ $donation_record->city->name }},
                                    @endif
                                    @if($donation_record->province)
                                        {{ $donation_record->province->name }}
                                    @endif
                                </dd>
                            </div>
                        @endif

                        <!-- Submitted By Donor -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Submitted By') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                @if($donation_record->submitted_by_donor)
                                    {{ __('You (Donor)') }}
                                @else
                                    {{ __('Admin') }}
                                @endif
                            </dd>
                        </div>
                    </dl>

                    <!-- Notes -->
                    @if($donation_record->notes)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }} mb-2">{{ __('Notes') }}</dt>
                            <dd class="text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donation_record->notes }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Blood Test Results -->
            @if($donation_record->bloodTest)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Blood Test Results') }}</h2>
                    </div>
                    
                    <div class="px-6 py-6">
                        <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Test Date -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Test Date') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donation_record->bloodTest->test_date->format('Y-m-d') }}</dd>
                            </div>

                            <!-- Overall Result -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('Overall Result') }}</dt>
                                <dd class="mt-1">
                                    @if($donation_record->bloodTest->overall_result == 0)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            {{ __('Safe') }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            {{ __('Unsafe') }}
                                        </span>
                                    @endif
                                </dd>
                            </div>

                            <!-- Test Results -->
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }} mb-3">{{ __('Test Details') }}</dt>
                                <dd class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                    <div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('HIV') }}</span>
                                        <p class="mt-1 text-sm font-semibold {{ $donation_record->bloodTest->hiv_result == 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $donation_record->bloodTest->hiv_result == 0 ? __('Negative') : __('Positive') }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('HBV') }}</span>
                                        <p class="mt-1 text-sm font-semibold {{ $donation_record->bloodTest->hbv_result == 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $donation_record->bloodTest->hbv_result == 0 ? __('Negative') : __('Positive') }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('HCV') }}</span>
                                        <p class="mt-1 text-sm font-semibold {{ $donation_record->bloodTest->hcv_result == 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $donation_record->bloodTest->hcv_result == 0 ? __('Negative') : __('Positive') }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Syphilis') }}</span>
                                        <p class="mt-1 text-sm font-semibold {{ $donation_record->bloodTest->syphilis_result == 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $donation_record->bloodTest->syphilis_result == 0 ? __('Negative') : __('Positive') }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Malaria') }}</span>
                                        <p class="mt-1 text-sm font-semibold {{ $donation_record->bloodTest->malaria_result == 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $donation_record->bloodTest->malaria_result == 0 ? __('Negative') : __('Positive') }}
                                        </p>
                                    </div>
                                </dd>
                            </div>

                            <!-- Test Logs -->
                            @if($donation_record->bloodTest->test_logs)
                                <div class="sm:col-span-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }} mb-2">{{ __('Test Logs') }}</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donation_record->bloodTest->test_logs }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

