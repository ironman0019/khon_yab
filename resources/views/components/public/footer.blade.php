@props([])
@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
    $siteEmail = \App\Models\Setting::get('site_email');
    $sitePhone = \App\Models\Setting::get('site_phone');
@endphp

<footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Site Info -->
            <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ $siteName }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('home.Blood Bank Management System') }}
                </p>
            </div>

            <!-- Quick Links -->
            <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('home.Quick Links') }}
                </h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('home.index') }}" 
                           class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                            {{ __('home.Home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home.about') }}" 
                           class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                            {{ __('home.About Us') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home.search') }}" 
                           class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                            {{ __('home.Search Blood Requests') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home.contact') }}" 
                           class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                            {{ __('home.Contact') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('home.Contact Information') }}
                </h3>
                <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    @if($siteEmail)
                    <li class="flex items-center {{ $isRtl ? 'flex-row-reverse justify-end' : '' }} gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <a href="mailto:{{ $siteEmail }}" class="hover:text-red-600 dark:hover:text-red-400 transition-colors">
                            {{ $siteEmail }}
                        </a>
                    </li>
                    @endif
                    @if($sitePhone)
                    <li class="flex items-center {{ $isRtl ? 'flex-row-reverse justify-end' : '' }} gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <a href="tel:{{ $sitePhone }}" class="hover:text-red-600 dark:hover:text-red-400 transition-colors">
                            {{ $sitePhone }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700 {{ $isRtl ? 'text-right' : 'text-center' }}">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                &copy; {{ date('Y') }} {{ $siteName }}. {{ __('home.All rights reserved.') }}
            </p>
        </div>
    </div>
</footer>

