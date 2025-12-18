@props([])
@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp

<header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-[55]">
    <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between h-16 px-4 sm:px-6 lg:px-8">
        <!-- Mobile menu button -->
        <button 
            @click="$dispatch('toggle-sidebar')"
            class="lg:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 {{ $isRtl ? 'order-last' : '' }}"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Right side items -->
        <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-4 {{ $isRtl ? 'order-first' : '' }}">
            <!-- Language Switcher -->
            <div class="relative">
                <x-language-switcher />
            </div>

            <!-- Dark Mode Toggle -->
            <x-dark-mode-toggle />

            <!-- User Dropdown -->
            <x-dropdown align="{{ $isRtl ? 'left' : 'right' }}" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none transition-colors">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-red-600 dark:bg-red-700 flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
                            </div>
                        </div>
                        <span class="hidden md:block">{{ Auth::user()->full_name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        {{ Auth::user()->email }}
                    </div>
                    <x-dropdown-link :href="route('dashboard')">
                        {{ __('admin.Back to Site') }}
                    </x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('admin.Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>

            <!-- Notifications -->
            <div x-data="{ 
                count: 0,
                notifications: [],
                loading: false,
                fetchNotifications() {
                    this.loading = true;
                    fetch('{{ route('admin.notifications') }}')
                        .then(response => response.json())
                        .then(data => {
                            this.count = data.count;
                            this.notifications = data.notifications;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Error fetching notifications:', error);
                            this.loading = false;
                        });
                }
            }"
            x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 60000)"
            class="relative z-[9999]">
                <div class="relative z-[9999]" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                    <div @click="open = ! open; fetchNotifications()">
                        <button class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span x-show="count > 0" 
                                  class="absolute top-0 {{ $isRtl ? 'left-0' : 'right-0' }} flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-semibold text-white bg-red-600 rounded-full ring-2 ring-white dark:ring-gray-800"
                                  x-text="count > 99 ? '99+' : count"
                                  style="display: none;"></span>
                        </button>
                    </div>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute z-[9999] mt-2 w-80 rounded-md shadow-lg right-0 origin-top-right"
                         style="display: none;"
                         @click="open = false">
                        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white dark:bg-gray-700">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ __('admin.Notifications') }}
                            </h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <template x-if="loading">
                                <div class="px-4 py-8 text-center">
                                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-red-600"></div>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.Loading...') }}</p>
                                </div>
                            </template>
                            <template x-if="!loading && notifications.length === 0">
                                <div class="px-4 py-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.No pending blood requests') }}</p>
                                </div>
                            </template>
                            <template x-for="notification in notifications" :key="notification.id">
                                <a :href="notification.url" 
                                   class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-600 border-b border-gray-200 dark:border-gray-700 transition-colors">
                                    <div class="flex items-start {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="w-2 h-2 rounded-full bg-red-600"></div>
                                        </div>
                                        <div class="flex-1 min-w-0 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                <span x-text="notification.patient_name"></span> - <span x-text="notification.blood_type"></span>
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                <span x-text="notification.medical_center"></span> • <span x-text="notification.number_of_bags"></span> {{ __('admin.bags') }}
                                            </p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="notification.created_at"></p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>
                        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.blood-request-management.index', ['status' => 0]) }}" 
                               class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium">
                                {{ __('admin.View all pending requests') }}
                            </a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
