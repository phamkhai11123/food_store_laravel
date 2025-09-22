/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
  safelist: [
  'animate-pulse',
  'animate-bounce',
  'bg-gradient-to-r',
  'from-red-500',
  'to-yellow-400',
],

}
