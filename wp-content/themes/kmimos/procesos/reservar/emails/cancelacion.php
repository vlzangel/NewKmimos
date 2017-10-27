<?php

    kmimos_set_kmisaldo($cliente["id"], $id, $servicio["id_reserva"]);
    update_cupos( $id, "-");
    
    $wpdb->query("UPDATE wp_posts SET post_status = 'wc-cancelled' WHERE ID = $id;");
    $wpdb->query("UPDATE wp_posts SET post_status = 'cancelled' WHERE ID = '{$servicio["id_reserva"]}';");

	$cuidador_info = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = ".$cuidador["id"]);

	$sql = "
        SELECT 
            DISTINCT id,
            ROUND ( ( 6371 * acos( cos( radians({$cuidador_info->latitud}) ) * cos( radians(latitud) ) * cos( radians(longitud) - radians({$cuidador_info->longitud}) ) + sin( radians({$cuidador_info->latitud}) ) * sin( radians(latitud) ) ) ), 2 ) as DISTANCIA,
            id_post,
            user_id,
            hospedaje_desde,
            adicionales
        FROM 
            cuidadores
        WHERE
            id_post != {$cuidador_info->id_post} AND 
            activo = 1
        ORDER BY DISTANCIA ASC
        LIMIT 0, 4
    ";

    $sugeridos = $wpdb->get_results($sql);

    $str_sugeridos = "";

    $file_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/cancelacion/partes/cuidadores.php';
    $plantilla_cuidador = file_get_contents($file_plantilla);

    foreach ($sugeridos as $valor) {
    	$nombre = $wpdb->get_row("SELECT post_title, post_name FROM wp_posts WHERE ID = ".$valor->id_post);
    	$rating = kmimos_petsitter_rating($valor->id_post, true); $rating_txt = "";
    	foreach ($rating as $key => $value) {
    		if( $value == 1 ){ $rating_txt .= "<img style='width: 15px; padding: 0px 1px;' src='[URL_IMGS]/huesito.png' >";
    		}else{ $rating_txt .= "<img style='width: 15px; padding: 0px 1px;' src='[URL_IMGS]/huesito_vacio.png' >"; }
    	}
    	$servicios = vlz_servicios($valor->adicionales, true);
    	$servicios_txt = "";
        if( count($servicios)+0 > 0 && $servicios != "" ){
            foreach ($servicios as $key => $value) {
                $servicios_txt .= "<img style='' src='[URL_IMGS]/servicios/".$value["img"]."' height='100%' >";
            }
        }
    	$temp = str_replace("[MONTO]", number_format( ($valor->hospedaje_desde*1.2), 2, ',', '.'), $plantilla_cuidador);
    	$temp = str_replace("[AVATAR]", kmimos_get_foto($valor->user_id), $temp);
    	$temp = str_replace("[NAME_CUIDADOR]", $nombre->post_title, $temp);
    	$temp = str_replace("[HUESOS]", $rating_txt, $temp);
    	$temp = str_replace("[SERVICIOS]", $servicios_txt, $temp);
    	$temp = str_replace('[LIKS]', get_home_url()."/petsitters/".$nombre->post_name."/", $temp);
    	$str_sugeridos .= $temp;
    }

    $msg_cliente = "";
    $msg_cuidador = "";

    if( $user == "STM" ){
        $msg_cliente = "Te notificamos que el sistema ha cancelado la reserva con el cuidador <strong>[name_cuidador]</strong> debido a que se venció el plazo de confirmación.";
        $msg_cuidador = "Te notificamos que el sistema ha cancelado la reserva realizada por <strong>[name_cliente]</strong> debido a que se venció el plazo de confirmación.";
    }else{
        if( $user == "CLI" ){
            $msg_cliente = "Te notificamos que la reserva ha sido cancelada exitosamente.";
            $msg_cuidador = "Te notificamos que el cliente <strong>[name_cliente]</strong> ha cancelado la reserva.";
        }else{
            $msg_cliente = "Te notificamos que el cuidador <strong>[name_cuidador]</strong> ha cancelado la reserva.";
            $msg_cuidador = "Te notificamos que la reserva ha sido cancelada exitosamente.";
        }
    }

    /* CORREO CLIENTE */
        $file = $PATH_TEMPLATE.'/template/mail/reservar/cancelacion/cancelar_cliente.php';
        $mensaje_cliente = file_get_contents($file);

        $mensaje_cliente = str_replace('[MODIFICACION]', $modificacion, $mensaje_cliente);

        $mensaje_cliente = str_replace('[mensaje]', $msg_cliente, $mensaje_cliente);
        $mensaje_cliente = str_replace('[name_cliente]', $cliente["nombre"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[CUIDADORES]', $str_sugeridos, $mensaje_cliente);
        $mensaje_cliente = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cliente);
    	
        $mensaje_cliente = get_email_html($mensaje_cliente);	

    	wp_mail( $cliente["email"], "Cancelación de Reserva", $mensaje_cliente);

    /* CORREO CUIDADOR */
        $file = $PATH_TEMPLATE.'/template/mail/reservar/cancelacion/cancelar_cuidador.php';
        $mensaje_cuidador = file_get_contents($file);

        $mensaje_cuidador = str_replace('[MODIFICACION]', $modificacion, $mensaje_cuidador);
        
        $mensaje_cuidador = str_replace('[mensaje]', $msg_cuidador, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[name_cliente]', $cliente["nombre"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cuidador);

    	$mensaje_cuidador = get_email_html($mensaje_cuidador, false);	

    	wp_mail( $cuidador["email"], "Cancelación de Reserva", $mensaje_cuidador);


    if( $user != "STM" ){

        if( $user == "CLI" ){
            $volver = get_home_url()."/perfil-usuario/historial/";
        }else{
            $volver = get_home_url()."/perfil-usuario/reservas/";
        }

        echo "
            <a href='".$volver."' style='
                border-top: solid 1px #CCC;
                border-bottom: solid 1px #CCC;
                margin: 10px auto;
                width: 600px;
                padding: 10px 0px;
                font-weight: 600;
                font-family: Arial;
                text-align: center;
                cursor: pointer;
                font-size: 13px;
                text-decoration: none;
                color: #000;
                display: block;
            '>
                Volver
            </a>
        ";

        if( $user == "CLI" ){
            echo $mensaje_cliente;
        }else{
            echo $mensaje_cuidador;
        }
    }
?>
