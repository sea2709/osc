<?php
    global $user_ID, $post, $ae_post_factory;
    $order_object = $ae_post_factory->get('ae_message');
    $custom = $order_object->convert($post);
?>
<li>
    <div id="custom-order-<?php echo $post->ID; ?>">
        <h2>
            <a data-id="<?php echo $custom->ID ?>"  title="<?php echo $custom->mjob_title; ?>" class="name-customer-order">
                <?php if($custom->mjob_title) echo $custom->mjob_title; ?>
            </a>
        </h2>

        <?php if($custom->label_status != "") : ?>
            <div class="label-status order-color <?php echo $custom->label_class; ?> custom-order-status" data-id="<?php echo $custom->ID ?>">
                <span><?php echo $custom->label_status; ?></span>
            </div>
        <?php endif; ?>


        <p class="post-content custom-order-content" data-id="<?php echo $custom->ID ?>">
            <?php
                if($custom->short_content) {
                    $shortContent = mje_filter_message_content($custom->short_content);
                    echo $shortContent;
                }
            ?>
        </p>
        <div class="outer-etd">
            <div class="deadline">
                <p>
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                    <span>
                        <?php echo $custom->deadline ?>
                    </span>
                </p>
            </div>

            <div class="budget custom-order-budget" data-id="<?php echo $custom->ID ?>">
                <p><span class="mje-price-text"><?php if($custom->budget) echo $custom->budget; ?></span></p>
            </div>
        </div>
        <?php if($user_ID == $custom->to_user && $custom->status != 'offer_sent') : ?>
            <div class="custom-order-btn">
                <button class="btn-decline" data-custom-order="<?php echo $custom->ID; ?>"><?php _e('Decline', 'enginethemes'); ?></button>
                <button class="btn-send-offer" data-custom-order="<?php echo $custom->ID; ?>"><?php _e('Send offer', 'enginethemes') ?></button>
            </div>
        <?php endif; ?>
    </div>
</li>