<?php

    session_start();
    
    require('../wp-load.php');

    date_default_timezone_set('America/Mexico_City');

    $time_ahora = time();

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

    
    echo "<pre>";

        // print_r( $r );
    
        if( count($r) > 0 ){

            foreach ($r as $request) {

                $reserva_id = $wpdb->get_var("SELECT * FROM wp_posts WHERE post_parent = ".$request->ID);
                $pre_change_status = get_post_meta($reserva_id, 'pre_change_status', true);
                $pre_change_status = json_decode( $pre_change_status );

                // print_r( $pre_change_status );

                if( ( time() + 60 ) > $pre_change_status->hora ){

                    echo date("H:i:s", ( time() + 60))." > ".date("H:i:s", $pre_change_status->hora)." <br>";

                    $acc = $pre_change_status->acc; 
                    $usu = $pre_change_status->usu;

                    $_GET["CONFIRMACION"] = "YES";

                    $id_orden = $r->ID;

                    // include( "../wp-content/themes/kmimos/procesos/reservar/emails/index.php");
                }

            }

        }
        
    echo "</pre>";

?>