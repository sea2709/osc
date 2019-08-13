<?php


if ( ! function_exists( 'et_log' ) ) {

    function et_log($input, $file_store = ''){

        $file_store = WP_CONTENT_DIR.'/et_log.log';

        if( is_array( $input ) || is_object( $input ) ){
            error_log( print_r($input, TRUE), 3, $file_store );
        } else {
            error_log( $input . "\n" , 3, $file_store);
        }
    }
}
function et_track_payment($input){
    if( defined('TRACK_PAYMENT')  && TRACK_PAYMENT ) {
        $file_store = WP_CONTENT_DIR.'/et_track_payment.log';

        if( is_array( $input ) || is_object( $input ) ){
            error_log( print_r($input, TRUE), 3, $file_store );
        } else {
            error_log( $input . "\n" , 3, $file_store);
        }
    }
}
/**
 * check setting of user to know current user is subscriber to receiver email or not.
 * @since: 1.3.7.2
 * @author: danng
*/
function et_get_subscriber_settings($user_id = 0){

    if( ! $user_id ){
        global $user_ID;
        $user_id = $user_ID;
    }

    $et_subscriber =  get_user_meta($user_id,'et_subscriber', true);

    if( $et_subscriber == '2' || $et_subscriber === 2 )
        return false;

    return true;

}
/**
 * detect user is allow to receive email or not.
*/
function mje_is_subscriber($user_id = 0 ){
    return et_get_subscriber_settings($user_id);
}

function mje_loop_item_css($convert){
	$default = 'col-lg-4 col-md-4 col-sm-6 col-xs-6 col-mobile-12 item_js_handle';
	$class= apply_filters('mje_loop_item_css', $default, $convert);
	return $class;
}
function mje_home_loop_item_css($convert){
	$default = "col-lg-3 col-md-3 col-sm-6 col-mobile-12";
	$class= apply_filters('mje_home_loop_item_css', $default, $convert);
	return $class;
}
/**
 * use this function for post mjob page only.
 * To make the theme compatible with Mjob Featured.
*/
function disable_plan_post_mjob(){
    return ae_get_option('disable_plan', false);
}

if( ! function_exists('has_mje_featured') ) {
    function has_mje_featured(){
        if( function_exists('mje_featured_loaded'))
            return true;
        return false;
    }
}

function has_mje_discount(){
    if( class_exists('Mje_Discount'))
        return true;
    return false;
}
function mje_update_time_used_discount($code){
    global $wpdb;
    $post = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_title = %s AND post_type = 'mje_coupon'", $code) );
    if ( null !== $post ) {

        $id = $post->ID;
        // et_log('Discount Post ID: '. $id);
        // et_log('Discount code: '.$code);
        $time_used = (int) get_post_meta($id,'time_used_discount', true);
        $update_time = $time_used + 1;
        update_post_meta($id, 'time_used_discount', $update_time);
    }
}