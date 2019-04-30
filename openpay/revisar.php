<?php
	include("../wp-load.php");

	include("openpay/Openpay.php");
	include("../wp-content/themes/kmimos/procesos/funciones/config.php");

	global $wpdb;

    date_default_timezone_set('America/Mexico_City');

    $limite = date("Y-m-d", strtotime("-4 day"));

	$ordenes_pendientes = $wpdb->get_results("
		SELECT 
			p.ID AS orden
		FROM 
			wp_posts AS p
		INNER JOIN wp_postmeta AS mts ON ( p.ID = mts.post_id ) 
		WHERE 
			1=1 
			AND mts.meta_key = '_payment_method' AND mts.meta_value = 'tienda'
			AND p.post_type = 'shop_order' AND p.post_status = 'wc-on-hold' AND p.post_date >= '{$limite}'
		GROUP BY 
			p.ID DESC
	");

	if( count($ordenes_pendientes) > 0){

		$ordenes = array();
		foreach ($ordenes_pendientes as $f) {
			$ordenes[$f->orden] = $f->orden;
		}

		// Inicialización OpenPay
		$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
		Openpay::setProductionMode( ($OPENPAY_PRUEBAS == 0) );
		
		$findDataRequest = array(
		    'creation[gte]' => $limite,
		    'offset' => 0,
		    'limit' => 500
	    );

		$chargeList = $openpay->charges->getList($findDataRequest);

		foreach ($chargeList as $key => $value) {

			if( in_array($value->order_id, $ordenes) ){

				unset( $ordenes[$value->order_id] );

				$hoy = time();

				if( $value->status == "in_progress" && ( $hoy > strtotime($value->due_date) ) ){
					$value->status = "cancelled";
				}

				switch ($value->status) {

					case 'cancelled':
						$id_orden = $value->order_id;
						$id_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");

						/*$wpdb->query("UPDATE wp_posts SET post_status = 'wc-cancelled' WHERE ID = $id_orden;");
    					$wpdb->query("UPDATE wp_posts SET post_status = 'cancelled' WHERE ID = '$id_reserva';");

    					update_cupos( $id_orden, "-");*/

    					$acc = "CCL"; $usu = "OPENPAY";
                		include( "../wp-content/themes/kmimos/procesos/reservar/emails/index.php");
					break;

					case 'completed':
						
						$id_orden = $value->order_id;
						$id_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");

						$id_item = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_reserva} AND meta_key = '_booking_order_item_id' ");
						$remanente = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = {$id_item} AND meta_key = '_wc_deposit_meta' ");

   						$hora_actual = date("Y-m-d H:i:s");
   						$wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_reserva};");
   						$wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_orden};");

					 	if( $remanente != 'a:1:{s:6:"enable";s:2:"no";}' ){
							$wpdb->query("UPDATE wp_posts SET post_status = 'unpaid' WHERE ID = $id_orden;");
    						$wpdb->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = '$id_reserva';");
						}else{
							$wpdb->query("UPDATE wp_posts SET post_status = 'paid' WHERE ID = $id_orden;");
    						$wpdb->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = '$id_reserva';");
						}

                		include( "../wp-content/themes/kmimos/procesos/reservar/emails/index.php");

					break;
				
				}
				
			}
		}

		if( count($ordenes) ){
			foreach ( $ordenes as $orden ) {
				$data = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$orden}' AND meta_key = '_openpay_busqueda' ");
				if( $data != null ){
					$data = unserialize($data);

					$customer = $openpay->customers->get($data["cliente"]);
					$charge = $customer->charges->get($data["transaccion_id"]);

					$hoy = time();

					if( $charge->status == "in_progress" && ( $hoy > strtotime($charge->due_date) ) ){
						$charge->status = "cancelled";
					}

					if( $charge->status == "cancelled" ){
						$id_orden = $charge->order_id;
						$id_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");

						$wpdb->query("UPDATE wp_posts SET post_status = 'wc-cancelled' WHERE ID = $id_orden;");
    					$wpdb->query("UPDATE wp_posts SET post_status = 'cancelled' WHERE ID = '$id_reserva';");

    					update_cupos( $id_orden, "-");
					}

					if( $charge->status == "completed" ){
						
						$id_orden = $charge->order_id;
						$id_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");

						$id_item = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_reserva} AND meta_key = '_booking_order_item_id' ");
						$remanente = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = {$id_item} AND meta_key = '_wc_deposit_meta' ");

   						$hora_actual = date("Y-m-d H:i:s");
   						$wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_reserva};");
   						$wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_orden};");

					 	if( $remanente != 'a:1:{s:6:"enable";s:2:"no";}' ){
							$wpdb->query("UPDATE wp_posts SET post_status = 'unpaid' WHERE ID = $id_orden;");
    						$wpdb->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = '$id_reserva';");
						}else{
							$wpdb->query("UPDATE wp_posts SET post_status = 'paid' WHERE ID = $id_orden;");
    						$wpdb->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = '$id_reserva';");
						}

                		include( "../wp-content/themes/kmimos/procesos/reservar/emails/index.php");
					}
				}else{
					echo "No hay datos de busqueda individual para la orden [{$orden}]<br>";
				}

			}
		}

	

	}

?>