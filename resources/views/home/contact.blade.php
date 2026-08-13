<x-public-layout>
    @php
        $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
        $authUser = auth()->user();
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

            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif

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

                        <div class="flex items-start {{ $isRtl ? 'flex-row-reverse' : '' }} gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ __('home.GitHub') }}
                                </h3>
                                <a href="https://github.com/ironman0019/khon_yab.git"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="text-red-600 dark:text-red-400 hover:underline break-all">
                                    {{ __('home.View source on GitHub') }}
                                </a>
                            </div>
                        </div>

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

                <!-- Contact Form -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ __('home.Contact Us') }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        {{ __('home.Send us a message and we will get back to you as soon as possible.') }}
                    </p>

                    <form method="POST" action="{{ route('home.contact.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('home.Name')" class="{{ $isRtl ? 'text-right' : 'text-left' }}" />
                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                class="mt-1 block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('name', $authUser?->full_name)"
                                required
                                autofocus
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('home.Email')" class="{{ $isRtl ? 'text-right' : 'text-left' }}" />
                            <x-text-input
                                id="email"
                                name="email"
                                type="email"
                                class="mt-1 block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('email', $authUser?->email)"
                                required
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('home.Phone')" class="{{ $isRtl ? 'text-right' : 'text-left' }}" />
                            <x-text-input
                                id="phone"
                                name="phone"
                                type="text"
                                class="mt-1 block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('phone')"
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <div>
                            <x-input-label for="subject" :value="__('home.Subject')" class="{{ $isRtl ? 'text-right' : 'text-left' }}" />
                            <x-text-input
                                id="subject"
                                name="subject"
                                type="text"
                                class="mt-1 block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('subject')"
                                required
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('subject')" />
                        </div>

                        <div>
                            <x-input-label for="message" :value="__('home.Message')" class="{{ $isRtl ? 'text-right' : 'text-left' }}" />
                            <x-textarea
                                id="message"
                                name="message"
                                rows="5"
                                class="mt-1 block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >{{ old('message') }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('message')" />
                        </div>

                        <div class="flex mt-2 {{ $isRtl ? 'justify-start' : 'justify-end' }}">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                {{ __('home.Send Message') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
