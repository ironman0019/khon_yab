@props([])
@php
    $locale = app()->getLocale();
    $isRtl = in_array($locale, ['fa', 'ps']);
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $dir }}" x-data="{ darkMode: $store.theme.dark }" x-bind:class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - {{ __('home.Home') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;700&family=Noto+Sans+Display:wght@400;700&family=Noto+Sans+Pashto:wght@400;700&display=swap" rel="stylesheet">
        
        <!-- Persian/Pashto Fonts -->
        @if($isRtl)
        <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Vazirmatn', sans-serif; }
        </style>
        @endif
        
        <!-- RTL Support -->
        <style>
            [dir="rtl"] {
                direction: rtl;
            }
            [dir="ltr"] {
                direction: ltr;
            }
        </style>

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
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col">
            <!-- Header -->
            <x-public.header />

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <x-public.footer />
        </div>

        @stack('scripts')
    </body>
</html>

