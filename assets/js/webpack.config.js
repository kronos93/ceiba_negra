const webpack_validator = require('webpack-validator');
const { resolve } = require('path');
const webpack = require('webpack');

var DEVELOPMENT = process.env.NODE_ENV === 'development';
var PRODUCTION = process.env.NODE_ENV === 'production';
console.log(process.env.NODE_ENV);
var entry = PRODUCTION ? [
    './src/script.js',
] : [
    'webpack/hot/dev-server',
    'webpack-dev-server/client?http://localhost:3030',
    './src/script.js',
];

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
    'externals': {
        'jquery': 'jQuery' //jquery is external and available at the global variable jQuery
    },
    "entry": entry,
    "plugins": plugins,
    "module": {
        "loaders": [{
            test: /\.js$/,
            loaders: ['babel-loader'],
            exclude: /node_modules/
        }]
    },
    "externals": {
        "jquery": 'jQuery'
    },
    "output": {
        "path": resolve(__dirname, './dist'),
        //"publicPath": "http://localhost:3030/dist/",
        "publicPath": "http://localhost/ceiba_negra/assets/js/dist/",
        "filename": "bundle.js"
    }
};

module.exports = webpack_validator(config);