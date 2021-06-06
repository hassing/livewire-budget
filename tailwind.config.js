const { repeat } = require('lodash');
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            gridTemplateColumns: {
                '15': 'repeat(15, minmax(0, 1fr))'
            }
        },
    },

    variants: {
        extend: {
            opacity: ['disabled']
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
