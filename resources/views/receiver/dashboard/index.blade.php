@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('receiver.Receiver Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('receiver.Welcome, :name', ['name' => $receiver->user->full_name]) }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('receiver.Manage your blood requests and view your profile') }}</p>
                </div>
                <a href="{{ route('home.index') }}" 
                   class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    {{ __('home.Home') }}
                </a>
            </div>

            <!-- Blood Request Statistics Cards -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('receiver.Blood Request Statistics') }}</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5">
                    <!-- Total Requests -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('receiver.Total Requests') }}</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($statistics['total_requests']) }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Requests -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('receiver.Pending Requests') }}</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($statistics['pending_requests']) }}</p>
                            </div>
                            <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Requests -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('receiver.Approved Requests') }}</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($statistics['approved_requests']) }}</p>
                            </div>
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Requests -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('receiver.Completed Requests') }}</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($statistics['completed_requests']) }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Rejected Requests -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('receiver.Rejected Requests') }}</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($statistics['rejected_requests']) }}</p>
                            </div>
                            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('receiver.blood-requests.create') }}" 
                   class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center gap-4">
                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div class="flex-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('receiver.Register Blood Request') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('receiver.Submit a new blood request') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('receiver.blood-requests.index') }}" 
                   class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center gap-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div class="flex-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('receiver.List Requests') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('receiver.View all your blood requests') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('receiver.profile.edit') }}" 
                   class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center gap-4">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('receiver.Receiver Profile') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('receiver.Manage your receiver information') }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Requests -->
            @if($recentRequests->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('receiver.Recent Requests') }}</h2>
                            <a href="{{ route('receiver.blood-requests.index') }}" 
                               class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                {{ __('receiver.View All') }}
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('receiver.Request ID') }}
                                    </th>
                                    <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('receiver.Date') }}
                                    </th>
                                    <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('receiver.Patient') }}
                                    </th>
                                    <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('receiver.Blood Type') }}
                                    </th>
                                    <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('receiver.Status') }}
                                    </th>
                                    <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('receiver.Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($recentRequests as $request)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            #{{ $request->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $request->created_at->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $request->patient_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $request->blood_type }}{{ $request->rh_factor === 'positive' ? '+' : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($request->status == 0)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    {{ __('receiver.Pending') }}
                                                </span>
                                            @elseif($request->status == 1)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ __('receiver.Approved') }}
                                                </span>
                                            @elseif($request->status == 2)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    {{ __('receiver.Rejected') }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                    {{ __('receiver.Completed') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('receiver.blood-requests.show', $request) }}" 
                                               class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                {{ __('receiver.View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('receiver.No blood requests yet.') }}</p>
                    <a href="{{ route('receiver.blood-requests.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                        {{ __('receiver.Create Your First Blood Request') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

