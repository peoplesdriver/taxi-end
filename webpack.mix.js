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

mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/app-vue.js', 'public/js/display/app.js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .js('resources/assets/js/display.js', 'public/js')
   .sass('resources/assets/sass/display.scss', 'public/css')
   .sass('resources/assets/sass/display-white.scss', 'public/css')
   .js('resources/assets/js/theory.js', 'public/js')
   .sass('resources/assets/sass/theory.scss', 'public/css')
   .sass('resources/assets/sass/dhivehi.scss', 'public/css');

