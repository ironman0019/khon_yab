@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Add Language') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Create a new language') }}</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('admin.language-management.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Code -->
                        <div>
                            <x-input-label for="code" :value="__('admin.Language Code')" />
                            <x-text-input 
                                id="code" 
                                name="code" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('code')" 
                                required 
                                autofocus 
                                placeholder="e.g., en, fa, ps"
                                maxlength="10"
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.ISO language code (e.g., en, fa, ps)') }}</p>
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('admin.Language Name')" />
                            <x-text-input 
                                id="name" 
                                name="name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('name')" 
                                required 
                                placeholder="e.g., English"
                            />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Native Name -->
                        <div>
                            <x-input-label for="native_name" :value="__('admin.Native Name')" />
                            <x-text-input 
                                id="native_name" 
                                name="native_name" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('native_name')" 
                                required 
                                placeholder="e.g., English, فارسی, پښتو"
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Name in the language itself') }}</p>
                            <x-input-error :messages="$errors->get('native_name')" class="mt-2" />
                        </div>

                        <!-- Direction -->
                        <div>
                            <x-input-label for="direction" :value="__('admin.Text Direction')" />
                            <x-select 
                                id="direction" 
                                name="direction" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('admin.Select Direction') }}</option>
                                <option value="ltr" {{ old('direction') == 'ltr' ? 'selected' : '' }}>{{ __('admin.Left to Right (LTR)') }}</option>
                                <option value="rtl" {{ old('direction') == 'rtl' ? 'selected' : '' }}>{{ __('admin.Right to Left (RTL)') }}</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('direction')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Checkboxes -->
                    <div class="mt-6 space-y-4">
                        <label class="inline-flex items-center">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600 dark:focus:ring-offset-gray-800"
                            >
                            <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Mark as active') }}</span>
                        </label>

                        <label class="inline-flex items-center {{ $isRtl ? 'me-6' : 'ms-6' }}">
                            <input 
                                type="checkbox" 
                                name="is_default" 
                                value="1"
                                {{ old('is_default', false) ? 'checked' : '' }}
                                class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600 dark:focus:ring-offset-gray-800"
                            >
                            <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Set as default language') }}</span>
                        </label>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center {{ $isRtl ? 'justify-start flex-row-reverse' : 'justify-end' }} gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.language-management.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Create Language') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

