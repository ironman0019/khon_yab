@props([])
@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp

<div 
    x-data="{ open: false }"
    @toggle-sidebar.window="open = !open"
    class="bg-white dark:bg-gray-800 w-64 h-screen shadow-lg fixed inset-y-0 z-50 transform transition-transform duration-200 ease-in-out flex flex-col overflow-hidden {{ $isRtl ? 'right-0 translate-x-full' : 'left-0 -translate-x-full' }} lg:translate-x-0 lg:static"
    :class="open && '!translate-x-0'"
>
    <!-- Logo -->
    <div class="flex items-center {{ $isRtl ? 'flex-row-reverse justify-between' : 'justify-between' }} h-16 px-4 bg-red-600 dark:bg-red-700 flex-shrink-0">
        <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2">
            <div class="text-white">
                <svg viewBox="0 0 200 200" class="w-10 h-10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Blood Drop Shape -->
                    <path d="M100 30 C80 30, 60 50, 60 80 C60 110, 80 140, 100 170 C120 140, 140 110, 140 80 C140 50, 120 30, 100 30 Z" 
                          class="fill-white" />
                    <!-- Cross Symbol -->
                    <line x1="100" y1="90" x2="100" y2="130" stroke="red" stroke-width="8" stroke-linecap="round"/>
                    <line x1="85" y1="110" x2="115" y2="110" stroke="red" stroke-width="8" stroke-linecap="round"/>
                </svg>
            </div>
            <span class="text-white font-bold text-lg">KhonYab</span>
        </div>
        <button @click="open = false" class="lg:hidden text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="admin-sidebar-scroll mt-6 px-3 flex-1 overflow-y-auto pb-6">
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ __('admin.Dashboard') }}
            </a>

            <!-- User Management -->
            <a href="{{ route('admin.user-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.user-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                {{ __('admin.User Management') }}
            </a>

            <!-- Receiver Management -->
            <a href="{{ route('admin.receiver-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.receiver-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ __('admin.Receiver Management') }}
            </a>

            <!-- Donor Management -->
            <a href="{{ route('admin.donor-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.donor-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                {{ __('admin.Donor Management') }}
            </a>

            <!-- Laboratory Management -->
            <a href="{{ route('admin.laboratory-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.laboratory-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                {{ __('admin.Laboratory Management') }}
            </a>

            <!-- Blood Request Management -->
            <a href="{{ route('admin.blood-request-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.blood-request-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                {{ __('admin.Blood Request Management') }}
            </a>

            <!-- Blood Donation Record Management -->
            <a href="{{ route('admin.blood-donation-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.blood-donation-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ __('admin.Blood Donation Records') }}
            </a>

            <!-- Blood Inventory Management -->
            <a href="{{ route('admin.inventory-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.inventory-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                {{ __('admin.Blood Inventory Management') }}
            </a>

            <!-- Language Management -->
            <a href="{{ route('admin.language-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.language-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                </svg>
                {{ __('admin.Language Management') }}
            </a>

            <!-- Province/City Management -->
            <a href="{{ route('admin.province-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.province-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('admin.Province/City Management') }}
            </a>

            <!-- Reports Management -->
            <a href="{{ route('admin.reports-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.reports-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ __('admin.Reports Management') }}
            </a>

            <!-- Contact Messages -->
            <a href="{{ route('admin.contact-message-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.contact-message-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                {{ __('admin.Contact Messages') }}
            </a>

            <!-- Database Backup -->
            <a href="{{ route('admin.backup-management.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.backup-management.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                {{ __('admin.Database Backup') }}
            </a>

            <!-- Site Settings -->
            <a href="{{ route('admin.settings.index') }}" 
               class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ __('admin.Site Settings') }}
            </a>
        </div>
    </nav>
</div>

<!-- Mobile Sidebar Overlay -->
<div 
    x-data="{ open: false }" 
    @toggle-sidebar.window="open = !open"
    @click="open = false" 
    x-show="open" 
    class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden transition-opacity duration-200" 
    style="display: none;"
    x-transition:enter="transition-opacity ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
></div>

<style>
    .admin-sidebar-scroll {
        scrollbar-width: thin;
        scrollbar-color: #dc2626 #f3f4f6;
    }

    .dark .admin-sidebar-scroll {
        scrollbar-color: #ef4444 #1f2937;
    }

    .admin-sidebar-scroll::-webkit-scrollbar {
        width: 8px;
    }

    .admin-sidebar-scroll::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 9999px;
    }

    .dark .admin-sidebar-scroll::-webkit-scrollbar-track {
        background: #1f2937;
    }

    .admin-sidebar-scroll::-webkit-scrollbar-thumb {
        background: #dc2626;
        border-radius: 9999px;
        border: 2px solid #f3f4f6;
        background-clip: padding-box;
    }

    .dark .admin-sidebar-scroll::-webkit-scrollbar-thumb {
        background: #ef4444;
        border-color: #1f2937;
    }

    .admin-sidebar-scroll::-webkit-scrollbar-thumb:hover {
        background: #b91c1c;
    }

    .dark .admin-sidebar-scroll::-webkit-scrollbar-thumb:hover {
        background: #f87171;
    }
</style>
