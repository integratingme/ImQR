/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Maven Pro', 'sans-serif'],
      },
      colors: {
        primary: {
          50: '#ffe4d9',
          100: '#ffe4d9',
          200: '#f2c0a9',
          300: '#e54e1a',
          400: '#e54e1a',
          500: '#e54e1a',
          600: '#e54e1a',
          700: '#c43d15',
          800: '#a32f10',
          900: '#82220b',
        },
        dark: {
          DEFAULT: '#2d3c3a',
          50: '#d8e1e0',
          100: '#d8e1e0',
          200: '#a8b8b6',
          300: '#788f8c',
          400: '#486662',
          500: '#2d3c3a',
          600: '#25302e',
          700: '#1d2422',
          800: '#151816',
          900: '#0d0c0a',
        },
        peach: {
          light: '#ffe4d9',
          DEFAULT: '#f2c0a9',
        },
        'light-gray': {
          DEFAULT: '#d8e1e0',
        },
      },
    },
  },
  plugins: [],
}
