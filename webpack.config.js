const Encore = require('@symfony/webpack-encore');
const path = require('path');
const webpack = require('webpack');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .enableVueLoader(() => {}, { runtimeCompilerBuild: false })
    .enableSingleRuntimeChunk()
    .enablePostCssLoader()
    .addAliases({
        '@symfony/stimulus-bridge/controllers.json': path.resolve(__dirname, 'assets/controllers/controllers.json'),
        'vue': 'vue/dist/vue.esm-bundler.js'
    })
    .addPlugin(new webpack.DefinePlugin({
        __VUE_OPTIONS_API__: JSON.stringify(true),
        __VUE_PROD_DEVTOOLS__: JSON.stringify(false),
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false)
    }))
;

module.exports = Encore.getWebpackConfig();
