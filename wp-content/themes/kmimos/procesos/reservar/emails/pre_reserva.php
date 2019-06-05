<?php

    $admin_id = get_post_meta($servicio["id_reserva"], "_booking_id_admin", true);
    $nombre_admin = get_user_meta($admin_id, "first_name", true)." ".get_user_meta($admin_id, "last_name", true);
    $email_admin = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$admin_id);
    $LINK_PAGO = get_home_url()."/pagar/".$servicio["id_reserva"];
    
    if( $servicio["desglose"]["reembolsar"]+0 > 0 ){
        $descuento_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/reembolsar.php';
        $descuento_plantilla = file_get_contents($descuento_plantilla);
        $descuento_plantilla = str_replace('[DEVOLVER]', number_format( $servicio["desglose"]["reembolsar"], 2, ',', '.'), $descuento_plantilla);
        $totales_plantilla = str_replace('[REEMBOLSAR]', $descuento_plantilla, $totales_plantilla);
    }else{
        $totales_plantilla = str_replace('[REEMBOLSAR]', "", $totales_plantilla);
    }

    $INFOR = [
        "desglose" => $desglose,
        "ADICIONALES" => $adicionales,
        "TRANSPORTE" => $transporte,
        "MODIFICACION" => $modificacion,
        "tipo_servicio" => $servicio["tipo"],
        "id_reserva" => $servicio["id_reserva"],
        "DETALLES_SERVICIO" => $detalles_plantilla,
        "DISPLAY_PASEOS" => "none",
        "inicio" => date("d/m", $servicio["inicio"]),
        "fin" => date("d/m", $servicio["fin"]),
        "anio" => date("Y", $servicio["fin"]),
        "tiempo" => $servicio["duracion"],
        "tipo_pago" => $servicio["metodo_pago"],
        "TOTALES" => $totales_plantilla,
        "tipo_pago" => $servicio["metodo_pago"],
        "name_cliente" => $cliente["nombre"],
        "avatar_cliente" => kmimos_get_foto($cliente["id"]),
        "telefonos_cliente" => $cliente["telefono"],
        "correo_cliente" => $cliente["email"],
        "name_cuidador" => $cuidador["nombre"],
        "avatar_cuidador" => kmimos_get_foto($cuidador["id"]),
        "telefonos_cuidador" => $cuidador["telefono"],
        "correo_cuidador" => $cuidador["email"],
        "direccion_cuidador" => $cuidador["direccion"],
        "ADMIN_NAME" => $nombre_admin,
        "ADMIN_EMAIL" => $email_admin,
        "LINK_PAGO" => $LINK_PAGO,
        "mascotas" => $mascotas,
    ];

    /* Administrador */
        $cliente = buildEmailTemplate_TEMP( "reservar/admin/pre_reserva", $INFOR);
        $cliente = get_email_html($cliente);

        if( isset($NO_ENVIAR) ){
            echo $cliente;
        }else{
            kmimos_mails_administradores_new("Solicitud de reserva #".$servicio["id_reserva"], $cliente);
        }


    /* Cliente */
        $mensaje = buildEmailTemplate_TEMP( "reservar/cliente/pre_reserva", $INFOR);
        $mensaje = get_email_html($mensaje);

        if( isset($NO_ENVIAR) ){
            echo $mensaje;
        }else{
            wp_mail( $cliente["email"], "Solicitud de Reserva por Pagar", $mensaje);
        }
?>