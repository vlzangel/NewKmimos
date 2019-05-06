<?php

require dirname(dirname(dirname(dirname(__DIR__)))) . '/lib/paypal/vendor/autoload.php';
include(dirname(dirname(dirname(dirname(__DIR__)))) . '/lib/Requests/Requests.php');

use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class CreateOrder
{

  public static function create($data=[], $echo=false)
  {
  		extract($data);
  		$resultado='';

		# Crear Orden Paypal
	    	$request = new OrdersCreateRequest();
		    $request->prefer('return=representation');
		    $request->body = self::buildRequestBody( $data );
		    $client = PayPalClient::client();
		    $response = $client->execute($request);
			$resultado = json_encode($response->result, JSON_PRETTY_PRINT);

		# Crear Orden Kmimos
		Requests::register_autoloader();
			$path = 'https://kmimos.com.mx/QA2/wp-content/themes/kmimos/procesos/reservar/pagar.php';
			// $path = 'http://kmimos.git/wp-content/themes/kmimos/procesos/reservar/pagar.php';
			$data['_paypal_order_id'] = $response->result->id;
	        $reserva_data = Requests::post($path,array(), $data);

	        $reserva = json_decode($reserva_data->body);
 
	    if( $reserva->order_id > 0 ){
	    	if ($echo){
				echo $resultado;
		    }
	    }

	    return $response;
  }

  private static function buildRequestBody( $data )
  {	
	  	$info = explode("===", $data['info']);

		$parametros_label = array(
			"pagar",
			"tarjeta",
			"fechas",
			"cantidades",
			"transporte",
			"adicionales",
			"cupones",
		);
		$parametros = array();

		foreach ($info as $key => $value) {
			if( array_key_exists($key, $parametros_label) ){
				$parametros[ $parametros_label[ $key ] ] = json_decode( str_replace('\"', '"', $value) );
			}
		}

		extract($parametros);

		$descripcion = ucfirst($pagar->tipo_servicio) . " - " . $pagar->name_servicio;
		$noches = $fechas->duracion;
		$total = $pagar->total; 

		$descuento = 0;
	    foreach ($cupones as $key => $value) {
	    	$descuento += $value[1];
	    }

		$descuento += $pagar->fee;
		$total_mascotas = 0;
		
		$tamanios = [
			'pequenos' => "Masc. Peq.",
			'medianos' => "Masc. Med.",
			'grandes'  => "Masc. Grd.",
			'gigantes' => "Masc. Gig.",
		];

		$items = [];
		// Servicios
			foreach ($cantidades as $key => $value) {	
				if( is_array($value) && count($value) > 1 ){
					if( $value[0] > 0 ){
						$items[] = array(
			              	'name' => $value[0] ." ". $tamanios[$key] . " x " . $value[1],
			              	'description' => $value[0] ." ". $tamanios[$key] . " x {$noches} Noches x " .$value[1],
			              	'sku' => $pagar->servicio ."-". ucfirst($key),
			              	'unit_amount' => array(
		                		'currency_code' => 'MXN',
			                  	'value' => $value[1] * $noches,
			                ),
			                'quantity' => $value[0],
			                'category' => 'PHYSICAL_GOODS',                
			            );
			            $total_mascotas += $value[0];
					}			
				}		
			}
		// Transporte
			if( !empty($transporte) ){			
				$items[] = array(
	              	'name' => $transporte[0],
	              	'description' => $transporte[0],
	              	'sku' => "Transporte",
	              	'unit_amount' => array(
	            		'currency_code' => 'MXN',
	                  	'value' => $transporte[1],
	                ),
	                'quantity' => 1,
	                'category' => 'PHYSICAL_GOODS',                
	            );
			}
		// Adicionales
			$item_adicional = [
				'bano' => 'Baño',
	            'corte' => 'Corte',
	            'acupuntura' => 'Acupuntura',
	            'limpieza_dental' => 'Limpieza dental',
	            'visita_al_veterinario' => 'Visita al veterinario',
			];
			foreach ($adicionales as $key => $value) {	
				if( $value > 0 ){
					$adicional_desc = ( array_key_exists($key, $item_adicional))? $item_adicional[$key]: str_replace('_', '', $key);
					$items[] = array(
		              	'name' => $adicional_desc,
		              	'description' => $adicional_desc,
		              	'sku' => "Adicional - ". ucfirst($adicional_desc),
		              	'unit_amount' => array(
	                		'currency_code' => 'MXN',
		                  	'value' => $value,
		                ),
		                'quantity' => $total_mascotas,
		                'category' => 'PHYSICAL_GOODS',                
		            );
				}		
			}
		// Parametros
	        $return = array(
	            'intent' => 'CAPTURE',
	            'application_context' =>
	                array(
	                	/*
	                    'return_url' => 'http://mx.kmimos.la/reservar/validar-pago/?p=paypal&t=return',
	                    'cancel_url' => 'http://mx.kmimos.la/reservar/validar-pago/?p=paypal&t=cancel',
	                    */
	                    'return_url' => 'http://kmimos.git/reservar/validar-pago/?p=paypal&t=return',
	                    'cancel_url' => 'http://kmimos.git/reservar/validar-pago/?p=paypal&t=cancel',
	                ),
	            'purchase_units' =>
	                array(
	                    0 =>
	                        array(
	                            'description' => $descripcion,
	                            'custom_id' => $pagar->cliente,
	                            'soft_descriptor' => $pagar->tipo_servicio,
	                            'amount' =>
	                                array(
	                                    'currency_code' => 'MXN',
	                                    'value' => $total - $descuento,
	                                    'reference_id' => 'PUHF',
	                                    'breakdown' =>
	                                        array(
	                                          'item_total' =>
	                                            array(
	                                              'currency_code' => 'MXN',
	                                              'value' => $total,
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
	                                              'value' => $descuento,
	                                            ),
	                                        ),
	                                ),
	                            'items' => $items,
	                                
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
		// print_r($return);
        return $return;
  }
  
}

if( !isset($_SESSION)){ session_start(); }
if( isset($_POST['info']) ){
	$orden = CreateOrder::create($_POST, true);
	$_SESSION['paypal'] = [
		'info' => $_POST['info'],
		'orden' => $orden,
	];
}
