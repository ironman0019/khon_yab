<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Site Settings') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Manage site-wide configuration and settings') }}</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Site Name -->
                        <div>
                            <x-input-label for="site_name" :value="__('admin.Site Name')" />
                            <x-text-input 
                                id="site_name" 
                                name="site_name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('site_name', $settings['site_name'] ?? config('app.name'))" 
                                required 
                                autofocus
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.The name of your site') }}</p>
                            <x-input-error :messages="$errors->get('site_name')" class="mt-2" />
                        </div>

                        <!-- Site Logo -->
                        <div>
                            <x-input-label for="site_logo" :value="__('admin.Site Logo')" />
                            
                            @if($settings['site_logo'] ?? null)
                                <div class="mt-2 mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ __('admin.Current Logo') }}:</p>
                                    <div class="inline-block p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" 
                                             alt="{{ __('admin.Site Logo') }}" 
                                             class="h-20 w-auto object-contain"
                                             id="current-logo-preview">
                                    </div>
                                </div>
                            @endif

                            <input 
                                id="site_logo" 
                                name="site_logo" 
                                type="file" 
                                accept="image/*"
                                class="block mt-1 w-full text-sm text-gray-900 dark:text-gray-300
                                       border border-gray-300 dark:border-gray-600 
                                       rounded-lg cursor-pointer 
                                       bg-white dark:bg-gray-700 
                                       focus:outline-none focus:border-red-500 focus:ring-red-500
                                       file:me-4 file:py-2 file:px-4 
                                       file:rounded-lg file:border-0 
                                       file:text-sm file:font-semibold 
                                       file:bg-red-50 dark:file:bg-red-900/20 
                                       file:text-red-700 dark:file:text-red-400 
                                       hover:file:bg-red-100 dark:hover:file:bg-red-900/30"
                                onchange="previewLogo(this)"
                            >
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.Upload a new logo (max 2MB, recommended: PNG or SVG)') }}</p>
                            
                            <!-- Preview for new upload -->
                            <div id="logo-preview-container" class="mt-2 hidden">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ __('admin.Preview') }}:</p>
                                <div class="inline-block p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                    <img id="logo-preview" src="" alt="{{ __('admin.Logo Preview') }}" class="h-20 w-auto object-contain">
                                </div>
                            </div>
                            
                            <x-input-error :messages="$errors->get('site_logo')" class="mt-2" />
                        </div>

                        <!-- Default Language -->
                        <div>
                            <x-input-label for="default_language_code" :value="__('admin.Default Language')" />
                            <select 
                                id="default_language_code" 
                                name="default_language_code" 
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-red-500 focus:ring-red-500"
                            >
                                <option value="">{{ __('admin.Select Default Language') }}</option>
                                @foreach($languages as $language)
                                    <option value="{{ $language->code }}" 
                                            {{ old('default_language_code', $settings['default_language_code'] ?? '') == $language->code ? 'selected' : '' }}>
                                        {{ $language->native_name }} ({{ $language->name }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.The default language for your site') }}</p>
                            <x-input-error :messages="$errors->get('default_language_code')" class="mt-2" />
                        </div>

                        <!-- Site Email -->
                        <div>
                            <x-input-label for="site_email" :value="__('admin.Site Email')" />
                            <x-text-input 
                                id="site_email" 
                                name="site_email" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('site_email', $settings['site_email'] ?? '')" 
                                placeholder="info@example.com"
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.Contact email address for the site') }}</p>
                            <x-input-error :messages="$errors->get('site_email')" class="mt-2" />
                        </div>

                        <!-- Site Phone -->
                        <div>
                            <x-input-label for="site_phone" :value="__('admin.Site Phone')" />
                            <x-text-input 
                                id="site_phone" 
                                name="site_phone" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('site_phone', $settings['site_phone'] ?? '')" 
                                placeholder="+1 234 567 8900"
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.Contact phone number for the site') }}</p>
                            <x-input-error :messages="$errors->get('site_phone')" class="mt-2" />
                        </div>

                        <!-- Site Address -->
                        <div>
                            <x-input-label for="site_address" :value="__('admin.Site Address')" />
                            <textarea 
                                id="site_address" 
                                name="site_address" 
                                rows="3"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-red-500 focus:ring-red-500"
                                placeholder="{{ __('admin.Enter site address') }}"
                            >{{ old('site_address', $settings['site_address'] ?? '') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.Physical address of your organization') }}</p>
                            <x-input-error :messages="$errors->get('site_address')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('admin.dashboard.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Save Settings') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 me-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800 dark:text-blue-300">
                        <p class="font-medium mb-1">{{ __('admin.Settings Information') }}</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>{{ __('admin.Changes to site settings will be applied immediately') }}</li>
                            <li>{{ __('admin.Logo images should be in PNG, JPG, or SVG format') }}</li>
                            <li>{{ __('admin.The default language will be used for new users and the site homepage') }}</li>
                            <li>{{ __('admin.Contact information is displayed publicly on the site') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewLogo(input) {
            const previewContainer = document.getElementById('logo-preview-container');
            const preview = document.getElementById('logo-preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.classList.add('hidden');
            }
        }
    </script>
</x-admin-layout>
