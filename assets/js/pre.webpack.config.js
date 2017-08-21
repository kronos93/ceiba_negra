const { resolve } = require('path');
const webpack = require('webpack');

var DEVELOPMENT = process.env.NODE_ENV === 'development';
var PRODUCTION = process.env.NODE_ENV === 'production';
console.log(process.env.NODE_ENV);
var entry = PRODUCTION ? { 'main': ['./src/script.js'], } : {
    'main': ['./src/script.js'],
};

var plugins =
    PRODUCTION ? [
        new webpack.optimize.UglifyJsPlugin(),
    ] : [
        new webpack.HotModuleReplacementPlugin(),
        // enable HMR globally
        new webpack.NamedModulesPlugin(),
        // prints more readable module names in the browser console on HMR updates
    ];
plugins.push(
    new webpack.DefinePlugin({
        DEVELOPMENT: JSON.stringify(DEVELOPMENT),
        PRODUCTION: JSON.stringify(PRODUCTION)
    })
);
const config = {
    externals: {
        jquery: 'jQuery' //jquery is external and available at the global variable jQuery
    },
    entry: entry,
    plugins: plugins,
    module: {
        rules: [{
                test: /\.js$/,
                exclude: /node_modules/,
                loader: "babel-loader"
            },
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader']
            },
            {
                test: /\.(png|jpg|gif|svg|eot|ttf|woff|woff2)$/,
                use: [{
                    loader: "url-loader"
                }]
            },
            //Configuración especial para datatables y archivos.js
            {
                test: /datatables\.net.*\.js$/,
                use: [{
                    loader: 'imports-loader?define=>false'
                }]
            }

        ]
    },
    output: {
        filename: '[name].bundle.js', //Archivo o carpeta + nombre del archivo de salida
        chunkFilename: '[name].bundle.js',
        path: resolve(__dirname, './dist'),
        publicPath: "http://localhost:3030/dist/",
        /* publicPath: "http://localhost/ceiba_negra/assets/js/dist/", */
        /*publicPath: "http://dev.huertoslaceiba.com/assets/js/dist/",*/
        /* "publicPath": "http://huertoslaceiba.com/assets/js/dist/", */
    }
};

module.exports = (config);