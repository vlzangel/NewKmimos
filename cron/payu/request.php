<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
 
	$dir_base = dirname(dirname(__DIR__));
	$response = json_encode($_REQUEST);

	// ********************************
	// Crear Log de Respuesta PayU
	// ********************************
	if( !empty($response) ){
		$file_name = "log/payu_".date('Ymd').".txt";
        $file = fopen($file_name, "a+");
        fwrite($file, date('Y/m/d H:i:s') . '  ===============  ' . PHP_EOL);
        fwrite($file, $response . PHP_EOL);
        fclose($file);
	}

	
	// ********************************
	// Procesar transacciones
	// ********************************
	$log = [];
	$datos = json_decode($response);

	if ( isset($datos->response_message_pol) ) {
		if ( $datos->response_message_pol == 'APPROVED' ) {

			include( $dir_base . "/vlz_config.php" );
			include( $dir_base . "/wp-load.php");
			include( $dir_base . "/wp-content/themes/kmimos/procesos/funciones/db.php");
			$db = new db( new mysqli($host, $user, $pass, $db) );

			$id_orden = $datos->order_id;
			$log['orden'] = $id_orden;

			/* ************************* */
			// Tipo de Deposito
			/* ************************* */
			$xitems = $db->get_var( "SELECT w.meta_key, w.meta_value 
				FROM wp_posts as p 
					inner join wp_postmeta as m ON m.post_id = p.ID 
						and m.meta_key = '_booking_order_item_id'
					inner join wp_woocommerce_order_itemmeta as w ON w.order_item_id = m.meta_value 
						and w.meta_key = '_wc_deposit_meta'
				WHERE p.post_parent = {$id_orden};", "meta_value" );

			$pago = unserialize($xitems);
			$deposito_enable = 'no';
			if(isset($pago["enable"]) && $pago["enable"] == 'yes'){
				$deposito_enable = 'yes';
			}

			/* ************************* */
			// Update fechas
			/* ************************* */
			$fecha = date("Y-m-d H:i:s");
			$db->query("UPDATE wp_posts SET post_date = '{$fecha}' WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
			$db->query("UPDATE wp_posts SET post_date = '{$fecha}' WHERE ID = {$id_orden};");

			/* ************************* */
			// Update Estatus reservas
			/* ************************* */
			if( $deposito_enable == "yes" ){
				$db->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = {$id_orden};");
				$log["Pedido"] = "Estatus actualizado - Pago: 20%";
			}else{
				$db->query("UPDATE wp_posts SET post_status = 'paid' WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
				$db->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = {$id_orden};");
				$log["Pedido"] = "Estatus actualizado - Pago: Total";
			} 

			/* ************************* */
			// Enviar Email
			/* ************************* */			
			$acc = "";
			include( $dir_base."/wp-content/themes/kmimos/procesos/reservar/emails/index.php" );

			$log['fin'] = "Terminado";
			 
		}else{
			$log["Estatus"] = $datos->response_message_pol;
		}
	}else{
		$log["Estatus"] = "Estatus: response_message_pol NO_DEFINIDA ";
	}
	
	$l = json_encode($log);
	print_r( $l );

	// ********************************
	// Crear Log de Respuesta
	// ********************************
	if( !empty($l) ){
		$file_name = "log/dev_".date('Ymd').".txt";
        $file = fopen($file_name, "a+");
        fwrite($file, date('Y/m/d H:i:s') . '  ===============  ' . PHP_EOL);
        fwrite($file, $l . PHP_EOL);
        fclose($file);
	}
