@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Blood Request Management') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Manage all blood requests') }}</p>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <form method="GET" action="{{ route('admin.blood-request-management.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Search') }}</label>
                        <x-text-input 
                            id="search"
                            name="search" 
                            type="text" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            value="{{ request('search') }}"
                            placeholder="{{ __('admin.Search by patient name, medical center, contact...') }}"
                        />
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
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('admin.Pending') }}</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('admin.Approved') }}</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>{{ __('admin.Rejected') }}</option>
                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>{{ __('admin.Completed') }}</option>
                        </x-select>
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

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Date From') }}</label>
                        <x-text-input 
                            id="date_from"
                            name="date_from" 
                            type="date" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            value="{{ request('date_from') }}"
                        />
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Date To') }}</label>
                        <x-text-input 
                            id="date_to"
                            name="date_to" 
                            type="date" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            value="{{ request('date_to') }}"
                        />
                    </div>

                    <!-- Buttons -->
                    <div class="lg:col-span-5 flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-end gap-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Filter') }}
                        </button>
                        <a href="{{ route('admin.blood-request-management.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Blood Requests Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Patient') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Blood Type') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Bags') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Medical Center') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Location') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Status') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Requested By') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Date') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-left' : 'text-right' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($bloodRequests as $request)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $request->patient_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('admin.Age') }}: {{ $request->patient_age }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            {{ $request->blood_type }}{{ $request->rh_factor == 'positive' ? '+' : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $request->number_of_bags }} {{ __('admin.bags') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $request->medical_center }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $request->contact_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $request->city->name ?? '' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $request->province->name ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($request->status == 0)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                {{ __('admin.Pending') }}
                                            </span>
                                        @elseif($request->status == 1)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                {{ __('admin.Approved') }}
                                            </span>
                                        @elseif($request->status == 2)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                {{ __('admin.Rejected') }}
                                            </span>
                                        @elseif($request->status == 3)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                {{ __('admin.Completed') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $request->requestedBy->full_name ?? '' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $request->requestedBy->email ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $request->created_at->format('Y-m-d') }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $request->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap {{ $isRtl ? 'text-left' : 'text-right' }} text-sm font-medium">
                                        <div class="flex {{ $isRtl ? 'justify-start' : 'justify-end' }} gap-2">
                                            <a href="{{ route('admin.blood-request-management.show', $request) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                               title="{{ __('admin.View') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            @if($request->status == 0)
                                                <a href="{{ route('admin.blood-request-management.edit', $request) }}" 
                                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors"
                                                   title="{{ __('admin.Edit') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('admin.No blood requests found') }}</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.No blood requests match your filters.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($bloodRequests->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $bloodRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

