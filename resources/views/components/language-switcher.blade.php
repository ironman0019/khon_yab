@props(['class' => ''])

@php
    $languages = \App\Models\Language::where('is_active', true)->orderBy('is_default', 'desc')->orderBy('name')->get();
    $currentLocale = app()->getLocale();
    $currentLanguage = $languages->firstWhere('code', $currentLocale);
    $uniqueId = 'lang-switcher-' . uniqid();
@endphp

<div class="{{ $class }} relative language-switcher-container" data-switcher-id="{{ $uniqueId }}">
    <button 
        type="button"
        class="language-switcher-button flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition-colors w-full {{ $class === 'w-full' ? 'justify-between' : '' }}"
        data-switcher-id="{{ $uniqueId }}"
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
        class="language-switcher-dropdown absolute {{ $class === 'w-full' ? 'left-0 right-0 w-full' : 'left-0' }} mt-2 w-40 {{ $class === 'w-full' ? 'w-full' : '' }} bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-[100] hidden"
        data-switcher-id="{{ $uniqueId }}"
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
(function() {
    // Initialize all language switchers on the page
    function initLanguageSwitchers() {
        document.querySelectorAll('.language-switcher-container').forEach(function(container) {
            const switcherId = container.getAttribute('data-switcher-id');
            const button = container.querySelector('.language-switcher-button[data-switcher-id="' + switcherId + '"]');
            const dropdown = container.querySelector('.language-switcher-dropdown[data-switcher-id="' + switcherId + '"]');
            
            if (!button || !dropdown) {
                return;
            }
            
            // Check if already initialized
            if (button.hasAttribute('data-initialized')) {
                return;
            }
            button.setAttribute('data-initialized', 'true');
            
            function toggleDropdown() {
                dropdown.classList.toggle('hidden');
            }
            
            function closeDropdown() {
                dropdown.classList.add('hidden');
            }
            
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                toggleDropdown();
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.language-switcher-container[data-switcher-id="' + switcherId + '"]')) {
                    closeDropdown();
                }
            });
        });
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLanguageSwitchers);
    } else {
        initLanguageSwitchers();
    }
    
    // Re-initialize when Alpine.js updates the DOM (for mobile menu)
    if (typeof Alpine !== 'undefined') {
        document.addEventListener('alpine:init', function() {
            // Watch for mobile menu changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        initLanguageSwitchers();
                    }
                });
            });
            
            const header = document.querySelector('header');
            if (header) {
                observer.observe(header, { childList: true, subtree: true });
            }
        });
    }
})();
</script>
