<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Blood Inventory Report') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('View current blood inventory status') }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.reports-management.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('Back to Reports') }}
                    </a>
                    <button onclick="window.print()" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        {{ __('Print') }}
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <form method="GET" action="{{ route('admin.reports-management.inventory') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Status') }}</label>
                        <select id="status" name="status" 
                                class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('In Stock') }}</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('Used') }}</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>{{ __('Expired') }}</option>
                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>{{ __('Discarded') }}</option>
                        </select>
                    </div>

                    <!-- Blood Type -->
                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Blood Type') }}</label>
                        <select id="blood_type" name="blood_type" 
                                class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                            <option value="">{{ __('All Blood Types') }}</option>
                            <option value="A" {{ request('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ request('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ request('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ request('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                        </select>
                    </div>

                    <!-- Expiration Filter -->
                    <div>
                        <label for="expiration_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Expiration') }}</label>
                        <select id="expiration_filter" name="expiration_filter" 
                                class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                            <option value="">{{ __('All') }}</option>
                            <option value="expired" {{ request('expiration_filter') == 'expired' ? 'selected' : '' }}>{{ __('Expired') }}</option>
                            <option value="expiring_soon" {{ request('expiration_filter') == 'expiring_soon' ? 'selected' : '' }}>{{ __('Expiring Soon') }}</option>
                            <option value="valid" {{ request('expiration_filter') == 'valid' ? 'selected' : '' }}>{{ __('Valid') }}</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.reports-management.inventory') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Inventory') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($total_count) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('By Status') }}</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ collect($by_status)->count() }} {{ __('statuses') }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('By Blood Type') }}</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ collect($by_blood_type)->count() }} {{ __('types') }}
                    </p>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Bag ID') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Blood Type') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Donor') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Province') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Entry Date') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Expiration Date') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($inventory as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->bag_id }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            {{ $item->blood_type }}{{ $item->rh_factor === 'positive' ? '+' : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $item->bloodDonationRecord->donor->user->full_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $item->province->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $item->entry_date ? $item->entry_date->format('Y-m-d') : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $item->expiration_date ? $item->expiration_date->format('Y-m-d') : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                '0' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                '1' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                '2' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                '3' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                            ];
                                            $statusLabels = [
                                                '0' => __('In Stock'),
                                                '1' => __('Used'),
                                                '2' => __('Expired'),
                                                '3' => __('Discarded'),
                                            ];
                                            $statusValue = (string) $item->status;
                                            $color = $statusColors[$statusValue] ?? $statusColors['0'];
                                            $label = $statusLabels[$statusValue] ?? __('Unknown');
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('No inventory found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
