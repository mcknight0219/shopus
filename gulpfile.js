var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss', 'public/css/main.css');
});

elixir(function(mix) {
    mix.browserify(['cms.js'], 'public/js/cms.js');

    mix.browserify(['login.js'], 'public/js/login.js');

    mix.browserify(['register.js'], 'public/js/register.js');
});

elixir(function(mix) {
    mix.copy('resources/assets/img', 'public/img');
});