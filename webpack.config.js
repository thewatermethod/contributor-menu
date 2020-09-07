const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const TerserPlugin = require("terser-webpack-plugin");

const isProduction = process.env.NODE_ENV === "production";

/***
 *
 * we've extended and changed the default wordpress webpack config here in order to solve a conflict with the jetpack plugin
 *
 */

module.exports = {
	...defaultConfig,
	optimization: {
		minimizer: [
			new TerserPlugin({
				cache: true,
				parallel: true,
				sourceMap: !isProduction,
				terserOptions: {
					module: true,
					output: {
						comments: /translators:/i,
					},
				},
				extractComments: false,
			}),
		],
	},
};
