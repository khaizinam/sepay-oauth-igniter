const mix = require('laravel-mix')
mix.js('public/assets/js/app.js', 'public/assets/build/js')
   .sass('public/assets/sass/app.scss', 'public/assets/build/css');
