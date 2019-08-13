<?php
// if ( ! defined( 'ABSPATH' ) || ! class_exists('AE_Base') ) {
//     exit; // Exit if accessed directly.
// }

class MJE_EF_Admin extends AE_Base
{
    /**
     *
     */
    public $prefix = '';

    /**
     * mje_request_Admin constructor.
     */
    public function __construct() {
        $this->prefix = MJOB_EXTRAFEE;
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
        $mjeef_container = new AE_Container( array(
            'class' => '',
            'id' => 'settings'
        ), $temp, '' );

        // Create page
        $pages['mje-ef'] = array(
            'args' => array(
                'parent_slug' => 'et-welcome',
                'page_title' => __( 'Extra Fees', 'mje_ef' ),
                'menu_title' => __( 'Extra Fees', 'mje_ef' ),
                'cap' => 'administrator',
                'slug' => 'et-mje-ef',
                'icon' => 'fa fa-star',
                'desc' => __( 'An extension for MicrojobEngine', 'mje_ef' )
            ),
            'container' => $mjeef_container
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
                'title' => __( 'Add fees to mjob', 'mje_request' ),
                'id' => 'ms-general',
                'class' => '',
                'icon' => '',
            ),
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __( 'General', 'mje_bookmark' ),
                        'id' => '',
                        'class' => '',
                        'desc' => ''
                    ),
                    'fields' => array(

                        array(
                            'id' => 'extra_fee_percent_label',
                            'type' => 'text',
                            'title' => __("Label Of Extra Fees for buyers(%)", 'enginethemes') ,
                            'desc' => __("The text will be showed in front end.", 'enginethemes') ,
                            'name' => 'extra_fee_percent_label',
                            'placeholder' => sprintf(__("Percentage Extra Fee %d(%%)", 'enginethemes'), ae_get_option('extra_fees_percentage') ) ,
                            'class' => 'option-item bg-grey-input ',
                            'default'=> sprintf(__("Percentage Extra Fee %d(%%)", 'enginethemes'), ae_get_option('extra_fees_percentage') ) ,
                        ),
                         array(
                            'id' => 'extra-fees-percentage',
                            'type' => 'number',
                            'title' => __("Extra Fees for buyers(%)", 'enginethemes') ,
                            'desc' => __("Set up the extra fees charged to the buyer as percentage of mJob price.", 'enginethemes') ,
                            'name' => 'extra_fee_percentage',
                            'placeholder' => __("0", 'enginethemes') ,
                            'class' => 'option-item bg-grey-input positive_int_zero',
                            'default'=> 0
                        ),
                        array(
                            'id' => 'extra_fee_fixed_label',
                            'type' => 'text',
                            'title' => __("Label of Processing fees ($)", 'enginethemes') ,
                            'desc' => __("The label will be showed in front-end.", 'enginethemes') ,
                            'name' => 'extra_fee_fixed_label',
                            'placeholder' => __("Fixed Extra Fee", 'enginethemes') ,
                            'class' => 'option-item bg-grey-input ',
                            'default'=> __("Fixed Extra Fee", 'enginethemes') ,
                        ),
                         array(
                            'id' => 'extra_fee_fixed',
                            'type' => 'number',
                            'title' => __("Processing fees ($)", 'enginethemes') ,
                            'desc' => __("Set up extra fees as exact amount.", 'enginethemes') ,
                            'name' => 'extra_fee_fixed',
                            'placeholder' => __("0", 'enginethemes') ,
                            'class' => 'option-item bg-grey-input positive_int_zero',
                            'default'=> 0
                        ),



                    )
                ),
            )
        );

        return $sections;
    }
}

if( class_exists('ET_Microjobengine') ){
   new MJE_EF_Admin();
}
