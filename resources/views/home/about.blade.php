<x-public-layout>
    @php
        $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
    @endphp

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ __('home.About Our System') }}
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    {{ __('home.A comprehensive blood bank management system designed to connect donors with those in need.') }}
                </p>
            </div>

            <!-- Mission Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ __('home.Our Mission') }}
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    {{ __('home.To provide a reliable and efficient blood bank management system that connects donors with those in need, ensuring timely access to blood supplies and saving lives.') }}
                </p>
            </div>

            <!-- Vision Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ __('home.Our Vision') }}
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    {{ __('home.To become the leading blood bank management platform, making blood donation and access seamless and accessible to everyone.') }}
                </p>
            </div>

            <!-- System Features Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                    {{ __('home.System Features') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start {{ $isRtl ? 'flex-row-reverse' : '' }} gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ __('home.Comprehensive donor database management') }}
                        </p>
                    </div>

                    <div class="flex items-start {{ $isRtl ? 'flex-row-reverse' : '' }} gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ __('home.Real-time blood inventory tracking') }}
                        </p>
                    </div>

                    <div class="flex items-start {{ $isRtl ? 'flex-row-reverse' : '' }} gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ __('home.Automated request approval workflow') }}
                        </p>
                    </div>

                    <div class="flex items-start {{ $isRtl ? 'flex-row-reverse' : '' }} gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ __('home.Multi-language interface support') }}
                        </p>
                    </div>

                    <div class="flex items-start {{ $isRtl ? 'flex-row-reverse' : '' }} gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ __('home.Detailed reporting and analytics') }}
                        </p>
                    </div>

                    <div class="flex items-start {{ $isRtl ? 'flex-row-reverse' : '' }} gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ __('home.Secure and reliable platform') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>

