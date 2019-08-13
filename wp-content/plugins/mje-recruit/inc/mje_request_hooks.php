<?php
function mje_request_patch($query) {
    if ( is_post_type_archive('mjob_request') ) {
        if ( !$query->is_main_query() ) return $query;

            $query->set('post_status', array(
                'publish',
            ));

    }
    return $query;
}
add_action('pre_get_posts','mje_request_patch');
function rec_load_single_template($file){
	if( is_singular( MJOB_RECRUIT ) ){
		$file =  MJOBREQUEST_PLUGIN_DIR . '/single-recruit.php';
	}
	return $file;
}

add_filter( 'template_include', 'rec_load_single_template' );