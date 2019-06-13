<?php

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

        $mensaje = buildEmailTemplate_TEMP( "reservar/admin/fallidos", $INFOR);
        $mensaje = get_email_html($mensaje);

        if( isset($NO_ENVIAR) ){
            echo $mensaje;
        }else{
            kmimos_mails_administradores_new("Pago fallido #".$servicio["id_reserva"], $mensaje);
        }

?>