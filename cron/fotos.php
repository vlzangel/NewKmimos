<?php

    session_start();
    
    require('../wp-load.php');

    date_default_timezone_set('America/Mexico_City');

    global $wpdb;

    $hora_actual = strtotime("now");
    $xhora_actual = date("H", $hora_actual);

    $periodo_sql = "subio_12 = '1' ";
    $periodo = 1;
    if( $xhora_actual == "18" ){
        $periodo_sql = "subio_06 = '1' ";
        $periodo = 2;
    }

    $hoy = date("Y-m-d");

    $SQL = "SELECT * FROM fotos WHERE {$periodo_sql} AND bloqueo = 0 AND fecha = '{$hoy}' AND moderacion != 'a:0:{}'";
    $fotos_a_enviar = $wpdb->get_results( $SQL );

    $PATH = dirname(__DIR__)."/wp-content/uploads/fotos/";

    foreach ($fotos_a_enviar as $key => $value) {
        $cliente_id = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$value->reserva} AND meta_key = '_booking_customer_id' ");
        $frecuencia = get_user_meta($cliente_id, "user_recibir_fotos", true);

        $email_cliente = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$cliente_id} ");

        $metas = get_user_meta($cliente_id);
        $nombre_cliente = $metas["first_name"][0]." ".$metas["last_name"][0];

        $enviar = true;
        $flujos = 1;

        switch ( $frecuencia ) {
            case 2: // Una vez al dia
                if( $periodo == 1 ){
                    $enviar = false;
                }else{
                    $flujos = 2;
                }
            break;
            case 3: // Una vez cada 2 dias
                
                $inicio = strtotime( substr(get_post_meta($value->reserva, "_booking_start", true), 0, 8) );
                $fin    = strtotime( substr(get_post_meta($value->reserva, "_booking_end", true), 0, 8) );

                $flujos = 2;

                if( $inicio == $fin && $periodo == 1 ){
                    $enviar = false;
                }elseif ( $inicio == $fin && $periodo == 2 ) {
                    $enviar = true;
                }else{
                    $transcurrido = ( ( strtotime($hoy)-$inicio ) / 86400)+1; // Ej: 22/11 a 21/11 = 1+1 = 2
                    $dias = ( ( $fin-$inicio ) / 86400)+1;
                    if( $dias == 2 && $transcurrido == 2 && $periodo == 1 ){ // Duracion 2 dias - periodo de la mañana
                        $enviar = false;
                    }elseif ( $dias == 2 && $transcurrido == 2 && $periodo == 2 ) { // Duracion 2 dias - periodo de la tarde
                        $enviar = true;
                    }else{
                        if( $transcurrido%2 == 0 && $periodo == 1 ){ // Duracion mayor a 2 dias - periodo de la mañana
                            $enviar = false;
                        }elseif ( $transcurrido%2 == 0 && $periodo == 2 ) {
                            $enviar = true;
                        }
                    }
                }

            break;
            case 4: // No enviar nunca
                $enviar = false;
            break;
        }

        if( $enviar ){

            $fotos = dirname(__DIR__).'/wp-content/themes/kmimos/template/mail/fotos/fotos.php';
            $fotos = file_get_contents($fotos);

            $periodo_txt = "";
            if( $flujos == 1 ){
                $temp = "ma&ntilde;ana";
                if( $periodo == 2 ){
                    $temp = "tarde";
                }
                $periodo_txt = " por la ".$temp;
                $collage = '<img src="'.get_home_url().'/wp-content/uploads/fotos/'.$value->reserva.'/'.date("Y-m-d").'_'.$periodo.'/collage.png" style="width: 600px;" />';
            }else{
                if( $periodo == 2 ){
                    $periodo_txt = " de la ma&ntilde;ana y la tarde";
                    $collage  = '<img src="'.get_home_url().'/wp-content/uploads/fotos/'.$value->reserva.'/'.date("Y-m-d").'_1/collage.png" style="width: 600px;" />';
                    $collage .= '<img src="'.get_home_url().'/wp-content/uploads/fotos/'.$value->reserva.'/'.date("Y-m-d").'_2/collage.png" style="width: 600px;" />';
                }
            }

            $fotos = str_replace('[FOTOS]', $collage, $fotos);
            $fotos = str_replace('[CLIENTE]', $nombre_cliente, $fotos);
            $fotos = str_replace('[PERIODO]', $periodo_txt, $fotos);
            $fotos = str_replace('[FECHA]', date("d/m/Y"), $fotos);
            $fotos = str_replace('[ID_RESERVA]', $value->reserva, $fotos);
            $fotos = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $fotos);

            $fotos = get_email_html($fotos);

            wp_mail( $email_cliente, "Fotos de tus mascotas", $fotos);

        }
    }

?>