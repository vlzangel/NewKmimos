<?php

require dirname(dirname(dirname(dirname(__DIR__)))) . '/lib/paypal/vendor/autoload.php';

use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class CreateOrder
{

  public static function create($data=[], $echo=false)
  {
	    $request = new OrdersCreateRequest();
	    $request->prefer('return=representation');
	    $request->body = self::buildRequestBody( $data );
	    $client = PayPalClient::client();
	    $response = $client->execute($request);
	    if ($echo)
	    {
	       echo json_encode($response->result, JSON_PRETTY_PRINT);
	    }
	    return $response;
  }

  private static function buildRequestBody( $data )
  {	
	  	$info = explode("===", $data);

		$parametros_label = array(
			"pagar",
			"tarjeta",
			"cantidades",
		);
		$parametros = array();

		foreach ($info as $key => $value) {
			if( array_key_exists($key, $parametros_label) ){
				$parametros[ $parametros_label[ $key ] ] = json_decode( str_replace('\"', '"', $value) );
			}
		}

		extract($parametros);

		// Parametros
	        $return = array(
	            'intent' => 'CAPTURE',
	            'application_context' =>
	                array(
	                    'return_url' => 'https://mx.kmimos.la/recargar/validar/?p=paypal&t=return',
	                    'cancel_url' => 'https://mx.kmimos.la/recargar/validar/?p=paypal&t=cancel',
	                ),
	            'purchase_units' =>
	                array(
	                    0 =>
	                        array(
	                            'description' => "3 Cupos x $10",
	                            'custom_id' => 'recarga',
	                            'soft_descriptor' => "3 Cupos x $10",
	                            'amount' =>
	                                array(
	                                    'currency_code' => 'MXN',
	                                    'value' => 30,
	                                    'reference_id' => 'PUHF',
	                                    'breakdown' =>
	                                        array(
	                                          'item_total' =>
	                                            array(
	                                              'currency_code' => 'MXN',
	                                              'value' => 30,
	                                            ),
	                                          'shipping' =>
	                                            array(
	                                              'currency_code' => 'MXN',
	                                              'value' => '0.00',
	                                            ),
	                                          'handling' =>
	                                            array(
	                                              'currency_code' => 'MXN',
	                                              'value' => '0.00',
	                                            ),
	                                          'tax_total' =>
	                                            array(
	                                              'currency_code' => 'MXN',
	                                              'value' => '0.00',
	                                            ),
	                                          'shipping_discount' =>
	                                            array(
	                                              'currency_code' => 'MXN',
	                                              'value' => 0,
	                                            ),
	                                        ),
	                                ),

	                            'items' => array(
	                            	0 => array(
							          	'name' => "3 Cupos x $10",
							          	'description' => "3 Cupos x $10",
							          	'sku' => "Recarga Conocer",
							          	'unit_amount' => array(
							        		'currency_code' => 'MXN',
							              	'value' => 10,
							            ),
							            'quantity' => 3,
							            'category' => 'PHYSICAL_GOODS',                
							        ),
	                            ),
	                                
	                            'shipping' =>
	                                array(
	                                  'method' => 'United States Postal Service',
	                                  'address' =>
	                                    array(
	                                      'address_line_1' => '123 Townsend St',
	                                      'address_line_2' => 'Floor 6',
	                                      'admin_area_2' => 'San Francisco',
	                                      'admin_area_1' => 'CA',
	                                      'postal_code' => '94107',
	                                      'country_code' => 'MX',
	                                    ),
	                                ),
	                        )
	                )
	        );

        return $return;
  }
  
}

if( !isset($_SESSION)){ session_start(); }
if( isset($_POST['info']) ){
	$orden = CreateOrder::create($_POST['info'], true);
	$_SESSION['paypal'] = $_POST['info'];
}
