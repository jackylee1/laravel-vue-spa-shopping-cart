let mix = require('laravel-mix');

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

mix.js('resources/assets/js/admin.js', 'public/js')
    .sourceMaps(false, 'source-map')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        uglify: {
            parallel: 8, // Use multithreading for the processing
            uglifyOptions: {
                mangle: true,
                compress: false, // The slow bit
            }
        }
    })
    .extract([
        'vue', 'vue-template-compiler',
        'vue-clipboard2', 'vue-croppa', 'vue-feather-icons', 'vue-page-title',
        'vue-router', 'vue-tinymce-editor', 'vuex',
        'axios', 'bootstrap', 'jquery', 'element-ui',
        'array-to-tree', 'laravel-file-manager',
        'slugify',
    ]).version();