const mix          = require('laravel-mix');
const tailwindcss  = require('tailwindcss')
let glob           = require("glob-all");
let PurgecssPlugin = require("purgecss-webpack-plugin");
require('laravel-mix-purgecss');

class TailwindExtractor {
    static extract(content) {
        return content.match(/[A-z0-9-:\/]+/g);
    }
}

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

mix.js([
    'resources/js/app.js',
    'resources/libs/iGrowl/javascripts/igrowl.js',
    'resources/libs/selectizeJs/js/standalone/selectize.min.js',
], 'public/js/app.js');

mix.styles([
    'resources/libs/iGrowl/stylesheets/animate.css',
    'resources/libs/iGrowl/stylesheets/igrowl.css',
    'resources/libs/iGrowl/stylesheets/icomoon/feather.css',
    'resources/libs/selectizeJs/css/selectize.css',
], 'public/css/vendor.css');

mix.copyDirectory('resources/libs/iGrowl/fonts/feather', 'public/fonts/feather');
