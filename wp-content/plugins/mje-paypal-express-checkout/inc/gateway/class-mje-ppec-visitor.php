<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class MJE_PPEC_Visitor extends ET_PaymentVisitor
{
    protected $_payment_type = 'paypal_express_checkout';

    function setup_checkout( ET_Order $order )
    {
        $order_pay = $order->generate_data_to_pay();

        // Check valid api
        if( ! mje_ppec_check_api_missing() ) {
            // Delete invoice, mje order
            mje_ppec_delete_order_data( $order_pay['ID'], $order_pay['product_id'] );

            return array(
                'success' => true,
                'ACK' => true,
                'paymentID' => null,
                'msg' => __( 'Missing API key', 'mje_ppec' )
            );
        }

        $access_token = mje_ppec_get_access_token();

        // inital post data
        $post_data['intent'] = "sale";
        $post_data['redirect_urls']['return_url'] = $this->_settings['return'];
        $post_data['redirect_urls']['cancel_url'] = et_get_page_link( 'cancel-payment' );
        $post_data['payer']['payment_method'] = "paypal";
        $post_data['transactions'][0]['amount']['total'] = $order_pay['total'];
        $post_data['transactions'][0]['amount']['currency'] = $order_pay['currencyCodeType'];
        // transaction items
        foreach ( $order_pay['products'] as $item ) {
            $post_data['transactions'][0]['item_list']['items'][] = array(
                'quantity' => $item['QTY'],
                'name' => $item['NAME'],
                'price' => $item['AMT'],
                'currency' => $order_pay['currencyCodeType']
            );
        }
        $post_data['transactions'][0]['invoice_number'] = MJE_PPEC_Config::get_invoice_prefix() . $order_pay['ID'];

        // get payment ID
        $payment_id = mje_ppec_get_payment_id( $access_token, json_encode( $post_data ) );

        $order->set_payment_code( $payment_id );
        $order->update_order();

        $resp = array(
            'success' => true,
            'ACK' => true,
            'paymentID' => $payment_id
        );

        if( is_null ( $payment_id ) ) {
            $resp['msg'] = __( 'An error occurred while making the payment!', 'mje_ppec' );
            // Delete invoice, mje order
            mje_ppec_delete_order_data( $order_pay['ID'], $order_pay['product_id'] );
        }

        return $resp;
    }

    function do_checkout( ET_Order $order )
    {
        $payment_return = array();
        $pending = false;
        if( isset( $_GET['paymentId'] ) && isset( $_GET['PayerID'] ) ) {
            $payment_id = $_GET['paymentId'];
            $payer_id = $_GET['PayerID'];

            if( $payment_id == $order->get_payment_code() ) {
                /* Execute payment */
                $resp = mje_ppec_do_payment( $payment_id, $payer_id );

                // update payer id
                $order->set_payer_id( $payer_id );
                $order->set_status( 'publish' );
                $order->update_order();

                $state = $resp->state;
                // publish order
                if( $state == 'approved' ) {
                    $payment_return = array(
                        'ACK' => true,
                        'payment' => MJE_PPEC_NAME,
                        'payment_status' => 'Completed'
                    );
                }
            } else {
                $pending = true;
            }
        } else {
            $pending = true;
        }

        if( $pending ) {
            $order->set_status( 'pending' );
            $order->update_order();

            $payment_return = array(
                'ACK' => false,
                'payment' => MJE_PPEC_NAME,
                'payment_status' => 'Pending',
            );
        }

        return $payment_return;
    }
}