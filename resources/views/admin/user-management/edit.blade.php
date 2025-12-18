@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 {{ $isRtl ? 'text-right' : 'text-left' }}">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Edit User') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Update user information') }}</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('admin.user-management.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <!-- Full Name -->
                    <div class="mb-6">
                        <x-input-label for="full_name" :value="__('admin.Full Name')" />
                        <x-text-input 
                            id="full_name" 
                            name="full_name" 
                            type="text" 
                            class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :value="old('full_name', $user->full_name)" 
                            required 
                            autofocus 
                        />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <x-input-label for="email" :value="__('admin.Email')" />
                        <x-text-input 
                            id="email" 
                            name="email" 
                            type="email" 
                            class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :value="old('email', $user->email)" 
                            required 
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password (Optional) -->
                    <div class="mb-6">
                        <x-input-label for="password" :value="__('admin.Password')" />
                        <x-text-input 
                            id="password" 
                            name="password" 
                            type="password" 
                            class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('admin.Leave blank to keep current password') }}"
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.Leave blank to keep current password') }}</p>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password (Optional) -->
                    <div class="mb-6">
                        <x-input-label for="password_confirmation" :value="__('admin.Confirm Password')" />
                        <x-text-input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- User Type -->
                    <div class="mb-6">
                        <x-input-label for="user_type" :value="__('admin.User Type')" />
                        <x-select 
                            id="user_type" 
                            name="user_type" 
                            class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            required
                        >
                            <option value="">{{ __('admin.Select User Type') }}</option>
                            <option value="0" {{ old('user_type', $user->user_type) == '0' ? 'selected' : '' }}>{{ __('admin.User') }}</option>
                            <option value="1" {{ old('user_type', $user->user_type) == '1' ? 'selected' : '' }}>{{ __('admin.Donor') }}</option>
                            <option value="2" {{ old('user_type', $user->user_type) == '2' ? 'selected' : '' }}>{{ __('admin.Hospital User') }}</option>
                        </x-select>
                        <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
                    </div>

                    <!-- Is Admin -->
                    <div class="mb-6">
                        <label class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <input 
                                type="checkbox" 
                                name="is_admin" 
                                value="1"
                                {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600 dark:focus:ring-offset-gray-800"
                            >
                            <span class="{{ $isRtl ? 'me-2' : 'ms-2' }} text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Make this user an admin') }}</span>
                        </label>
                        <x-input-error :messages="$errors->get('is_admin')" class="mt-2" />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center {{ $isRtl ? 'justify-start' : 'justify-end' }} gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.user-management.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Update User') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
