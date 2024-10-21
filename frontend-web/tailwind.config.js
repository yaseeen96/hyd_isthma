const plugin = require('tailwindcss/plugin');
const rotateX = plugin(function ({ addUtilities }) {
    addUtilities({
        '.rotate-y-180': {
            transform: 'rotateY(180deg)',
        },
    });
});

const flowbite = require('flowbite-react/tailwind');

module.exports = {
    content: ['./index.html', './src/**/*.{js,ts,jsx,tsx}', './node_modules/react-tailwindcss-datepicker/dist/index.esm.js', flowbite.content()],
    theme: {
        container: {
            center: true,
        },
        extend: {
            keyframes: {
                'slide-in': {
                    '0%': { transform: 'translateY(50px)', opacity: 0 },
                    '100%': { transform: 'translateY(0)', opacity: 1 },
                },
                'grow-shrink': {
                    '0%, 50%': { transform: 'scale(1)' },
                    '25%': { transform: 'scale(1.1)' },
                },
                progress: {
                    '0%': { height: '0%' },
                    '100%': { height: '100%' },
                },
            },
            animation: {
                'slide-in': 'slide-in 1s ease-out',
                'grow-shrink': 'grow-shrink 3s infinite',
                progress: 'progress 2s ease-in-out forwards',
            },
            colors: {
                gray: {
                    100: '#E5E5E5',
                    200: '#CCCCCC',
                    300: '#B2B2B2',
                    400: '#999999',
                    500: '#7F7F7F',
                    600: '#666666',
                    700: '#4C4C4C',
                    800: '#1A1A1A',
                },
                primary: {
                    DEFAULT: '#5C0099',
                    100: '#F4F0F7',
                    200: '#DED3E5',
                    300: '#BCA3CC',
                    400: '#A96FCF',
                    500: '#8635BD',
                    600: '#5C0099',
                },
                secondary: {
                    DEFAULT: '#fdc500',
                    light: '#fff7e6',
                    'dark-light': 'rgb(253 197 0 / 15%)',
                },
                success: {
                    DEFAULT: '#00ab55',
                    light: '#ddf5f0',
                    'dark-light': 'rgba(0,171,85,.15)',
                },
                danger: {
                    DEFAULT: '#e7515a',
                    light: '#fff5f5',
                    'dark-light': 'rgba(231,81,90,.15)',
                },
                warning: {
                    DEFAULT: '#e2a03f',
                    light: '#fff9ed',
                    'dark-light': 'rgba(226,160,63,.15)',
                },
                info: {
                    DEFAULT: '#2196f3',
                    light: '#e7f7ff',
                    'dark-light': 'rgba(33,150,243,.15)',
                },
                dark: {
                    DEFAULT: '#3b3f5c',
                    light: '#eaeaec',
                    'dark-light': 'rgba(59,63,92,.15)',
                },

                white: {
                    DEFAULT: '#ffffff',
                    light: '#e0e6ed',
                    dark: '#888ea8',
                },
            },
            fontFamily: {
                nunito: ['Nunito', 'sans-serif'],
            },
            spacing: {
                4.5: '18px',
            },
            boxShadow: {
                '3xl': '0 2px 2px rgb(224 230 237 / 46%), 1px 6px 7px rgb(224 230 237 / 46%)',
            },
            typography: ({ theme }) => ({
                DEFAULT: {
                    css: {
                        '--tw-prose-invert-headings': theme('colors.white.dark'),
                        '--tw-prose-invert-links': theme('colors.white.dark'),
                        h1: { fontSize: '40px', marginBottom: '0.5rem', marginTop: 0 },
                        h2: { fontSize: '32px', marginBottom: '0.5rem', marginTop: 0 },
                        h3: { fontSize: '28px', marginBottom: '0.5rem', marginTop: 0 },
                        h4: { fontSize: '24px', marginBottom: '0.5rem', marginTop: 0 },
                        h5: { fontSize: '20px', marginBottom: '0.5rem', marginTop: 0 },
                        h6: { fontSize: '16px', marginBottom: '0.5rem', marginTop: 0 },
                        p: { marginBottom: '0.5rem' },
                        li: { margin: 0 },
                        img: { margin: 0 },
                    },
                },
            }),
        },
    },
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'class',
        }),
        require('@tailwindcss/typography'),
        rotateX,
        flowbite.plugin(),
    ],
};
