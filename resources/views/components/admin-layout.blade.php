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

        <title>{{ config('app.name', 'Laravel') }} - {{ __('admin.Admin Panel') }}</title>

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

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex {{ $isRtl ? 'flex-row-reverse' : '' }}">
            <!-- Sidebar -->
            <x-admin.sidebar />

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Header -->
                <x-admin.header />

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
                    @if(session('success'))
                        <div class="bg-green-500 text-white px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-500 text-white px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>

