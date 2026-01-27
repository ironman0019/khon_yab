@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('laboratory.Messages') }}
        </h2>
    </x-slot>

    <div class="h-[calc(100vh-8rem)] flex flex-col">
        <!-- WhatsApp-style Two-Panel Layout -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Left Panel: Conversations List -->
            <div class="w-full md:w-[350px] flex flex-col border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <!-- Header with New Message Button -->
                <div class="h-16 px-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('laboratory.Messages') }}</h1>
                    <a href="{{ route('laboratory.messages.create') }}" 
                       class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                       title="{{ __('laboratory.New Message') }}">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="px-3 py-2 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                    <div class="relative">
                        <div class="absolute inset-y-0 {{ $isRtl ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="conversation-search"
                            class="block w-full {{ $isRtl ? 'pr-10 pl-3' : 'pl-10 pr-3' }} py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                            placeholder="{{ __('laboratory.Search conversations...') }}"
                        >
                    </div>
                </div>

                <!-- Conversations List -->
                <div class="flex-1 overflow-y-auto" id="conversations-list">
                    @forelse($conversations as $conversation)
                        <a href="{{ route('laboratory.messages.show', $conversation['partner']->id) }}" 
                           class="conversation-item block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700 transition-colors"
                           data-name="{{ strtolower($conversation['partner']->full_name) }}"
                           data-message="{{ strtolower($conversation['latest_message']->message ?? '') }}">
                            <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center gap-3">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-red-600 dark:bg-red-700 flex items-center justify-center text-white text-lg font-semibold">
                                        {{ strtoupper(substr($conversation['partner']->full_name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center justify-between gap-2 mb-1">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $conversation['partner']->full_name }}
                                        </h3>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">
                                            {{ $conversation['latest_message']->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center gap-2">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate flex-1">
                                            {{ $conversation['latest_message']->message }}
                                        </p>
                                        @if($conversation['unread_count'] > 0)
                                            <span class="flex-shrink-0 inline-flex items-center justify-center w-5 h-5 text-xs font-medium bg-red-600 text-white rounded-full">
                                                {{ $conversation['unread_count'] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('laboratory.No conversations') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('laboratory.Get started by sending a new message.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Panel: Empty State -->
            <div class="hidden md:flex flex-1 flex-col items-center justify-center bg-gray-50 dark:bg-gray-900">
                <div class="text-center px-4">
                    <svg class="mx-auto h-24 w-24 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('laboratory.Select a conversation') }}</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('laboratory.Choose a conversation from the list to start messaging') }}</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('conversation-search');
            const conversationsList = document.getElementById('conversations-list');
            const conversationItems = conversationsList.querySelectorAll('.conversation-item');

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    
                    conversationItems.forEach(function(item) {
                        const name = item.getAttribute('data-name') || '';
                        const message = item.getAttribute('data-message') || '';
                        
                        if (name.includes(searchTerm) || message.includes(searchTerm)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
