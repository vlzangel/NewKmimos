<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

	include_once($raiz."/wp-load.php");

	date_default_timezone_set('America/Mexico_City');

	if( !isset($_SESSION)){ session_start(); }

	include_once($raiz."/vlz_config.php");
	include_once("../funciones/db.php");
	include_once("../funciones/config.php");
	include_once("../../lib/openpay2/Openpay.php");

	include_once("reservar.php");

	$xdb = $db;
	$db = new db( new mysqli($host, $user, $pass, $db) );

	include_once("../funciones/generales.php");

	extract($_POST);

	$info = explode("===", $info);

	$parametros_label = array(
		"pagar",
		"tarjeta"
	);

	$parametros = array();

	foreach ($info as $key => $value) {
		$parametros[ $parametros_label[ $key ] ] = json_decode( str_replace('\"', '"', $value) );
	}

	extract($parametros);

	$pagar->total = $pagar->total-$pagar->fee;

	$id_orden = 0;

	if( $pagar->id_fallida != 0 ){
		$id_orden = $pagar->id_fallida;
		$metodo = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_orden} AND meta_key = '_payment_method' ");
		if( $metodo != $pagar->tipo ){
			$tipos = array(
				"tienda" => "Tienda",
				"tarjeta" => "Tarjeta",
				"paypal" => "Paypal",
				"Mercadopago" => "Mercadopago",
			);
			$db->get_var("UPDATE wp_postmeta SET meta_value = '{$pagar->tipo}' WHERE post_id = {$id_orden} AND meta_key = '_payment_method';");
			$db->get_var("UPDATE wp_postmeta SET meta_value = '{$tipos[$pagar->tipo]}' WHERE post_id = {$id_orden} AND meta_key = '_payment_method_title';");
		}
	}
	$informacion = serialize($parametros);

	if( $_SESSION["pagando"] == ""){
		$_SESSION["pagando"] = "YES";

	    $cupos_a_decrementar = $parametros["cantidades"]->cantidad;

	    if( $pagar->total <= $descuentos ){
	    	$db->query("UPDATE wp_posts SET post_status = 'paid' WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
			$db->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = {$id_orden};");
	    	echo json_encode(array( "order_id" => $id_orden ));		    
			include(__DIR__."/emails/index.php");
			exit;
	    }

	   	if( $descuentos > 0 ){
		    $pagar->total -= $descuentos;
    	}

    	if( $pagar->tipo == "tienda" || $pagar->tipo == "tarjeta" ){
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
				     	'email' => $email,
				     	'requires_account' => false,
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
						"status" => "Error, obteniendo customer 1"
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
					     	'email' => $email,
				     		'requires_account' => false,
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
							"status" => "Error, obteniendo customer 2"
						));
						exit();
					}
			    }
		   	}
		   	update_post_meta($id_orden, '_openpay_customer_id', $customer->id);
    	}
 
		if( $pagar->deviceIdHiddenFieldName != "" || ($pagar->tipo != "tienda" && $pagar->tipo != "tarjeta") ){

		   	switch ( $pagar->tipo ) {
		   		case 'tarjeta':
		   			
		   			if( $pagar->token != "" ){

						$chargeData = array(
						    'method' 			=> 'card',
						    'source_id' 		=> $pagar->token,
						    'amount' 			=> (float) $pagar->total,
						    'order_id' 			=> $id_orden,
						    'description' 		=> "Tarjeta",
						    'use_card_points'	=> $tarjeta->puntos,
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
							$para_buscar = array(
								"cliente" => $customer->id,
								"transaccion_id" => $charge->id
							);
							$para_buscar = serialize($para_buscar);
							$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_openpay_busqueda', '{$para_buscar}');");
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
							include(__DIR__."/emails/index.php");
				        }else{
							unset($_SESSION["pagando"]);
				            echo json_encode(array(
								"error" => $id_orden,
								"tipo_error" => $error,
								"status" => "Error, pago fallido",
								"info_pago" => $chargeData
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
		   			$chargeRequest = array(
					    'method' => 'store',
					    'amount' => (float) $pagar->total,
					    'description' => 'Tienda',
					    'order_id' => "0_".$id_orden,
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
						$db->query("UPDATE wp_posts SET post_status = 'wc-on-hold' WHERE ID = {$id_orden};");
						$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_openpay_pdf', '{$pdf}');");
						$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_openpay_tienda_vence', '{$due_date}');");
						$para_buscar = array(
							"cliente" => $customer->id,
							"transaccion_id" => $charge->id
						);
						$para_buscar = serialize($para_buscar);
						$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_openpay_busqueda', '{$para_buscar}');");
		   				echo json_encode(array(
		   					"user_id" => $customer->id,
							"pdf" => $pdf,
							"barcode_url"  => $charge->payment_method->barcode_url,
							"order_id" => $id_orden
						));
						sleep(1);
						include(__DIR__."/emails/index.php");
					}else{
						unset($_SESSION["pagando"]);
			            echo json_encode(array(
							"error" => $id_orden,
							"tipo_error" => $error,
							"status" => "Error, pago fallido"
						));
			        }
	   			break;

		   		case 'paypal':
		   			$due_date = date('Y-m-d\TH:i:s', strtotime('+ 48 hours'));
		   			include dirname(__FILE__)."/pasarelas/paypal/create.php";
					$orden_class = new CreateOrder();
					$orden = $orden_class->create(get_home_url(), $_POST, true);
					$_SESSION['paypal'] = [
						'info' => $_POST['info'],
						'orden' => $orden,
					];
					$orden = json_decode($orden);
					$_paypal_order_id = $orden->id;
					$url_paypal = '';
					if( $orden->status == 'CREATED' ){
						foreach ($orden->links as $key => $r) {
							if($r->rel == 'approve'){
								$url_paypal = $r->href;
								break;
							}
						}
					}

					$db->query("UPDATE wp_posts SET post_status = 'wc-on-hold' WHERE ID = {$id_orden};");
					$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_paypal_vence', '{$due_date}');");
					$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_paypal_order_id', '".$_paypal_order_id."');");
					
					include(__DIR__."/emails/index.php");
	   				
	   				echo json_encode(array(
	   					"user_id" => $customer->id,
						"order_id" => $id_orden,
						"return_data" => true,
						"url_pago" => $url_paypal,
					));
					
	   			break;

	   			case 'mercadopago':
		   			$due_date = date('Y-m-d\TH:i:s', strtotime('+ 48 hours'));
		   			 
					$db->query("UPDATE wp_posts SET post_status = 'wc-on-hold' WHERE ID = {$id_orden};");
					$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_mercadopago_vence', '{$due_date}');");
					$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_mercadopago_data', '');");

		   			$ruta = get_home_url();
		   			
		   			include dirname(dirname(__DIR__)).'/lib/mercadopago/mercadopago.php';
		   			include dirname(__FILE__)."/pasarelas/mercadopago/create.php";
					
					include(__DIR__."/emails/index.php");

	   				echo json_encode(array(
	   					"user_id" => $customer->id,
						"order_id" => $id_orden,
						"return_data" => true,
						"url_pago" => $res["links"],
					));
			     
	   			break;
		   	}

		}else{
			unset($_SESSION["pagando"]);
			echo json_encode(array(
				"Error" => "Sin ID de dispositivo",
				"Data"  => $_POST
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