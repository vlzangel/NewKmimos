<?php

	include(realpath( dirname(dirname(dirname(__DIR__) ) )."/lib/openpay/Openpay.php" ));
	
	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( ($OPENPAY_PRUEBAS == 0) );

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
				'line1' 		=> "Mexico ",
				'state' 		=> "DF",
				'city' 			=> "Mexico",
				'postal_code' 	=> "10100",
				'country_code' 	=> 'MX'
			)
	   	);
	   	$customer = $openpay->customers->add($customerData);

	   	$openpay_customer_id = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$pagar->cliente} AND meta_key = '_openpay_customer_id'");
	   	if( $openpay_customer_id != false ){
	   		$db->query("UPDATE wp_usermeta SET meta_value = '{$customer->id}' WHERE user_id = {$pagar->cliente} AND meta_key = '_openpay_customer_id';");
	   	}else{
	   		$db->query("INSERT INTO wp_usermeta VALUES (NULL, {$pagar->cliente}, '_openpay_customer_id', '{$customer->id}');");
	   	}
	   	
	}

	switch ( $pagar->tipo ) {
		case 'tarjeta':
			
			if( $pagar->token != "" ){

			$chargeData = array(
			    'method' 			=> 'card',
			    'source_id' 		=> $pagar->token,
			    'amount' 			=> (float) $pagar->total,
			    'order_id' 			=> $id_orden,
			    'description' 		=> "Tarjeta",
			    'device_session_id' => $pagar->deviceIdHiddenFieldName
		    );

			$charge = ""; $error = "";

			try {
	            $charge = $customer->charges->create($chargeData);
	        } catch (Exception $e) {
	        	$error = $e->getErrorCode();
	        }
			
			if ($charge != false) {

				if( $deposito["enable"] == "yes" ){
					$db->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = {$id_orden};");
				}else{
					$db->query("UPDATE wp_posts SET post_status = 'paid' WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
					$db->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = {$id_orden};");
				}

	            echo json_encode(array(
					"order_id" => $id_orden
				));

			    if( isset($_SESSION[$id_session] ) ){
			    	update_cupos( array(
				    	"servicio" => $_SESSION[$id_session]["servicio"],
				    	"tipo" => $parametros["pagar"]->tipo_servicio,
			    		"autor" => $parametros["pagar"]->cuidador,
				    	"inicio" => strtotime($_SESSION[$id_session]["fechas"]["inicio"]),
				    	"fin" => strtotime($_SESSION[$id_session]["fechas"]["fin"]),
				    	"cantidad" => $_SESSION[$id_session]["variaciones"]["cupos"]
				    ), "-");
					$_SESSION[$id_session] = "";
					unset($_SESSION[$id_session]);
				}

				update_cupos( array(
			    	"servicio" => $parametros["pagar"]->servicio,
			    	"tipo" => $parametros["pagar"]->tipo_servicio,
			    	"autor" => $parametros["pagar"]->cuidador,
			    	"inicio" => strtotime($parametros["fechas"]->inicio),
			    	"fin" => strtotime($parametros["fechas"]->fin),
			    	"cantidad" => $cupos_a_decrementar
			    ), "+");
    
				// include(__DIR__."/../emails/index.php");

	        }else{

	            echo json_encode(array(
					"error" => $id_orden,
					"tipo_error" => $error,
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
			$due_date = date('Y-m-d\TH:i:s', strtotime('+ 48 hours'));

			$chargeRequest = array(
			    'method' => 'store',
			    'amount' => (float) $pagar->total,
			    'description' => 'Tienda',
			    'order_id' => $id_orden,
			    'due_date' => $due_date
			);

			$charge = $customer->charges->create($chargeRequest);

			$pdf = $OPENPAY_URL."/paynet-pdf/".$MERCHANT_ID."/".$charge->payment_method->reference;

			$db->query("UPDATE wp_posts SET post_status = 'wc-on-hold' WHERE ID = {$id_orden};");
			$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_openpay_pdf', '{$pdf}');");
			$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_openpay_tienda_vence', '{$due_date}');");

			echo json_encode(array(
				"user_id" => $customer->id,
				"pdf" => $pdf,
				"barcode_url"  => $charge->payment_method->barcode_url,
				"order_id" => $id_orden
			));
	    
		    if( isset($_SESSION[$id_session] ) ){
		    	update_cupos( array(
			    	"servicio" => $_SESSION[$id_session]["servicio"],
			    	"tipo" => $parametros["pagar"]->tipo_servicio,
		    		"autor" => $parametros["pagar"]->cuidador,
			    	"inicio" => strtotime($_SESSION[$id_session]["fechas"]["inicio"]),
			    	"fin" => strtotime($_SESSION[$id_session]["fechas"]["fin"]),
			    	"cantidad" => $_SESSION[$id_session]["variaciones"]["cupos"]
			    ), "-");
				$_SESSION[$id_session] = "";
				unset($_SESSION[$id_session]);
			}

			update_cupos( array(
		    	"servicio" => $parametros["pagar"]->servicio,
		    	"tipo" => $parametros["pagar"]->tipo_servicio,
		    	"autor" => $parametros["pagar"]->cuidador,
		    	"inicio" => strtotime($parametros["fechas"]->inicio),
		    	"fin" => strtotime($parametros["fechas"]->fin),
		    	"cantidad" => $cupos_a_decrementar
		    ), "+");

			// include(__DIR__."/../emails/index.php");

			break;

	}