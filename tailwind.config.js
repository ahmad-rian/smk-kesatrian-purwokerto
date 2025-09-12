import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/robsontenorio/mary/src/View/Components/**/*.php'
    ],

    theme: {
        extend: {
            fontFamily: {
                'title': ['Bricolage Grotesque', ...defaultTheme.fontFamily.sans],
                'body': ['Inter', 'Poppins', ...defaultTheme.fontFamily.sans],
                'sans': ['Inter', 'Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                    950: '#172554',
                },
            },
        },
    },

    plugins: [
        require('daisyui')
    ],

    // DaisyUI Configuration
    daisyui: {
        themes: [
            {
                light: {
                    'primary': '#3b82f6',
                    'primary-focus': '#2563eb',
                    'primary-content': '#ffffff',
                    'secondary': '#f3f4f6',
                    'secondary-focus': '#e5e7eb',
                    'secondary-content': '#1f2937',
                    'accent': '#10b981',
                    'accent-focus': '#059669',
                    'accent-content': '#ffffff',
                    'neutral': '#374151',
                    'neutral-focus': '#1f2937',
                    'neutral-content': '#ffffff',
                    'base-100': '#ffffff',
                    'base-200': '#f9fafb',
                    'base-300': '#f3f4f6',
                    'base-content': '#1f2937',
                    'info': '#3abff8',
                    'success': '#36d399',
                    'warning': '#fbbd23',
                    'error': '#f87272',
                },
                dark: {
                    'primary': '#3b82f6',
                    'primary-focus': '#2563eb',
                    'primary-content': '#ffffff',
                    'secondary': '#374151',
                    'secondary-focus': '#1f2937',
                    'secondary-content': '#f9fafb',
                    'accent': '#10b981',
                    'accent-focus': '#059669',
                    'accent-content': '#ffffff',
                    'neutral': '#1f2937',
                    'neutral-focus': '#111827',
                    'neutral-content': '#f9fafb',
                    'base-100': '#1f2937',
                    'base-200': '#374151',
                    'base-300': '#4b5563',
                    'base-content': '#f9fafb',
                    'info': '#3abff8',
                    'success': '#36d399',
                    'warning': '#fbbd23',
                    'error': '#f87272',
                },
            },
        ],
        darkTheme: 'dark',
        base: true,
        styled: true,
        utils: true,
        prefix: '',
        logs: true,
        themeRoot: ':root',
    },
};