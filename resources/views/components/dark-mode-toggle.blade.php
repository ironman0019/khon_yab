@props(['class' => ''])

<button 
    type="button"
    class="dark-mode-toggle-button {{ $class }} p-2 text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
>
    <!-- Moon Icon (Dark Mode) -->
    <svg class="dark-mode-moon-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
    </svg>
    
    <!-- Sun Icon (Light Mode) -->
    <svg class="dark-mode-sun-icon w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
    </svg>
</button>

<script>
(function() {
    // Only initialize once
    if (window.darkModeInitialized) {
        return;
    }
    window.darkModeInitialized = true;
    
    function initDarkMode() {
        const buttons = document.querySelectorAll('.dark-mode-toggle-button');
        if (buttons.length === 0) {
            return;
        }
        
        function getDarkMode() {
            const stored = localStorage.getItem('darkMode');
            if (stored !== null) {
                return stored === 'true';
            }
            return window.matchMedia('(prefers-color-scheme: dark)').matches;
        }
        
        function updateIcons(isDark) {
            document.querySelectorAll('.dark-mode-moon-icon').forEach(icon => {
                if (isDark) {
                    icon.classList.add('hidden');
                } else {
                    icon.classList.remove('hidden');
                }
            });
            
            document.querySelectorAll('.dark-mode-sun-icon').forEach(icon => {
                if (isDark) {
                    icon.classList.remove('hidden');
                } else {
                    icon.classList.add('hidden');
                }
            });
        }
        
        function toggleDarkMode() {
            const isDark = getDarkMode();
            const newDarkMode = !isDark;
            localStorage.setItem('darkMode', newDarkMode.toString());
            
            if (newDarkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            updateIcons(newDarkMode);
        }
        
        // Initialize icons on load
        updateIcons(getDarkMode());
        
        // Add click handlers to all buttons
        buttons.forEach(button => {
            button.addEventListener('click', toggleDarkMode);
        });
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDarkMode);
    } else {
        initDarkMode();
    }
})();
</script>
