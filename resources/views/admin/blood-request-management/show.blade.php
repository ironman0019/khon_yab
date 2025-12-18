@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Blood Request Details') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.View blood request information and details') }}</p>
                </div>
                <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2">
                    @if($bloodRequest->status == 0)
                        <a href="{{ route('admin.blood-request-management.edit', $bloodRequest) }}" 
                           class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ __('admin.Edit') }}
                        </a>
                    @endif
                    <a href="{{ route('admin.blood-request-management.index') }}" 
                       class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        {{ __('admin.Back to List') }}
                    </a>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                @if($bloodRequest->status == 0)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                        {{ __('admin.Pending') }}
                    </span>
                @elseif($bloodRequest->status == 1)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                        {{ __('admin.Approved') }}
                    </span>
                @elseif($bloodRequest->status == 2)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                        {{ __('admin.Rejected') }}
                    </span>
                @elseif($bloodRequest->status == 3)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        {{ __('admin.Completed') }}
                    </span>
                @endif
            </div>

            <!-- Request Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Request Information') }}</h2>
                </div>
                
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Patient Name -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Patient Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->patient_name }}</dd>
                        </div>

                        <!-- Patient Age -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Patient Age') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->patient_age }}</dd>
                        </div>

                        <!-- Blood Type -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Blood Type') }}</dt>
                            <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                    {{ $bloodRequest->blood_type }}{{ $bloodRequest->rh_factor == 'positive' ? '+' : '-' }}
                                </span>
                            </dd>
                        </div>

                        <!-- Number of Bags -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Number of Bags') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->number_of_bags }} {{ __('admin.bags') }}</dd>
                        </div>

                        <!-- Medical Center -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Medical Center') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->medical_center }}</dd>
                        </div>

                        <!-- Contact Number -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Contact Number') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->contact_number }}</dd>
                        </div>

                        <!-- Province -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Province') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->province->name ?? '' }}</dd>
                        </div>

                        <!-- City -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.City') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->city->name ?? '' }}</dd>
                        </div>

                        <!-- Request Reason -->
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Request Reason') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->request_reason }}</dd>
                        </div>

                        <!-- Notes -->
                        @if($bloodRequest->notes)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Notes') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->notes }}</dd>
                            </div>
                        @endif

                        <!-- Rejection Reason -->
                        @if($bloodRequest->rejection_reason)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Rejection Reason') }}</dt>
                                <dd class="mt-1 text-sm text-red-600 dark:text-red-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->rejection_reason }}</dd>
                            </div>
                        @endif

                        <!-- Requested By -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Requested By') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                {{ $bloodRequest->requestedBy->full_name ?? '' }}
                            </dd>
                            <dd class="mt-1 text-sm text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                {{ $bloodRequest->requestedBy->email ?? '' }}
                            </dd>
                        </div>

                        <!-- Approved By -->
                        @if($bloodRequest->approvedBy)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Approved/Rejected By') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    {{ $bloodRequest->approvedBy->full_name ?? '' }}
                                </dd>
                                @if($bloodRequest->approval_date)
                                    <dd class="mt-1 text-sm text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                        {{ $bloodRequest->approval_date->format('Y-m-d H:i:s') }}
                                    </dd>
                                @endif
                            </div>
                        @endif

                        <!-- Created At -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->created_at->format('Y-m-d H:i:s') }}</dd>
                        </div>

                        <!-- Updated At -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Updated At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodRequest->updated_at->format('Y-m-d H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($bloodRequest->status == 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Actions') }}</h3>
                    <div class="flex flex-wrap gap-3">
                        <!-- Approve Button with Modal -->
                        <div x-data="{ showApproveModal: false }" class="inline">
                            <button type="button" 
                                    @click="showApproveModal = true"
                                    class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('admin.Approve') }}
                            </button>
                            
                            <div x-show="showApproveModal" 
                                 x-cloak
                                 class="fixed inset-0 z-50 overflow-y-auto"
                                 style="display: none;">
                                <div class="flex items-center justify-center min-h-screen px-4">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showApproveModal = false"></div>
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6 relative z-50" @click.away="showApproveModal = false">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Approve Blood Request') }}</h3>
                                        <form method="POST" action="{{ route('admin.blood-request-management.approve', $bloodRequest) }}">
                                            @csrf
                                            <div class="mb-4">
                                                <x-input-label for="approve_notes" :value="__('admin.Notes (Optional)')" />
                                                <textarea 
                                                    id="approve_notes"
                                                    name="notes" 
                                                    rows="3"
                                                    class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                                    placeholder="{{ __('admin.Add any notes...') }}"
                                                ></textarea>
                                            </div>
                                            <div class="flex {{ $isRtl ? 'justify-start' : 'justify-end' }} gap-2">
                                                <button type="button" 
                                                        @click="showApproveModal = false"
                                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                                                    {{ __('admin.Cancel') }}
                                                </button>
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                    {{ __('admin.Approve') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Button with Modal -->
                        <div x-data="{ showRejectModal: false }" class="inline">
                            <button type="button" 
                                    @click="showRejectModal = true"
                                    class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                {{ __('admin.Reject') }}
                            </button>
                            
                            <div x-show="showRejectModal" 
                                 x-cloak
                                 class="fixed inset-0 z-50 overflow-y-auto"
                                 style="display: none;">
                                <div class="flex items-center justify-center min-h-screen px-4">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showRejectModal = false"></div>
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6 relative z-50" @click.away="showRejectModal = false">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Reject Blood Request') }}</h3>
                                        <form method="POST" action="{{ route('admin.blood-request-management.reject', $bloodRequest) }}">
                                            @csrf
                                            <div class="mb-4">
                                                <x-input-label for="rejection_reason" :value="__('admin.Rejection Reason')" />
                                                <textarea 
                                                    id="rejection_reason"
                                                    name="rejection_reason" 
                                                    rows="3"
                                                    class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                                    placeholder="{{ __('admin.Enter rejection reason...') }}"
                                                    required
                                                >{{ old('rejection_reason') }}</textarea>
                                                <x-input-error :messages="$errors->get('rejection_reason')" class="mt-2" />
                                            </div>
                                            <div class="mb-4">
                                                <x-input-label for="reject_notes" :value="__('admin.Notes (Optional)')" />
                                                <textarea 
                                                    id="reject_notes"
                                                    name="notes" 
                                                    rows="2"
                                                    class="block mt-1 w-full border-red-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                                    placeholder="{{ __('admin.Add any notes...') }}"
                                                >{{ old('notes') }}</textarea>
                                            </div>
                                            <div class="flex {{ $isRtl ? 'justify-start' : 'justify-end' }} gap-2">
                                                <button type="button" 
                                                        @click="showRejectModal = false"
                                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                                                    {{ __('admin.Cancel') }}
                                                </button>
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                    {{ __('admin.Reject') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($bloodRequest->status == 1)
                <!-- Complete Button for Approved Requests -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Actions') }}</h3>
                    <form method="POST" action="{{ route('admin.blood-request-management.complete', $bloodRequest) }}" 
                          class="inline"
                          onsubmit="return confirm('{{ __('admin.Are you sure you want to mark this request as completed?') }}')">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('admin.Mark as Completed') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

