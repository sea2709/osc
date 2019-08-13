<?php
function mje_offer_sync(){
	$method = $_REQUEST['method'];

	$result = MJE_Offer::get_instance()->sync($_REQUEST);

	$response = array('success' => true,'msg' => __('Your offer has been submitted','enginethemes') );
	if( ! is_wp_error($result) ){
        do_action('do_after_submit_offer',$result);
		wp_send_json($response);
	}
	$response['success'] = false;
	$response['msg'] = $result->get_error_message();
	$response['data'] = $result;
	// send response to client
	wp_send_json($response);

}
add_action('wp_ajax_ae_mje_offer_sync', 'mje_offer_sync' );
function mje_recruit_archive_action(){
    global $user_ID;
    $re_id = $_REQUEST['ID'];
    $request = get_post($re_id);
    if( ! is_wp_error($request) && $request->post_author == $user_ID ){
        wp_update_post(array('ID' => $re_id,'post_status' => 'archive') );

    }
}
add_action('wp_ajax_archive-request', 'mje_recruit_archive_action' );
function change_args_request($args){

	$post_type = '';
	if( isset($_REQUEST['query'])){

		$post_type = isset($_REQUEST['query']['post_type']) ? $_REQUEST['query']['post_type'] :'';
	}

	if( is_post_type_archive( MJOB_RECRUIT ) || $post_type == MJOB_RECRUIT ){
		$args['post_type'] = MJOB_RECRUIT ;
	}

	return $args;

}
add_filter('mje_mjob_filter_query_args','change_args_request');
function fetch_requests(){
	 $request = $_REQUEST;
        $page = $request['page'];
        $query_args = $request['query'];
        $query_args['page'] = $page;

        $review_obj = MJE_Request::get_instance();
        $reviews = $review_obj->fetch($query_args);
        $reviews = $reviews['data'];

        if(!empty($reviews)) {
            wp_send_json(array(
                'success' => true,
                'data' => $reviews,
                'max_num_pages' => $query_args['total']
            ));
        } else {
            wp_send_json(array(
                'success' => false
            ));
        }
}
add_action('wp_ajax_mjob-fetch-request', 'fetch_requests');
function mje_partch_custom_ofer($response,$request){


	$mjob_id = isset($request['mjob']) ? $request['mjob']:0;
	if( $mjob_id && empty( $response['data']->mjob_id ) ){
		$prequest = get_post($mjob_id);

		if($prequest->post_type == MJOB_RECRUIT){
			$response['data']->mjob_id  = $mjob_id;
			$response['data']->mjob_title = $prequest->post_title;
    	    $response['data']->mjob_guid = $prequest->guid;
    	}


	}

	//$result->mjob_id = get_post_meta($result->ID, "custom_order_mjob", true);
	return $response;

}
add_filter('ae_message_response','mje_partch_custom_ofer', 10 ,2 );
function insert_notification_to_buyer($message, $request){
		$message_data = $message['data'];
		// var_dump($message_data);
		// var_dump($request);
		if(!is_wp_error($message_data) && $request['type'] == 'offer' && $request['method'] == 'create'){
			$to_user = $request['to_user'];
			// send notification to buyer here.
		}
		//var_dump($message_data);

		// $code = 'type=' . $mode . '_credit';
  //       $code .= '&from=' . $user_wallet->balance;
  //       $code .= '&to=' . $balance;
  //       $code .= '&amount=' . $amount;
  //       $code .= '&message=' . $message;
  //       MJE_Notification_Action::get_instance()->create( $code, $user );
	}
add_action('ae_after_message', 'insert_notification_to_buyer' , 10, 2);

//add_action( 'mje_other_type_notification', array( $this, 'mrequest_convert_notification' ) );
function mrequest_convert_notification( $post ) {
        $code = trim( $post->post_content );
        $code = str_ireplace( '&amp;', '&', $post->post_content );
        $code = strip_tags( $code );

        // Convert string to variables
        parse_str( $code , $result);

        $type = isset($result['type']) ? $result['type'] : '';
        $amount = isset($result['amount']) ? $result['amount'] : '';
        $from = isset($result['from']) ? $result['from'] : '';
        $to = isset($result['to']) ? $result['to'] : '';

        if( 'new_offer' == $type  ) {

        $post->noti_content = sprintf( __( 'Admin deducted %s from your available credit. Your current credit has been changed from %s to %s', 'enginethemes'), $amount, $from, $to );


            $post->noti_link = et_get_page_link( 'revenues' ) . '#topup';
        }
    }

function mje_recruit_custom($post){

    $code = trim( $post->post_content );
    $code = str_ireplace( '&amp;', '&', $post->post_content );
    $code = strip_tags( $code );

    // Convert string to variables
    parse_str( $code ,$result);

  	$type = isset($result['type']) ? $result['type']:'';

    $request_id = isset($result['request_id']) ? $result['request_id']:'';
    if($type == 'new_offer'){
    	$post->noti_link = get_the_permalink( $request_id );
    	$request = '<strong>' . get_the_title( $request_id ) . '</strong>';
    	$post->noti_content = sprintf( __( 'There is a new offer on your  request: %s.', 'enginethemes' ), $request );
	}

	return $post;
}

add_filter('mje_build_notification','mje_recruit_custom', 999);

?>