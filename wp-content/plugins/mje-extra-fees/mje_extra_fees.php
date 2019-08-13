<?php
/*
Plugin Name: MjE Extra Fees
Plugin URI: https://enginethemes.com/
Description: Allow admin add extra fees for the mjobs.
Version: 1.0
Author: EngineThemes
Author URI: enginethemes.com
License: GPLv2 or later
Text Domain: enginethemes
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function _mjeef_load_plugin() {

	// bootstrap the core plugin
	define( 'MJOB_EXTRAFEE','ef');
	define( 'MJOB_EXTRAFEES','efs');
	define( 'MJEEF_VERSION', '1.0' );
	define( 'MJEEF_PLUGIN_DIR', dirname( __FILE__ ) . '/' );
	define( 'MJEEF_PLUGIN_URL', plugins_url( '/' , __FILE__ ) );
	define( 'MJEEF_PLUGIN_FILE', __FILE__ );
	//include_once dirname( __FILE__ ) . '/inc/class-mjob-request.php';
	include_once dirname( __FILE__ ) . '/inc/functions.php';

	// Initialize admin section of plugin

}

add_action( 'plugins_loaded', '_mjeef_load_plugin', 99 );
add_action( 'init',  'mje_ef_load_translations', 6 );
function mje_ef_load_translations() {
    load_plugin_textdomain( 'enginethemes', false,  dirname( plugin_basename( __FILE__ ) ). '/languages' );
}
function _mjeef_init(){

	if( is_admin() ){
		include_once dirname( __FILE__ ) . '/admin/admin.php';
	}

}
add_action( 'after_setup_theme', '_mjeef_init', 999 );
