import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                tecsisa: {
                    yellow: '#FFD100', // Yellow from logo
                    dark: '#111827',   // Slate 900 for texts
                    light: '#F8FAFC',  // Slate 50 for backgrounds
                },
            },
        },
    },

    plugins: [forms],
};
