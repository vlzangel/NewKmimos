<?php

    $datos_cliente = getTemplate("reservar/partes/datos_cliente");
    $datos_cuidador = getTemplate("reservar/partes/datos_cuidador");

    $INFORMACION = [
        // GENERALES

            'HEADER'                => "reserva",
            'ID_RESERVA'            => $servicio["id_reserva"],
            'SERVICIOS'             => $servicios_plantilla,
            'MASCOTAS'              => $mascotas,
            'DESGLOSE'              => $desglose,
            'ADICIONALES'           => $adicionales,
            'TRANSPORTE'            => $transporte,
            'MODIFICACION'          => $modificacion,
            'TIPO_SERVICIO'         => trim($servicio["tipo"]),
            'DETALLES_SERVICIO'     => $detalles_plantilla,
            'TOTALES'               => str_replace('[REEMBOLSAR]', "", $totales_plantilla),

            'ACEPTAR'               => $servicio["aceptar_rechazar"]["aceptar"],
            'RECHAZAR'              => $servicio["aceptar_rechazar"]["cancelar"],

        // CLIENTE
            'DATOS_CLIENTE'         => $datos_cliente,
            'NAME_CLIENTE'          => $cliente["nombre"],
            'AVATAR_CLIENTE'        => kmimos_get_foto($cliente["id"]),
            'TELEFONOS_CLIENTE'     => $cliente["telefono"],
            'CORREO_CLIENTE'        => $cliente["email"],
            
        // CUIDADOR
            'DATOS_CUIDADOR'        => $datos_cuidador,
            'NAME_CUIDADOR'         => $cuidador["nombre"],
            'AVATAR_CUIDADOR'       => kmimos_get_foto($cuidador["id"]),
            'TELEFONOS_CUIDADOR'    => $cuidador["telefono"],
            'CORREO_CUIDADOR'       => $cuidador["email"],
            'DIRECCION_CUIDADOR'    => $cuidador["direccion"],
    ];
    
    $inmediata = "";

    if( $confirmacion_titulo == "Confirmación de Reserva" ){

	   /* Correo CLIENTE */

            $mensaje = buildEmailTemplate(
                'reservar/cliente/nueva', 
                $INFORMACION
            );

    		$mensaje = buildEmailHtml(
                $mensaje, 
                [
                    'user_id' => $cliente["id"], 
                    'barras_ayuda' => true,
                    'test' => true
                ]
            );

            if( isset($NO_ENVIAR) ){
                // showEmail( $mensaje );
                // sendEmailTest( "Solicitud de reserva - CLIENTE", $mensaje );
            }else{
                wp_mail( $cliente["email"], "Solicitud de reserva", $mensaje);
            }

    	/* Correo CUIDADOR */

            $mensaje = buildEmailTemplate(
                'reservar/cuidador/nueva', 
                $INFORMACION
            );

            $mensaje = buildEmailHtml(
                $mensaje, 
                [
                    'user_id' => $cliente["id"], 
                    'dudas' => false,
                    'test' => true
                ]
            );

            if( isset($NO_ENVIAR) ){
                // showEmail( $mensaje );
                // sendEmailTest( 'Nueva Reserva - '.$servicio["tipo"].' por: '.$cliente["nombre"]." - CUIDADOR", $mensaje );
            }else{
                wp_mail( $cuidador["email"], 'Nueva Reserva - '.$servicio["tipo"].' por: '.$cliente["nombre"], $mensaje);
            }

    }else{
        $totales_plantilla = str_replace('[REEMBOLSAR]', $reembolsar_plantilla, $totales_plantilla);
        $inmediata = "Inmediata";
    }

    /* Correo ADMINISTRADOR */

        if( $inmediata == "Inmediata" ){
            $INFORMACION['HEADER'] = "reservaInmediata";
            $INFORMACION['ID_RESERVA']= "Código de reserva #".$servicio["id_reserva"];
        }else{
            $INFORMACION['ID_RESERVA']= "Reserva #: ".$servicio["id_reserva"];
        }

        $mensaje = buildEmailTemplate(
            'reservar/admin/nueva', 
            $INFORMACION
        );

        $mensaje = buildEmailHtml(
            $mensaje, 
            [
                'user_id' => $cliente["id"], 
                'dudas' => false,
                'test' => true
            ]
        );

        if( isset($NO_ENVIAR) ){
            showEmail( $mensaje );
            sendEmailTest( 'Nueva Reserva '.$inmediata.' - '.$servicio["tipo"].' por: '.$cliente["nombre"]." - ADMINISTRADOR", $mensaje );
        }else{
            kmimos_mails_administradores_new('Nueva Reserva '.$inmediata.' - '.$servicio["tipo"].' por: '.$cliente["nombre"], $mensaje);
        }

        setSessionCode(); 
?>