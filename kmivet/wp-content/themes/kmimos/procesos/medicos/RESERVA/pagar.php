<?php
	session_start();

	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
	include_once($raiz."/wp-load.php");
	date_default_timezone_set('America/Mexico_City');
	
	if( !isset($_SESSION)){ session_start(); }

	include_once(dirname(dirname(__DIR__))."/funciones/config.php");
	include_once(dirname(dirname(__DIR__))."/funciones/openpay.php");
	include_once(dirname(dirname(__DIR__))."/funciones/mediqo.php");
	include_once(dirname(dirname(dirname(__DIR__)))."/lib/openpay2/Openpay.php");

	// ini_set('display_errors', 'On');
	// error_reporting(E_ALL);

	global $wpdb;
	extract($_POST);

	$cita = new_cita($_POST); // Creación de cita en status pendiente
	if( !$cita['status'] ){
		die(json_encode([
    		"code" => 0,
    		"msg"  => 'No se pudo crear el registro de la reserva',
    		"extra" => $cita
    	]));
	}

	$cita_id = $cita['id'];

	/*Preparamos lib de openpay */
		$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
		Openpay::setProductionMode( ($OPENPAY_PRUEBAS == 0) );

	/* Buscamos IDs de openpay y mediqo */
		$customer = get_openpay_customer($user_id, $openpay); // Obtenemos el customer_id de openpay
		// $mediqo_id = get_mediqo_customer($user_id); // Obtenemos el customer_id de mediqo

	/*
	if( $mediqo_id['status'] == 'ko' ){
		die(json_encode([
    		"error" => true,
    		"msg"  => 'No podemos obtener acceso a su cuenta de Mediqo, por favor contacte al equipo de soporte de Kmimos para brindarle la asistencia correspondiente.',
    		"res"  => $mediqo_id
    	]));
	}else{
		$mediqo_id = $mediqo_id['id'];
	}
	*/

	$chargeData = '';
	if( $deviceIdHiddenFieldName == "" AND $customer["status"] == 'ko' ){
		die(json_encode([
    		"error" => true,
    		"msg"  => 'id del dispositivo no recibido'
    	]));
	}else{

		/*
		// Parametros para crear consulta en mediqo //
			$params = [
				'medic' => $medico_id,
				'patient' => $mediqo_id,
				'specialty' => $specialty_id,
				'dueTo' => $cita_fecha,
				'paymentType' => 0,
				'appointmentType' => 1,
				'isCash' => true,
				'address' => $cita_direccion
			];
			if( $cita_latitud != '' ){
				$params['lat'] = $cita_latitud;
				$params['lng'] = $cita_longitud;
			}

		// Creamos consulta en mediqo //
			$appointment = add_appointments($params);
			$data = json_encode([ $params, $appointment ]);
			if( $appointment['status'] == 'ok' ){
				$wpdb->query("UPDATE wp_kmivet_reservas SET cita_id = '{$appointment['id']}', status = 1 WHERE id = '{$cita_id}' ");
			}else{
				$wpdb->query("UPDATE wp_kmivet_reservas SET cita_id = '{$data}' WHERE id = '{$cita_id}' ");
			}
		
		if( $appointment['status'] == 'ko' ){
			die(json_encode([
	    		"error" => true,
	    		"msg"  => $appointment['info']->message
	    	]));
		}else{
		*/

			$customer = $customer["info"];
		   	switch ( $cita_tipo_pago ) {
		   		case 'tarjeta':
		   			if( $token == "" ){
						die(json_encode([
				    		"error" => true,
				    		"msg"  => "Token de pago de openpay no recibido"
				    	]));
		   			}else{
						$chargeData = array(
						    'method' 			=> 'card',
						    'source_id' 		=> $token,
						    'amount' 			=> (float) $cita_precio,
						    'order_id' 			=> $ENTORNO.'_'.$cita_id.'_'.time(),
						    'description' 		=> "Tarjeta",
						   	// 'use_card_points'	=> $tarjeta->puntos,
						    'device_session_id' => $deviceIdHiddenFieldName
					    );
						$change = "";
						try {
				            $change = $customer->charges->create($chargeData);
				            if ($change == false) {
								die(json_encode([
					        		"error" => 5,
					        		"msg"  => 'procesando el pago'
					        	]));
							}else{
								$wpdb->query("UPDATE wp_kmivet_reservas SET status = 1 WHERE id = '{$cita_id}' ");
								
								$_infos  = $_SESSION['medicos_info'];
								$_medico = [];
								foreach ($_infos[ $medico_id ] as $key => $value) {
									$_medico[ $key ] = $value;
								}
								$cliente = get_user_meta($user_id, 'first_name', true).' '.get_user_meta($user_id, 'last_name', true);
								$cliente_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$user_id);
								$telefono_cliente = get_user_meta($user_id, 'user_mobile', true).' / '.get_user_meta($user_id, 'user_phone', true);
							    
						    	$fecha_cita = date("d/m", strtotime($cita_fecha));
						    	$hora_cita  = date("h:ia", strtotime($cita_fecha));

							    $INFORMACION = [
						        	"KV_URL_IMGS" 		 => getTema().'/KMIVET/img',
						        	"URL" 				 => get_home_url(),
						        	"URL_CANCELAR"		 => get_home_url().'/citas/cancelar/'.$cita_id,

						        	"NAME_VETERINARIO" 	 	=> $_medico['firstName'].' '.$_medico['lastName'],
						        	"TELEFONOS_VETERINARIO" => $_medico['phone'],
						        	"CORREO_VETERINARIO" 	=> $_medico['email'],

						        	"NAME_CLIENTE" 		 => $cliente,
						        	"TELEFONOS_CLIENTE"  => $telefono_cliente,
						        	"CORREO_CLIENTE" 	 => $cliente_email,

						        	"CONSULTA_ID" 		 => $cita_id,
						        	"TIPO_SERVICIO" 	 => 'CONSULTA A DOMICILIO',
						        	"FECHA" 	 		=> $fecha_cita,
						        	"HORA" 	 			=> $hora_cita,
						        	"TIPO_PAGO" 		=> 'Pago por Tarjeta',
						        	"TOTAL" 			=> number_format($cita_precio, 2, ',', '.'),

						        	"LAT_MEDIC" 		=> $_medico["lat"],
						        	"LNG_MEDIC" 		=> $_medico["lng"],
						        	"SPE_MEDIC" 		=> $specialty_id
						        ];

						        $info_email = json_encode($INFORMACION, JSON_UNESCAPED_UNICODE);

						        $wpdb->query("UPDATE wp_kmivet_reservas SET info_email = '{$info_email}' WHERE id = '{$cita_id}' ");

							    /* EMAIL al CLIENTE */
							    	$mensaje = kv_get_email_html(
								        'KMIVET/reservas/nueva_cliente', 
								        $INFORMACION
								    );
							        wp_mail($cliente_email, 'Kmivet - Nueva Solicitud de Consulta', $mensaje);


							    /* EMAIL al CUIDADOR */
							    	$mensaje = kv_get_email_html(
								        'KMIVET/reservas/nueva_veterinario', 
								        $INFORMACION
								    );
							        wp_mail($_medico['email'], 'Kmivet - Nueva Solicitud de Consulta', $mensaje);

							    /* EMAIL al ADMINISTRADOR */
							    	$mensaje = kv_get_email_html(
								        'KMIVET/reservas/nueva_admin', 
								        $INFORMACION
								    );
								    $header = kv_get_emails_admin();
							        wp_mail('soporte.kmimos@gmail.com', 'Kmivet - Nueva Solicitud de Consulta', $mensaje, $header);

							    die(json_encode([
									"msg" => "Pago realizado exitosamente",
									"cid" => $cita_id,
									"error" => false,
								]));

							}

				        } catch (Exception $e) {
				        	die(json_encode([
				        		"error" => true,
				        		"msg" => $e
				        	]));
				        }
					}
				break;

				default:
					die(json_encode([
		        		"error" => true,
		        		"msg" => "Tipo de pago no valido"
		        	]));
				break;
			}

		// }

	}

	die( json_encode([
		"error" => 6,
		"msg"  => 'Error inesperado'
	]));
?>