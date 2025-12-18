import './bootstrap';

import Alpine from 'alpinejs';

// Register store BEFORE Alpine starts (only if not already registered)
// This is for pages that don't use the CDN version
document.addEventListener('alpine:init', () => {
    // Only register if store doesn't already exist (might be registered in HTML)
    if (typeof Alpine !== 'undefined' && !Alpine.store('theme')) {
        Alpine.store('theme', {
            dark: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),

            toggle() {
                this.dark = !this.dark;
                localStorage.setItem('darkMode', this.dark);
                this.updateTheme();
            },

            init() {
                this.updateTheme();
            },

            updateTheme() {
                if (this.dark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });
        
        // Initialize theme on page load
        Alpine.store('theme').init();
    }
});

// Only set window.Alpine and start if not already done (e.g., by CDN)
// Check if Alpine is already loaded (from CDN)
if (typeof window.Alpine === 'undefined') {
    window.Alpine = Alpine;
    Alpine.start();
} else {
    // Alpine already loaded from CDN, just make sure store is registered
    if (typeof Alpine !== 'undefined' && !Alpine.store('theme')) {
        // Store should already be registered by the inline script, but just in case
        const isDark = localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
        Alpine.store('theme', {
            dark: isDark,
            toggle() {
                this.dark = !this.dark;
                localStorage.setItem('darkMode', this.dark);
                if (this.dark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            },
            init() {},
            updateTheme() {
                if (this.dark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });
    }
}
