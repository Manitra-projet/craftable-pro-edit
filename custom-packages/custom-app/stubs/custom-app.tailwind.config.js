const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/custom-app.blade.php",
    "./resources/js/custom-app/**/*.vue",
    "./custom-packages/custom-app/resources/js/**/*.vue"
  ],

  theme: {
    extend: {
      colors: {
        primary: colors.indigo,
        secondary: colors.fuchsia,
        gray: colors.slate,
        warning: colors.amber,
        danger: colors.red,
        success: colors.lime,
        info: colors.sky,
      },
      fontFamily: {
        sans: ["Nunito", ...defaultTheme.fontFamily.sans],
      },
      screens: {
        '3xl': '1800px',
      },
    },
  },

  plugins: [require("@tailwindcss/typography"), require("@tailwindcss/forms")],
};
