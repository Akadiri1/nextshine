/** @type {import('tailwindcss').Config} */

// Brand colours are CSS variables holding RGB channels. src/input.css sets
// the defaults and v1/views/includes/theme.php overrides them from
// settings_site_colors, so an admin can recolour the site without a rebuild.
const brand = (name) => `rgb(var(--color-${name}) / <alpha-value>)`;

module.exports = {
  // The beauty site (v1/views/beauty, beauty.js) has its own build in
  // tailwind.beauty.config.js, so it is left out here.
  content: [
    './v1/views/*.php',
    './v1/views/includes/**/*.php',
    './www/assets/js/app.js',
  ],
  // .container is defined by hand in src/input.css to match the original
  // 1140px shell, rather than following Tailwind's per-breakpoint widths.
  corePlugins: { container: false },
  theme: {
    // The design was built with max-width queries at 480, 768 and 1024px.
    // These min-width screens start one pixel above each, so every layout
    // switches at exactly the same width it always did.
    screens: {
      xs: '481px',
      md: '769px',
      lg: '1025px',
    },
    extend: {
      colors: {
        navy: {
          DEFAULT: brand('navy'),
          dark: brand('navy-dark'),
        },
        teal: {
          DEFAULT: brand('teal'),
          light: brand('teal-light'),
          pale: brand('teal-pale'),
        },
        white: brand('white'),
        'off-white': brand('off-white'),
        grey: {
          DEFAULT: brand('grey'),
          light: brand('grey-light'),
          dark: brand('grey-dark'),
        },
        ink: brand('ink'),
      },
      fontFamily: {
        display: ['Poppins', 'sans-serif'],
        sans: ['Inter', 'sans-serif'],
      },
      boxShadow: {
        sm: '0 1px 3px rgba(0,0,0,0.08)',
        md: '0 4px 16px rgba(0,0,0,0.10)',
        lg: '0 8px 32px rgba(0,0,0,0.14)',
      },
    },
  },
  plugins: [],
};
