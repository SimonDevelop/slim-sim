const path = require("path")
const UglifyJSPlugin = require("uglifyjs-webpack-plugin")
const ExtractTextPlugin = require("extract-text-webpack-plugin")
const OptimizeCssnanoPlugin = require('@intervolga/optimize-cssnano-plugin');
const dev = process.env.NODE_ENV === "dev"

let config = {
    entry: [ // Put here your entries for compilation
        './assets/js/app.js',
        './assets/scss/app.scss',
        // './assets/sass/app.sass',
        // './assets/css/app.css',
    ],
    mode: 'development',
    watch: dev,
    output: {
        path: path.resolve("./public/assets"),
        filename: dev ? 'js/bundle.js' : 'js/bundle.min.js',
        publicPath: '/assets/'
    },
    resolve: {
        alias: {
            '@css': path.resolve('./assets/css/'),
            '@sass': path.resolve('./assets/sass/'),
            '@scss': path.resolve('./assets/scss/'),
            '@img': path.resolve('./assets/img/'),
            '@js': path.resolve('./assets/js/'),
        }
    },
    devtool: dev ? "cheap-module-eval-source-map" : false,
    module: {
        rules: [
            {
                test:/\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: ['babel-loader'],
            }, {
                test:/\.(scss|sass)$/,
                use: ExtractTextPlugin.extract({
                    fallback: "style-loader",
                    use: ['css-loader', 'sass-loader']
                })
            }, {
                test:/\.css$/,
                use: ExtractTextPlugin.extract({
                    fallback: "style-loader",
                    use: ['css-loader']
                })
            }, {
                test: /\.(woff2?|eot|ttf|otf)$/,
                loader: 'file-loader'
            },{
                test: /\.(png|jpe?g|gif|svg)$/,
                use: [
                    {
                        loader: 'url-loader',
                        options: {
                            limit: 8192,
                            name: 'img/[name].[ext]'
                        }
                    }
                ]
            }, {
                loader: 'img-loader',
                options: {
                    enabled: !dev
                }
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin({
            filename: dev ? 'css/[name].css' : 'css/[name].min.css'
        })
    ]
};

if (!dev) {
    config.mode = 'production'
    config.plugins.push(new UglifyJSPlugin())
    config.plugins.push(new OptimizeCssnanoPlugin())
}

module.exports = config
