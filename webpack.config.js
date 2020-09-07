const path = require("path");

module.exports = {
    mode: 'development',
    entry: "./resources/js/app.js",
    output: {
        filename: 'require.js',
        path: path.resolve(__dirname, "Public/js")
    },
    module: {
        rules: [
            {
                test: /\.(scss)$/,
                use: [{
                    loader: 'style-loader', // Adds CSS to the DOM by injecting a `<style>` tag in your page
                }, {
                    loader: 'css-loader', // Interprets `@import` and `url()` like `import/require()` and will resolve them
                }, {
                    loader: 'postcss-loader', // Run post css actions
                    options: {
                        plugins: function () { // post css plugins, can be exported to postcss.config.js
                            return [
                                require('autoprefixer')
                            ];
                        }
                    }
                }, {
                    loader: 'sass-loader' // compiles Sass to CSS
                }]
            },
        ],
    },
};