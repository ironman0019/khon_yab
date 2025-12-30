@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('receiver.Blood Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('receiver.All Blood Requests') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('receiver.View all your blood requests') }}</p>
                </div>
                <a href="{{ route('receiver.blood-requests.create') }}" 
                   class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('receiver.Register Blood Request') }}
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <form method="GET" action="{{ route('receiver.blood-requests.index') }}" class="flex flex-col gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('receiver.Search') }}</label>
                            <x-text-input 
                                id="search"
                                name="search" 
                                type="text" 
                                class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                value="{{ request('search') }}"
                                placeholder="{{ __('receiver.Search by patient name, medical center, or request ID') }}"
                            />
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('receiver.Filter by Status') }}</label>
                            <x-select 
                                id="status"
                                name="status" 
                                class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">{{ __('receiver.All Statuses') }}</option>
                                <option value="0" @selected(request('status') == '0')>{{ __('receiver.Pending') }}</option>
                                <option value="1" @selected(request('status') == '1')>{{ __('receiver.Approved') }}</option>
                                <option value="2" @selected(request('status') == '2')>{{ __('receiver.Rejected') }}</option>
                                <option value="3" @selected(request('status') == '3')>{{ __('receiver.Completed') }}</option>
                            </x-select>
                        </div>
                    </div>

                    <div class="flex {{ $isRtl ? 'flex-row-reverse justify-start' : 'justify-end' }} gap-2">
                        <a href="{{ route('receiver.blood-requests.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('receiver.Reset') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('receiver.Filter') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Blood Requests Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if($bloodRequests->count() > 0)
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
                                        {{ __('receiver.Bags') }}
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
                                @foreach($bloodRequests as $request)
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $request->number_of_bags }}
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
                                            <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2">
                                                <a href="{{ route('receiver.blood-requests.show', $request) }}" 
                                                   class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    {{ __('receiver.View') }}
                                                </a>
                                                @if($request->status == 0)
                                                    <a href="{{ route('receiver.blood-requests.edit', $request) }}" 
                                                       class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                        {{ __('receiver.Edit') }}
                                                    </a>
                                                @endif
                                                @if(in_array($request->status, [1, 3]))
                                                    <a href="{{ route('receiver.blood-requests.print', $request) }}" 
                                                       target="_blank"
                                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        {{ __('receiver.Print') }}
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $bloodRequests->links() }}
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <p>{{ __('receiver.No blood requests yet.') }}</p>
                        <a href="{{ route('receiver.blood-requests.create') }}" 
                           class="mt-4 inline-block text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                            {{ __('receiver.Create Your First Blood Request') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

