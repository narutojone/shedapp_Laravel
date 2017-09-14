module.exports = {
  root: true,
  // https://github.com/feross/standard/blob/master/RULES.md#javascript-standard-style
  extends: 'standard',
  // environments
  env: {
    'browser': true,
    'node': true,
    'es6': true,
    'jquery': true
  },
  // required to lint *.vue files
  plugins: [
    'html'
  ],
  globals: {
    '_': false,
    'moment': false
  },
  // add your custom rules here
  'rules': {
    'space-before-function-paren': 0,
    'indent': 0,
    // allow paren-less arrow functions
    'arrow-parens': 0,
    // allow debugger during development
    'no-debugger': process.env.NODE_ENV === 'production' ? 2 : 0,
    'eol-last': 0,
    'no-useless-escape': 0,
  },
  "parser": "babel-eslint",
  "parserOptions": {
    "sourceType": "module",
    "allowImportExportEverywhere": true,
    "codeFrame": false
  }
}
