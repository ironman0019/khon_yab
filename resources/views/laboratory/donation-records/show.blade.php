@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Donation Record Details') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Complete report for donation record #:id', ['id' => $donationRecord->id]) }}</p>
                </div>
                <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2">
                    <!-- Add Test Results Button (only if status is Test Pending and no test exists) -->
                    @if($donationRecord->status == 0 && !$donationRecord->bloodTest)
                        <a href="{{ route('laboratory.donation-records.test.create', $donationRecord) }}" 
                           class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('admin.Add Test Results') }}
                        </a>
                    @endif

                    <!-- Edit Test Results Button (if test exists and not discarded) -->
                    @if($donationRecord->bloodTest && $donationRecord->status != 3)
                        <a href="{{ route('laboratory.donation-records.test.edit', $donationRecord) }}" 
                           class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ __('admin.Edit Test Results') }}
                        </a>
                    @endif

                    <a href="{{ route('laboratory.donation-records.print', $donationRecord) }}" 
                       target="_blank"
                       class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        {{ __('admin.Print Receipt') }}
                    </a>
                    <a href="{{ route('laboratory.donation-records.edit', $donationRecord) }}" 
                       class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('admin.Edit') }}
                    </a>
                    <form method="POST" 
                          action="{{ route('laboratory.donation-records.destroy', $donationRecord) }}" 
                          class="inline"
                          onsubmit="return confirm('{{ __('admin.Are you sure you want to delete this donation record?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            {{ __('admin.Delete') }}
                        </button>
                    </form>
                    <a href="{{ route('laboratory.donation-records.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        {{ __('admin.Back to List') }}
                    </a>
                </div>
            </div>

            <!-- Donation Record Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Donation Record Information') }}</h2>
                </div>
                
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Record ID -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Record ID') }}</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">#{{ $donationRecord->id }}</dd>
                        </div>

                        <!-- Donation Type -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Donation Type') }}</dt>
                            <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                @php
                                    $donationTypes = [
                                        0 => __('admin.Whole Blood'),
                                        1 => __('admin.Plasma'),
                                        2 => __('admin.Platelets'),
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $donationTypes[$donationRecord->donation_type] ?? __('admin.Unknown') }}
                                </span>
                            </dd>
                        </div>

                        <!-- Amount -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Amount') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donationRecord->amount_ml }} ml</dd>
                        </div>

                        <!-- Status -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Status') }}</dt>
                            <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                @php
                                    $statusLabels = [
                                        0 => __('admin.Test Pending'),
                                        1 => __('admin.Safe'),
                                        2 => __('admin.Unsafe'),
                                        3 => __('admin.Discarded'),
                                    ];
                                    $statusColors = [
                                        0 => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        1 => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        2 => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        3 => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$donationRecord->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ $statusLabels[$donationRecord->status] ?? __('admin.Unknown') }}
                                </span>
                            </dd>
                        </div>

                        <!-- Donation Date -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Donation Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donationRecord->donation_date->format('Y-m-d') }}</dd>
                        </div>

                        <!-- Expiration Date -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Expiration Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donationRecord->expiration_date->format('Y-m-d') }}</dd>
                        </div>

                        <!-- Recorded By -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Recorded By') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                {{ $donationRecord->recordedByAdmin->full_name ?? __('admin.Unknown') }}
                            </dd>
                        </div>

                        <!-- Submitted By Donor -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Submitted By Donor') }}</dt>
                            <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                @if($donationRecord->submitted_by_donor)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        {{ __('admin.Yes') }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                        {{ __('admin.No') }}
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <!-- Created At -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donationRecord->created_at->format('Y-m-d H:i:s') }}</dd>
                        </div>

                        <!-- Updated At -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Updated At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donationRecord->updated_at->format('Y-m-d H:i:s') }}</dd>
                        </div>
                    </dl>

                    <!-- Notes -->
                    @if($donationRecord->notes)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Notes') }}</dt>
                            <dd class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donationRecord->notes }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Donor Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Donor Information') }}</h2>
                </div>
                <div class="px-6 py-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">
                        <a href="{{ route('admin.donor-management.show', $donationRecord->donor) }}" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium">
                            {{ $donationRecord->donor->user->full_name }}
                        </a>
                        ({{ $donationRecord->donor->blood_type }}{{ $donationRecord->donor->rh_factor == 'positive' ? '+' : '-' }})
                    </p>
                </div>
            </div>

            <!-- Donation Location -->
            @if($donationRecord->province || $donationRecord->city)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Donation Location') }}</h2>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @if($donationRecord->province)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Province') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donationRecord->province->name }}</dd>
                                </div>
                            @endif
                            @if($donationRecord->city)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.City') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donationRecord->city->name }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            @endif

            <!-- Related Blood Inventory -->
            @if($donationRecord->bloodInventory->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Related Blood Inventory') }}</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-4">
                            @foreach($donationRecord->bloodInventory as $inventory)
                                <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-start">
                                        <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('admin.inventory-management.show', $inventory) }}" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                                    {{ __('admin.Bag ID') }}: {{ $inventory->bag_id }}
                                                </a>
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                {{ __('admin.Blood Type') }}: {{ $inventory->blood_type }}{{ $inventory->rh_factor == 'positive' ? '+' : '-' }}
                                            </p>
                                        </div>
                                        <div class="{{ $isRtl ? 'text-left' : 'text-right' }}">
                                            @php
                                                $inventoryStatusLabels = [
                                                    0 => __('admin.In Stock'),
                                                    1 => __('admin.Used'),
                                                    2 => __('admin.Expired'),
                                                    3 => __('admin.Discarded'),
                                                ];
                                                $inventoryStatusColors = [
                                                    0 => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                                    1 => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                                    2 => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                                    3 => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400',
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $inventoryStatusColors[$inventory->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                                                {{ $inventoryStatusLabels[$inventory->status] ?? __('admin.Unknown') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Related Blood Test -->
            @if($donationRecord->bloodTest)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Blood Test Results') }}</h2>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Test Date') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donationRecord->bloodTest->test_date->format('Y-m-d') }}</dd>
                            </div>
                            @php
                                $testedBy = $donationRecord->bloodTest->getRelationValue('tested_by');
                            @endphp
                            @if($testedBy)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Tested By') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $testedBy->full_name }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Overall Result') }}</dt>
                                <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    @if($donationRecord->bloodTest->overall_result)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            {{ __('admin.Unsafe') }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            {{ __('admin.Safe') }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>

                        <!-- Individual Test Results -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Individual Test Results') }}</h3>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @php
                                    $testResults = [
                                        'HIV' => $donationRecord->bloodTest->hiv_result,
                                        'HBV' => $donationRecord->bloodTest->hbv_result,
                                        'HCV' => $donationRecord->bloodTest->hcv_result,
                                        'Syphilis' => $donationRecord->bloodTest->syphilis_result,
                                        'Malaria' => $donationRecord->bloodTest->malaria_result,
                                    ];
                                @endphp
                                @foreach($testResults as $testName => $result)
                                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $testName }}:</span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $result ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' }}">
                                            {{ $result ? __('admin.Positive') : __('admin.Negative') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Test Logs -->
                        @if($donationRecord->bloodTest->test_logs)
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Test Logs') }}</dt>
                                <dd class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap bg-gray-50 dark:bg-gray-900 p-4 rounded-lg {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $donationRecord->bloodTest->test_logs }}</dd>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($donationRecord->status == 0)
                <!-- Show message if no test and status is Test Pending -->
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800 p-4 mb-6">
                    <p class="text-sm text-yellow-800 dark:text-yellow-300 {{ $isRtl ? 'text-right' : 'text-left' }}">
                        <strong>{{ __('admin.Test Pending') }}:</strong> {{ __('admin.Blood test results have not been recorded yet. Please add test results.') }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

