<?php
// if ( ! defined( 'ABSPATH' ) || ! class_exists('AE_Base') ) {
//     exit; // Exit if accessed directly.
// }

class mje_featured_Admin extends AE_Base
{
    /**
     *
     */
    public $prefix = '';

    /**
     * mje_featured_Admin constructor.
     */
    public function __construct() {
        $this->prefix = 'mje_featured';
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
        $bookmark_container = new AE_Container( array(
            'class' => '',
            'id' => 'settings'
        ), $temp, '' );

        // Create page
        $pages['mjob-featured'] = array(
            'args' => array(
                'parent_slug' => 'et-welcome',
                'page_title' => __( 'Mjob Featured', 'mje_featured' ),
                'menu_title' => __( 'Mjob Featured', 'mje_featured' ),
                'cap' => 'administrator',
                'slug' => 'et-mjob-featured',
                'icon' => 'fa fa-star',
                'desc' => __( 'An extension for MicrojobEngine', 'mje_featured' )
            ),
            'container' => $bookmark_container
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
                'title' => __( 'General Settings', 'mje_featured' ),
                'id' => 'ms-general',
                'class' => '',
                'icon' => '',
            ),
            'groups' => array(

                array(
                   'args' => array(
                        'title' => __( 'General', 'mje_featured' ),
                        'id' => '',
                        'class' => '',
                        'desc' => ''
                    ),
                    'fields' => array(

                        array(
                            'id' => 'mje_featured_ribbon_text',
                            'class' => '',
                            'type' => 'text',
                            'title' => __( 'Ribbon  Label Text', 'mje_featured' ),
                            'desc' => __( 'Label for the "Featured Mjob" item', 'mje_featured'),
                            'name' => 'mje_featured_ribbon_text',
                            'default' => __('FEATURED','mje_featured'),
                        ),

                    )
                ),
            )
        );

        return $sections;
    }
}

//if( class_exists('ET_Microjobengine') ){
    new mje_featured_Admin();
//}

