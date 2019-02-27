<?php
require dirname(dirname(dirname(dirname(__DIR__)))) . '/lib/paypal/vendor/autoload.php';

use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class Order
{
    public function get( $orderId )
    {
        $client = PayPalClient::client();
        $response = $client->execute(new OrdersGetRequest( $orderId ));
        return $response;
    }

    public function validar( $orderId ){
    	$order = $this->get( $orderId );
    	if( isset($order->result->status) && $order->result->status == 'APPROVED' ){
    		return true;
    	}
    	return false;
    }
}
