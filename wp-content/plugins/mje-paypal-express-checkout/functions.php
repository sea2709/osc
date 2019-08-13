<?php
/**
 * Make a POST request
 *
 * @param string $request_url
 * @param array $headers
 * @param array $post_data
 * @return array|mixed|object
 *
 * @since 1.0
 * @author Tat Thien
 */
function mje_ppec_remote_post( $request_url, $headers, $post_data ) {
    $response = wp_remote_post( $request_url, array(
        'blocking', false,
        'headers' => $headers,
        'body' => $post_data
    ));
    return json_decode( $response['body'] );
}

function mje_ppec_get_payment_id( $access_token, $post_data ) {
    $endpoint = MJE_PPEC_Config::get_endpoint();
    $request_url = $endpoint . "/v1/payments/payment";

    $headers = array(
        "Content-type" => "application/json",
        "Authorization" => "Bearer " . $access_token
    );
    $resp = mje_ppec_remote_post( $request_url, $headers, $post_data );

    return $resp->id;
}

function mje_ppec_do_payment( $payment_id, $payer_id ) {
    $access_token = mje_ppec_get_access_token();

    $endpoint = MJE_PPEC_Config::get_endpoint();
    $request_url = $endpoint . "/v1/payments/payment/" . $payment_id . "/execute";

    $headers = array(
        "Content-type" => "application/json",
        "Authorization" => "Bearer " . $access_token
    );

    $post_data = array(
        'payer_id' => $payer_id
    );

    $resp = mje_ppec_remote_post( $request_url, $headers, json_encode( $post_data ) );
    return $resp;
}

function mje_ppec_get_access_token() {
    $endpoint = MJE_PPEC_Config::get_endpoint();
    $client_id = MJE_PPEC_Config::get_client_id();
    $client_secret = MJE_PPEC_Config::get_client_secret();

    $request_url = $endpoint . '/v1/oauth2/token';
    $headers = array(
        'Content-type' => 'application/x-www-form-urlencoded',
        'Authorization' => 'Basic '. base64_encode( $client_id .':'. $client_secret),
    );
    $post_data = array(
        'grant_type' => 'client_credentials'
    );

    $resp = mje_ppec_remote_post( $request_url, $headers, $post_data );

    return $resp->access_token;
}

function mje_ppec_check_api_missing() {
    if( ! is_null( MJE_PPEC_Config::get_client_id() ) && ! is_null( MJE_PPEC_Config::get_client_secret() ) ) {
        return true;
    } else {
        return false;
    }
}

function mje_ppec_delete_order_data( $invoice_id, $order_id ) {
    wp_delete_post( $invoice_id );
    wp_delete_post( $order_id );
}