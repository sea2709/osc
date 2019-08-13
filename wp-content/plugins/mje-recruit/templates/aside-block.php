<?php global $user_ID, $mjob_post; ?>
<div class="box-shadow box-aside-stat">

    <div class="mjob-single-stat">
        <div class="stat-block clearfix">
            <span class="price center"><?php echo $mjob_post->et_budget_text; ?></span>
        </div>


    </div>


    <div class="custom-order-link hide">
        <?php

        if( $user_ID != $mjob_post->post_author ) {
            $conversation_parent = 0;
            $conversation_guid = '';
            if($conversation = mje_get_conversation( $user_ID, $mjob_post->post_author )) {
                $conversation_parent = $conversation[0]->ID;
                $conversation_guid = $conversation[0]->guid;
            }

            $send_custom_order_id = 'bt-send-custom';
            if( in_array( $mjob_post->post_status, array( 'pause', 'pending', 'draft', 'reject', 'archive' ) ) ) {
                $send_custom_order_id = 'bt-send-custom-disable';
            }
            ?>
            <div>
                <a id="<?php echo $send_custom_order_id; ?>" data-mjob-name="<?php echo $mjob_post->post_title; ?>" data-mjob="<?php echo $mjob_post->ID ?>" data-conversation-guid="<?php echo $conversation_guid; ?>" data-conversation-parent="<?php echo $conversation_parent; ?>" data-to-user="<?php echo $mjob_post->post_author; ?>" data-from-user="<?php echo $user_ID ?>" style="cursor: pointer"><?php _e('Send custom order','enginethemes'); ?><i class="fa fa-paper-plane"></i></a>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<?php if($mjob_post->opening_message && $mjob_post->opening_message != '' && ($user_ID == $mjob_post->post_author || is_super_admin())) : ?>
    <div class="box-shadow opening-message">
        <div class="aside-title">
            <?php _e('Opening Message', 'enginethemes') ?> <i class="fa fa-question-circle popover-opening-message" style="cursor: pointer" aria-hidden="true"></i>
        </div>
        <div class="content">
            <?php
            $opening_message = wpautop($mjob_post->opening_message);
            $num_opening_message = str_word_count($opening_message);
            if($num_opening_message > 40) {
                ?>
                <div class="content-opening-message hide-content gradient">
                    <?php
                    echo $opening_message;
                    ?>
                </div>
                <a class="show-opening-message"><?php _e('Show more', 'enginethemes') ?></a>
                <?php
            } else {
                echo '<div class="content-opening-message">';
                echo $opening_message;
                echo '</div>';
                echo '<a class="show-opening-message"></a>';

            }
            ?>
        </div>
    </div>
<?php endif; ?>
<!-- single-profile !-->

<?php
global $wp_query, $ae_post_factory, $post, $user_ID;
// Get author data
$user_id = $mjob_post->post_author;

if($user_id == $user_ID) {
    $seller_id = get_post_meta($post->ID, 'seller_id', true);
    if(!empty($seller_id)) {
        $user_id = $seller_id;
    }
}

$user = mJobUser::getInstance();
$user_data = $user->get($user_id);

// Convert profile
$profile_obj = $ae_post_factory->get('mjob_profile');
$profile_id = get_user_meta($user_id, 'user_profile_id', true);
if($profile_id) {
    $profile = get_post($profile_id);
    if($profile && !is_wp_error($profile)) {
        $profile = $profile_obj->convert($profile);
    }
}

// User profile information
$description = !empty($profile->profile_description) ? $profile->profile_description : "";
$display_name = isset($user_data->display_name) ? $user_data->display_name : '';
$country_name = isset($profile->tax_input['country'][0]) ? $profile->tax_input['country'][0]->name : '';
$languages = isset($profile->tax_input['language']) ? $profile->tax_input['language'] : '';
?>
<div class="box-aside box-shadow">
    <div class="personal-profile">
        <div class="float-center">
            <?php
            echo mje_avatar($user_id, 75);
            ?>
        </div>
        <h4 class="float-center"><?php echo $display_name; ?></h4>
        <div class="line">
            <span class="line-distance"></span>
        </div>
        <ul class="profile">
            <li class="location clearfix">
                <div class="pull-left">
                    <span><i class="fa fa-map-marker"></i><?php _e('From ', 'enginethemes') ?></span>
                </div>
                <div class="pull-right">
                    <?php echo $country_name; ?>
                </div>
            </li>

            <li class="language clearfix">
                <div class="pull-left">
                    <span><i class="fa fa-globe"></i><?php _e('Languages ', 'enginethemes'); ?></span>
                </div>
                <div class="pull-right">
                    <?php
                    if(!empty($languages)) {
                        foreach($languages as $language) {
                            ?>
                            <p class="lang-item"><?php echo $language->name; ?></p>
                            <?php
                        }
                    }
                    ?>
                </div>
            </li>

            <?php mJobUser::showUserTimeZone( $user_id ); ?>

            <li class="bio clearfix">
                <span> <i class="fa fa-info-circle"></i><?php _e('Bio', 'enginethemes'); ?></span>
                <div class="content-bio">
                    <?php echo wp_trim_words($description, 50, '...'); ?>
                </div>
            </li>

            <?php
            /**
             * Show information for public profile
             */
            if(is_author()) {
                ?>
                <li class="clearfix">
                    <span> <i class="fa fa-money"></i><?php _e('Payment info', 'enginethemes'); ?></span>
                    <p>
                        <?php echo $payment_info; ?>
                    </p>
                </li>

                <li class="clearfix">
                    <span> <i class="fa fa-home"></i><?php _e('Billing info', 'enginethemes'); ?></span>
                    <ul>
                        <li>
                            <div class="cate-title"><?php _e('Business full name', 'enginethemes'); ?></div>
                            <p><?php echo $billing_full_name; ?></p>
                        </li>
                        <li>
                            <div class="cate-title"><?php _e('Full Address', 'enginethemes'); ?></div>
                            <p><?php echo $billing_full_address; ?></p>
                        </li>
                        <li>
                            <div class="cate-title"><?php _e('Country', 'enginethemes'); ?></div>
                            <?php
                            $country = get_term($billing_country);
                            echo '<p>'. $country->name .'</p>';
                            ?>
                        </li>
                        <li>
                            <div class="cate-title"><?php _e('VAT Number (USA)', 'enginethemes'); ?></div>
                            <p><?php echo $billing_vat; ?></p>
                        </li>
                    </ul>
                </li>
                <?php
            }
            ?>
        </ul>


        <div class="link-personal">
            <ul>
                <?php mje_show_contact_link($user_id); ?>
                <li><a href="<?php echo get_author_posts_url($user_id); ?>" class="profile-link"><?php _e('View my profile', 'enginethemes'); ?><i class="fa fa-user"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<?php wp_reset_query(); ?>