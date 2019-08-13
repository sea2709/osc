<?php
/*
Plugin Name: MjE PayPal Express Checkout
Plugin URI: http://enginethemes.com/
Description: PayPal Express Checkout for MicrojobEngine
Version: 1.0.1
Author: EngineThemes
Author URI: http://enginethemes.com/
License: A "Slug" license name e.g. GPL2
Text Domain: mje_ppec
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if( ! class_exists('MJE_PayPal_Express_Checkout') ) :
    class MJE_PayPal_Express_Checkout
    {
        /**
         * @var string
         */
        public $version = '1.0.0';

        /**
         * The single instance of the class.
         *
         * @var MJE_PayPal_Express_Checkout
         */
        protected static $_instance = null;

        /**
         * Main Instance.
         *
         * @return MJE_PayPal_Express_Checkout
         */
        public static function instance() {
            if( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * MJE_PayPal_Express_Checkout constructor.
         */
        public function __construct() {
            // load translations
            $this->define_constants();
            $this->init_hooks();
        }

        private function define_constants() {
            // MJE_PAYPAL_EC is a shorthand for MjE PayPal Express Checkout
            $this->define( 'MJE_PPEC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
            $this->define( 'MJE_PPEC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            $this->define( 'MJE_PPEC_VERSION', $this->version );
            $this->define( 'MJE_PPEC_NAME', 'paypal_express_checkout' );
        }

        /**
         * Define constant if not already set.
         *
         * @param string $name
         * @param string|bool $value
         */
        private function define( $name, $value ) {
            if( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        /**
         * Hook into actions and filters
         */
        public function init_hooks() {
            add_action( 'after_setup_theme', array( $this, 'includes' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
            add_action( 'init', array( $this, 'load_translations' ) );
        }

        /**
         * Require files
         * Run after setup theme
         */
        public function includes() {
            include dirname( __FILE__ ) . '/functions.php';
            include dirname( __FILE__ ) . '/inc/index.php';
        }

        /**
         * Get plugin template path
         *
         * @return string
         */
        public function template_path() {
            return MB_PLUGIN_PATH . '/templates';
        }

        public function add_scripts() {
            wp_enqueue_style( 'ppec-checkout', MJE_PPEC_PLUGIN_URL . '/assets/css/mje-paypal-express-checkout.css', MJE_PPEC_VERSION, 'all');

            if( is_page_template( 'page-order.php' ) && ae_get_option( 'mje_ppec_enable' ) ) {
                wp_enqueue_script( 'mje-ppec.js',  MJE_PPEC_PLUGIN_URL . '/assets/js/mje-paypal-express-checkout.js', array(
                    'jquery',
                    'underscore',
                    'backbone',
                    'appengine'
                ), MJE_PPEC_VERSION, true );

                wp_localize_script( 'mje-ppec.js', 'mje_ppec', array(
                    'env' => ae_get_option( 'test_mode', true) ? 'sandbox' : 'production',
                    'valid_api' => mje_ppec_check_api_missing(),
                    'button' => array(
                        'size' => apply_filters( 'mje_ppec_button_size', 'small' ),
                        'color' => ae_get_option( 'mje_ppec_button_color', 'gold' ),
                        'shape' => ae_get_option( 'mje_ppec_button_shape', 'rect' ),
                        'label' => ae_get_option( 'mje_ppec_button_label', 'checkout' )
                    )
                ) );
            }
        }

        public function load_translations() {
            load_plugin_textdomain( 'mje_ppec', false,  dirname( plugin_basename( __FILE__ ) ). '/languages' );
        }
    }
endif;

/**
 * @return MJE_PayPal_Express_Checkout
 */
function MJE_PAYPAL_EC() {
    return MJE_PayPal_Express_Checkout::instance();
}

$GLOBALS['mje_paypal_ec'] = MJE_PAYPAL_EC();