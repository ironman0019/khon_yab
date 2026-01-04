@props([])
@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
    $siteLogo = \App\Models\Setting::get('site_logo');
@endphp

<header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2 sm:gap-3 flex-shrink-0 min-w-0">
                @if($siteLogo)
                    <a href="{{ route('home.index') }}" class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <img src="{{ asset('storage/' . $siteLogo) }}" 
                             alt="{{ $siteName }}" 
                             class="h-8 w-8 sm:h-10 sm:w-10 object-contain flex-shrink-0">
                        <span class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white truncate">khonYab</span>
                    </a>
                @else
                    <a href="{{ route('home.index') }}" class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <x-auth-logo inline class="h-8 w-8 sm:h-10 sm:w-10 flex-shrink-0" />
                        <span class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white truncate">khonYab</span>
                    </a>
                @endif
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-6">
                <a href="{{ route('home.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium">
                    {{ __('home.Home') }}
                </a>
                <a href="{{ route('home.about') }}" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium">
                    {{ __('home.About Us') }}
                </a>
                <a href="{{ route('home.search') }}" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium">
                    {{ __('home.Search Blood Requests') }}
                </a>
                <a href="{{ route('home.contact') }}" class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium">
                    {{ __('home.Contact') }}
                </a>
            </div>

            <!-- Right Side Items -->
            <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-3">
                <!-- Desktop Only: Language Switcher -->
                <div class="hidden md:block">
                    <x-language-switcher />
                </div>

                <!-- Desktop Only: Dark Mode Toggle -->
                <div class="hidden md:block">
                    <x-dark-mode-toggle />
                </div>

                <!-- Desktop Only: Sign In Button -->
                <div class="hidden md:block">
                    @auth
                        @php
                            $dashboardRoute = auth()->user()->isAdmin() 
                                ? route('admin.dashboard.index')
                                : match(auth()->user()->user_type) {
                                    \App\Enums\UserType::Donor->value => route('donor.dashboard.index'),
                                    \App\Enums\UserType::Laboratory->value => route('laboratory.dashboard.index'),
                                    \App\Enums\UserType::Receiver->value => route('receiver.dashboard.index'),
                                    default => route('dashboard'),
                                };
                        @endphp
                        <a href="{{ $dashboardRoute }}" 
                           class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('home.Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('home.Sign In') }}
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                    aria-label="Toggle menu"
                >
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div 
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="md:hidden pb-4 border-t border-gray-200 dark:border-gray-700 mt-2 pt-4 relative overflow-visible"
            style="display: none;"
        >
            <div class="flex flex-col {{ $isRtl ? 'items-end' : 'items-start' }} gap-3">
                <!-- Navigation Links -->
                <a href="{{ route('home.index') }}" 
                   class="px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium">
                    {{ __('home.Home') }}
                </a>
                <a href="{{ route('home.about') }}" 
                   class="px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium">
                    {{ __('home.About Us') }}
                </a>
                <a href="{{ route('home.search') }}" 
                   class="px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium">
                    {{ __('home.Search Blood Requests') }}
                </a>
                <a href="{{ route('home.contact') }}" 
                   class="px-3 py-2 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors font-medium">
                    {{ __('home.Contact') }}
                </a>

                <!-- Divider -->
                <div class="border-t border-gray-200 dark:border-gray-700 my-2 w-full"></div>

                <!-- Mobile Only: Language Switcher -->
                <div class="w-full {{ $isRtl ? 'text-right' : 'text-left' }} relative overflow-visible">
                    <div class="px-3 py-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                        Language
                    </div>
                    <div class="px-3 relative overflow-visible">
                        <x-language-switcher class="w-full" />
                    </div>
                </div>

                <!-- Mobile Only: Dark Mode Toggle -->
                <div class="flex items-center justify-between w-full px-3 py-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Dark Mode
                    </span>
                    <x-dark-mode-toggle />
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-200 dark:border-gray-700 my-2 w-full"></div>

                <!-- Mobile Only: Sign In/Dashboard Button -->
                @auth
                    @php
                        $dashboardRoute = auth()->user()->isAdmin() 
                            ? route('admin.dashboard.index')
                            : match(auth()->user()->user_type) {
                                \App\Enums\UserType::Donor->value => route('donor.dashboard.index'),
                                \App\Enums\UserType::Laboratory->value => route('laboratory.dashboard.index'),
                                \App\Enums\UserType::Receiver->value => route('receiver.dashboard.index'),
                                default => route('dashboard'),
                            };
                    @endphp
                    <a href="{{ $dashboardRoute }}" 
                       class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors text-center">
                        {{ __('home.Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors text-center">
                        {{ __('home.Sign In') }}
                    </a>
                @endauth
            </div>
        </div>
    </nav>
</header>

