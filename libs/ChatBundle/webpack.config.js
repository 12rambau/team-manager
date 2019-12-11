var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('./src/Resources/public/')
    .setPublicPath('./')
    .setManifestKeyPrefix('bundles/btba_chat')

    /*
     * ENTRY CONFIG
     */
    .addEntry('btba_chat', './assets/js/app.js')

    // will require an extra script tag for runtime.js
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    ;

module.exports = Encore.getWebpackConfig();

