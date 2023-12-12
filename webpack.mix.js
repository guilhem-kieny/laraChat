const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .vue({version:2})
    .css('resources/css/app.css', 'public/css')
    .version();
