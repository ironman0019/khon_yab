@props([])
@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
    $locale = app()->getLocale();
    
    // Helper function to extract string value from setting (handles arrays/translations)
    $getSettingValue = function($value, $default = null) use ($locale) {
        if (is_array($value)) {
            // If it's an array, try to get the value for current locale
            if (isset($value[$locale])) {
                return $value[$locale];
            }
            // Fallback to first value if available
            if (!empty($value)) {
                return reset($value);
            }
            return $default;
        }
        return $value ?? $default;
    };
    
    $siteNameRaw = \App\Models\Setting::get('site_name', config('app.name'));
    $siteName = $getSettingValue($siteNameRaw, config('app.name'));
    
    $siteEmailRaw = \App\Models\Setting::get('site_email');
    $siteEmail = $getSettingValue($siteEmailRaw);
    
    $sitePhoneRaw = \App\Models\Setting::get('site_phone');
    $sitePhone = $getSettingValue($sitePhoneRaw);
    
    $siteAddressRaw = \App\Models\Setting::get('site_address');
    $siteAddress = $getSettingValue($siteAddressRaw);
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
                    {{ __('home.Blood Donation Management System') }}
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
                    <li class="flex items-center {{ $isRtl ? 'flex-row-reverse justify-end' : '' }} gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                        </svg>
                        <a href="https://github.com/ironman0019/khon_yab.git"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="hover:text-red-600 dark:hover:text-red-400 transition-colors">
                            {{ __('home.GitHub') }}
                        </a>
                    </li>
                    @if($siteAddress)
                    <li class="flex items-start {{ $isRtl ? 'flex-row-reverse justify-end' : '' }} gap-2">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="hover:text-red-600 dark:hover:text-red-400 transition-colors">
                            {{ $siteAddress }}
                        </span>
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

