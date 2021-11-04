const colors = require('tailwindcss/colors')

module.exports = {
  purge: {
    content: ['./resources/views/**/*.php'],
    options: {
      safelist: [/^bg-/, /^text-/, /^border-/],
      keyframes: true
    }
  },
  darkMode: 'media', // or 'media' or 'class'
  theme: {
    colors: {
      current: colors.sky,
      black: colors.black,
      white: colors.white,
      gray: colors.trueGray,
      indigo: colors.indigo,
      red: colors.rose,
      green: colors.green,
      blue: colors.blue,
      yellow: colors.yellow,
      warm: colors.warmGray,
      orange: colors.orange,
      cyan: colors.cyan,
      lime: colors.lime,
      emerald: colors.emerald,
      teal: colors.teal,
      sky: colors.sky,
      rose: colors.rose
    },
    extend: {
      spacing: {
        '100': '100px',
        '200': '200px',
        '300': '300px',
        '400': '400px',
        '500': '500px',
        '600': '600px',
        '700': '700px',
        '800': '800px',
        '900': '900px'
      }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
