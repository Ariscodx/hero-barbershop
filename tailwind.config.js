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
                sans: ['Poppins', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#1F2A1D',
                secondary: '#C8A96A',
                accent: {
                    DEFAULT: '#C8A96A',
                    hover: '#EAD7A5',
                },
                brand: {
                    bg: '#F5F5F5',
                    card: '#FFFFFF',
                    text: '#111827',
                    border: '#E5E7EB',
                }
            }
        },
    },

    plugins: [forms],
};
