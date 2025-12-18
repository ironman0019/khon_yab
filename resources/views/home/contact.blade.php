<x-public-layout>
    @php
        $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
    @endphp

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ __('home.Get in Touch') }}
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    {{ __('home.We would love to hear from you. Send us a message and we will respond as soon as possible.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ __('home.Contact Information') }}
                    </h2>
                    
                    <div class="space-y-6">
                        @if($settings['site_email'])
                        <div class="flex items-start {{ $isRtl ? 'flex-row-reverse' : '' }} gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ __('home.Email') }}
                                </h3>
                                <a href="mailto:{{ $settings['site_email'] }}" 
                                   class="text-red-600 dark:text-red-400 hover:underline">
                                    {{ $settings['site_email'] }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($settings['site_phone'])
                        <div class="flex items-start {{ $isRtl ? 'flex-row-reverse' : '' }} gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ __('home.Phone') }}
                                </h3>
                                <a href="tel:{{ $settings['site_phone'] }}" 
                                   class="text-red-600 dark:text-red-400 hover:underline">
                                    {{ $settings['site_phone'] }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($settings['site_address'])
                        <div class="flex items-start {{ $isRtl ? 'flex-row-reverse' : '' }} gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ __('home.Address') }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $settings['site_address'] }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Contact Form (Optional - can be implemented later) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ __('home.Contact Us') }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        {{ __('home.Send us a message and we will get back to you as soon as possible.') }}
                    </p>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            {{ __('home.Contact form will be available soon. Please use the contact information provided.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>

