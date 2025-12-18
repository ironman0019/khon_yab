<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Monthly/Yearly Report') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.View aggregated monthly and yearly statistics') }}</p>
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
                <form method="GET" action="{{ route('admin.reports-management.monthly-yearly') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Period Type -->
                    <div>
                        <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.Period Type') }}</label>
                        <select id="period" name="period" 
                                class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                            <option value="month" {{ request('period', 'month') == 'month' ? 'selected' : '' }}>{{ __('admin.Monthly') }}</option>
                            <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>{{ __('admin.Yearly') }}</option>
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.Start Date') }}</label>
                        <input type="date" id="start_date" name="start_date" 
                               value="{{ request('start_date', \Illuminate\Support\Carbon::now()->startOfMonth()->format('Y-m-d')) }}"
                               class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('admin.End Date') }}</label>
                        <input type="date" id="end_date" name="end_date" 
                               value="{{ request('end_date', \Illuminate\Support\Carbon::now()->endOfMonth()->format('Y-m-d')) }}"
                               class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Filter') }}
                        </button>
                        <a href="{{ route('admin.reports-management.monthly-yearly') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 no-print">
                <!-- Donations Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.Donations by Period') }}</h2>
                    <div class="h-[400px]">
                        <canvas id="donationsChart"></canvas>
                    </div>
                </div>

                <!-- Requests Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.Requests by Period') }}</h2>
                    <div class="h-[400px]">
                        <canvas id="requestsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Statistics Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Period') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Donations') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Total Amount (ml)') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Requests') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Approved') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Total Bags Requested') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($data as $periodData)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $periodData['period'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ number_format($periodData['donations']['count']) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ number_format($periodData['donations']['total_amount_ml']) }} ml
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ number_format($periodData['requests']['count']) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ number_format($periodData['requests']['approved']) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ number_format($periodData['requests']['total_bags']) }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('admin.No data found for the selected period') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const donationsData = @json(array_values($data));
            const periods = donationsData.map(d => d.period);
            const donationCounts = donationsData.map(d => d.donations.count);
            const requestCounts = donationsData.map(d => d.requests.count);

            // Donations Chart
            const donationsCtx = document.getElementById('donationsChart').getContext('2d');
            new Chart(donationsCtx, {
                type: 'bar',
                data: {
                    labels: periods,
                    datasets: [{
                        label: '{{ __('admin.Donations') }}',
                        data: donationCounts,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Requests Chart
            const requestsCtx = document.getElementById('requestsChart').getContext('2d');
            new Chart(requestsCtx, {
                type: 'line',
                data: {
                    labels: periods,
                    datasets: [{
                        label: '{{ __('admin.Requests') }}',
                        data: requestCounts,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>

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

            /* Hide canvas elements (charts) */
            canvas {
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
