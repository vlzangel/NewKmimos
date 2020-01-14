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
    		"msg"  => 'No podemos obtener acceso a su cuenta de Mediqo, por favor contacte al equipo de soporte de Kmimos para brindarle la asistencia correspondiente.',
    		"res"  => $mediqo_id
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

							$_infos  = $_SESSION['medicos_info'];
							$_medico = [];
							foreach ($_infos[ $id ] as $key => $value) {
								$_medico[ $key ] = $value;
							}

							$cliente = get_user_meta($user_id, 'first_name', true).' '.get_user_meta($user_id, 'last_name', true);
							$cliente_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$user_id);

							$telefono_cliente = get_user_meta($user_id, 'user_mobile', true).' / '.get_user_meta($user_id, 'user_phone', true);

						    $header = kv_get_emails_admin();

						    /* EMAIL al CLIENTE */

						    	$fecha_cita = date("d/m", strtotime($cita_fecha));
						    	$hora_cita  = date("h:ia", strtotime($cita_fecha));

						    	$mensaje = kv_get_email_html(
							        'KMIVET/reservas/nueva_cliente', 
							        [
							        	"KV_URL_IMGS" 		 => getTema().'/KMIVET/img',
							        	"URL" 				 => get_home_url(),
							        	
							        	"CONSULTA_ID" 		 => $cita_id,

							        	"NAME_CLIENTE" 		 => $cliente,

							        	"NAME_VETERINARIO" 	 	=> $_medico['firstName'].' '.$_medico['lastName'],
							        	"TELEFONOS_VETERINARIO" => $_medico['phone'],
							        	"CORREO_VETERINARIO" 	=> $_medico['email'],

							        	"TIPO_SERVICIO" 	 => 'CONSULTA A DOMICILIO',
							        	"FECHA" 	 		=> $fecha_cita,
							        	"HORA" 	 			=> $hora_cita,
							        	"TIPO_PAGO" 		=> 'Pago por Tarjeta',
							        	"TOTAL" 			=> number_format($cita_precio, 2, ',', '.'),
							        ]
							    );

						        wp_mail($cliente_email, 'Kmivet - Nueva Solicitud de Consulta', $mensaje);


						    /* EMAIL al CUIDADOR */
						        

						    	$mensaje = kv_get_email_html(
							        'KMIVET/reservas/nueva_veterinario', 
							        [
							        	"KV_URL_IMGS" 		 => getTema().'/KMIVET/img',
							        	"URL" 				 => get_home_url(),

							        	"CONSULTA_ID" 		 => $cita_id,

							        	"NAME_VETERINARIO" 	 => $_medico['firstName'].' '.$_medico['lastName'],

							        	"NAME_CLIENTE" 		 => $cliente,
							        	"TELEFONOS_CLIENTE"  => $telefono_cliente,
							        	"CORREO_CLIENTE" 	 => $cliente_email,

							        	"TIPO_SERVICIO" 	 => 'CONSULTA A DOMICILIO',
							        	"FECHA" 	 		=> $fecha_cita,
							        	"HORA" 	 			=> $hora_cita,
							        	"TIPO_PAGO" 		=> 'Pago por Tarjeta',
							        	"TOTAL" 			=> number_format($cita_precio, 2, ',', '.'),
							        ]
							    );

						        wp_mail($_medico['email'], 'Kmivet - Nueva Solicitud de Consulta', $mensaje);


						    /* EMAIL al ADMINISTRADOR */
						        

						    	$mensaje = kv_get_email_html(
							        'KMIVET/reservas/nueva_admin', 
							        [
							        	"KV_URL_IMGS" 		 => getTema().'/KMIVET/img',
							        	"URL" 				 => get_home_url(),
							        	"NAME_VETERINARIO" 	 => $_medico['firstName'].' '.$_medico['lastName'],
							        	"NAME_CLIENTE" 		 => "Administrador",
							        	"CONSULTA_ID" 		 => $cita_id,

							        	"TELEFONOS_CUIDADOR" => $_medico['phone'],
							        	"CORREO_CUIDADOR" 	 => $_medico['email'],

							        	"TIPO_SERVICIO" 	 => 'CONSULTA A DOMICILIO',

							        	"FECHA" 	 		=> $fecha_cita,
							        	"HORA" 	 			=> $hora_cita,
							        	"TIPO_PAGO" 		=> 'Pago por Tarjeta',
							        	"TOTAL" 			=> number_format($cita_precio, 2, ',', '.'),
							        ]
							    );

						        wp_mail('soporte.kmimos@gmail.com', 'Kmivet - Nueva Solicitud de Consulta', $mensaje, $header);

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