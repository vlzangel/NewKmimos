<?php
    session_start();
    require('../wp-load.php');
    date_default_timezone_set('America/Mexico_City');
    global $wpdb;

    $hora_actual = strtotime("now");
    $hora_actual = strtotime("18:00:00");
    $xhora_actual = date("H", $hora_actual);

    $periodo_sql = "subio_12 = '1' ";
    $periodo = 1;
    if( $xhora_actual >= "18" ){
        $periodo_sql = "subio_06 = '1' ";
        $periodo = 2;
    }

    $hoy = date("Y-m-d");
    $SQL = "SELECT * FROM fotos WHERE {$periodo_sql} AND bloqueo = 0 AND fecha = '{$hoy}' AND moderacion != 'a:0:{}'";
    $fotos_a_enviar = $wpdb->get_results( $SQL );

    $PATH = dirname(__DIR__)."/wp-content/uploads/fotos/";

    foreach ($fotos_a_enviar as $key => $value) {
        $mascotas = ( get_post_meta($value->reserva, '_booking_persons', true) );
        $total_mascotas = 0;
        foreach ($mascotas as $_key => $_value) {
            $total_mascotas += $value;
        }

        $cliente_id = get_post_meta($value->reserva, '_booking_customer_id', true);
        $servicio_id = get_post_meta($value->reserva, '_booking_product_id', true);
        $frecuencia = get_user_meta($cliente_id, "user_recibir_fotos", true);
        $email_cliente = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$cliente_id} ");
        $metas = get_user_meta($cliente_id);
        $nombre_cliente = $metas["first_name"][0]." ".$metas["last_name"][0];

        $cuidador_id = $wpdb->get_var("SELECT post_parent FROM wp_posts WHERE ID = {$servicio_id}");
        $nombre_cuidador = $wpdb->get_var("SELECT post_title FROM wp_posts WHERE ID = {$cuidador_id}");

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
            $periodos_a_mostrar = 1;
            $periodo_txt = "";
            if( $flujos == 1 ){
                $temp = "ma&ntilde;ana";
                if( $periodo == 2 ){
                    $temp = "tarde";
                    $periodos_a_mostrar = 2;
                }
                $periodo_txt = " por la ".$temp;
                $moderacion = unserialize($value->moderacion);
                $collage = "";
                foreach ($moderacion[ $periodos_a_mostrar ] as $key => $foto) {
                    $collage .= '<img src="'.get_home_url().'/wp-content/uploads/fotos/'.$value->reserva.'/'.date("Y-m-d").'_'.$periodo.'/'.$foto.'" style="width: 23%; height: 120px; padding: 1%;" />';
                }
            }else{
                if( $periodo == 2 ){
                    $periodos_a_mostrar = 3;
                    $periodo_txt = " de la ma&ntilde;ana y la tarde";
                    $moderacion = unserialize($value->moderacion);
                    $collage = "";
                        foreach ($moderacion[ 1 ] as $key => $foto) {
                            $collage .= '<img src="'.get_home_url().'/wp-content/uploads/fotos/'.$value->reserva.'/'.date("Y-m-d").'_1/'.$foto.'" style="width: 23%; height: 120px; padding: 1%;" />';
                        }
                    $collage .= "";
                        foreach ($moderacion[ 2 ] as $key => $foto) {
                            $collage .= '<img src="'.get_home_url().'/wp-content/uploads/fotos/'.$value->reserva.'/'.date("Y-m-d").'_2/'.$foto.'" style="width: 23%; height: 120px; padding: 1%;" />';
                        }
                    $collage .= "";
                }
            }

            $msg = "tu peludo";
            if( $total_mascotas > 1 ){
                $msg = "tus peludos";
            }
            $fotos = str_replace('[FOTOS]', $collage, $fotos);
            $fotos = str_replace('[CLIENTE]', $nombre_cliente, $fotos);
            $fotos = str_replace('[CUIDADOR]', $nombre_cuidador, $fotos);
            $fotos = str_replace('[MSG_PELUDOS]', $msg, $fotos);
            $fotos = str_replace('[PERIODO]', $periodo_txt, $fotos);
            $fotos = str_replace('[FECHA]', date("d/m/Y"), $fotos);
            $fotos = str_replace('[ID_RESERVA]', $value->reserva, $fotos);
            $fotos = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $fotos);
            $fotos = str_replace('[URL_VER]', get_home_url()."/perfil-usuario/ver-fotos/{$value->reserva}?ver=".$periodos_a_mostrar."&fecha=".date("Y-m-d"), $fotos);
            $fotos = get_email_html($fotos);
            
            if( isset($_GET["prueba"]) ){
                echo $fotos;
            }else{
                wp_mail( $email_cliente, "Fotos de tus mascotas", $fotos);
            }
            
        }
    }

?>