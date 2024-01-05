/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          900: '#001E33',
          600: '#154B72',
          200: '#A9C7E9',
          50: '#F4F8FF',
        },
        secondary: {
          600: '#FE6638',
          500: '#FF936D',
        }
      },
      fontFamily: {
        'poppins': ['Poppins', 'sans-serif'],
        'montserrat': ['Montserrat', 'sans-serif'],
      },
      animation: {
        'loop-scroll': 'loop-scroll 50s linear infinite',
      },
      keyframes: {
        'loop-scroll': {
          from: {transform: 'translateX(0)'},
          to: {transform: 'translateX(-100%)'},
        }
      },
    },
  },
  plugins: [],
}
