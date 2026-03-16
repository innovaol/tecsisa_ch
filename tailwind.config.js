import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
            },
            colors: {
                tecsisa: {
                    yellow: '#FFD100',
                    dark: '#0A0F1C',
                },
                'theme-bg': 'var(--theme-bg)',
                'theme-text': 'var(--theme-text)',
                'theme-muted': 'var(--theme-text-muted)',
                'theme-card': 'var(--theme-card)',
                'theme-border': 'var(--theme-border)',
                'theme-header': 'var(--theme-header)',
            },
        },
    },

    plugins: [forms],
};
