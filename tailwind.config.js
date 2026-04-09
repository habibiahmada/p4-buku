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
                    colors: {
                        linen: '#F7F2EA',
                        parch: '#EDE6D6',
                        ink: '#1C1917',
                        sage: '#4A7C59',
                        'sage-light': '#D6E8DC',
                        copper: '#B5651D',
                        'copper-light': '#F5E6D3',
                        muted: '#8C8073',
                    },
                    fontFamily: {
                        serif: ['Lora', 'Georgia', 'serif'],
                        mono: ['DM Mono', 'monospace'],
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            },

    plugins: [forms],
};
