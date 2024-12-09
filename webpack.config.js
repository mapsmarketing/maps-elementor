const webpack = require('webpack');
const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CompressionPlugin = require('compression-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');

module.exports = {
  watch: false,
  watchOptions: {
    ignored: ['**/node_modules'],
  },
  entry: {
    posts: [path.resolve(__dirname, 'Modules/Posts/assets/skin-carousel.js')],
    'maps-conditions-admin': [path.resolve(__dirname, 'Lib/Conditions/assets/admin.js')],
    'maps-conditions-public': [path.resolve(__dirname, 'Lib/Conditions/assets/public.js')],
    'maps-documents': [path.resolve(__dirname, 'Modules/Documents/assets/skin-default.js')],
    'maps-documents-links': [path.resolve(__dirname, 'Modules/Document_Links/assets/skin-default.js')],
    'maps-cta-accordion': [
      path.resolve(__dirname, 'Modules/CTA_Accordion/assets/skin-default.js'),
      path.resolve(__dirname, 'Modules/CTA_Accordion/assets/skin-horizontal.js'),
    ],
    'maps-carousel': [path.resolve(__dirname, 'Modules/Carousel/assets/skin-default.js')],
    'maps-slider-tabs': [path.resolve(__dirname, 'Modules/Slider_Tabs/assets/skin-default.js')],
    'maps-taxonomy': [path.resolve(__dirname, 'Modules/Taxonomy/assets/skin-default.js')],
    'maps-teams': [path.resolve(__dirname, 'Modules/Teams/assets/skin-default.js')],
    'maps-menu-full-screen': [path.resolve(__dirname, 'Modules/Menu_Full_Screen/assets/skin-default.js')],
    'maps-menu-multi-level': [path.resolve(__dirname, 'Modules/Menu_Multi_Level/assets/skin-default.js')],
    'maps-nav-menu': [path.resolve(__dirname, 'Modules/Nav_Menu/assets/skin-default.js')],
    'maps-toggle-timeline': [path.resolve(__dirname, 'Modules/Toggle_Timeline/assets/skin-default.js')],
    'maps-toggle-text': [path.resolve(__dirname, 'Modules/Toggle_Text/assets/skin-default.js')],
    'maps-reorder': [path.resolve(__dirname, 'Lib/Reorder/assets/reorder.js')],
  },
  output: {
    path: path.resolve(__dirname, 'assets'),
    filename: 'js/[name].bundle.min.js',
  },
  externals: {
    jquery: 'jQuery',
  },
  plugins: [
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: ['**/*', '!img', '!img/**/*'],
    }),
    new MiniCssExtractPlugin({
      filename: 'css/[name].bundle.min.css',
    }),
    new CompressionPlugin({
      test: /\.(js|css)$/,
    }),
    new ESLintPlugin({
      files: 'Modules/**/*.{js,jsx}',
      extensions: ['js', 'jsx'],
    }),
  ],
  module: {
    rules: [
      {
        test: /\.js$/,
        use: ['babel-loader'],
        exclude: [/node_modules/],
      },
      {
        test: /\.css$/i,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              url: false,
            },
          },
        ],
      },
    ],
  },
  optimization: {
    splitChunks: {
      cacheGroups: {
        commons: {
          test: /[\\/]node_modules[\\/]/,
          name: 'vendor',
          chunks: 'all',
        },
      },
    },
    minimizer: [new CssMinimizerPlugin(), new TerserPlugin()],
  },
};
