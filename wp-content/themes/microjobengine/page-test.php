<?php
/**
 * Template Name: Page test
 *
 */
wp_head();
/*
$request = wp_remote_post('http://update.enginethemes.com/?do=product-update&product=microjobengine&type=theme', array(
    'body' => array(
        'action' => 'version',
        'product' => 'microjobengine',
        'key' => 'OsPOWLXCaVGif9asbEem'
    )
));

echo '<pre>';
var_dump( $request );
echo '</pre>';
*/

var_dump($_GET['id'],get_post_meta($_GET['id'],'video_meta',true));
wp_footer();
?>