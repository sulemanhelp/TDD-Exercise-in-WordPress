const path = require( 'path' );

/**
 * WordPress dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		'block-editor': './assets/js/src/block-editor.js',
	},
	output: {
		path: path.resolve( __dirname, 'dist/js' ),
		filename: '[name].js',
	},
};
