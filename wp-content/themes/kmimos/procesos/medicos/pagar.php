<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
	include_once($raiz."/wp-load.php");
	date_default_timezone_set('America/Mexico_City');
	if( !isset($_SESSION)){ session_start(); }

	include_once(dirname(__DIR__)."/funciones/config.php");
	include_once(dirname(__DIR__)."/funciones/openpay.php");
	include_once(dirname(__DIR__)."/funciones/mediqo.php");
	include_once(dirname(dirname(__DIR__))."/lib/openpay2/Openpay.php");

	// ini_set('display_errors', 'On');
	// error_reporting(E_ALL);

	global $wpdb;

	extract($_POST);

	function new_cita($_POST_) {
		global $wpdb;
		extract( $_POST_ );
		$user_id = $_POST_['user_id'];
		unset( $_POST_['user_id'] );
		$data = json_encode($_POST_);
		$sql = "INSERT INTO wp_kmivet_reservas VALUES(
			NULL,
			'{$user_id}',
			'',
			'{$data}',
			'Pendiente',
			NOW()
		)";
		$wpdb->query( $sql );
		return $wpdb->insert_id;
	}
	$cita_id = new_cita($_POST); // Creación de cita en status pendiente

	$error = []; $res = [];

	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( ($OPENPAY_PRUEBAS == 0) );
	$customer = get_openpay_customer($user_id, $openpay); // Obtenemos el customer_id de openpay
	$mediqo_id = get_mediqo_customer($user_id); // Obtenemos el customer_id de mediqo

	if( $mediqo_id['status'] == 'ko' ){
		$error[] = [
    		"code" => 0,
    		"msg"  => 'No podemos obtener acceso a su cuenta de Mediqo, por favor contacte al equipo de soporte de Kmimos para brindarle la asistencia correspondiente.'
    	];
	}else{
		$mediqo_id = $mediqo_id['id'];
	}

	$chargeData = '';
	if( $deviceIdHiddenFieldName != "" AND $customer["status"] == 'ok' ){

		$customer = $customer["info"];

	   	switch ( $cita_tipo_pago ) {
	   		case 'tarjeta':
	   			if( $token != "" ){
					$chargeData = array(
					    'method' 			=> 'card',
					    'source_id' 		=> $token,
					    'amount' 			=> (float) $cita_precio,
					    'order_id' 			=> $ENTORNO.'_'.$cita_id.'_'.time(),
					    'description' 		=> "Tarjeta",
					   //  'use_card_points'	=> $tarjeta->puntos,
					    'device_session_id' => $deviceIdHiddenFieldName
				    );
					$change = "";
					try {
			            $change = $customer->charges->create($chargeData);
			            if ($change != false) {

							$wpdb->query("UPDATE wp_kmivet_reservas SET status = 'Pagado' WHERE id = '{$cita_id}' ");
							$res = [
								"msg" => "Pago realizado exitosamente",
								"cid" => $cita_id
							];

							$params = [
								'medic' => $medico_id,
								'patient' => $mediqo_id,
								'specialty' => $specialty_id,
								'dueTo' => $cita_fecha,
								'paymentType' => 0,
								'appointmentType' => 1,
								'isCash' => true,
								'address' => $cita_direccion
								//'paymentType' => ( $cita_tipo_pago == 'tarjeta') ? 0 : 1
							];

							if( $cita_latitud != '' ){
								$params['lat'] = $cita_latitud;
								$params['lng'] = $cita_longitud;
							}

							/*
							if( $cita_tipo_pago == 'tarjeta' ){
								$params['payment'] = [
									'number' => $cita_tarjeta,
									'firstName' => $cita_nombre,
									'lastName' => $cita_apellido,
									'token' => $token
								];
							}

							[
								{
									"medic":"4794a05285f74bd0980b88243782ab5d",
									"patient":"b153904e054440b5a4a47e96595a8dd0",
									"specialty":"e41ed3d30309496a845c611dfd8f2e3d",
									"dueTo":"2019-12-15 00:45",
									"paymentType":0,
									"appointmentType":1
								}
								,{"status":"ko","info":{"code":"Metodo de pago invalido","datetime":"2019-12-13 22:18:27Z","message":"No se selecciono efectivo, no hay fondos suficientes o la ifnormacion de pago es incorecta","status":"FAIL"}}]
							*/

							$appointment_id = add_appointments($params);

							$data = json_encode([
								$params,
								$appointment_id
							]);

							if( $appointment_id['status'] == 'ok' ){
								$wpdb->query("UPDATE wp_kmivet_reservas SET cita_id = '{$appointment_id['id']}' WHERE id = '{$cita_id}' ");
							}else{
								$wpdb->query("UPDATE wp_kmivet_reservas SET cita_id = '{$data}' WHERE id = '{$cita_id}' ");
							}

							$mensaje = buildEmailTemplate(
					            'KMIVET/reservas/nueva', 
					            [
					            	"EMAIL"   => $kv_email,
					            	"NOMBRE"  => $kv_nombre,
					            	"CLAVE"   => $random_password,
					            	"URL" 	  => get_home_url().'/kmivet/',
					            ]
					        );

						    $header = [
						    	'BCC: a.veloz@kmimos.la',
						    	'BCC: y.chaudary@kmimos.la',
						    ];

					        wp_mail($kv_email, 'Kmivet - Gracias por registrarte como veterinario!', $mensaje, $header);

						}else{
							$error[] = [
				        		"code" => 0,
				        		"msg"  => 'procesando el pago'
				        	];
						}
			        } catch (Exception $e) {
			        	$error[] = [
			        		"code" => $e
			        	];
			        }
				}
			break;
		}
	}else{
		$error[] = [
    		"code" => 0,
    		"msg"  => 'id del dispositivo no recibido'
    	];
	}

	$res['errores'] = $error;

	echo json_encode($res);

	die();
?>