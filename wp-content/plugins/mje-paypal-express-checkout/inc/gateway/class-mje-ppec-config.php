<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class MJE_PPEC_Config
{
    protected static $_instance;

    /**
     * Main instance.
     *
     * @return MJE_PPEC_Config;
     */
    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * MJE_PPEC_Config constructor.
     */
    public function __construct()
    {

    }

    /**
     * Get API endpoint
     *
     * @return string
     */
    public static function get_endpoint() {
        return ae_get_option( 'test_mode', true )
            ? 'https://api.sandbox.paypal.com'
            : 'https://api.paypal.com';
    }

    /**
     * Get merchant id
     *
     * @return string
     */
    public static function get_merchant_id() {
        return 'E9GCL5FX4TU2C';
    }

    /**
     * Get API client id
     *
     * @return string
     */
    public static function get_client_id() {
        return ae_get_option( 'test_mode', true )
            ? ae_get_option( 'mje_ppec_sandbox_client_id', '' )
            : ae_get_option( 'mje_ppec_live_client_id', '' );
    }

    /**
     * Get API secret
     *
     * @return string
     */
    public static function get_client_secret() {
        return ae_get_option( 'test_mode', true )
            ? ae_get_option( 'mje_ppec_sandbox_secret', '' )
            : ae_get_option( 'mje_ppec_live_secret', '' );
    }

    /**
     * Get invoice prefix
     *
     * @return string
     */
    public static function get_invoice_prefix() {
        return ae_get_option( 'mje_ppec_invoice_prefix', 'MJE-PPEC-' );
    }
}