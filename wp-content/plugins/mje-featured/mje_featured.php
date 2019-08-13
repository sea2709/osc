<?php
/*
Plugin Name: MJE Featured
Plugin URI: http://enginethemes.com/
Description: Allow mjobs to be featured on homepage & categories listing page
Version: 1.0
Author: EngineThemes
Author URI: enginethemes.com
License: GPLv2 or later
Text Domain: enginethemes
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
if( ! function_exists('has_mje_featured') ){
	function has_mje_featured(){
		return true;
	}
}


function mje_featured_loaded() {


	define( 'MJEFEATURED_VERSION', '1.0' );
	define( 'MJEFEATURED_PLUGIN_DIR', dirname( __FILE__ ) . '/' );
	define( 'MJEFEATURED_PLUGIN_URL', plugins_url( '/' , __FILE__ ) );
	define( 'MJEFEATURED_PLUGIN_FILE', __FILE__ );
	include_once dirname( __FILE__ ) . '/inc/functions.php';
	include_once dirname( __FILE__ ) . '/inc/class-front.php';

}


add_action( 'plugins_loaded', 'mje_featured_loaded', 999 );


function mjob_adin_init(){

	include_once dirname( __FILE__ ) . '/settings/class-mjob-featured-admin.php';
}
add_action('after_setup_theme','mjob_adin_init',999);


function load_translations() {
    load_plugin_textdomain( 'mje_featured', false,  dirname( plugin_basename( __FILE__ ) ). '/languages' );
}
add_action( 'init','load_translations' , 999);
