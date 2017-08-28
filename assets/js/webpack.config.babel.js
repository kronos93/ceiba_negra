import { resolve } from 'path';
import webpack from 'webpack';
import FaviconsWebpackPlugin from 'favicons-webpack-plugin';
import HtmlWebpackPlugin from 'html-webpack-plugin';
export default (env, argv) => {
    console.log(env);
    const PRODUCTION = (env) ? env.prod : false;
    return {
        externals: {
            jquery: 'jQuery' //jquery is external and available at the global variable jQuery
        },
        context: resolve(__dirname, 'src'),
        entry: {
            'main': ['./script.js'],
        },
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
        plugins: [
            new HtmlWebpackPlugin(),
            new FaviconsWebpackPlugin({
                logo: '../../img/logos/logo.png',
                prefix: '../../img/icons/'
            }),
        ],
        output: {
            filename: '[name].bundle.js', //Archivo o carpeta + nombre del archivo de salida
            chunkFilename: 'chunks/[name].bundle.js',
            path: resolve(__dirname, 'dist'),
            publicPath: ((PRODUCTION) ? 'http://192.168.0.4/ceiba_negra/' : 'http://192.168.0.4/ceiba_negra/') + 'assets/js/dist/'
        }
    };
};