@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Blood Inventory Management') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Manage all blood inventory entries') }}</p>
                </div>
                <a href="{{ route('admin.inventory-management.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('admin.Add Inventory Entry') }}
                </a>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <form method="GET" action="{{ route('admin.inventory-management.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Search') }}</label>
                        <x-text-input 
                            id="search"
                            name="search" 
                            type="text" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            value="{{ request('search') }}"
                            placeholder="{{ __('admin.Search by bag ID, donor name...') }}"
                        />
                    </div>

                    <!-- Blood Type Filter -->
                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Blood Type') }}</label>
                        <x-select 
                            id="blood_type"
                            name="blood_type" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">{{ __('admin.All Types') }}</option>
                            <option value="A" {{ request('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ request('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ request('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ request('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                        </x-select>
                    </div>

                    <!-- RH Factor Filter -->
                    <div>
                        <label for="rh_factor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.RH Factor') }}</label>
                        <x-select 
                            id="rh_factor"
                            name="rh_factor" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">{{ __('admin.All') }}</option>
                            <option value="positive" {{ request('rh_factor') == 'positive' ? 'selected' : '' }}>{{ __('admin.Positive') }}</option>
                            <option value="negative" {{ request('rh_factor') == 'negative' ? 'selected' : '' }}>{{ __('admin.Negative') }}</option>
                        </x-select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Status') }}</label>
                        <x-select 
                            id="status"
                            name="status" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">{{ __('admin.All Statuses') }}</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('admin.In Stock') }}</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('admin.Used') }}</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>{{ __('admin.Expired') }}</option>
                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>{{ __('admin.Discarded') }}</option>
                        </x-select>
                    </div>

                    <!-- Province Filter -->
                    <div>
                        <label for="province_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Province') }}</label>
                        <x-select 
                            id="province_id"
                            name="province_id" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">{{ __('admin.All Provinces') }}</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <!-- Expiration Filter -->
                    <div>
                        <label for="expiration_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Expiration') }}</label>
                        <x-select 
                            id="expiration_filter"
                            name="expiration_filter" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">{{ __('admin.All') }}</option>
                            <option value="expired" {{ request('expiration_filter') == 'expired' ? 'selected' : '' }}>{{ __('admin.Expired') }}</option>
                            <option value="expiring_soon" {{ request('expiration_filter') == 'expiring_soon' ? 'selected' : '' }}>{{ __('admin.Expiring Soon') }}</option>
                            <option value="valid" {{ request('expiration_filter') == 'valid' ? 'selected' : '' }}>{{ __('admin.Valid') }}</option>
                        </x-select>
                    </div>

                    <!-- Buttons -->
                    <div class="lg:col-span-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-end gap-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Filter') }}
                        </button>
                        <a href="{{ route('admin.inventory-management.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Inventory Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Bag ID') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Blood Type') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Donor') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Location') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Entry Date') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Expiration Date') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Status') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-left' : 'text-right' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($inventory as $item)
                                @php
                                    $isExpired = $item->expiration_date && $item->expiration_date->isPast();
                                    $isExpiringSoon = $item->expiration_date && $item->expiration_date->isFuture() && $item->expiration_date->diffInDays(now()) <= 7;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $isExpired ? 'bg-red-50 dark:bg-red-900/10' : ($isExpiringSoon ? 'bg-yellow-50 dark:bg-yellow-900/10' : '') }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                            {{ $item->bag_id }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            {{ $item->blood_type }}{{ $item->rh_factor == 'positive' ? '+' : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->bloodDonationRecord && $item->bloodDonationRecord->donor)
                                            <div class="text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                                {{ $item->bloodDonationRecord->donor->user->full_name ?? '' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                                ID: {{ $item->bloodDonationRecord->donor_id ?? '' }}
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400 dark:text-gray-500 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.N/A') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                            {{ $item->province->name ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                            {{ $item->entry_date ? $item->entry_date->format('Y-m-d') : '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm {{ $isExpired ? 'text-red-600 dark:text-red-400 font-semibold' : ($isExpiringSoon ? 'text-yellow-600 dark:text-yellow-400 font-semibold' : 'text-gray-900 dark:text-white') }} {{ $isRtl ? 'text-right' : 'text-left' }}">
                                            {{ $item->expiration_date ? $item->expiration_date->format('Y-m-d') : '' }}
                                        </div>
                                        @if($isExpiringSoon && !$isExpired)
                                            <div class="text-xs text-yellow-600 dark:text-yellow-400 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                                {{ __('admin.Expires in') }} {{ $item->expiration_date->diffInDays(now()) }} {{ __('admin.days') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->status == 0)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                {{ __('admin.In Stock') }}
                                            </span>
                                        @elseif($item->status == 1)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                {{ __('admin.Used') }}
                                            </span>
                                        @elseif($item->status == 2)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                {{ __('admin.Expired') }}
                                            </span>
                                        @elseif($item->status == 3)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                                {{ __('admin.Discarded') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap {{ $isRtl ? 'text-left' : 'text-right' }} text-sm font-medium">
                                        <div class="flex {{ $isRtl ? 'justify-start flex-row-reverse' : 'justify-end' }} gap-2">
                                            <a href="{{ route('admin.inventory-management.show', ['inventory_management' => $item]) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                               title="{{ __('admin.View') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.inventory-management.edit', ['inventory_management' => $item]) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors"
                                               title="{{ __('admin.Edit') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            @if($item->status == 0)
                                                <form method="POST" 
                                                      action="{{ route('admin.inventory-management.mark-as-used', $item) }}" 
                                                      class="inline"
                                                      onsubmit="return confirm('{{ __('admin.Are you sure you want to mark this inventory as used?') }}')">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                                            title="{{ __('admin.Mark as Used') }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <form method="POST" 
                                                      action="{{ route('admin.inventory-management.mark-as-expired', $item) }}" 
                                                      class="inline"
                                                      onsubmit="return confirm('{{ __('admin.Are you sure you want to mark this inventory as expired?') }}')">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300 transition-colors"
                                                            title="{{ __('admin.Mark as Expired') }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" 
                                                  action="{{ route('admin.inventory-management.destroy', ['inventory_management' => $item]) }}" 
                                                  class="inline"
                                                  onsubmit="return confirm('{{ __('admin.Are you sure you want to delete this inventory entry?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                        title="{{ __('admin.Delete') }}">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('admin.No inventory entries found') }}</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.Get started by creating a new inventory entry.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($inventory->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $inventory->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

