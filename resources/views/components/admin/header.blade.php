@props([])
@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp

<header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
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

            <!-- Notifications -->
            <button class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="absolute top-0 {{ $isRtl ? 'left-0' : 'right-0' }} block h-2 w-2 rounded-full bg-red-600 ring-2 ring-white dark:ring-gray-800"></span>
            </button>

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
        </div>
    </div>
</header>
