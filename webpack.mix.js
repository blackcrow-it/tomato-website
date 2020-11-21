const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    output: {
        chunkFilename: 'js/chunks/[name].[chunkhash].js',
    },
});

mix
    .js('resources/js/backend.js', 'public/js')
    .js('resources/js/frontend.js', 'public/js')

    .sass('resources/sass/backend.scss', 'public/css')
    .sass('public/tomato/assets/scss/main.scss', 'public/tomato/assets/css')

    .copyDirectory('resources/json', 'public/json')

    .version();
