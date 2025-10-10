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
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            // Di sini kita menambahkan palet warna Camture
            colors: {
                'camture-green-dark': '#337357',
                'camture-green-light': '#8D9F71',
                'camture-pink-bg': '#FFDBE5',
                'camture-rose': '#E27396',
                'camture-rose-hover': '#ca6285',
                'camture-beige': '#EADAB2',
                'camture-peach': '#FFB4A2',
            },
        },
    },

    plugins: [forms],
};