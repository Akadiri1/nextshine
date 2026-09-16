/** @type {import('tailwindcss').Config} */

// NextShine Beauty (the /beauty page). A different business from NextShine
// Cleaning, so it has its own build, palette and type; nothing here is shared
// with tailwind.config.js. Built to www/assets/css/beauty.css.
module.exports = {
  content: [
    './v1/views/beauty/**/*.php',
    './www/assets/js/beauty.js',
  ],
  corePlugins: { container: false },
  theme: {
    // The supplied beauty.html was built with max-width queries at 480 and
    // 768px; these start one pixel above each.
    screens: {
      xs: '481px',
      md: '769px',
      lg: '1025px', // gallery: four columns on wide screens
    },
    extend: {
      colors: {
        gold:     { DEFAULT: '#C4973A', light: '#E8C06A' },
        wine:     { DEFAULT: '#6B1E3B', dark: '#4A1028' },
        cream:    { DEFAULT: '#FAF6EF', dark: '#F0E8D8' },
        charcoal: '#1C1C1C',
        mid:      '#5A5A5A',
        light:    '#9A9A9A',
      },
      fontFamily: {
        serif: ['"Cormorant Garamond"', 'serif'],
        sans:  ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
