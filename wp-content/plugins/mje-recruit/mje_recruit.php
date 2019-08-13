<?php
/*
Plugin Name: MJE Recruit
Plugin URI: http://enginethemes.com/
Version: 1.2
Author: EngineThemes
Author URI: enginethemes.com
License: GPLv2 or later
Text Domain: enginethemes
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function _mjobrecruit_load_plugin() {

	// bootstrap the core plugin
	define( 'MJOB_RECRUIT','recruit');
	define( 'MJOB_RECRUITS','recruits');
	define( 'MJOB_REQUEST','recruit');
	define( 'MJE_REQUEST', 'recruit');
	define( 'MJOB_REQUESTS','recruit');
	define( 'MJOBREQUEST_VERSION', '1.0' );
	define( 'MJOBREQUEST_PLUGIN_DIR', dirname( __FILE__ ) . '/' );
	define( 'MJOBREQUEST_PLUGIN_URL', plugins_url( '/' , __FILE__ ) );
	define( 'MJOBREQUEST_PLUGIN_FILE', __FILE__ );
	//include_once dirname( __FILE__ ) . '/inc/class-mjob-request.php';
	include_once dirname( __FILE__ ) . '/inc/functions.php';
	include_once dirname( __FILE__ ) . '/inc/class-ae-post.php';
	include_once dirname( __FILE__ ) . '/inc/class-mje-request.php';
	include_once dirname( __FILE__ ) . '/inc/class-mje-offer.php';
	include_once dirname( __FILE__ ) . '/inc/mje_request_hooks.php';

	include_once dirname( __FILE__ ) . '/inc/ajax_hook.php';
	include_once dirname( __FILE__ ) . '/inc/class-mje-request-front.php';
	include_once dirname( __FILE__ ) . '/inc/recruit_emails.php';


}

add_action( 'plugins_loaded', '_mjobrecruit_load_plugin', 99 );
add_action( 'init',  'mje_recuits_load_translations', 6 );
function mje_recuits_load_translations() {
    load_plugin_textdomain( 'mje_recruit', false,  dirname( plugin_basename( __FILE__ ) ). '/languages' );
}
function _mjobrecuits_init(){

	global $ae_post_factory;

	if( is_null($ae_post_factory) ){
		$ae_post_factory = new MJE_Post_Factory();
	}
	//$ae_post_factory->set( 'mjob_request', new MJOB_Request( array('mjob_category'), array('et_budget') ) );
	$ae_post_factory->set( MJOB_RECRUIT, new MJE_Request() );
	$ae_post_factory->set( 'mje_offer', new MJE_Offer() );
	if( is_admin() ){
		include_once dirname( __FILE__ ) . '/admin/admin.php';
	}

}
add_action( 'after_setup_theme', '_mjobrecuits_init', 999 );


function _mje_recuits_on_plugin_deactivation(){

}
function _mje_recruits_on_plugin_activation(){
	include_once dirname( __FILE__ ) . '/inc/class-request-install.php';
	Request_Install::get_instance()->install();
}
register_activation_hook( __FILE__, '_mje_recruits_on_plugin_activation' );
register_deactivation_hook( __FILE__, '_mje_recuits_on_plugin_deactivation' );

if( ! function_exists('get_my_recruit_page_link') ):
	function get_my_recruit_page_link(){
		$option_name = 'mje_my_recruit_page_id';
	    $options = get_option( 'et_options' );
	    $my_request_page_id = isset( $options[$option_name] ) ? $options[$option_name] : '';
	    if ( ! empty( $my_request_page_id ) ) {
	    	return get_permalink($my_request_page_id);
	    }
	    return home_url();
	}
endif;