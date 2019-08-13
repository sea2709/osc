<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class MJE_PPEC_Admin extends AE_Base
{
    protected static $_instance;

    /**
     * Main instance.
     *
     * @return MJE_PPEC_Admin;
     */
    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct() {
        $this->add_filter( 'mje_payment_gateway_setting_sections', 'add_setting_fields' );
    }

    /**
     * Hook into payment settings section
     *
     * @param array $sections
     * @return array $sections
     * @since 1.0
     * @author Tat Thien
     */
    public function add_setting_fields( $sections ) {
        $sections['mje_ppec_section'] = $this->get_sections();
        return $sections;
    }

    /**
     * Generate setting fields
     *
     * @return array
     * @since 1.0
     * @author Tat Thien
     */
    public function get_sections() {
        $sections = array(
            'args' => array(
                'title' => __( 'PayPal Express Checkout', 'mje_ppec' ),
                'id' => 'mje-ppec-section',
                'class' => '',
                'icon' => '',
            ),
            'groups' => array(
                array(
                    'args' => array(
                        'title' => __( 'PayPal Express Checkout', 'mje_ppec' ),
                        'id' => '',
                        'class' => '',
                        'desc' => __( 'Allow customers to conveniently checkout directly with PayPal.', 'mje_ppec' )
                    ),
                    'fields' => array(
                        array(
                            'id' => 'mje-ppec-enable',
                            'class' => '',
                            'type' => 'switch',
                            'title' => __( 'Enable/Disable', 'mje_ppec' ),
                            'desc' => __( 'Enabling this will activate PayPal Express Checkout payment gateway.', 'mje_ppec'),
                            'name' => 'mje_ppec_enable',
                        ),
                        /* API credentials */
                        array(
                            'id' => 'mje-ppec-api',
                            'class' => '',
                            'type' => 'combine',
                            'title' => __( 'API Credentials', 'mje_ppec' ),
                            'desc' => __( 'Visit <a href="https://developer.paypal.com/developer/applications/create" target="_blank">https://developer.paypal.com/developer/applications/create</a> to create an app to receive REST API credentials for testing and live transactions.', 'mje_ppec'),
                            'name' => '',
                            'children' => array(
                                array(
                                    'id' => 'mje-ppec-sandbox-client-id',
                                    'class' => 'test-key',
                                    'type' => 'text',
                                    'title' => __( 'Sandbox client id', 'mje_ppec' ),
                                    'desc' => '',
                                    'name' => 'mje_ppec_sandbox_client_id',
                                ),
                                array(
                                    'id' => 'mje-ppec-sandbox-secret',
                                    'class' => 'test-key',
                                    'type' => 'text',
                                    'title' => __( 'Sandbox secret', 'mje_ppec' ),
                                    'desc' => '',
                                    'name' => 'mje_ppec_sandbox_secret',
                                ),
                                array(
                                    'id' => 'mje-ppec-live-client-id',
                                    'class' => 'live-key',
                                    'type' => 'text',
                                    'title' => __( 'Live client id', 'mje_ppec' ),
                                    'desc' => '',
                                    'name' => 'mje_ppec_live_client_id',
                                ),
                                array(
                                    'id' => 'mje-ppec-live-secret',
                                    'class' => 'live-key',
                                    'type' => 'text',
                                    'title' => __( 'Live secret', 'mje_ppec' ),
                                    'desc' => '',
                                    'name' => 'mje_ppec_live_secret',
                                ),
                            )
                        ),
                        /* Invoice prefix */
                        array(
                            'id' => 'mje-ppec-invoice-prefix',
                            'class' => '',
                            'type' => 'text',
                            'title' => __( 'Invoice prefix', 'mje_ppec' ),
                            'desc' => __( 'Enter the unique prefix for your invoice numbers if your PayPal account is used for multiple websites, since PayPal won’t allow orders with the same invoice number.', 'mje_ppec'),
                            'name' => 'mje_ppec_invoice_prefix',
                            'default' => 'MJE-PPEC-'
                        ),

                        array(
                            'id' => 'mje-ppec-button-customize',
                            'class' => '',
                            'type' => 'combine',
                            'title' => __( 'Button styles', 'mje_ppec' ),
                            'desc' => __( 'Use the button style parameters to define the color, shape, and size of the button.', 'mje_ppec' ),
                            'name' => '',
                            'children' => array(
                                array(
                                    'id' => 'mje-ppec-button-shape',
                                    'class' => '',
                                    'type' => 'select',
                                    'title' => __( 'Button shape', 'mje_ppec' ),
                                    'desc' => __( '', 'mje_ppec'),
                                    'name' => 'mje_ppec_button_shape',
                                    'data' => array(
                                        'rect' => __( 'Rectangle', 'mje_ppec' ),
                                        'pill' => __( 'Pill', 'mje_ppec' ),
                                    )
                                ),
                                array(
                                    'id' => 'mje-ppec-button-color',
                                    'class' => '',
                                    'type' => 'select',
                                    'title' => __( 'Button color', 'mje_ppec' ),
                                    'desc' => __( '', 'mje_ppec'),
                                    'name' => 'mje_ppec_button_color',
                                    'data' => array(
                                        'gold' => __( 'Gold', 'mje_ppec' ),
                                        'blue' => __( 'Blue', 'mje_ppec' ),
                                        'silver' => __( 'Silver', 'mje_ppec' ),
                                    )
                                ),
                            )
                        ),
                    )
                ),
            )
        );

        return $sections;
    }
}

new MJE_PPEC_Admin();