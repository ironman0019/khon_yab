@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Blood Inventory Details') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.View blood inventory entry information and details') }}</p>
                </div>
                <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2">
                    <a href="{{ route('admin.inventory-management.edit', ['inventory_management' => $bloodInventory]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('admin.Edit') }}
                    </a>
                    <form method="POST" 
                          action="{{ route('admin.inventory-management.destroy', ['inventory_management' => $bloodInventory]) }}" 
                          class="inline"
                          onsubmit="return confirm('{{ __('admin.Are you sure you want to delete this inventory entry?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            {{ __('admin.Delete') }}
                        </button>
                    </form>
                    <a href="{{ route('admin.inventory-management.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        {{ __('admin.Back to List') }}
                    </a>
                </div>
            </div>

            @php
                $isExpired = $bloodInventory->expiration_date && $bloodInventory->expiration_date->isPast();
                $isExpiringSoon = $bloodInventory->expiration_date && $bloodInventory->expiration_date->isFuture() && $bloodInventory->expiration_date->diffInDays(now()) <= 7;
            @endphp
            <!-- Status Badge -->
            <div class="mb-6">
                @if($bloodInventory->status == 0)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        {{ __('admin.In Stock') }}
                    </span>
                @elseif($bloodInventory->status == 1)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                        {{ __('admin.Used') }}
                    </span>
                @elseif($bloodInventory->status == 2)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                        {{ __('admin.Expired') }}
                    </span>
                @elseif($bloodInventory->status == 3)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                        {{ __('admin.Discarded') }}
                    </span>
                @endif
                @if($isExpired)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 {{ $isRtl ? 'mr-2' : 'ml-2' }}">
                        {{ __('admin.Expired') }}
                    </span>
                @elseif($isExpiringSoon)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 {{ $isRtl ? 'mr-2' : 'ml-2' }}">
                        {{ __('admin.Expiring Soon') }} ({{ $bloodInventory->expiration_date->diffInDays(now()) }} {{ __('admin.days') }})
                    </span>
                @endif
            </div>

            <!-- Inventory Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Inventory Information') }}</h2>
                </div>
                
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Bag ID -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Bag ID') }}</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodInventory->bag_id }}</dd>
                        </div>

                        <!-- Blood Type -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Blood Type') }}</dt>
                            <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                    {{ $bloodInventory->blood_type }}{{ $bloodInventory->rh_factor == 'positive' ? '+' : '-' }}
                                </span>
                            </dd>
                        </div>

                        <!-- Province -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Province') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodInventory->province->name ?? '' }}</dd>
                        </div>

                        <!-- Entry Date -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Entry Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                {{ $bloodInventory->entry_date ? $bloodInventory->entry_date->format('Y-m-d') : '' }}
                            </dd>
                        </div>

                        <!-- Exit Date -->
                        @if($bloodInventory->exit_date)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Exit Date') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    {{ $bloodInventory->exit_date->format('Y-m-d') }}
                                </dd>
                            </div>
                        @endif

                        <!-- Expiration Date -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Expiration Date') }}</dt>
                            <dd class="mt-1 text-sm {{ $isExpired ? 'text-red-600 dark:text-red-400 font-semibold' : ($isExpiringSoon ? 'text-yellow-600 dark:text-yellow-400 font-semibold' : 'text-gray-900 dark:text-white') }} {{ $isRtl ? 'text-right' : 'text-left' }}">
                                {{ $bloodInventory->expiration_date ? $bloodInventory->expiration_date->format('Y-m-d') : '' }}
                            </dd>
                        </div>

                        <!-- Added By -->
                        @if($bloodInventory->addedBy)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Added By') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    {{ $bloodInventory->addedBy->full_name ?? '' }}
                                </dd>
                            </div>
                        @endif

                        <!-- Removed By -->
                        @if($bloodInventory->removedBy)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Removed By') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    {{ $bloodInventory->removedBy->full_name ?? '' }}
                                </dd>
                            </div>
                        @endif

                        <!-- Notes -->
                        @if($bloodInventory->notes)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Notes') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodInventory->notes }}</dd>
                            </div>
                        @endif

                        <!-- Created At -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodInventory->created_at->format('Y-m-d H:i:s') }}</dd>
                        </div>

                        <!-- Updated At -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Updated At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $bloodInventory->updated_at->format('Y-m-d H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Donation Record Information -->
            @if($bloodInventory->bloodDonationRecord)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Related Donation Record') }}</h2>
                    </div>
                    
                    <div class="px-6 py-6">
                        <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Donor -->
                            @if($bloodInventory->bloodDonationRecord->donor)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Donor') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                        {{ $bloodInventory->bloodDonationRecord->donor->user->full_name ?? '' }}
                                    </dd>
                                </div>
                            @endif

                            <!-- Donation Date -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Donation Date') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    {{ $bloodInventory->bloodDonationRecord->donation_date ? $bloodInventory->bloodDonationRecord->donation_date->format('Y-m-d') : '' }}
                                </dd>
                            </div>

                            <!-- Amount -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Amount') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    {{ $bloodInventory->bloodDonationRecord->amount_ml ?? '' }} {{ __('admin.ml') }}
                                </dd>
                            </div>

                            <!-- Status -->
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Record Status') }}</dt>
                                <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    @if($bloodInventory->bloodDonationRecord->status == 0)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            {{ __('admin.Test Pending') }}
                                        </span>
                                    @elseif($bloodInventory->bloodDonationRecord->status == 1)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            {{ __('admin.Safe') }}
                                        </span>
                                    @elseif($bloodInventory->bloodDonationRecord->status == 2)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            {{ __('admin.Unsafe') }}
                                        </span>
                                    @elseif($bloodInventory->bloodDonationRecord->status == 3)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                            {{ __('admin.Discarded') }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            @if($bloodInventory->status == 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Actions') }}</h3>
                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} flex-wrap gap-3">
                        <!-- Mark as Used Button -->
                        <form method="POST" action="{{ route('admin.inventory-management.mark-as-used', $bloodInventory) }}" 
                              class="inline"
                              onsubmit="return confirm('{{ __('admin.Are you sure you want to mark this inventory as used?') }}')">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('admin.Mark as Used') }}
                            </button>
                        </form>

                        <!-- Mark as Expired Button -->
                        <form method="POST" action="{{ route('admin.inventory-management.mark-as-expired', $bloodInventory) }}" 
                              class="inline"
                              onsubmit="return confirm('{{ __('admin.Are you sure you want to mark this inventory as expired?') }}')">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('admin.Mark as Expired') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

