var path = require('path')

var config = {
  env: 'development',
  build: {
    index: path.resolve(__dirname, '../../urbanshedconcepts/public/app/index.html'),
    assetsRoot: path.resolve(__dirname, '/Library/WebServer/Documents/urbanshedconcepts/public/app/'),
    assetsSubDirectory: './',
    assetsPublicPath: '/app/',
    productionSourceMap: false
  },
  paths: {
    root: path.resolve(__dirname, '..')
  },
  debug: true
}

module.exports = config
