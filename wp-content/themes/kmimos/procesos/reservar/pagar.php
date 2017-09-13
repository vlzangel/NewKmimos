<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
	include_once($raiz."/wp-load.php");

	include_once($raiz."/vlz_config.php");
	include_once("../funciones/db.php");
	include_once("../funciones/config.php");
	include_once("../../lib/openpay/Openpay.php");

	include_once("reservar.php");

	$xdb = $db;
	$db = new db( new mysqli($host, $user, $pass, $db) );

	extract($_POST);

	$info = explode("===", $info);

	$parametros_label = array(
		"pagar",
		"tarjeta",
		"fechas",
		"cantidades",
		"transporte",
		"adicionales",
	);

	$parametros = array();

	foreach ($info as $key => $value) {
		$parametros[ $parametros_label[ $key ] ] = json_decode( str_replace('\"', '"', $value) );
	}

	extract($parametros);

	$informacion = serialize($parametros);

	$num_mascotas = $cantidades->cantidad;

	$time = time();
    $hoy = date("Y-m-d H:i:s", $time);
    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $inicio = strtotime( $fechas->inicio );
    $fecha_formato = date('d', $inicio)." ".$meses[date('n', $inicio)-1]. ", ".date('Y', $inicio) ;

    if( $pagar->metodo != "deposito" ){
	    $deposito = array(
			"enable" => "no"
	    );
    }else{
	    $deposito = array(
	    	"deposit" => ( $pagar->total - ( $pagar->total / 1.2) ),
			"enable" => "yes",
			"ratio" => 1,
			"remaining" => ( $pagar->total / 1.2),
			"total" => $pagar->total
	    );
    }

    $tamanos = array(
    	"pequenos" => "Pequeñas",
    	"medianos" => "Medianas",
    	"grandes"  => "Grandes",
    	"gigantes" => "Gigantes"
    );

    $mascotas = array();
    foreach ($cantidades as $key => $value) {
    	if( $key != "cantidad" ){
	    	if( is_array($value) ){
	    		if( $value[0] > 0 ){
	    			$mascotas[ "Mascotas ".$tamanos[ $key ] ] = $value[0];
	    		}
	    	}
    	}
    }

    $diaNoche = "d&iacute;a";
	if( $pagar->tipo_servicio == "hospedaje" ){
		$diaNoche = "Noche";
	}

    if( $fechas->duracion > 1 ){
    	$fechas->duracion .= " ".$diaNoche."s";
    }else{
    	$fechas->duracion .= " ".$diaNoche;
    }

    $data_reserva = array(
		"servicio" 				=> $pagar->servicio,
		"titulo_servicio" 		=> $pagar->name_servicio,
		"cliente" 				=> $pagar->cliente,
		"cuidador" 				=> $pagar->cuidador,
		"hoy" 					=> $hoy,
		"fecha_formato" 		=> $fecha_formato,
		"token" 				=> time(),
		"inicio" 				=> date("Ymd", strtotime( $fechas->inicio ) ),
		"fin" 					=> date("Ymd", strtotime( $fechas->fin ) ),
		"monto" 				=> $pagar->total,
		"num_mascotas" 			=> $num_mascotas,
		"metodo_pago" 			=> $pagar->tipo,
		"metodo_pago_titulo" 	=> $pagar->tipo,
		"moneda" 				=> "MXN",
		"duracion_formato" 		=> $fechas->duracion,
		"mascotas" 				=> $mascotas,
		"deposito" 				=> $deposito,
		"status_reserva" 		=> "unpaid",
		"status_orden" 			=> "wc-pending"
	);

    $data_cliente = array();
    $xdata_cliente = $db->get_results("
	SELECT 
		meta_key, meta_value 
	FROM 
		wp_usermeta 
	WHERE
		user_id = {$pagar->cliente} AND (
			meta_key = 'first_name' OR
			meta_key = 'last_name' OR
			meta_key = 'user_mobile' OR
			meta_key = 'user_phone' OR
			meta_key = 'billing_email' OR
			meta_key = 'billing_address_1' OR
			meta_key = 'billing_address_2' OR
			meta_key = 'billing_city' OR
			meta_key = 'billing_state' OR
			meta_key = 'billing_postcode' OR
			meta_key = '_openpay_customer_id'
		)"
    );

    foreach ($xdata_cliente as $key => $value) {
    	$data_cliente[ $value->meta_key ] = utf8_encode($value->meta_value);
    }

    $reservar = new Reservas($db, $data_reserva);

    $id_orden = $reservar->new_reserva();
    
	if( $pagar->deviceIdHiddenFieldName != "" ){

		$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);

		foreach ($data_cliente as $key => $value) {
			if( $data_cliente[$key] == "" ){
				$data_cliente[$key] = "_";
			}
		}

		$nombre 	= $data_cliente["first_name"];
		$apellido 	= $data_cliente["last_name"];
		$email 		= $pagar->email;
		$telefono 	= $data_cliente["user_mobile"];
		$direccion 	= $data_cliente["billing_address_1"];
		$estado 	= $data_cliente["billing_state"];
		$municipio 	= $data_cliente["billing_city"];
		$postal  	= $data_cliente["billing_postcode"];

		$cliente_openpay = $data_cliente["_openpay_customer_id"];

		if( $id_invalido ){ $cliente_openpay = ""; }

	   	if( $cliente_openpay != "" ){
	   		$customer = $openpay->customers->get( $cliente_openpay );
	   	}else{
	   		$customerData = array(
				'name' 				=> $nombre,
				'last_name' 		=> $apellido,
				'email' 			=> $email,
				'requires_account' 	=> false,
				'phone_number' 		=> $telefono,
				'address' => array(
					'line1' 		=> $direccion,
					'state' 		=> $estado,
					'city' 			=> $municipio,
					'postal_code' 	=> $postal,
					'country_code' 	=> 'MX'
				)
		   	);
		   	$customer = $openpay->customers->add($customerData);

		   	update_user_meta($pagar->cliente, '_openpay_customer_id', $customer->id);
	   	}

	   	switch ( $pagar->tipo ) {
	   		case 'tarjeta':
	   			
	   			if( $pagar->token != "" ){
	   				$cardDataRequest = array(
					    'holder_name' => $tarjeta->nombre,
					    'card_number' => $tarjeta->numero,
					    'cvv2' => $tarjeta->codigo,
					    'expiration_month' => $tarjeta->mes,
					    'expiration_year' => $tarjeta->anio,
					    'device_session_id' => $pagar->deviceIdHiddenFieldName,
					    'address' => array(
					            'line1' => $customer->address->line1,
					            'line2' => $customer->address->line2,
					            'line3' => $customer->address->line3,
					            'postal_code' => $customer->address->postal_code,
					            'state' => $customer->address->state,
					            'city' => $customer->address->city,
					            'country_code' => 'MX'
					    )
					);

					$cardList = $customer->cards->getList( array() );

					$card = "";

					if( count($cardList) == 0 ){
						try {
				            $card = $customer->cards->add($cardDataRequest);
				        } catch (Exception $e) { }
					}else{
						$no_existe = true;
						$card_num = substr($card_number, 0, 6)."XXXXXX".substr($card_number, -4);
						foreach ($cardList as $key => $card) {
							if( $card_num == $card->card_number ){
								$no_existe = false;
							}
						}
						if( $no_existe ){
							try {
					            $card = $customer->cards->add($cardDataRequest);
					        } catch (Exception $e) { }
						}
					}

					$chargeData = array(
					    'method' 			=> 'card',
					    'source_id' 		=> $card->id,
					    'amount' 			=> (float) $pagar->total,
					    'order_id' 			=> $id_orden,
					    'description' 		=> "Tarjeta",
					    'device_session_id' => $pagar->deviceIdHiddenFieldName
				    );

					$charge = "";

					try {
			            $charge = $customer->charges->create($chargeData);
			        } catch (Exception $e) { }
					
					if ($charge != false) {

						if( $deposito["enable"] == "yes" ){
							$db->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = {$id_orden};");
						}else{
							$db->query("UPDATE wp_posts SET post_status = 'paid' WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
							$db->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = {$id_orden};");
						}

			            echo json_encode(array(
		   					"openpay_customer_id" => $customer->id,
							"order_id" => $id_orden
						));
			        }else{
			            echo json_encode(array(
		   					"openpay_customer_id" => $customer->id,
							"order_id" => $id_orden,
							"status" => "Error, pago fallido"
						));
			        }

	   			}else{
	   				echo json_encode(array(
						"Error" => "Sin tokens",
						"Data"  => $_POST
					));
	   			}

   			break;

	   		case 'tienda':
	   			$due_date = date('Y-m-d\TH:i:s', strtotime('+ 24 hours'));

	   			$chargeRequest = array(
				    'method' => 'store',
				    'amount' => (float) $pagar->total,
				    'description' => 'Tienda',
				    'order_id' => $id_orden,
				    'due_date' => $due_date
				);

				$charge = $customer->charges->create($chargeRequest);

   				echo json_encode(array(
   					"user_id" => $customer->id,
					"pdf" => "https://sandbox-dashboard.openpay.mx/paynet-pdf/".$MERCHANT_ID."/".$charge->payment_method->reference,
					"barcode_url"  => $charge->payment_method->barcode_url,
					"order_id" => $id_orden
				));

   			break;

	   	}

	}else{
		echo json_encode(array(
			"Error" => "Sin ID de dispositivo",
			"Data"  => $_POST
		));
	}

?>