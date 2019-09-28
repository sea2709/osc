<?php
/**
 * Used by Dashboard
 */
global $wp_query, $ae_post_factory, $post;
$post_object    = $ae_post_factory->get('mjob_order');
$current        = $post_object->current_post;
$orderThumb = $current->mjob_post_thumbnail;
if (empty($orderThumb)) {
    $photo_default = get_template_directory_uri().'/assets/img/mjob.png';
    if (ae_get_option('default_mjob')) {
        $default = ae_get_option('default_mjob');
        $defautl_thumb = $default['mjob_detail_slider'];
        $photo_default = $defautl_thumb[0];
    }

    $orderThumb = $photo_default;
}

?>
<li class="order-item">
    <div class="mjob-item">
        <div class="status-label">
            <span class="<?php echo $current->status_class; ?>"><?php echo $current->status_text; ?></span>
        </div>

        <div class="mjob-item__image">
            <a href="<?php echo $current->permalink; ?>">
                <img src="<?php echo $orderThumb ?>" alt="<?php echo $current->post_title; ?>">
            </a>
        </div><!-- end .mjob-item__image -->

        <div class="mjob-item__entry">
            <div class="mjob-item__title">
                <h2 class="trimmed" title="<?php echo $current->post_title; ?>">
                    <a href="<?php echo $current->permalink; ?>"><?php echo $current->post_title; ?></a>
                </h2>
            </div><!-- end .mjob-item__title -->

            <div class="mjob-item__price">
                <div class="mjob-item__price-inner">
                    <span class="starting-text customize-color"><?php _e( 'Total price:', 'enginethemes' ); ?></span>
                    <span class="price-text customize-color"><?php echo $current->amount_text; ?></span>
                </div>
            </div><!-- end .mjob-item__price -->

            <div class="mjob-item__bottom clearfix">
                <div class="mjob-item__author">
                    <span><?php printf( __( 'Author <a href="%s">%s</a>', 'enginethemes' ), $current->mjob_author_url, $current->mjob_author_name ) ?></span>
                </div><!-- end .mjob-item__author -->

                <div class="order-item__date">
                    <?php echo isset($current->post_human_time) ? $current->post_human_time : ''; ?>
                </div>
            </div>
        </div>
    </div>
</li>

