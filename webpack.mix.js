let mix = require('laravel-mix');

mix.js('resources/assets/js/admin.js', 'public/js')
    .js('resources/assets/js/public.js', 'public/js')
    .sourceMaps(false, 'source-map')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        uglify: {
            parallel: 8,
            uglifyOptions: {
                mangle: true,
                compress: false,
            }
        }
    })
    .extract([
        'vue', 'vue-clipboard2', 'vue-page-title', 'vue-router', 'vuex',
        'axios', 'bootstrap', 'jquery', 'array-to-tree', 'slugify',
    ]).version();