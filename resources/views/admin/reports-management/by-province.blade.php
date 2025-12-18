<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Reports by Province') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('View comprehensive statistics by province') }}</p>
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
                <form method="GET" action="{{ route('admin.reports-management.by-province') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Province -->
                    <div>
                        <label for="province_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Province') }}</label>
                        <select id="province_id" name="province_id" 
                                class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                            <option value="">{{ __('Select Province') }}</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}" {{ request('province_id') == $prov->id ? 'selected' : '' }}>
                                    {{ $prov->name }}
                                </option>
                            @endforeach
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

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Date From') }}</label>
                        <input type="date" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}"
                               class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Date To') }}</label>
                        <input type="date" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}"
                               class="block w-full border-gray-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                    </div>

                    <!-- Buttons -->
                    <div class="md:col-span-4 flex items-end gap-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.reports-management.by-province') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            @if($province)
                <!-- Province Statistics -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ $province->name }}</h2>
                    
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Donors -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Donors') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($donors['total']) }}</p>
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ __('Active') }}: {{ number_format($donors['active']) }}</span> | 
                                <span>{{ __('Healthy') }}: {{ number_format($donors['healthy']) }}</span>
                            </div>
                        </div>

                        <!-- Requests -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Requests') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($requests['total']) }}</p>
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ __('Bags') }}: {{ number_format($requests['total_bags']) }}</span>
                            </div>
                        </div>

                        <!-- Inventory -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Blood Inventory') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($inventory['total']) }}</p>
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ __('In Stock') }}: {{ number_format($inventory['in_stock']) }}</span>
                            </div>
                        </div>

                        <!-- Donations -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Donations') }}</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($donations['total']) }}</p>
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ number_format($donations['total_amount_ml']) }} ml</span>
                            </div>
                        </div>
                    </div>

                    <!-- Blood Type Breakdown -->
                    @if(!empty($donors['by_blood_type']))
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Donors by Blood Type') }}</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($donors['by_blood_type'] as $bloodType => $count)
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($count) }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $bloodType }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 text-center">
                    <p class="text-gray-500 dark:text-gray-400">{{ __('Please select a province to view statistics') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
