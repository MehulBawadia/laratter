const mix          = require('laravel-mix');
const tailwindcss  = require('tailwindcss')
let glob           = require("glob-all");
let PurgecssPlugin = require("purgecss-webpack-plugin");
require('laravel-mix-purgecss');

mix.sass('resources/sass/app.scss', 'public/css')
   .options({
        processCssUrls: false,
        postCss: [
            tailwindcss('./tailwind.config.js'),
        ]
});
mix.styles(['public/css/app.css'], 'public/css/app.css').purgeCss({
    enabled: mix.inProduction()
});

mix.js(['resources/js/app.js'], 'public/js/app.js');
