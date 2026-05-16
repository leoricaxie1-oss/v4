import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{js,ts}',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50:  '#eff8ff',
                    100: '#dbeefe',
                    200: '#bedffe',
                    300: '#91c8fd',
                    400: '#5da7fa',
                    500: '#3884f5',
                    600: '#1f63ea',
                    700: '#1c4ed6',
                    800: '#1d40ad',
                    900: '#1e3a8a',
                    950: '#172554',
                },
            },
        },
    },
    plugins: [forms, typography],
};
