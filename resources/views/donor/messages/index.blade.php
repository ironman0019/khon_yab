@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('donor.Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('donor.Messages') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('donor.View and manage your messages') }}</p>
                </div>
                <a href="{{ route('donor.messages.create') }}" 
                   class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('donor.New Message') }}
                </a>
            </div>

            <!-- Conversations List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                @forelse($conversations as $conversation)
                    <a href="{{ route('donor.messages.show', $conversation['partner']->id) }}" 
                       class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700 transition-colors {{ $loop->last ? '' : 'border-b' }}">
                        <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between">
                            <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center gap-4 flex-1 min-w-0">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-red-600 dark:bg-red-700 flex items-center justify-center text-white text-lg font-semibold">
                                        {{ strtoupper(substr($conversation['partner']->full_name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center gap-2">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $conversation['partner']->full_name }}
                                        </h3>
                                        @if($conversation['unread_count'] > 0)
                                            <span class="flex-shrink-0 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium bg-blue-600 text-white rounded-full">
                                                {{ $conversation['unread_count'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate mt-1">
                                        {{ $conversation['latest_message']->message }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        {{ $conversation['latest_message']->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex-shrink-0 {{ $isRtl ? 'mr-4' : 'ml-4' }}">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('donor.No conversations') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('donor.Get started by sending a new message.') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('donor.messages.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                {{ __('donor.New Message') }}
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

