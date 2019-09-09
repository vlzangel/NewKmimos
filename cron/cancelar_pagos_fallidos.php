<?php

    session_start();
    
    require('../wp-load.php');

    date_default_timezone_set('America/Mexico_City');

    global $wpdb;

    $fecha_cancelacion = time()-1800;

    if( $xhora_actual < $fin ){
        $sql = "
            SELECT ID, post_type FROM wp_posts WHERE 
            post_type IN (
                'shop_order'
            ) AND
            post_status IN (
                'pending',
            ) AND post_date < '".date("Y-m-d H:i:s", $fecha_cancelacion)."'
        ";
        $r = $wpdb->get_results( $sql );

        foreach ($r as $orden) {

            $id_orden = $orden->ID;
            $wpdb->query("UPDATE wp_posts SET post_status = 'cancelled' WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
            $wpdb->query("UPDATE wp_posts SET post_status = 'wc-cancelled' WHERE ID = {$id_orden};");
            update_cupos( $id_orden );

            update_post_meta($id_orden, 'cancelado_por', 'Tiempo de pago vencido');

        }

    }

?>