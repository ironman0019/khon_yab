<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['fa', 'ps']) ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('auth.Blood Bank Management System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Persian/Pashto Fonts -->
        @if(in_array(app()->getLocale(), ['fa', 'ps']))
        <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Vazirmatn', sans-serif; }
        </style>
        @endif

        <!-- Initialize theme class immediately -->
        <script>
            (function() {
                const isDark = localStorage.getItem('darkMode') === 'true' || 
                              (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
                if (isDark) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        <!-- Initialize Alpine Store BEFORE Alpine loads -->
        <script>
            document.addEventListener('alpine:init', () => {
                const isDark = localStorage.getItem('darkMode') === 'true' || 
                              (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
                
                Alpine.store('theme', {
                    dark: isDark,

                    toggle() {
                        this.dark = !this.dark;
                        localStorage.setItem('darkMode', this.dark);
                        this.updateTheme();
                    },

                    init() {
                        this.updateTheme();
                    },

                    updateTheme() {
                        if (this.dark) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                });
            });
        </script>
        
        <!-- Alpine.js CDN -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased bg-gradient-to-br from-red-50 via-white to-red-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Header with Logo and Controls -->
            <div class="w-full sm:max-w-md px-6 mb-4 flex justify-between items-center">
                <div class="flex-1"></div>
                <div class="flex items-center gap-4">
                    <x-language-switcher class="relative" />
                    <x-dark-mode-toggle />
                </div>
            </div>

            <!-- Logo -->
            <div class="mb-6">
                <x-auth-logo />
            </div>

            <!-- Main Content Card -->
            <div class="w-full sm:max-w-md px-6 py-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-xl border border-red-100 dark:border-gray-700 overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-6 text-sm text-gray-600 dark:text-gray-400 text-center">
                <p>&copy; {{ date('Y') }} {{ __('auth.Blood Bank Management System') }}</p>
            </div>
        </div>
    </body>
</html>
