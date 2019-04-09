<?php

    $imgHeader = "reservas";
    if( $confirmacion_titulo == "Confirmación de Reserva Inmediata" ){
        $imgHeader = "reservaInmediata";
    }

    $INFORMACION["HEADER"] = $imgHeader;

    if( !isset($NO_ENVIAR) || $superAdmin == "YES" ){
        kmimos_registros_fotos( $servicio["id_reserva"] );
    }

    if( $superAdmin != "YES" ){
        update_post_meta($servicio["id_reserva"], 'confirmado_por', $_GET["u"]);
    }else{
        update_post_meta($servicio["id_reserva"], 'confirmado_por', $_GET["u"]."_super_admin");
    }

   /* Correo CLIENTE */

        $mensaje = buildEmailTemplate(
            'reservar/cliente/confirmacion', 
            $INFORMACION
        );

        $mensaje = buildEmailHtml(
            $mensaje, 
            [
                'user_id' => $cliente["id"], 
                'test' => true
            ]
        );

        if( isset($NO_ENVIAR) ){
            showEmail( $mensaje );
            // sendEmailTest( $confirmacion_titulo." - CLIENTE", $mensaje );
        }else{
            wp_mail( $cliente["email"], $confirmacion_titulo, $mensaje);
        }
    
    /* Correo CUIDADOR */

        $mensaje = buildEmailTemplate(
            'reservar/cuidador/confirmacion', 
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
            // sendEmailTest( $confirmacion_titulo." - CUIDADOR", $mensaje );
        }else{
            wp_mail( $cuidador["email"], $confirmacion_titulo, $mensaje);
        }

    /* Correo ADMINISTRADOR */

        $mensaje = buildEmailTemplate(
            'reservar/admin/confirmacion', 
            $INFORMACION
        );

        $mensaje = buildEmailHtml(
            $mensaje, 
            [
                'user_id' => $cliente["id"], 
                'test' => true
            ]
        );

        if( isset($NO_ENVIAR) ){
            showEmail( $mensaje );
            // sendEmailTest( $confirmacion_titulo." - ADMINISTRADOR", $mensaje );
        }else{
            kmimos_mails_administradores_new($confirmacion_titulo, $mensaje);
        }

        if( $superAdmin == "" ){
            $CONTENIDO .= "<div class='msg_acciones'>
                <strong>¡Todo esta listo!</strong><br>
                La reserva #".$servicio["id_reserva"].", ha sido confirmada exitosamente de acuerdo a tu petición.
            </div>";
        }

    setSessionCode();
?>