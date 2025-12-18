<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Add Translation') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Create a new translation key and value') }}</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('admin.language-management.translations.store', $language ?? []) }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Key -->
                        <div>
                            <x-input-label for="key" :value="__('Translation Key')" />
                            <x-text-input 
                                id="key" 
                                name="key" 
                                type="text" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                :value="old('key')" 
                                required 
                                autofocus 
                                placeholder="e.g., Dashboard, Edit, Delete"
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Unique key for this translation') }}</p>
                            <x-input-error :messages="$errors->get('key')" class="mt-2" />
                        </div>

                        <!-- Group -->
                        <div>
                            <x-input-label for="group" :value="__('Group')" />
                            <x-select 
                                id="group" 
                                name="group" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('Select Group') }}</option>
                                @foreach($groups as $groupItem)
                                    <option value="{{ $groupItem }}" {{ old('group') == $groupItem ? 'selected' : '' }}>
                                        {{ ucfirst($groupItem) }}
                                    </option>
                                @endforeach
                            </x-select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Translation group (e.g., menu, form, button)') }}</p>
                            <x-input-error :messages="$errors->get('group')" class="mt-2" />
                        </div>

                        <!-- Language -->
                        <div>
                            <x-input-label for="language_code" :value="__('Language')" />
                            <x-select 
                                id="language_code" 
                                name="language_code" 
                                class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            >
                                <option value="">{{ __('Select Language') }}</option>
                                @foreach($languages as $lang)
                                    <option value="{{ $lang->code }}" {{ old('language_code', $language?->code) == $lang->code ? 'selected' : '' }}>
                                        {{ $lang->name }} ({{ $lang->code }})
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('language_code')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Value -->
                    <div class="mt-6">
                        <x-input-label for="value" :value="__('Translation Value')" />
                        <textarea 
                            id="value" 
                            name="value" 
                            rows="4"
                            class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm"
                            required
                            placeholder="{{ __('Enter the translated text...') }}"
                        >{{ old('value') }}</textarea>
                        <x-input-error :messages="$errors->get('value')" class="mt-2" />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.language-management.translations.index', $language ?? []) }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('Create Translation') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

