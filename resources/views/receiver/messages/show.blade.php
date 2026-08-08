@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
    $currentUser = auth()->user();
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('receiver.Messages') }}
        </h2>
    </x-slot>

    <div class="h-[calc(100vh-8rem)] flex flex-col">
        <!-- WhatsApp-style Two-Panel Layout -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Left Panel: Conversations List -->
            <div class="hidden md:flex w-[350px] flex-col border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <!-- Header with New Message Button -->
                <div class="h-16 px-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('receiver.Messages') }}</h1>
                    <a href="{{ route('receiver.messages.create') }}" 
                       class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                       title="{{ __('receiver.New Message') }}">
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
                            placeholder="{{ __('receiver.Search conversations...') }}"
                        >
                    </div>
                </div>

                <!-- Conversations List -->
                <div class="flex-1 overflow-y-auto" id="conversations-list">
                    @forelse($conversations as $conversation)
                        <a href="{{ route('receiver.messages.show', $conversation['partner']->id) }}" 
                           class="conversation-item block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700 transition-colors {{ $conversation['partner']->id === $user->id ? 'bg-gray-100 dark:bg-gray-700' : '' }}"
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
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('receiver.No conversations') }}</h3>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Panel: Chat View -->
            <div class="flex-1 flex flex-col bg-gray-50 dark:bg-gray-900">
                <!-- Chat Header -->
                <div class="h-16 px-4 flex items-center gap-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <!-- Back Button (Mobile Only) -->
                    <a href="{{ route('receiver.messages.index') }}" 
                       class="md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center gap-3 flex-1">
                        <div class="w-10 h-10 rounded-full bg-red-600 dark:bg-red-700 flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr($user->full_name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0 {{ $isRtl ? 'text-right' : 'text-left' }}">
                            <h2 class="text-base font-semibold text-gray-900 dark:text-white truncate">{{ $user->full_name }}</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto px-4 py-4 space-y-2" id="messages-container" data-user-id="{{ $user->id }}" data-last-message-id="{{ $messages->last()?->id ?? 0 }}">
                    @forelse($messages as $message)
                        <div class="message-item flex {{ $message->sender_id === $currentUser->id ? ($isRtl ? 'justify-start' : 'justify-end') : ($isRtl ? 'justify-end' : 'justify-start') }}" data-message-id="{{ $message->id }}">
                            <div class="max-w-[65%] md:max-w-[70%]">
                                @if($message->subject && $loop->first)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1 px-2 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                        {{ $message->subject }}
                                    </div>
                                @endif
                                <div class="rounded-lg px-4 py-2 {{ $message->sender_id === $currentUser->id ? 'bg-red-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm' }}">
                                    <p class="text-sm whitespace-pre-wrap break-words">{{ $message->message }}</p>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 px-2 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                    {{ $message->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('receiver.No messages yet') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input Area -->
                <div class="border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                    <form method="POST" action="{{ route('receiver.messages.store') }}" class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-end gap-2">
                        @csrf
                        <input type="hidden" name="recipient_user_type" value="{{ $user->user_type }}">
                        <input type="hidden" name="recipient_email" value="{{ $user->email }}">
                        
                        <div class="flex-1">
                            <textarea 
                                id="message-input"
                                name="message" 
                                rows="1"
                                required
                                class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none text-sm"
                                placeholder="{{ __('receiver.Type your message...') }}"
                                oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 120) + 'px'"
                            ></textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button type="submit" 
                                id="send-button"
                                class="flex-shrink-0 p-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            const isRtl = {{ $isRtl ? 'true' : 'false' }};
            const currentUserId = {{ $currentUser->id }};
            const recipientId = {{ $user->id }};
            const fetchMessagesUrl = '{{ route('receiver.messages.fetch', $user->id) }}';
            const fetchConversationsUrl = '{{ route('receiver.messages.conversations.fetch') }}';
            const storeMessageUrl = '{{ route('receiver.messages.store') }}';
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            let lastMessageId = parseInt(document.getElementById('messages-container')?.getAttribute('data-last-message-id') || '0');
            let pollingInterval = null;
            let isPolling = false;
            let hasShownSubject = false;

            document.addEventListener('DOMContentLoaded', function() {
                initAutoScroll();
                initSearch();
                initMessageForm();
                startPolling();
                
                document.addEventListener('visibilitychange', function() {
                    if (document.hidden) {
                        stopPolling();
                    } else {
                        startPolling();
                    }
                });
            });

            function initAutoScroll() {
                const container = document.getElementById('messages-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }

            function scrollToBottom() {
                const container = document.getElementById('messages-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }

            function initSearch() {
                const searchInput = document.getElementById('conversation-search');
                const conversationsList = document.getElementById('conversations-list');
                const conversationItems = conversationsList ? conversationsList.querySelectorAll('.conversation-item') : [];

                if (searchInput && conversationItems.length > 0) {
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
            }

            function initMessageForm() {
                const messageInput = document.getElementById('message-input');
                const sendButton = document.getElementById('send-button');
                const form = messageInput ? messageInput.closest('form') : null;

                if (!form || !messageInput) return;

                messageInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        if (sendButton && sendButton.disabled) return;
                        form.requestSubmit();
                    }
                });

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const message = messageInput.value.trim();
                    if (!message) return;

                    sendButton.disabled = true;
                    messageInput.disabled = true;
                    const originalButtonContent = sendButton.innerHTML;
                    sendButton.innerHTML = '<svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

                    fetch(storeMessageUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            recipient_user_type: form.querySelector('input[name="recipient_user_type"]').value,
                            recipient_email: form.querySelector('input[name="recipient_email"]').value,
                            message: message
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.message) {
                            addMessageToUI(data.message, true);
                            
                            if (data.conversations) {
                                updateConversationsList(data.conversations);
                            }
                            
                            messageInput.value = '';
                            messageInput.style.height = '';
                            lastMessageId = data.message.id;
                            
                            // Show success message
                            showSuccess('Message sent successfully.');
                        } else {
                            const errorMsg = data.message || data.error || 'Failed to send message. Please try again.';
                            showError(errorMsg);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        let errorMsg = 'Failed to send message. Please try again.';
                        if (error.errors) {
                            const firstError = Object.values(error.errors)[0];
                            errorMsg = Array.isArray(firstError) ? firstError[0] : firstError;
                        } else if (error.message) {
                            errorMsg = error.message;
                        }
                        showError(errorMsg);
                    })
                    .finally(() => {
                        sendButton.disabled = false;
                        messageInput.disabled = false;
                        sendButton.innerHTML = originalButtonContent;
                        messageInput.focus();
                    });
                });
            }

            function addMessageToUI(message, isOwnMessage) {
                const container = document.getElementById('messages-container');
                if (!container) return;

                const emptyState = container.querySelector('.flex.items-center.justify-center.h-full');
                if (emptyState) {
                    emptyState.remove();
                }

                const messageDiv = document.createElement('div');
                messageDiv.className = `message-item flex ${isOwnMessage ? (isRtl ? 'justify-start' : 'justify-end') : (isRtl ? 'justify-end' : 'justify-start')}`;
                messageDiv.setAttribute('data-message-id', message.id);

                const isCurrentUser = message.sender_id === currentUserId;
                const messageTime = new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });

                let subjectHtml = '';
                if (message.subject && !hasShownSubject) {
                    subjectHtml = `<div class="text-xs text-gray-500 dark:text-gray-400 mb-1 px-2 ${isRtl ? 'text-right' : 'text-left'}">${escapeHtml(message.subject)}</div>`;
                    hasShownSubject = true;
                }

                messageDiv.innerHTML = `
                    <div class="max-w-[65%] md:max-w-[70%]">
                        ${subjectHtml}
                        <div class="rounded-lg px-4 py-2 ${isCurrentUser ? 'bg-red-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm'}">
                            <p class="text-sm whitespace-pre-wrap break-words">${escapeHtml(message.message)}</p>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 px-2 ${isRtl ? 'text-right' : 'text-left'}">${messageTime}</div>
                    </div>
                `;

                container.appendChild(messageDiv);
                scrollToBottom();
            }

            function updateConversationsList(conversations) {
                const conversationsList = document.getElementById('conversations-list');
                if (!conversationsList || !conversations) return;

                conversations.forEach(function(conv) {
                    const existingItem = conversationsList.querySelector(`a[href*="/messages/${conv.partner.id}"]`);
                    const partner = conv.partner;
                    const latestMessage = conv.latest_message;
                    const unreadCount = conv.unread_count || 0;
                    
                    const time = latestMessage ? new Date(latestMessage.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false }) : '';
                    const isActive = partner.id === recipientId;
                    
                    const itemHtml = `
                        <a href="/receiver/messages/${partner.id}" 
                           class="conversation-item block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700 transition-colors ${isActive ? 'bg-gray-100 dark:bg-gray-700' : ''}"
                           data-name="${partner.full_name.toLowerCase()}"
                           data-message="${(latestMessage?.message || '').toLowerCase()}">
                            <div class="flex ${isRtl ? 'flex-row-reverse' : ''} items-center gap-3">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-red-600 dark:bg-red-700 flex items-center justify-center text-white text-lg font-semibold">
                                        ${partner.full_name.charAt(0).toUpperCase()}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0 ${isRtl ? 'text-right' : 'text-left'}">
                                    <div class="flex ${isRtl ? 'flex-row-reverse' : ''} items-center justify-between gap-2 mb-1">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">${escapeHtml(partner.full_name)}</h3>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">${time}</span>
                                    </div>
                                    <div class="flex ${isRtl ? 'flex-row-reverse' : ''} items-center gap-2">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate flex-1">${escapeHtml(latestMessage?.message || '')}</p>
                                        ${unreadCount > 0 ? `<span class="flex-shrink-0 inline-flex items-center justify-center w-5 h-5 text-xs font-medium bg-red-600 text-white rounded-full">${unreadCount}</span>` : ''}
                                    </div>
                                </div>
                            </div>
                        </a>
                    `;

                    if (existingItem) {
                        existingItem.outerHTML = itemHtml;
                    } else {
                        conversationsList.insertAdjacentHTML('beforeend', itemHtml);
                    }
                });
            }

            function fetchNewMessages() {
                if (isPolling) return;
                isPolling = true;

                fetch(`${fetchMessagesUrl}?since_id=${lastMessageId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(function(message) {
                            const existing = document.querySelector(`.message-item[data-message-id="${message.id}"]`);
                            if (!existing) {
                                addMessageToUI(message, message.sender_id === currentUserId);
                                lastMessageId = Math.max(lastMessageId, message.id);
                            }
                        });
                        
                        fetchConversations();
                    }
                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                })
                .finally(() => {
                    isPolling = false;
                });
            }

            function fetchConversations() {
                fetch(fetchConversationsUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.conversations) {
                        updateConversationsList(data.conversations);
                    }
                })
                .catch(error => {
                    console.error('Error fetching conversations:', error);
                });
            }

            function startPolling() {
                if (pollingInterval) return;
                pollingInterval = setInterval(fetchNewMessages, 2500);
            }

            function stopPolling() {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }

            function showError(message) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg z-50';
                errorDiv.textContent = message;
                document.body.appendChild(errorDiv);
                
                setTimeout(() => {
                    errorDiv.remove();
                }, 3000);
            }

            function showSuccess(message) {
                // Remove any existing success notifications
                const existing = document.querySelector('.success-notification');
                if (existing) {
                    existing.remove();
                }
                
                // Create success notification
                const successDiv = document.createElement('div');
                successDiv.className = 'success-notification fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-[9999] transition-opacity duration-300';
                successDiv.textContent = message;
                successDiv.style.opacity = '0';
                document.body.appendChild(successDiv);
                
                // Fade in
                requestAnimationFrame(() => {
                    successDiv.style.opacity = '1';
                });
                
                // Auto-dismiss after 3 seconds
                setTimeout(() => {
                    successDiv.style.opacity = '0';
                    setTimeout(() => {
                        if (successDiv.parentNode) {
                            successDiv.remove();
                        }
                    }, 300);
                }, 3000);
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
        })();
    </script>
    @endpush
</x-app-layout>
