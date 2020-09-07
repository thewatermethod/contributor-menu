const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const TerserPlugin = require("terser-webpack-plugin");

const isProduction = process.env.NODE_ENV === "production";

module.exports = {
	...defaultConfig,
	optimization: {
		minimizer: [
			new TerserPlugin({
				cache: true,
				parallel: true,
				sourceMap: !isProduction,
				terserOptions: {
					compress: false,
					keep_fnames: true,
					mangle: false,
					moduele: true,
					output: {
						comments: /translators:/i,
					},
				},
				extractComments: false,
			}),
		],
	},
};
