<?php
	include("../wp-load.php");

	include("openpay/Openpay.php");
	include("../wp-content/themes/kmimos/procesos/funciones/config.php");

	global $wpdb;

    date_default_timezone_set('America/Mexico_City');

 	extract($_GET);

 	$id_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");

 	if( $id_reserva != null ){
 		
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

 	}else{
 		echo "El ID [ {$id_orden}] no pertenece a una orden.";
 	}

?>