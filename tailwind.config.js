import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    300: '#a5b4fc',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                    950: '#1e1b4b',
                },
                surface: {
                    DEFAULT: '#ffffff',
                    muted: '#f8fafc',
                    elevated: '#ffffff',
                },
            },
            boxShadow: {
                premium: '0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 16px rgba(15, 23, 42, 0.06)',
                'premium-lg': '0 4px 6px rgba(15, 23, 42, 0.04), 0 12px 32px rgba(15, 23, 42, 0.08)',
                'premium-glow': '0 0 0 1px rgba(99, 102, 241, 0.08), 0 8px 24px rgba(79, 70, 229, 0.12)',
                sidebar: '4px 0 24px rgba(15, 23, 42, 0.06)',
            },
            backgroundImage: {
                'mesh-light': 'radial-gradient(at 40% 20%, rgba(99, 102, 241, 0.08) 0px, transparent 50%), radial-gradient(at 80% 0%, rgba(139, 92, 246, 0.06) 0px, transparent 50%), radial-gradient(at 0% 50%, rgba(59, 130, 246, 0.05) 0px, transparent 50%)',
                'mesh-dark': 'radial-gradient(at 40% 20%, rgba(99, 102, 241, 0.15) 0px, transparent 50%), radial-gradient(at 80% 0%, rgba(139, 92, 246, 0.1) 0px, transparent 50%)',
                'brand-gradient': 'linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #818cf8 100%)',
                'sidebar-gradient': 'linear-gradient(180deg, #0f172a 0%, #1e1b4b 50%, #1e293b 100%)',
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.25rem',
            },
        },
    },

    plugins: [forms],
};
