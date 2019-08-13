<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class MJE_PPEC_Gateway
{
    protected static $_instance;

    /**
     * Main instance.
     *
     * @return MJE_PPEC_Gateway;
     */
    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * MJE_PEC_Gateway constructor.
     */
    public function __construct()
    {
        $this->init_hooks();
    }

    public function init_hooks() {
        add_filter( 'et_build_payment_visitor', array( $this, 'build_payment_visitor' ), 10, 3 );
        add_filter( 'mje_render_payment_name', array( $this, 'render_payment_name' ) );
        add_filter( 'mje_payment_list', array( $this, 'add_payment_list' ) );
        add_action( 'wp_ajax_mje-ppec-execute-payment', array( $this, 'execute_payment' ) );
    }

    public function build_payment_visitor( $class, $payment_type, $order ) {
        if( $payment_type == strtoupper( MJE_PPEC_NAME ) ) {
            $class = new MJE_PPEC_Visitor( $order );
        }

        return $class;
    }

    public function render_payment_name( $payment_name ) {
        $value = '<p class="payment-name paypal-express-checkout" title="' . __( 'PayPal Express Checkout', 'mje_ppec' ) . '"><img src="'. MJE_PPEC_PLUGIN_URL .'/assets/img/ppec-logo.png" /><span>' . __( 'PayPal Express Checkout', 'mje_ppec' ) . '</span></p>';
        $payment_name = wp_parse_args( $payment_name, array( MJE_PPEC_NAME => $value ) );
        return $payment_name;
    }

    /**
     * Add PayPal Express Checkout to payment list
     * this list is used for filter
     */
    public function add_payment_list( $payment_list ) {
        $payment_list[MJE_PPEC_NAME] = __( 'PayPal Express Checkout', 'mje_ppec' );
        return $payment_list;
    }
}

MJE_PPEC_Gateway::get_instance();