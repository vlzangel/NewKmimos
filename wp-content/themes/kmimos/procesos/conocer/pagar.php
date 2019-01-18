<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

	include_once($raiz."/wp-load.php");

	date_default_timezone_set('America/Mexico_City');

	if( !isset($_SESSION)){ session_start(); }

	include_once($raiz."/vlz_config.php");
	include_once("../funciones/db.php");
	include_once("../funciones/config.php");
	include_once("../../lib/openpay2/Openpay.php");

	// include_once("reservar.php");

	$db = new db( new mysqli($host, $user, $pass, $db) );

	include_once("../funciones/generales.php");

	extract($_POST);

	$info = explode("===", $info);

	$parametros_label = array(
		"pagar",
		"tarjeta",
		"cantidades",
	);

	$parametros = array();

	foreach ($info as $key => $value) {
		$parametros[ $parametros_label[ $key ] ] = json_decode( str_replace('\"', '"', $value) );
	}

	extract($parametros);

	// print_r( $parametros );

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

    $_SESSION["pagando"] = "";

	if( $_SESSION["pagando"] == ""){
		$_SESSION["pagando"] = "YES";

		if( $pagar->deviceIdHiddenFieldName != "" ){

			$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
			Openpay::setProductionMode( ($OPENPAY_PRUEBAS == 0) );

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

			$id_invalido = true;

			if( $cliente_openpay == "" ){
				try {
					$customerData = array(
				     	'name' => $nombre,
				     	'email' => $email
				  	);
					$customer = $openpay->customers->add($customerData);
					$cliente_openpay = $customer->id;
					update_user_meta($pagar->cliente, "_openpay_customer_id", $customer->id);
					$id_invalido = false;
				} catch (Exception $e) {
					$error = $e->getErrorCode();
					unset($_SESSION["pagando"]);

		            echo json_encode(array(
						"error" => $id_orden,
						"tipo_error" => $error,
						"desc" => $e->getDescription(),
						"status" => "Error, pago fallido 1"
					));

					exit();
				}
		    }

		    if( $id_invalido ){
			    try {
					$customer = $openpay->customers->get($cliente_openpay);
				} catch (Exception $e) {

					try {
				    	$customerData = array(
					     	'name' => $nombre,
					     	'email' => $email
					  	);
						$customer = $openpay->customers->add($customerData);
						$cliente_openpay = $customer->id;
						update_user_meta($pagar->cliente, "_openpay_customer_id", $customer->id);
					} catch (Exception $e) {
						$error = $e->getErrorCode();
						unset($_SESSION["pagando"]);

			            echo json_encode(array(
							"error" => $id_orden,
							"tipo_error" => $error,
							"desc" => $e->getDesciription(),
							"status" => "Error, pago fallido 2"
						));

						exit();
					}
			    }
		   	}

		   	update_post_meta($id_orden, '_openpay_customer_id', $customer->id);

		   	$hoy = date("Y-m-d H:i:s");

		   	if( $pagar->id_fallida == 0 ){
				$db->query("
					INSERT INTO 
						conocer_pedidos 
					VALUES (
						NULL, 
						{$pagar->cliente}, 
						'{$customer->id}', 
						'_', 
						NULL,
						'{$hoy}',
						'Pendiente',
						30,
						3,
						''
					);
				");

				$id_orden = $db->insert_id();
			}else{
				$id_orden = $pagar->id_fallida;
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

							$metas = json_encode([
								"show_pago" => 1
							]);

							$db->query("
								UPDATE 
									conocer_pedidos 
								SET										
									transaccion_id = '{$charge->id}',
									tipo_pago = 'Tarjeta',
									status = 'Pagado',
									metadata = '{$metas}'
								WHERE 
									id = {$id_orden}
							");

				            echo json_encode(array(
								"order_id" => $id_orden,
								"status" => "Pagada",
							));
							
							include(__DIR__."/emails/index.php");

				        }else{
							unset($_SESSION["pagando"]);
				            echo json_encode(array(
								"error" => $id_orden,
								"tipo_error" => $error,
								"status" => "Error, pago fallido 3"
							));
				        }

		   			}else{
		   				unset($_SESSION["pagando"]);
		   				echo json_encode(array(
							"Error" => "Sin tokens",
							"Data"  => $_POST
						));
		   			}

	   			break;

		   		case 'tienda':
		   			$due_date = date('Y-m-d\TH:i:s', strtotime('+ 48 hours'));
		   			
		   			// $id_orden = $id_orden."_local";

		   			$chargeRequest = array(
					    'method' => 'store',
					    'amount' => (float) $pagar->total,
					    'description' => 'Tienda',
					    'order_id' => "conocer_".$id_orden,
					    'due_date' => $due_date
					);

					$charge = $customer->charges->create($chargeRequest);

					try {
			            $charge = $customer->charges->create($chargeRequest);
			        } catch (Exception $e) {
			        	$error = $e->getErrorCode();
			        }

					if ($charge != false) {

						$pdf = $OPENPAY_URL."/paynet-pdf/".$MERCHANT_ID."/".$charge->payment_method->reference;

		   				$info = json_encode(array(
							"pdf" => $pdf,
							"vence" => $due_date,
							"barcode_url"  => $charge->payment_method->barcode_url
						));

						$db->query("
							UPDATE 
								conocer_pedidos 
							SET										
								transaccion_id = '{$charge->id}',
								tipo_pago = 'Tienda',
								metadata = '{$info}'
							WHERE 
								id = {$id_orden}
						");

			            echo json_encode(array(
							"order_id" => $id_orden,
							"status" => "Pendiente",
						));

						include(__DIR__."/emails/index.php");

					}else{
						unset($_SESSION["pagando"]);
			            echo json_encode(array(
							"error" => $id_orden,
							"tipo_error" => $error,
							"status" => "Error, pago fallido 4"
						));
			        }

	   			break;

		   	}

		}else{
			unset($_SESSION["pagando"]);
			echo json_encode(array(
				"Error" => "Sin ID de dispositivo",
				"Data"  => $parametros,
				"deviceIdHiddenFieldName"  => $pagar,
			));
		}

	}else{
		echo json_encode(array(
			"error" => $id_orden,
			"tipo_error" => "Pagando...",
			"status" => "Error, pago fallido"
		));
	}

	exit();

?>