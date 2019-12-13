<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
	include_once($raiz."/wp-load.php");
	date_default_timezone_set('America/Mexico_City');
	if( !isset($_SESSION)){ session_start(); }

	include_once(dirname(__DIR__)."/funciones/config.php");
	include_once(dirname(__DIR__)."/funciones/funciones_openpay.php");
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
			'{$data}',
			'Pendiente',
			NOW()
		)";
		$wpdb->query( $sql );
		return $wpdb->insert_id;
	}

	$cita_id = new_cita($_POST);

	$error = [];
	$res = [];

	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( ($OPENPAY_PRUEBAS == 0) );

	$customer = get_openpay_customer($user_id, $openpay);

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
					    'order_id' 			=> $cita_id,
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
						}else{
							$error[] = [
				        		"code" => 0,
				        		"msg"  => 'Procesando el pago'
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
    		"msg"  => 'Id del dispositivo no recibido'
    	];
	}

	$res['errores'] = $error;

	echo json_encode($res);

	die();
?>