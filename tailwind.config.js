import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    presets: [
        require("./vendor/wireui/wireui/tailwind.config.js")
    ],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './vendor/wireui/wireui/src/*.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/WireUi/**/*.php',
        './vendor/wireui/wireui/src/Components/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.6s ease-out',
                'pulse-slow': 'pulse 2s infinite',
                'shimmer': 'shimmer 1.5s infinite',
                'shake': 'shake 0.5s ease-in-out',
            },
            keyframes: {
                fadeInUp: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateY(30px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                shimmer: {
                    '0%': {
                        'background-position': '-200px 0',
                    },
                    '100%': {
                        'background-position': 'calc(200px + 100%) 0',
                    },
                },
                shake: {
                    '0%, 100%': { transform: 'translateX(0)' },
                    '25%': { transform: 'translateX(-5px)' },
                    '75%': { transform: 'translateX(5px)' },
                },
            },
        },
    },

    plugins: [forms],
};
