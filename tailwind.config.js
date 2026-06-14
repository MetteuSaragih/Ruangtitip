/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'rutip-purple': '#7B2D8B',
        'rutip-light': '#F3E8F7',
        'rutip-dark': '#4A1860',
      }
    },
  },
  plugins: [],
}