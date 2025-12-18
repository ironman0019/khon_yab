<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('auth.Email')" />
            <x-text-input id="email" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required 
                          autofocus 
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('auth.Password')" />
            <x-text-input id="password" 
                          class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                          type="password"
                          name="password"
                          required 
                          autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" 
                       type="checkbox" 
                       class="rounded border-red-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:bg-gray-700 dark:focus:ring-red-600 dark:focus:ring-offset-gray-800" 
                       name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('auth.Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition-colors" 
                   href="{{ route('password.request') }}">
                    {{ __('auth.Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="bg-red-600 hover:bg-red-700 focus:ring-red-500 dark:bg-red-700 dark:hover:bg-red-600">
                {{ __('auth.Log in') }}
            </x-primary-button>
        </div>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('auth.Don\'t have an account?') }}
                <a href="{{ route('register') }}" class="font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 underline">
                    {{ __('auth.Register') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>