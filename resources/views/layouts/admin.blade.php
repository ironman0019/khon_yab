@php
    $locale = app()->getLocale();
    $isRtl = in_array($locale, ['fa', 'ps']);
    $dir = $isRtl ? 'rtl' : 'ltr';
    // Debug: Uncomment to see locale detection
    // \Log::info('Admin Layout - Locale: ' . $locale . ', isRtl: ' . ($isRtl ? 'true' : 'false'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $dir }}" x-data="{ darkMode: $store.theme.dark }" x-bind:class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - {{ __('Admin Panel') }}</title>

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
        
        <!-- RTL Support -->
        <style>
            [dir="rtl"] {
                direction: rtl;
            }
            [dir="ltr"] {
                direction: ltr;
            }
        </style>
        
        <!-- Debug: Remove this after testing -->
        @if(config('app.debug'))
        <style>
            body::before {
                content: 'Locale: {{ $locale }} | RTL: {{ $isRtl ? "YES" : "NO" }} | Dir: {{ $dir }}';
                position: fixed;
                top: 0;
                left: 0;
                background: red;
                color: white;
                padding: 4px 8px;
                z-index: 9999;
                font-size: 12px;
            }
        </style>
        @endif

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <x-admin.sidebar />

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden {{ $isRtl ? 'order-first' : '' }}">
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
