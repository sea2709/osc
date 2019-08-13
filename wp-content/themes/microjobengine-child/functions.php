<?php
if (!function_exists('mje_show_user_header')) {
    /**
     * Show user section on main navigation
     * @param void
     * @return void
     * @since 1.0
     * @package Microjobengine
     * @category File Functions
     * @author Tat Thien
     */
    function mje_show_user_header() {
        global $current_user;
        $conversation_unread = mje_get_unread_conversation_count();
        // Check empty current user
        if (!empty($current_user->ID)) {
            ?>
            <div class="notification-icon list-message et-dropdown">
                <span id="show-notifications" class="link-message">
                    <?php echo mje_is_has_unread_notification() ? '<span class="alert-sign">' . mje_get_unread_notification_count() . '</span>' : ''; ?>
                    <i class="fa fa-bell"></i>
                </span>
            </div>

            <div class="message-icon list-message dropdown et-dropdown">
                <div class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                    <span class="link-message">
                         <?php
                         if ($conversation_unread > 0) {
                             echo '<span class="alert-sign">' . $conversation_unread . '</span>';
                         }
                         ?>
                        <i class="fa fa-comment"></i>
                    </span>
                </div>
                <div class="list-message-box dropdown-menu" aria-labelledby="dLabel">
                    <div class="list-message-box-header">
                        <span>
                            <?php
                            printf(__('<span class="unread-message-count">%s</span> New', 'enginethemes'), $conversation_unread);
                            ?>
                        </span>
                        <a href="#" class="mark-as-read"><?php _e('Mark all as read', 'enginethemes');?></a>
                    </div>

                    <ul class="list-message-box-body">
                        <?php
                        mje_get_user_dropdown_conversation();
                        ?>
                    </ul>

                    <div class="list-message-box-footer">
                        <a href="<?php echo et_get_page_link('my-list-messages'); ?>"><?php _e('View all', 'enginethemes');?></a>
                    </div>
                </div>
            </div>

            <!--<div class="list-notification">
                <span class="link-notification"><i class="fa fa-bell"></i></span>
            </div>-->
            <?php
            $absolute_url = mje_get_full_url($_SERVER);
            if ( is_mje_submit_page() ) {
                $post_link = '#';
            } else {
                $post_link = et_get_page_link('post-service') . '?return_url=' . $absolute_url;
            }
            ?>
            <div class="link-post-services">
                <a href="<?php echo $post_link; ?>"><?php _e('Post a job', 'enginethemes');?>
                    <div class="plus-circle"><i class="fa fa-plus"></i></div>
                </a>
            </div>
            <div class="user-account">
                <div class="dropdown user-account-dropdown et-dropdown">
                    <div class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                        <span class="avatar">
                            <span class="display-avatar"><?php echo mje_avatar($current_user->ID, 35); ?></span>
                            <span class="display-name"><?php echo $current_user->display_name; ?></span>
                        </span>
                        <span><i class="fa fa-angle-right"></i></span>
                    </div>
                    <ul class="dropdown-menu et-dropdown-login" aria-labelledby="dLabel">
                        <li><a href="<?php echo et_get_page_link('dashboard'); ?>"><?php _e('Dashboard', 'enginethemes');?></a></li>
                        <?php
                        /**
                         * Add new item menu after Dashboard
                         *
                         * @since 1.3.1
                         * @author Tan Hoai
                         */
                        do_action('mje_before_user_dropdown_menu');
                        ?>
                        <li><a href="<?php echo et_get_page_link("profile"); ?>"><?php _e('My profile', 'enginethemes');?></a></li>
                        <li><a href="<?php echo et_get_page_link("my-list-order"); ?>"><?php _e('My orders', 'enginethemes');?></a></li>
                        <li><a href="<?php echo et_get_page_link("my-listing-jobs"); ?>"><?php _e('My jobs', 'enginethemes');?></a></li>
                        <li><a href="<?php echo et_get_page_link("my-invoices"); ?>"><?php _e('My invoices', 'enginethemes');?></a></li>
                        <li class="post-service-link"><a href="<?php echo et_get_page_link('post-service'); ?>"><?php _e('Post a mJob', 'enginethemes');?>
                                <div class="plus-circle"><i class="fa fa-plus"></i></div>
                            </a></li>
                        <li class="get-message-link">
                            <a href="<?php echo et_get_page_link('my-list-messages'); ?>"><?php _e('Message', 'enginethemes');?></a>
                        </li>
                        <?php
                        /**
                         * Add new item menu before Sign out
                         *
                         * @since 1.3.1
                         * @author Tan Hoai
                         */
                        do_action('mje_after_user_dropdown_menu');
                        ?>
                        <li><a href="<?php echo wp_logout_url(home_url()); ?>"><?php _e('Sign out', 'enginethemes');?> </a></li>
                    </ul>
                    <div class="overlay-user"></div>
                </div>
            </div>
            <?php
        }
    }
}

if(!function_exists('mje_filter_message_content')) {
    function mje_filter_message_content($content) {
        // Get bad words
        $bad_words = (ae_get_option('filter_bad_words'))?ae_get_option('filter_bad_words'):'';
        //$bad_words = ($bad_words && !is_array($bad_words))?trim($bad_words):'';
//        $bad_words = preg_replace('/\s+/', '', $bad_words);

        $content = apply_filters('mjob_before_filter_message_content', $content);
        if(!empty($bad_words)) {
            // Get bad words replace
            $bad_words_replace = ae_get_option('bad_word_replace');
            if(empty($bad_words_replace)) {
                $bad_words_replace = "[bad word]";
            }
            $bad_words_arr = explode(",", $bad_words);
            foreach($bad_words_arr as $bad_word) {
                $bad_word = trim($bad_word);
                if(!empty($bad_word)) {
                    $content = str_ireplace($bad_word, $bad_words_replace, $content);
                }
            }
        }

        $content = apply_filters('mjob_after_filter_message_content', $content);

        return $content;
    }
}

add_action( 'wp_ajax_fre_get_skills', 'osc_fre_get_skills_handler' );

function osc_fre_get_skills_handler() {
    // Make your response and echo it.
    $request = $_REQUEST;
    if(isset($request['query']) ){
        $skills = get_terms( array(
            'taxonomy' => 'skill',
            'hide_empty' => false,
            'name__like'    => $request['query']
        ) );

        $data = [];
        foreach($skills as $term){
            array_push($data,$term->name);
        }

        wp_send_json($data);
    }

    wp_die();
}

function microjobengine_child_scripts_function() {
    wp_enqueue_script( 'app-engine-ext', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('appengine'));
}

add_action('wp_enqueue_scripts','microjobengine_child_scripts_function');

// important variables that will be used throughout this example
$bucket = 'osc.expert';

// these can be found on your Account page, under Security Credentials > Access Keys
$accessKeyId = 'AKIASWRYJXGBV4RQRAPG';
$secret = 'yEF8mntMWy6f+IGUjO2/lyx0S7K0e4cQSZimR7Ew';
$region = 'us-east-2';

$credential = $accessKeyId . '/' . date('Ymd') . '/' . $region . '/s3/aws4_request';
$date = date('Ymd\THis\Z');

$policy = base64_encode(json_encode(array(
    // ISO 8601 - date('c'); generates uncompatible date, so better do it manually
    'expiration' => date('Y-m-d\TH:i:s.000\Z', strtotime('+1 day')),
    'conditions' => array(
        array('bucket' => $bucket),
        array('acl' => 'public-read'),
//        array('starts-with', '$Content-Type', 'image/'),
        // Plupload internally adds name field, so we need to mention it here
        array('starts-with', '$name', ''),
        array('starts-with', '$key', ''),

        array('x-amz-credential' => $credential),
        array('x-amz-algorithm' => 'AWS4-HMAC-SHA256'),
        array('x-amz-date' => $date),
        array('x-amz-storage-class' => 'ONEZONE_IA')
    )
), JSON_UNESCAPED_SLASHES));

function createSigningKey($secretAccessKey, $date, $awsRegion) {
    $dateKey = hash_hmac('sha256', $date, "AWS4" . $secretAccessKey, true);
    $dateRegionKey = hash_hmac('sha256', $awsRegion, $dateKey, true);
    $dateRegionServiceKey = hash_hmac('sha256', 's3', $dateRegionKey, true);
    $signingKey = hash_hmac('sha256', 'aws4_request', $dateRegionServiceKey, true);

    return $signingKey;
}

$s3Params = [
    'policy' => $policy,
    'signature' => hash_hmac('sha256', $policy, createSigningKey($secret, date('Ymd'), $region)),
    'accessKeyId' => $accessKeyId,
    'bucket' => $bucket,
    'date' => $date,
    'credential' => $credential,
    'storageClass' => 'ONEZONE_IA',
    'url' => "https://$bucket.s3.amazonaws.com/",
    'shortdate' => date('Ymd')
];

wp_enqueue_script( 'my_js_library', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'));
wp_localize_script( 'my_js_library', 'OSC_S3', $s3Params );