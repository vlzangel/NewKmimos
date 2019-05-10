<?php
require dirname(dirname(dirname(dirname(__DIR__)))) . '/lib/paypal/vendor/autoload.php';

use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class Order
{
    public function get( $orderId )
    {
        $client = PayPalClient::client();
        $response = $client->execute(new OrdersGetRequest( $orderId ));
        return $response;
    }

    public function capture($order_id){
        $client = PayPalClient::client();
        $request = new OrdersCaptureRequest($order_id);
        $request->prefer('return=representation');
        try {
            $response = $client->execute($request);
        }catch (HttpException $ex) {
            // echo $ex->statusCode;
            // print_r($ex->getMessage());
            $response = null;
        }
        return $response;
    }

    public function validar( $orderId ){
    	$order = $this->get( $orderId );
        echo "<pre>";
            print_r($order);
        echo "</pre>";
    	if( isset($order->result->status) && $order->result->status == 'APPROVED' ){
    		return true;
    	}
    	return false;
    }
}
