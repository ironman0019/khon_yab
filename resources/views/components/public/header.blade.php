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
            <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                @if($siteLogo)
                    <a href="{{ route('home.index') }}" class="flex items-center">
                        <img src="{{ asset('storage/' . $siteLogo) }}" 
                             alt="{{ $siteName }}" 
                             class="h-10 w-auto object-contain">
                    </a>
                @else
                    <a href="{{ route('home.index') }}" class="flex items-center">
                        <x-auth-logo inline class="h-10 w-10" />
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
                <!-- Language Switcher -->
                <x-language-switcher />

                <!-- Dark Mode Toggle -->
                <x-dark-mode-toggle />

                <!-- Sign In Button -->
                @auth
                    @php
                        $dashboardRoute = auth()->user()->isAdmin() 
                            ? route('admin.dashboard.index')
                            : match(auth()->user()->user_type) {
                                \App\Enums\UserType::Donor->value => route('donor.dashboard.index'),
                                \App\Enums\UserType::HospitalUser->value => route('hospital.dashboard.index'),
                                \App\Enums\UserType::User->value => route('user.dashboard.index'),
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
            class="md:hidden pb-4 border-t border-gray-200 dark:border-gray-700 mt-2 pt-4"
            style="display: none;"
        >
            <div class="flex flex-col {{ $isRtl ? 'items-end' : 'items-start' }} gap-3">
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
            </div>
        </div>
    </nav>
</header>

