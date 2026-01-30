@props(['class' => ''])

@php
    $languages = \App\Models\Language::where('is_active', true)->orderBy('is_default', 'desc')->orderBy('name')->get();
    $currentLocale = app()->getLocale();
    $currentLanguage = $languages->firstWhere('code', $currentLocale);
@endphp

<div class="{{ $class }} relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <button
        type="button"
        @click="open = !open"
        class="language-switcher-button flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors w-full {{ $class === 'w-full' ? 'justify-between' : '' }}"
    >
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
            </svg>
            <span class="language-switcher-current">{{ $currentLanguage ? $currentLanguage->native_name : strtoupper($currentLocale) }}</span>
        </div>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute {{ $class === 'w-full' ? 'left-0 right-0 w-full' : 'left-0' }} mt-2 w-40 {{ $class === 'w-full' ? 'w-full' : '' }} bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-[100]"
        style="display: none;"
    >
        <div class="py-1">
            @foreach($languages as $language)
                <a href="{{ route('language.switch', ['locale' => $language->code]) }}"
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-gray-700 hover:text-red-600 dark:hover:text-red-400 {{ $language->code === $currentLocale ? 'bg-red-50 dark:bg-gray-700' : '' }}"
                   @click="open = false">
                    {{ $language->native_name }} ({{ $language->code }})
                </a>
            @endforeach
        </div>
    </div>
</div>
