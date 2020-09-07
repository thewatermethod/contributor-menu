<?php
/**
 * Plugin Name:     Contributor Menu
 * Description:     
 * Version:         0.1.0
 * Author:          Matt Bevilacqua
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     contributor-menu
 *
 * @package         wcw
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */

require_once( 'includes/class-menus-rest.php');
WCW_Menus_Rest::init();

function contributors_menu_wcw_render_block($atts) {
	if( $atts['selectedMenu'] === 0) {
		return '<div></div>';
	}

	$menu_items = wp_get_nav_menu_items($atts['selectedMenu']);

	$menu_html = []; 

	if( !is_array($menu_items) ) {
		return '<div></div>';
	}

	foreach ($menu_items as $item) {
		if( isset($item->url) && isset( $item->title))
		array_push($menu_html, '<li><a href="'.$item->url.'">'.$item->title.'</a></li>');
	}	

	return '<ul class="wp-block-contributor-menu">'.implode('', $menu_html).'</ul>';
}


function create_block_contributor_menu_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "wcw/contributor-menu" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'wcw-contributor-menu-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);



	wp_set_script_translations( 'wcw-contributor-menu-block-editor', 'contributor-menu' );



	$style_css = 'build/index.css';
	wp_register_style(
		'wcw-contributor-menu-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'wcw/contributor-menu', array(
		'editor_script'   => 'wcw-contributor-menu-block-editor',		
		'style'           => 'wcw-contributor-menu-block',
		'render_callback' => 'contributors_menu_wcw_render_block',
		'attributes' => array(         			
			'menus'=> array(
				'type' => 'array',			
			),		
			'selectedMenu' => array(
				'type' => "number",
				'default'=> 0,
			),				
		 ),
	) );
}

add_action( 'init', 'create_block_contributor_menu_block_init' );
