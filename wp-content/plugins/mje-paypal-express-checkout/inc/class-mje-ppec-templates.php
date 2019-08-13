<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class MJE_PPEC_Templates
{
    public static function init_hooks() {
        add_action( 'mje_after_payment_list', array( __CLASS__, 'render_checkout_button' ) );
    }

    public static function render_checkout_button() {
        $allow = ae_get_option( 'mje_ppec_enable' );
        if( $allow ) :
        ?>
        <script src="https://www.paypalobjects.com/api/checkout.js"></script>
        <li class="mje-ppec-button-checkout">
            <div class="outer-payment-items hvr-underline-from-left">
                <div id="paypal-button"></div>
            </div>
        </li>
        <?php
        endif;
    }
}

MJE_PPEC_Templates::init_hooks();