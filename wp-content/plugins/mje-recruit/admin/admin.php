<?php
// if ( ! defined( 'ABSPATH' ) || ! class_exists('AE_Base') ) {
//     exit; // Exit if accessed directly.
// }

class MJE_Request_Admin extends AE_Base
{
    /**
     *
     */
    public $prefix = '';

    /**
     * mje_request_Admin constructor.
     */
    public function __construct() {
        $this->prefix = MJOB_RECRUIT;
        $this->add_action( 'ae_admin_menu_pages', 'add_admin_menu_pages' );
    }

    /**
     * Add settings page for Stripe
     *
     * @param array $pages
     * @return array $pages
     * @since 1.0.0
     * @author Tat Thien
     */
    public function add_admin_menu_pages( $pages ) {
        $options = AE_Options::get_instance();
        $temp = array();
        $sections = array();
        $sections['general'] = $this->get_general_section();

        // Generate sections
        foreach ( $sections as $section ) {
            $temp[] = new AE_section( $section['args'], $section['groups'], $options );
        }

        // Create container
        $request_container = new AE_Container( array(
            'class' => '',
            'id' => 'settings'
        ), $temp, '' );

        // Create page
        $pages['mje-request'] = array(
            'args' => array(
                'parent_slug' => 'et-welcome',
                'page_title' => __( 'Recruit', 'mje_request' ),
                'menu_title' => __( 'Recruit', 'mje_request' ),
                'cap' => 'administrator',
                'slug' => 'et-mje-request',
                'icon' => 'fa fa-star',
                'desc' => __( 'An extension for MicrojobEngine', 'mje_request' )
            ),
            'container' => $request_container
        );

        return $pages;
    }

    /**
     * Generate general settings section
     *
     * @param void
     * @return array $sections
     * @since 1.1.4
     * @author Tat Thien
     */
    public function get_general_section () {
        $sections = array(
            'args' => array(
                'title' => __( 'General Settings', 'mje_request' ),
                'id' => 'ms-general',
                'class' => '',
                'icon' => '',
            ),
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __( 'General', 'mje_request' ),
                        'id' => '',
                        'class' => '',
                        'desc' => ''
                    ),
                    'fields' => array(
                        array(
                            'id' => $this->prefix . '_my_request_page_id',
                            'class' => '',
                            'type' => 'select',
                            'title' => __( 'My Recruits Page', 'mje_request' ),
                            'desc' => __( 'Choose a page displayed as My Recruit Page', 'mje_request'),
                            'name' => $this->prefix . '_my_request_page_id',
                            'data' => mje_get_page_ids()
                        ),



                    )
                ),
            )
        );

        return $sections;
    }
}

if( class_exists('ET_Microjobengine') ){
   new MJE_Request_Admin();
}