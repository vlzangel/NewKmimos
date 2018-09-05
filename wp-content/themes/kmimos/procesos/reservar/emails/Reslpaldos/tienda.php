<?php
    
    $inmediata = "";

    if( $confirmacion_titulo == "Confirmación de Reserva" ){

	   /* Correo Cliente */

        $instrucciones = $PATH_TEMPLATE.'/template/mail/reservar/partes/instrucciones.php';
        $instrucciones = file_get_contents($instrucciones);

        $cuidador_file = $PATH_TEMPLATE.'/template/mail/reservar/cliente/nueva_tienda.php';
        $mensaje_cliente = file_get_contents($cuidador_file);

        $fin = strtotime( str_replace("/", "-", $_POST['service_end']) );


        $mensaje_cliente = str_replace('[HEADER]', "reservaTienda", $mensaje_cliente);

        $mensaje_cliente = str_replace('[SERVICIOS]', $servicios_plantilla, $mensaje_cliente);

        $mensaje_cliente = str_replace('[mascotas]', $mascotas, $mensaje_cliente);
        $mensaje_cliente = str_replace('[desglose]', $desglose, $mensaje_cliente);

        /* Datos Instrucciones */

            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $vence = strtotime( $servicio["vence"]);

            $fecha = date('d', $vence)." de ".$meses[date('n', $vence)-1]. " ".date('Y', $vence) ;
            $hora = "(".date('H:i A', $vence).")";
        
            $mensaje_cliente = str_replace('[INSTRUCCIONES]', $instrucciones, $mensaje_cliente);

            $mensaje_cliente = str_replace('[CODIGO]', end( explode("/", $servicio["pdf"]) ), $mensaje_cliente);
            $mensaje_cliente = str_replace('[MONTO]', $MONTO, $mensaje_cliente);
            $mensaje_cliente = str_replace('[FECHA]', $fecha, $mensaje_cliente);
            $mensaje_cliente = str_replace('[HORA]', $hora, $mensaje_cliente);

        /* Datos Instrucciones */

        $mensaje_cliente = str_replace('[TOTALES]', str_replace('[REEMBOLSAR]', "", $totales_plantilla), $mensaje_cliente);

        $mensaje_cliente = str_replace('[MODIFICACION]', $modificacion, $mensaje_cliente);
        
        $mensaje_cliente = str_replace('[ADICIONALES]', $adicionales, $mensaje_cliente);
        $mensaje_cliente = str_replace('[TRANSPORTE]', $transporte, $mensaje_cliente);
        
        $mensaje_cliente = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cliente);

        $mensaje_cliente = str_replace('[tipo_servicio]', trim($servicio["tipo"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[DETALLES_SERVICIO]', $detalles_plantilla, $mensaje_cliente);

        $mensaje_cliente = str_replace('[PDF]', $servicio["pdf"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[name_cliente]', $cliente["nombre"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[name_cuidador]', $cuidador["nombre"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[avatar]', kmimos_get_foto($cuidador["id"]), $mensaje_cliente);
        $mensaje_cliente = str_replace('[telefonos_cuidador]', $cuidador["telefono"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[correo_cuidador]', $cuidador["email"], $mensaje_cliente);
        $mensaje_cliente = str_replace('[direccion_cuidador]', $cuidador["direccion"], $mensaje_cliente);

        $mensaje_cliente = str_replace('[TOTALES]', str_replace('[REEMBOLSAR]', "", $totales_plantilla), $mensaje_cliente);

        $mensaje_cliente = get_email_html($mensaje_cliente, true, true, $cliente["id"], false, true);

        if( isset($NO_ENVIAR) ){
            echo $mensaje_cliente;
        }else{
            wp_mail( $cliente["email"], "Solicitud de reserva", $mensaje_cliente);
        }

    }else{
        $inmediata = "Inmediata";
    }


        /* Administrador */

		$admin_file = $PATH_TEMPLATE.'/template/mail/reservar/admin/nueva_tienda.php';
        $mensaje_admin = file_get_contents($admin_file);

        /* Generales */

            $datos_cuidador = $PATH_TEMPLATE.'/template/mail/reservar/partes/datos_cuidador.php';
            $datos_cuidador = file_get_contents($datos_cuidador);

            $datos_cliente = $PATH_TEMPLATE.'/template/mail/reservar/partes/datos_cliente.php';
            $datos_cliente = file_get_contents($datos_cliente);
    
            $mensaje_admin = str_replace('[DATOS_CLIENTE]', $datos_cliente, $mensaje_admin);
            $mensaje_admin = str_replace('[DATOS_CUIDADOR]', $datos_cuidador, $mensaje_admin);

            $mensaje_admin = str_replace('[MASCOTAS]', $mascotas, $mensaje_admin);

            if( $servicio["desglose"]["reembolsar"]+0 > 0 ){
                $descuento_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/reembolsar.php';
                $descuento_plantilla = file_get_contents($descuento_plantilla);
                $descuento_plantilla = str_replace('[DEVOLVER]', number_format( $servicio["desglose"]["reembolsar"], 2, ',', '.'), $descuento_plantilla);
                $totales_plantilla = str_replace('[REEMBOLSAR]', $descuento_plantilla, $totales_plantilla);
            }else{
                $totales_plantilla = str_replace('[REEMBOLSAR]', "", $totales_plantilla);
            }

            $mensaje_admin = str_replace('[SERVICIOS]', $servicios_plantilla, $mensaje_admin);

            $mensaje_admin = str_replace('[HEADER]', "reservaTienda", $mensaje_admin);

            $mensaje_admin = str_replace('[DESGLOSE]', $desglose, $mensaje_admin);
            
            $mensaje_admin = str_replace('[ADICIONALES]', $adicionales, $mensaje_admin);
            $mensaje_admin = str_replace('[TRANSPORTE]', $transporte, $mensaje_admin);

            $mensaje_admin = str_replace('[MODIFICACION]', $modificacion, $mensaje_admin);

            $mensaje_admin = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_admin);

            $mensaje_admin = str_replace('[tipo_servicio]', trim($servicio["tipo"]), $mensaje_admin);
            $mensaje_admin = str_replace('[id_reserva]', $servicio["id_reserva"], $mensaje_admin);

            $mensaje_admin = str_replace('[DETALLES_SERVICIO]', $detalles_plantilla, $mensaje_admin);
            
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

		$mensaje_admin = get_email_html($mensaje_admin, true, true, $cliente["id"], false, true);

        if( isset($NO_ENVIAR) ){
            echo $mensaje_admin;
        }else{
            kmimos_mails_administradores_new("Solicitud de reserva ".$inmediata." #".$servicio["id_reserva"], $mensaje_admin);
        }
        
?>