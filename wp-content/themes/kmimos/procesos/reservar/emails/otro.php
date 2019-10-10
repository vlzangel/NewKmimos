<?php

    $INFORMACION["HEADER"] = "reserva";

    $inmediata = "";

    if( $confirmacion_titulo == "Confirmación de Reserva Inmediata" ){
    }else{
        $inmediata = "Inmediata";
    }

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
                showEmail( $mensaje );
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
                showEmail( $mensaje );
                // sendEmailTest( 'Nueva Reserva - '.$servicio["tipo"].' por: '.$cliente["nombre"]." - CUIDADOR", $mensaje );
            }else{
                wp_mail( $cuidador["email"], 'Nueva Reserva - '.$servicio["tipo"].' por: '.$cliente["nombre"], $mensaje);
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