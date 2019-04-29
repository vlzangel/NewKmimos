<?php

    session_start();
    
    require('../wp-load.php');

    date_default_timezone_set('America/Mexico_City');

    global $wpdb;

    $sql = "
        SELECT ID, post_type FROM wp_posts WHERE 
        post_type = 'shop_order' AND
        post_status IN (
            'pending',
            'wc-completed',
            'wc-processing',
            'wc-partially-paid'
        ) AND ping_status != 'closed' 
    ";
    $r = $wpdb->get_results( $sql );

    /*
    echo "<pre>";
        print_r( $r );
    echo "</pre>";
    */

    if( count($r) > 0 ){

        foreach ($r as $request) {

            $pre_change_status = get_post_meta($servicio["id_reserva"], 'pre_change_status', true);
            $pre_change_status = json_decode( $pre_change_status );

            if( $pre_change_status->hora > ( time() + 60 ) ){

                $acc = $pre_change_status->acc; 
                $usu = $pre_change_status->usu;

                $_GET["CONFIRMACION"] = "YES";

                $id_orden = $r->ID;

                include( "../wp-content/themes/kmimos/procesos/reservar/emails/index.php");
            }

        }

    }

?>