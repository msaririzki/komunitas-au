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
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                midnight: {
                    900: '#020205', // Deepest black-blue
                    800: '#0A0A0F', // Surface
                    700: '#12121A', // Elevated
                },
                glass: {
                    100: 'rgba(255, 255, 255, 0.03)',
                    200: 'rgba(255, 255, 255, 0.05)',
                    300: 'rgba(255, 255, 255, 0.10)',
                },
                neon: {
                    purple: '#b026ff',
                    blue: '#4c6ef5',
                    pink: '#ff0080',
                }
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'glow': 'glow 3s ease-in-out infinite',
                'slide-up': 'slide-up 0.5s ease-out',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                glow: {
                    '0%, 100%': { opacity: 0.5 },
                    '50%': { opacity: 1 },
                },
                'slide-up': {
                    '0%': { transform: 'translateY(20px)', opacity: 0 },
                    '100%': { transform: 'translateY(0)', opacity: 1 },
                }
            }
        },
    },

    plugins: [forms],
};
