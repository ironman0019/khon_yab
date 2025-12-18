@props(['class' => ''])

@php
    $languages = \App\Models\Language::where('is_active', true)->orderBy('is_default', 'desc')->orderBy('name')->get();
    $currentLocale = app()->getLocale();
    $currentLanguage = $languages->firstWhere('code', $currentLocale);
@endphp

<div class="{{ $class }} relative" id="language-switcher">
    <button 
        id="language-button"
        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
        </svg>
        <span id="current-lang">{{ $currentLanguage ? $currentLanguage->native_name : strtoupper($currentLocale) }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div 
        id="language-dropdown"
        class="absolute mt-2 w-40 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50 hidden"
    >
        <div class="py-1">
            @foreach($languages as $language)
                <a href="{{ route('language.switch', ['locale' => $language->code]) }}" 
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-gray-700 hover:text-red-600 dark:hover:text-red-400 language-option {{ $language->code === $currentLocale ? 'bg-red-50 dark:bg-gray-700' : '' }}"
                   data-lang="{{ $language->code }}">
                    {{ $language->native_name }} ({{ $language->code }})
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('language-button');
    const dropdown = document.getElementById('language-dropdown');
    const currentLang = document.getElementById('current-lang');
    
    if (!button || !dropdown || !currentLang) {
        return;
    }
    
    function toggleDropdown() {
        dropdown.classList.toggle('hidden');
    }
    
    function closeDropdown() {
        dropdown.classList.add('hidden');
    }
    
    button.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleDropdown();
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#language-switcher')) {
            closeDropdown();
        }
    });
    
    // Don't prevent default navigation - let the links work normally
    // The language will be updated when the page reloads after navigation
});
</script>
