<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Request_Install
{
    public static $instance;
    public static function get_instance()    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function install() {
        self::create_pages();
    }

    /**
     * Create My Bookmark Page with shortcode already
     */
    public static function create_pages() {
        $option_name = 'mje_my_recruit_page_id';
        $options = get_option( 'et_options' );
        $my_request_page_id = isset( $options[$option_name] ) ? $options[$option_name] : '';
        if( empty( $my_bookmark_page_id ) ) {
            // create new page with content is shortcode [mje_my_bookmark]
            $page = wp_insert_post( array(
                'post_title' => 'My Recruiments',
                'post_content' => '[my_recruitments]',
                'post_status' => 'publish',
                'post_type' => 'page'
            ) );
            // set page template
            update_post_meta( $page, '_wp_page_template', 'page-user-default.php' );
            // update option
            $options[$option_name] = $page;
            update_option( 'et_options', $options );
        }
    }
}