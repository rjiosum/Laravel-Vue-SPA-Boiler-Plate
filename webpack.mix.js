const mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.json', '.jxs', '.vue'],
        alias: {
            '@': path.resolve(__dirname, 'resources/js/')
        }
    },
    output:{
        chunkFilename: 'js/[id].[chunkhash].js'
    }
});


mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

if (mix.inProduction()) {
    mix.version();
}
