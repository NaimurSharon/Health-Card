import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
<<<<<<< HEAD
=======
        './resources/js/**/*.jsx',
        './resources/js/**/*.js',
        './resources/js/**/*.tsx',
        './resources/js/**/*.ts',
    ],
    safelist: [
        'bg-slate-900',
        'bg-black',
        'h-full',
        'h-screen',
        'bg-gradient-to-br',
        'from-slate-900',
        'via-slate-950',
        'to-black',
        'backdrop-blur-xl',
        'backdrop-blur-2xl',
>>>>>>> c356163 (video call ui setup)
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                fadeIn: 'fadeIn 0.5s ease-in-out',
<<<<<<< HEAD
=======
                fadeInUp: 'fadeInUp 0.6s ease-out',
                slideInDown: 'slideInDown 0.5s ease-out',
                slideInRight: 'slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
                bounce: 'bounce 2s ease-in-out infinite',
                ping: 'ping 2s cubic-bezier(0, 0, 0.2, 1) infinite',
                pulse: 'pulse 3s ease-in-out infinite',
                ripple: 'ripple 0.6s linear',
>>>>>>> c356163 (video call ui setup)
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
<<<<<<< HEAD
=======
                fadeInUp: {
                    from: {
                        opacity: '0',
                        transform: 'translateY(20px)',
                    },
                    to: {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                slideInDown: {
                    from: {
                        opacity: '0',
                        transform: 'translateY(-20px)',
                    },
                    to: {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                slideInRight: {
                    from: {
                        opacity: '0',
                        transform: 'translateX(100%)',
                    },
                    to: {
                        opacity: '1',
                        transform: 'translateX(0)',
                    },
                },
                bounce: {
                    '0%, 100%': {
                        transform: 'translateY(0)',
                    },
                    '50%': {
                        transform: 'translateY(-10px)',
                    },
                },
                ping: {
                    '75%, 100%': {
                        transform: 'scale(2)',
                        opacity: '0',
                    },
                },
                pulse: {
                    '0%, 100%': {
                        opacity: '0.5',
                        transform: 'scale(1)',
                    },
                    '50%': {
                        opacity: '0.8',
                        transform: 'scale(1.1)',
                    },
                },
                ripple: {
                    to: {
                        transform: 'scale(4)',
                        opacity: '0',
                    },
                },
            },
            backdropBlur: {
                xs: '2px',
>>>>>>> c356163 (video call ui setup)
            },
        },
    },

    plugins: [forms],
<<<<<<< HEAD
};

=======
};
>>>>>>> c356163 (video call ui setup)
