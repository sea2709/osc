<?php
function mje_show_featured_block(){
	$option_name = 'mjob_featured_block_home';
    $options = get_option( 'et_options' );
    $show_block = isset( $options[$option_name] ) ?  $options[$option_name] : '';

    if( empty($show_block) )
        return true;
    return ($show_block == '0') ? false : true ;

}
function mjob_featured_block(){
	if( mje_show_featured_block() )
		echo do_shortcode('[mjobs_featured]');
}
function html_mjob_featured_home(){
    $args = array(
        'post_type' => 'mjob_post',
        'post_status' => 'publish',
        'orderby'        => 'rand',
        'meta_key'   => 'et_featured',
        'meta_value' => '1',
        'meta_compare' => 'f_compare',
        'posts_per_page'=> 4,
    );
    $query = new WP_Query($args);
    global $count_featured;
    if( $query->have_posts() ){
        $loop_class = "col-lg-3 col-md-3 col-sm-6 col-mobile-12 featured-item ";
        global $ae_post_factory;
        $post_object = $ae_post_factory->get('mjob_post');
        ob_start();
        while ($query->have_posts() ) {
            $query->the_post();
            global $post;
            $convert = $post_object->convert( $post );
            $post_data[] = $convert;
            echo '<li class="'.$loop_class.'">';
            // $et_featured = get_post_meta($post->ID,'et_featured', true);
            // var_dump($et_featured);
            mje_get_template( 'template/mjob-item.php', array( 'current' => $convert ) );
            echo '</li>';
            $count_featured++;
        }
        return ob_get_clean();
    }
    return '';
}
function mjob_featured_label(){

	$option_name = 'mjob_featured_label';
    $options = get_option( 'et_options' );
    $label = isset( $options[$option_name] ) ? $options[$option_name] : __('FEATURED MICROJOBS','mjob_featured');
    return  $label;
}

function number_mjob_featured_show(){

	$option_name = 'mjob_featured_number_items';
    $options = get_option( 'et_options' );
    $value = isset( $options[$option_name] ) ? (int) $options[$option_name] : 4;
    return $value;

}
function mjob_featured_ribbon_text(){

    $option_name = 'mje_featured_ribbon_text';
    $options = get_option( 'et_options' );
    $value = isset( $options[$option_name] ) ? $options[$option_name] : __('FEATURED','mjob_featured');
    return $value;

}
