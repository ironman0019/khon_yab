<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Bag Expiration Report') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.View expiring and expired blood bags') }}</p>
                </div>
                <div class="flex gap-2 no-print">
                    <a href="{{ route('admin.reports-management.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('admin.Back to Reports') }}
                    </a>
                    <button onclick="window.print()" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        {{ __('admin.Print') }}
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6 no-print">
                <form method="GET" action="{{ route('admin.reports-management.bag-expiration') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Expiration Type -->
                    <div>
                        <label for="expiration_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.Expiration Type') }}</label>
                        <select id="expiration_type" name="expiration_type" 
                                class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                            <option value="">{{ __('admin.All') }}</option>
                            <option value="expired" {{ request('expiration_type') == 'expired' ? 'selected' : '' }}>{{ __('admin.Expired') }}</option>
                            <option value="expiring_soon" {{ request('expiration_type') == 'expiring_soon' ? 'selected' : '' }}>{{ __('admin.Expiring Soon (7 days)') }}</option>
                        </select>
                    </div>

                    <!-- Blood Type -->
                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.Blood Type') }}</label>
                        <select id="blood_type" name="blood_type" 
                                class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                            <option value="">{{ __('admin.All Blood Types') }}</option>
                            <option value="A" {{ request('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ request('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ request('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ request('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                        </select>
                    </div>

                    <!-- Expiration Date From -->
                    <div>
                        <label for="expiration_date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.Expiration Date From') }}</label>
                        <input type="date" id="expiration_date_from" name="expiration_date_from" 
                               value="{{ request('expiration_date_from') }}"
                               class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                    </div>

                    <!-- Expiration Date To -->
                    <div>
                        <label for="expiration_date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.Expiration Date To') }}</label>
                        <input type="date" id="expiration_date_to" name="expiration_date_to" 
                               value="{{ request('expiration_date_to') }}"
                               class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                    </div>

                    <!-- Buttons -->
                    <div class="lg:col-span-4 flex items-end gap-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Filter') }}
                        </button>
                        <a href="{{ route('admin.reports-management.bag-expiration') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 no-print">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <p class="text-sm font-medium text-red-800 dark:text-red-400">{{ __('admin.Expired Bags') }}</p>
                    <p class="text-2xl font-bold text-red-900 dark:text-red-300">{{ number_format($expired_count) }}</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-400">{{ __('admin.Expiring Soon') }}</p>
                    <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-300">{{ number_format($expiring_soon_count) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.Total Bags') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($inventory->count()) }}</p>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Bag ID') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Blood Type') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Entry Date') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Expiration Date') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Status') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($inventory as $item)
                                @php
                                    $today = \Illuminate\Support\Carbon::today();
                                    $expired = $item->expiration_date && $item->expiration_date->lt($today);
                                    $expiringSoon = $item->expiration_date && $item->expiration_date->gte($today) && $item->expiration_date->lte($today->copy()->addDays(7));
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $expired ? 'bg-red-50 dark:bg-red-900/20' : ($expiringSoon ? 'bg-yellow-50 dark:bg-yellow-900/20' : '') }}">
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
                                            {{ $item->entry_date ? $item->entry_date->format('Y-m-d') : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium {{ $expired ? 'text-red-600 dark:text-red-400' : ($expiringSoon ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-white') }}">
                                            {{ $item->expiration_date ? $item->expiration_date->format('Y-m-d') : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($expired)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                {{ __('admin.Expired') }}
                                            </span>
                                        @elseif($expiringSoon)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                {{ __('admin.Expiring Soon') }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ __('admin.Valid') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('admin.No expiring bags found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            /* Hide elements with no-print class */
            .no-print {
                display: none !important;
            }

            /* Hide sidebar */
            aside {
                display: none !important;
            }

            /* Hide header */
            header {
                display: none !important;
            }

            /* Hide page description */
            .mb-6.flex.justify-between.items-center p {
                display: none !important;
            }

            /* Reset body and main container */
            body {
                margin: 0 !important;
                padding: 20px !important;
                background: #fff !important;
            }

            main {
                padding: 0 !important;
                background: #fff !important;
                overflow: visible !important;
            }

            /* Show content container */
            .py-6 {
                padding: 0 !important;
            }

            .max-w-7xl {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Style the page title */
            h1 {
                font-size: 24px !important;
                font-weight: bold !important;
                color: #000 !important;
                margin-bottom: 20px !important;
                margin-top: 0 !important;
            }

            /* Style the table for printing */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                border: 1px solid #000 !important;
                margin-top: 20px !important;
            }

            th, td {
                border: 1px solid #000 !important;
                padding: 8px !important;
                text-align: left !important;
                color: #000 !important;
                background: #fff !important;
            }

            th {
                background-color: #f3f4f6 !important;
                font-weight: bold !important;
            }

            /* Remove dark mode colors from table */
            table * {
                color: #000 !important;
            }

            thead {
                background-color: #f3f4f6 !important;
            }

            tbody tr {
                background: #fff !important;
            }

            /* Ensure table container is visible */
            .bg-white.rounded-lg.shadow-sm.border {
                border: none !important;
                box-shadow: none !important;
                background: #fff !important;
            }

            /* Show table container */
            .overflow-x-auto {
                overflow: visible !important;
            }

            /* Hide alerts */
            [role="alert"] {
                display: none !important;
            }
        }
    </style>
</x-admin-layout>
