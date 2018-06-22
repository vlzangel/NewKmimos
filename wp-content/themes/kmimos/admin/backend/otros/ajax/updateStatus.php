<?php
	
	extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    $superAdmin = "YES";

    $id_orden = $ORDEN_ID;

    switch ( $status ) {
        
        case 'pagado':
            $id_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");

            $id_item = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_reserva} AND meta_key = '_booking_order_item_id' ");
            $remanente = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = {$id_item} AND meta_key = '_wc_deposit_meta' ");

            $hora_actual = date("Y-m-d H:i:s");
            $wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_reserva};");
            $wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_orden};");

            $remanente = unserialize($remanente);

            if( $remanente["enable"] != 'no' ){
                $wpdb->query("UPDATE wp_posts SET post_status = 'unpaid' WHERE ID = $id_orden;");
                $wpdb->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = '$id_reserva';");
            }else{
                $wpdb->query("UPDATE wp_posts SET post_status = 'paid' WHERE ID = $id_orden;");
                $wpdb->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = '$id_reserva';");
            }

        break;

        case 'pagado_email':

            $id_reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = {$id_orden} AND post_type LIKE 'wc_booking'");

            $id_item = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_reserva} AND meta_key = '_booking_order_item_id' ");
            $remanente = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = {$id_item} AND meta_key = '_wc_deposit_meta' ");

            $hora_actual = date("Y-m-d H:i:s");
            $wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_reserva};");
            $wpdb->query("UPDATE wp_posts SET post_date = '{$hora_actual}' WHERE ID = {$id_orden};");

            $remanente = unserialize($remanente);

            if( $remanente["enable"] != 'no' ){
                $wpdb->query("UPDATE wp_posts SET post_status = 'unpaid' WHERE ID = $id_orden;");
                $wpdb->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = '$id_reserva';");
            }else{
                $wpdb->query("UPDATE wp_posts SET post_status = 'paid' WHERE ID = $id_orden;");
                $wpdb->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = '$id_reserva';");
            }

            include( $raiz."/wp-content/themes/kmimos/procesos/reservar/emails/index.php");

        break;

        case 'confirmado':
            
            $acc = "CFM"; $usu = "CUI"; $NO_ENVIAR = "NO";

            include( $raiz."/wp-content/themes/kmimos/procesos/reservar/emails/index.php");

        break;

        case 'confirmado_email':
            
            $acc = "CFM"; $usu = "CUI";

            include( $raiz."/wp-content/themes/kmimos/procesos/reservar/emails/index.php");

        break;

        case 'cancelado':
            
            $acc = "CCL"; $usu = "CUI"; $NO_ENVIAR = "NO";

            include( $raiz."/wp-content/themes/kmimos/procesos/reservar/emails/index.php");

        break;

        case 'cancelado_email':
            
            $acc = "CCL"; $usu = "CUI";

            include( $raiz."/wp-content/themes/kmimos/procesos/reservar/emails/index.php");

        break;
    
    }

	exit;
?>