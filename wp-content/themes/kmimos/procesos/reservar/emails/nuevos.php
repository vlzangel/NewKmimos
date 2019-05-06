<?php
    
    /* Administrador */

    $admin_file = $PATH_TEMPLATE.'/template/mail/reservar/admin/nueva_otros.php';
    $mensaje_admin = file_get_contents($admin_file);

    /* Generales */

        $mensaje_admin = str_replace('[mascotas]', $mascotas, $mensaje_admin);

        if( $servicio["desglose"]["reembolsar"]+0 > 0 ){
            $descuento_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/reembolsar.php';
            $descuento_plantilla = file_get_contents($descuento_plantilla);
            $descuento_plantilla = str_replace('[DEVOLVER]', number_format( $servicio["desglose"]["reembolsar"], 2, ',', '.'), $descuento_plantilla);
            $totales_plantilla = str_replace('[REEMBOLSAR]', $descuento_plantilla, $totales_plantilla);
        }else{
            $totales_plantilla = str_replace('[REEMBOLSAR]', "", $totales_plantilla);
        }

        $mensaje_admin = str_replace('[desglose]', $desglose, $mensaje_admin);
        
        $mensaje_admin = str_replace('[ADICIONALES]', $adicionales, $mensaje_admin);
        $mensaje_admin = str_replace('[TRANSPORTE]', $transporte, $mensaje_admin);

        $mensaje_admin = str_replace('[MODIFICACION]', $modificacion, $mensaje_admin);

        $mensaje_admin = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_admin);

        $mensaje_admin = str_replace('[tipo_servicio]', $servicio["tipo"], $mensaje_admin);
        $mensaje_admin = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_admin);

        $mensaje_admin = str_replace('[DETALLES_SERVICIO]', $detalles_plantilla, $mensaje_admin);
        $mensaje_admin = str_replace('[DISPLAY_PASEOS]', "none", $mensaje_admin);
        
        $mensaje_admin = str_replace('[inicio]', date("d/m", $servicio["inicio"]), $mensaje_admin);
        $mensaje_admin = str_replace('[fin]', date("d/m", $servicio["fin"]), $mensaje_admin);
        $mensaje_admin = str_replace('[anio]', date("Y", $servicio["fin"]), $mensaje_admin);
        $mensaje_admin = str_replace('[tiempo]', $servicio["duracion"], $mensaje_admin);
        $mensaje_admin = str_replace('[tipo_pago]', $servicio["metodo_pago"], $mensaje_admin);

        $mensaje_admin = str_replace('[TOTALES]', $totales_plantilla, $mensaje_admin);

        $mensaje_admin = str_replace('[tipo_pago]', $servicio["metodo_pago"], $mensaje_admin);
        $mensaje_admin = str_replace('[PDF]', $servicio["pdf"], $mensaje_admin);

    /* Datos Cliente */

        $mensaje_admin = str_replace('[name_cliente]', $cliente["nombre"], $mensaje_admin);
        $mensaje_admin = str_replace('[avatar_cliente]', kmimos_get_foto($cliente["id"]), $mensaje_admin);
        $mensaje_admin = str_replace('[telefonos_cliente]', $cliente["telefono"], $mensaje_admin);
        $mensaje_admin = str_replace('[correo_cliente]', $cliente["email"], $mensaje_admin);

    /* Datos Cuidador */
    
        $mensaje_admin = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_admin);
        $mensaje_admin = str_replace('[avatar_cuidador]', kmimos_get_foto($cuidador["id"]), $mensaje_admin);
        $mensaje_admin = str_replace('[telefonos_cuidador]', $cuidador["telefono"], $mensaje_admin);
        $mensaje_admin = str_replace('[correo_cuidador]', $cuidador["email"], $mensaje_admin);
        $mensaje_admin = str_replace('[direccion_cuidador]', $cuidador["direccion"], $mensaje_admin);

    $mensaje_admin = get_email_html($mensaje_admin);

    if( isset($NO_ENVIAR) ){
        echo $mensaje_admin;
    }else{
        kmimos_mails_administradores_new("Solicitud de reserva #".$servicio["id_reserva"], $mensaje_admin);
    }
?>