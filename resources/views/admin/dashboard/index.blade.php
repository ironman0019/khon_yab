@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Dashboard') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Welcome back, here\'s what\'s happening with your blood bank system.') }}</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <x-admin.stat-card 
                    :title="__('admin.Total Donors')" 
                    :value="number_format($statistics['total_donors'] ?? 0)"
                    color="red"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </x-slot>
                </x-admin.stat-card>

                <x-admin.stat-card 
                    :title="__('admin.Blood Requests')" 
                    :value="number_format($statistics['total_blood_requests'] ?? 0)"
                    color="blue"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </x-slot>
                </x-admin.stat-card>

                <x-admin.stat-card 
                    :title="__('admin.Blood Inventory')" 
                    :value="number_format($statistics['total_blood_inventory'] ?? 0)"
                    color="green"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </x-slot>
                </x-admin.stat-card>

                <x-admin.stat-card 
                    :title="__('admin.Donations Today')" 
                    :value="number_format($statistics['donations_today'] ?? 0)"
                    color="purple"
                >
                    <x-slot name="icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </x-slot>
                </x-admin.stat-card>
            </div>

            <!-- Charts and Alerts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Charts Section (2 columns) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Monthly Donations Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.Monthly Donations') }}</h2>
                        <div class="h-64">
                            <canvas id="monthlyDonationsChart"></canvas>
                        </div>
                    </div>

                    <!-- Request Status Chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.Request Status Breakdown') }}</h2>
                        <div class="h-64">
                            <canvas id="requestStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Alerts Section (1 column) -->
                <div class="space-y-6">
                    <!-- Alerts Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.Alerts') }}</h2>
                        <div class="space-y-4">
                            @if(($statistics['alerts']['pending_requests'] ?? 0) > 0)
                                <div class="flex items-start p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 {{ in_array(app()->getLocale(), ['fa', 'ps']) ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                                            {{ __('admin.Pending Requests') }}
                                        </p>
                                        <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">
                                            {{ $statistics['alerts']['pending_requests'] }} {{ __('admin.requests need attention') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if(($statistics['alerts']['expired_blood'] ?? 0) > 0)
                                <div class="flex items-start p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 {{ in_array(app()->getLocale(), ['fa', 'ps']) ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-red-800 dark:text-red-300">
                                            {{ __('admin.Expired Blood') }}
                                        </p>
                                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                            {{ $statistics['alerts']['expired_blood'] }} {{ __('admin.bags need disposal') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if(!empty($statistics['alerts']['low_stock'] ?? []))
                                <div class="flex items-start p-3 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg">
                                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 mt-0.5 {{ in_array(app()->getLocale(), ['fa', 'ps']) ? 'ml-3' : 'mr-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-orange-800 dark:text-orange-300">
                                            {{ __('admin.Low Stock Alert') }}
                                        </p>
                                        <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">
                                            {{ count($statistics['alerts']['low_stock']) }} {{ __('admin.blood types running low') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if(($statistics['alerts']['pending_requests'] ?? 0) === 0 && ($statistics['alerts']['expired_blood'] ?? 0) === 0 && empty($statistics['alerts']['low_stock'] ?? []))
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p>{{ __('admin.All systems operational') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Blood Type Distribution -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.Blood Type Distribution') }}</h2>
                        <div class="h-64">
                            <canvas id="bloodTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('admin.Quick Links') }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <a href="{{ route('admin.user-management.index') }}" class="flex flex-col items-center justify-center p-4 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-colors border border-red-200 dark:border-red-800">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">{{ __('admin.Users') }}</span>
                    </a>

                    <a href="{{ route('admin.donor-management.index') }}" class="flex flex-col items-center justify-center p-4 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-colors border border-red-200 dark:border-red-800">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">{{ __('admin.Donors') }}</span>
                    </a>

                    <a href="{{ route('admin.blood-request-management.index') }}" class="flex flex-col items-center justify-center p-4 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-colors border border-red-200 dark:border-red-800">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">{{ __('admin.Requests') }}</span>
                    </a>

                    <a href="{{ route('admin.inventory-management.index') }}" class="flex flex-col items-center justify-center p-4 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-colors border border-red-200 dark:border-red-800">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">{{ __('admin.Inventory') }}</span>
                    </a>

                    <a href="{{ route('admin.reports-management.index') }}" class="flex flex-col items-center justify-center p-4 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-colors border border-red-200 dark:border-red-800">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center">{{ __('admin.Reports') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Monthly Donations Chart
        const monthlyDonationsData = @json($statistics['graphical_statistics']['monthly_donations'] ?? []);
        const monthlyLabels = Object.keys(monthlyDonationsData);
        const monthlyValues = Object.values(monthlyDonationsData);

        new Chart(document.getElementById('monthlyDonationsChart'), {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: '{{ __('admin.Donations') }}',
                    data: monthlyValues,
                    borderColor: 'rgb(220, 38, 38)',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    tension: 0.4,
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

        // Request Status Chart
        const requestStatusData = @json($statistics['graphical_statistics']['requests_by_status'] ?? []);
        const statusLabels = Object.keys(requestStatusData);
        const statusValues = Object.values(requestStatusData);

        new Chart(document.getElementById('requestStatusChart'), {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: '{{ __('admin.Requests') }}',
                    data: statusValues,
                    backgroundColor: [
                        'rgba(220, 38, 38, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)'
                    ]
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

        // Blood Type Distribution Chart
        const bloodTypeData = @json($statistics['graphical_statistics']['inventory_by_blood_type'] ?? []);
        const bloodTypeLabels = bloodTypeData.map(item => item.blood_type);
        const bloodTypeValues = bloodTypeData.map(item => item.count);

        new Chart(document.getElementById('bloodTypeChart'), {
            type: 'doughnut',
            data: {
                labels: bloodTypeLabels,
                datasets: [{
                    data: bloodTypeValues,
                    backgroundColor: [
                        'rgba(220, 38, 38, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(236, 72, 153, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    @endpush
</x-admin-layout>
