<?php

/**
 * class Mailing control mail options
 *
 * @package  AE Mailing
 * @category mail
 *
 * @since  1.0
 * @author Dakachi
 */
class AE_Mailing extends AE_Base
{
    public static $instance;

    static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new AE_Mailing();
        }

        return self::$instance;
    }

    function __construct() {
    }

    /**
     * send email to user after he successful confirm email
     * @param Int $user_id
     * @version 1.0
     */
    function confirmed_mail($user_id) {
        $user = new WP_User($user_id);
        $user_email = $user->user_email;

        $subject = __("Congratulations! Your account has been verified successfully.", 'enginethemes');
        $message = ae_get_option('confirmed_mail_template');

        $this->wp_mail($user_email, $subject, $message, array(
            'user_id' => $user_id
        ));
    }

    /**
     * mail to user when a user contact him
     * @param Object WP_User  $author
     * @param string $inbox_message
     * @author ThaiNT
     */
    function inbox_mail($author, $inbox_message) {
        global $current_user;

        $headers = 'From: ' . $current_user->display_name . ' <' . $current_user->user_email . '>' . "\r\n";
        $headers .= "Reply-To: $current_user->user_email " . "\r\n";

        /**
         * Filter inbox mail header
         * @param string $headers
         */
        $headers = apply_filters('ae_inbox_mail_headers', $headers);

        $subject = sprintf(__('[%s]New Private Message From %s', 'enginethemes') , get_bloginfo('blogname') , $current_user->display_name);
        $message = ae_get_option('inbox_mail_template');
        $inbox_message = stripslashes(str_replace("\n", "<br>", $inbox_message));
        $sender = get_author_posts_url($current_user->ID);

        /**
         * replace holder receive
         */
        $message = str_ireplace('[display_name]', $author->display_name, $message);

        $message = str_ireplace('[sender_link]', $sender, $message);
        $message = str_ireplace('[sender]', $current_user->display_name, $message);
        $message = str_ireplace('[message]', $inbox_message, $message);
        $message = str_ireplace('[blogname]', get_bloginfo('blogname') , $message);
        $this->wp_mail( $author->user_email, $subject, $message, array(), $headers);
    }

    /**
     * user forgot pass mail
     * @param Int $user_id
     * @param String $key Activate key
     */
    function forgot_mail($user_id, $key) {
        $user = new WP_User($user_id);
        $user_email = $user->user_email;
        $user_login = $user->user_login;

        $message = ae_get_option('forgotpass_mail_template');

        $activate_url = add_query_arg(array(
            'user_login' => $user_login,
            'key' => $key
        ) , et_get_page_link('reset-pass'));

        $activate_url = '<a href="' . $activate_url . '">' . __("Recover Link", 'enginethemes') . '</a>';
        $message = str_ireplace('[activate_url]', $activate_url, $message);
        $message = str_ireplace('[recover_url]', $activate_url, $message);
        if (is_multisite()) $blogname = $GLOBALS['current_site']->site_name;
        else $blogname = wp_specialchars_decode(get_option('blogname') , ENT_QUOTES);

        $subject = sprintf(__('[%s] Password Reset', 'enginethemes') , $blogname);

        $subject = apply_filters('et_retrieve_password_title', $subject);

        $this->wp_mail($user_email, $subject, $message, array(
            'user_id' => $user_id
        ));
    }

    /**
     * send email after user reset password successfully
     * @param int $user_id
     * @return void
     * @package Appengine
     * @category Mailing
     * @author Tat Thien
     */
    function resetpass_mail($user_id) {
        $user = new WP_User($user_id);
        $user_email = $user->user_email;
        $message = ae_get_option('resetpass_mail_template');
        $site_url = '<a href="'. get_site_url() .'" target="_blank">'. get_site_url() .'</a>';
        $message = str_ireplace('[site_url]', $site_url, $message);
        if (is_multisite()) {
            $blogname = $GLOBALS['current_site']->site_name;
        } else {
            $blogname = wp_specialchars_decode(get_option('blogname') , ENT_QUOTES);
        }

        $subject = sprintf(__('[%s] Reset Password Successfully', 'enginethemes'), $blogname);
        $subject = apply_filters('ae_reset_password_mail_title', $subject);
        $this->wp_mail($user_email, $subject, $message, array(
            'user_id' => $user_id
        ));
    }

    /**
     * user report mail
     * @param String $admin_email
     * @param Array $request
     */
    function report_mail($admin_email, $request) {

        $user = new WP_User($request['user_report']);
        $place = get_post($request['comment_post_ID']);
        $subject = sprintf(__("[%s]New report message from %s.", 'enginethemes') , get_option('blogname') , $user->display_name);

        $message = ae_get_option('ae_report_mail');
        $message = str_replace('[place_title]', $place->post_title, $message);
        $message = str_replace('[place_link]', get_permalink($place->ID) , $message);
        $message = str_replace('[report_message]', wpautop($request['comment_content']) , $message);
        $message = str_replace('[user_name]', $user->display_name, $message);
        $message = str_replace('[reports_link]', admin_url('edit-comments.php?comment_type=report') , $message);

        $this->wp_mail($admin_email, $subject, $message);
    }

    /**
     * user approve claim mail
     * @param String $user_email
     * @param Array $request
     */
    function approve_claim_mail($user_email, $request) {

        $user = new WP_User($request['user_request']);
        $place = get_post($request['place_id']);
        $subject = sprintf(__("[%s]Your claim request has been approved.", 'enginethemes') , get_option('blogname'));

        $message = ae_get_option('ae_approve_claim_mail');
        $message = str_replace('[place_title]', $place->post_title, $message);
        $message = str_replace('[place_link]', get_permalink($place->ID) , $message);
        $message = str_replace('[display_name]', $user->display_name, $message);

        $this->wp_mail($user_email, $subject, $message);
    }

    /**
     * user reject claim mail
     * @param String $user_email
     * @param Array $request
     */
    function reject_claim_mail($user_email, $request) {

        $user = new WP_User($request['user_request']);
        $place = get_post($request['place_id']);
        $subject = sprintf(__("[%s]Your claim request has been rejected.", 'enginethemes') , get_option('blogname'));

        $message = ae_get_option('ae_reject_claim_mail');
        $message = str_replace('[place_title]', $place->post_title, $message);
        $message = str_replace('[display_name]', $user->display_name, $message);

        $this->wp_mail($user_email, $subject, $message);
    }

    /**
     * user claim mail
     * @param String $admin_email
     * @param Array $request
     */
    function claim_mail($admin_email, $request) {

        $user = new WP_User($request['user_request']);
        $place = get_post($request['place_id']);
        $subject = sprintf(__("[%s]New claim request from %s.", 'enginethemes') , get_option('blogname') , $user->display_name);

        $message = ae_get_option('ae_claim_mail');
        $message = str_replace('[place_title]', $place->post_title, $message);
        $message = str_replace('[place_link]', get_permalink($place->ID) , $message);
        $message = str_replace('[claim_message]', wpautop($request['message']) , $message);
        $message = str_replace('[user_name]', $user->display_name, $message);
        $message = str_replace('[claim_full_name]', $request['display_name'], $message);
        $message = str_replace('[claim_email]', $user->user_email, $message);
        $message = str_replace('[claim_phone]', $request['phone'], $message);
        $message = str_replace('[claim_address]', $request['location'], $message);
        $message = str_replace('[place_edit_link]', admin_url('post.php?post=' . $request['place_id']) . '&action=edit', $message);

        $this->wp_mail($admin_email, $subject, $message);
    }

    /**
     * Register email
     * @param integer $user_id
     * @return void
     * @since 1.0
     * @package Appengine
     * @category void
     * @author Daikachi
     */
    function register_mail($user_id) {
        $user = new WP_User($user_id);
        $user_email = $user->user_email;

        $subject = sprintf(__("Congratulations! You have successfully registered to %s.", 'enginethemes') , get_option('blogname'));

        if (ae_get_option('user_confirm')) {
            $message = ae_get_option('confirm_mail_template');
        } else {
            $message = ae_get_option('register_mail_template');
        }
        $this->wp_mail($user_email, $subject, $message, array(
            'user_id' => $user_id
        ));
    }

    /* user request a new confirm email */
    function request_confirm_mail($user_id) {
        global $current_user;
        $user = $current_user;
        $user_email = $user->user_email;
        if (ae_get_option('user_confirm')) {
            update_user_meta($user_id, 'register_status', 'unconfirm');
            update_user_meta($user_id, 'key_confirm', wp_hash(md5($user_email . time())));
        }
        $subject = sprintf(__("Confirm your email address to activate your account at %s.", 'enginethemes') , get_option('blogname'));

        //if (ae_get_option('user_confirm')) {
        $message = ae_get_option('confirm_mail_template');

        // }
        return $this->wp_mail($user_email, $subject, $message, array(
            'user_id' => $user_id
        ));
    }

    /**
     * Change status
     * @param string $new_status
     * @param string $old_status
     * @param object $post
     * @return string $new_status
     * @since 1.0
     * @package Appengine
     * @category void
     * @author Daikachi
     */
    function change_status($new_status, $old_status, $post) {

        if ($new_status != $old_status) {

            $authorid = $post->post_author;
            $user = get_userdata($authorid);
            $user_email = $user->user_email;

            switch ($new_status) {
                case 'publish':

                    // publish post mail
                    $subject = sprintf(__("Your post '%s' has been approved.", 'enginethemes') , get_the_title($post->ID));
                    $message = ae_get_option('publish_mail_template');

                    //send mail
                    $this->wp_mail($user_email, $subject, $message, array(
                        'user_id' => $authorid,
                        'post' => $post->ID
                    ) , '');
                    break;

                case 'archive':

                    // archive post mail

                    $subject = sprintf(__('Your post "%s" has been archived', 'enginethemes') , get_the_title($post->ID));
                    $message = ae_get_option('archive_mail_template');

                    // send mail
                    $this->wp_mail($user_email, $subject, $message, array(
                        'user_id' => $authorid,
                        'post' => $post->ID
                    ) , '');

                    break;

                default:

                    //code
                    break;
            }
        }
        return $new_status;
    }

    /**
     * send reject mail function
     * @param array $data
     * @author Tambh
     * @version 1.0
     */
    function reject_post($data) {

        // get post author
        $user = get_user_by('id', $data['post_author']);
        $user_email = $user->user_email;

        // mail title
        $subject = sprintf(__("Your post '%s' has been rejected.", 'enginethemes') , get_the_title($data['ID']));

        // get reject mail template
        $message = ae_get_option('reject_mail_template');

        // filter reject message
        $message = str_replace('[reject_message]', $data['reject_message'], $message);

        // send reject mail
        $this->wp_mail($user_email, $subject, $message, array(
            'user_id' => $data['post_author'],
            'post' => $data['ID']
        ) , '');
    }

    /**
     * new post alert to admin
     * @param Int $post
     * @since 1.1
     * @author Dakachi
     */
    function new_post_alert($post) {
        $mail = ae_get_option('new_post_alert', '') ? ae_get_option('new_post_alert', '') : get_option('admin_email');
        $subject = __("Have a new post on your site.", 'enginethemes');
        $message = sprintf(__("<p>Hi,</p><p> Have a new post on your site. You can review it here: %s </p>", 'enginethemes') , get_permalink($post));
        $this->wp_mail($mail, $subject, $message);
    }

    /**
     * send a cash notification mail to customer
     *
     * @param string $message Cash message
     * @param integer $user_id The user 's id who purchase by cash
     * @param array $package
     * @param integer $post_id The post id user pay for
     *
     * @author Dakachi
     * @version 1.1
     */
    public function send_cash_message($message, $user_id, $package, $post_id = '') {
        $user = get_userdata($user_id);
        if ($post_id) {
            $subject = sprintf(__("You submit a post by cash on '%s'", 'enginethemes') , ae_get_option('blogname'));
        } else {
            $subject = sprintf(__("You purchase successfully package '%s' by cash on '%s'", 'enginethemes') , $package['NAME'], ae_get_option('blogname'));
        }

        $mail_template = ae_get_option('cash_notification_mail');
        $message = str_replace('[cash_message]', $message, $mail_template);
        $this->wp_mail($user->user_email, $subject, $message, array(
            'user_id' => $user_id,
            'post' => $post_id
        ));
    }

    /**
     * send receipt when submit a payment successful payment email
     * @param Int $user_id user purchase id
     * @param Array $order Order data
     * @return (bool) (required) Whether the email contents were sent successfully.
     */
    public function send_receipt($user_id, $order, $data) {

        $subject = __('Thank you for your payment!', 'enginethemes');
        if($order['payment'] == 'cash') {
            $subject = __('Follow these steps to complete your payment.', 'enginethemes');
        }

        $user = get_userdata($user_id);

        $content = ae_get_option('ae_receipt_mail');
        $content = apply_filters('ae_filter_receipt_mail_template', $content, $order, $data);

        $products = $order['products'];

        $product = array_pop($products);
        $ad_id = $product['ID'];

        //$ad             =   get_post($ad_id);
        $ad_url = '<a href="' . get_permalink($ad_id) . '">' . get_the_title($ad_id) . '</a>';

        $content = str_ireplace('[link]', $ad_url, $content);
        $content = str_ireplace('[display_name]', $user->display_name, $content);
        $content = str_ireplace('[payment]', ucfirst($order['payment']), $content);
        $content = str_ireplace('[invoice_id]', $order['ID'], $content);
        $content = str_ireplace('[date]', date(get_option('date_format') , time()) , $content);
        $content = str_ireplace('[total]', $order['total'], $content);
        $content = str_ireplace('[currency]', $order['currency'], $content);
        return $this->wp_mail($user->user_email, $subject, $content, array(
            'user_id' => $user_id,
            'post' => $ad_id
        ));
    }

    /**
     * send mail function
     * @param $to
     * @param $subject
     * @param $content
     * @param array $filter
     *  - post : the post id will be replace by placeholder in $content
     *  - user_id : the user_id will be replace by placeholder in $content
     * @param array/string $headers mail header
     * @return (bool) (required) Whether the email contents were sent successfully.
     * @author Dakachi <ledd@youngworld.vn>
     * @since 1.0
     */
    public function wp_mail($to, $subject, $content, $filter = array() , $headers = '') {
        if ($headers == '') {

            // $headers = 'MIME-Version: 1.0' . "\r\n";
            // $headers.= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers.= "From: " . get_option('blogname') . " < " . get_option('admin_email') . "> \r\n";
        }

        /**
         * site info url, name, admin email
         */
        $content = str_ireplace('[site_url]', get_bloginfo('url') , $content);
        $content = str_ireplace('[blogname]', get_bloginfo('name') , $content);
        $content = str_ireplace('[admin_email]', get_option('admin_email') , $content);

        if (isset($filter['user_id'])) {
            $content = $this->filter_authentication_placeholder($content, $filter['user_id']);
        }
        if (isset($filter['post'])) {

            // filter post placeholder
            $content = $this->filter_post_placeholder($content, $filter['post']);
        }
        $content = html_entity_decode((string)$content, ENT_QUOTES, 'UTF-8');
        $subject = html_entity_decode((string)$subject, ENT_QUOTES, 'UTF-8');

        //$content    = $this->get_mail_header() . $content . $this->get_mail_footer() ;
        add_filter('wp_mail_content_type', array(
            $this,
            'set_html_content_type'
        ));
        $a = wp_mail($to, $subject, $this->get_mail_header() . $content . $this->get_mail_footer() , $headers);
        remove_filter('wp_mail_content_type', array(
            $this,
            'set_html_content_type'
        ));
        return $a;
    }

    function set_html_content_type() {
        return 'text/html';
    }

    /**
     * return mail header template
     */
    function get_mail_header() {

        $mail_header = apply_filters('ae_get_mail_header', '');
        if ($mail_header != '') return $mail_header;

        $logo_url = get_template_directory_uri() . "/img/logo-de.png";
        $options = AE_Options::get_instance();

        // save this setting to theme options
        $site_logo = $options->site_logo;
        if (!empty($site_logo)) {
            $logo_url = $site_logo['large'][0];
        }

        $logo_url = apply_filters('ae_mail_logo_url', $logo_url);

        $customize = et_get_customization();

        $mail_header = '<html>
                        <head>
                        </head>
                        <body style="font-family:"Roboto",OpenSans,"Open Sans",Arial,sans-serif;font-size: 18px;margin: 0; padding: 0; color: #222222;background-color: #f0f0f0">
                        <div style="background:#F0F0F0;padding: 40px;">
                        <div style="margin: 0px auto; width:600px; background: #FFF; border: 1px solid ' . $customize['background'] . '">
                            <table width="100%" cellspacing="0" cellpadding="0">
                            <tr style="background: #2a394e; height: 63px; vertical-align: middle;">
                                <td style="padding: 10px 5px 10px 20px; width: 100%; text-align: center">
                                <a href="' . home_url() . '"> 
                                    <img style="max-height: 100px" src="' . $logo_url . '" alt="' . get_option('blogname') . '">
                                </a>
                                </td>
                            </tr>
                            <tr><td style="height: 5px; background-color: ' . $customize['background'] . ';"></td></tr>
                            <tr>
                                <td style="background: #ffffff; color: #222222; line-height: 26px; padding: 10px 20px;font-size: 18px;">';
        return $mail_header;
    }

    /**
     * return mail footer html template
     */
    function get_mail_footer() {

        $mail_footer = apply_filters('ae_get_mail_footer', '');
        if ($mail_footer != '') return $mail_footer;

        $info = apply_filters('ae_mail_footer_contact_info', get_option('blogname') . ' <br>');

        $mail_footer = '</td>
                        </tr>
                        <tr>
                            <td style="background: #ffffff; color: #222222; line-height: 20px; padding: 10px 20px;"><a href="' . home_url() . '"> ' . $info . '</a></td>
                        </tr>
                        </table>
                    </div>
                    </div>
                    </body>
                    </html>';
        return $mail_footer;
    }

    /**
     * mail filter placeholder function
     * @param string $content
     * @param integer $user_id
     * @return string $content
     * @author Dakachi
     * @since 1.0
     */
    function filter_authentication_placeholder($content, $user_id) {
        $user = new WP_User($user_id);

        /**
         * member user login, username
         */
        $content = str_ireplace('[user_login]', $user->user_login, $content);
        $content = str_ireplace('[user_name]', $user->user_login, $content);

        // user nicename plaholder
        $content = str_ireplace('[user_nicename]', ucfirst($user->user_nicename) , $content);

        //member email
        $content = str_ireplace('[user_email]', $user->user_email, $content);

        /**
         * member display name
         */
        $content = str_ireplace('[display_name]', ucfirst($user->display_name) , $content);
        $content = str_ireplace('[member]', ucfirst($user->display_name) , $content);

        /**
         * author posts link
         */
        $author_link = '<a href="' . get_author_posts_url($user_id) . '" >' . __("Author's Posts", 'enginethemes') . '</a>';
        $content = str_ireplace('[author_link]', $author_link, $content);

        $key_confirm = get_user_meta($user_id, 'key_confirm', true);
        $confirm_link = add_query_arg(array(
            'act' => 'confirm',
            'key' => $key_confirm
        ) , home_url());

        $confirm_link = '<a style="background:#10a2ef;border-radius:45px;color:#FFFFFF;padding: 13px 16px;" href="' . $confirm_link . '" >' . __("Confirm link", 'enginethemes') . '</a>';

        /**
         * confirm link
         */
        $content = str_ireplace('[confirm_link]', $confirm_link, $content);

        /**
         * filter mail content et_filter_auth_email
         * @param String $content mail content will be filter
         * @param id $user_id The user id who the email will be sent to
         */
        $content = apply_filters('ae_filter_auth_email', $content, $user_id);

        return $content;
    }

    /**
     * filter mail content with post place holder
     * @param string $content
     * @param integer $post_id
     * @return string $content
     * @author Dakachi
     * @since 1.0
     */
    function filter_post_placeholder($content, $post_id = '') {
        if(!$post_id) return $content;
        $post = get_post($post_id);

        if(!$post || is_wp_error( $post )) return $content;


        $title = apply_filters('the_title', $post->post_title);

        /**
         * post content
         */
        $content = str_ireplace('[title]', $title, $content);
        $content = str_ireplace('[desc]', apply_filters('ae_the_content', $post->post_content) , $content);
        $content = str_ireplace('[excerpt]', apply_filters('the_excerpt', $post->post_excerpt) , $content);
        $content = str_ireplace('[author]', get_the_author_meta('display_name', $post->post_author) , $content);

        /**
         * post link
         */
        $post_link = '<a href="' . get_permalink($post_id) . '" >' . $title . '</a>';
        $content = str_ireplace('[link]', $post_link, $content);

        /**
         * author posts link
         */
        $author_link = '<a href="' . get_author_posts_url($post->post_author) . '" >' . __("Author's Posts", 'enginethemes') . '</a>';
        $content = str_ireplace('[author_link]', $author_link, $content);

        /**
         * filter mail content et_filter_ad_email
         * @param String $content mail content will be filter
         * @param id $user_id The post id which the email is related to
         */
        $content = apply_filters('ae_filter_post_email', $content, $post_id);

        return $content;
    }
}
