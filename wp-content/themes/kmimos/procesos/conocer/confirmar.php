<?php

    $wpdb->query("UPDATE wp_postmeta SET meta_value = '2' WHERE post_id = $id_orden AND meta_key = 'request_status';");
    $wpdb->query("UPDATE wp_posts SET post_status = 'publish' WHERE ID = '{$id_orden}';");

    $INFORMACION["HEADER"] = "conocer";

    /* Correo CLIENTE */

        $mensaje = buildEmailTemplate(
            'conocer/cliente/confirmar', 
            $INFORMACION
        );

        $mensaje = buildEmailHtml(
            $mensaje, 
            [
                'user_id' => null, 
                'dudas' => false,
                'beneficios' => false, 
                'test' => true
            ]
        );

        if( isset($NO_ENVIAR) ){
            showEmail( $mensaje );
            // sendEmailTest( "Confirmación de Solicitud para Conocer Cuidador - CLIENTE", $mensaje );
        }else{
            wp_mail( $email_cliente, "Confirmación de Solicitud para Conocer Cuidador", $mensaje);
        }

    /* Correo CUIDADOR */

        $mensaje = buildEmailTemplate(
            'conocer/cuidador/confirmar', 
            $INFORMACION
        );

        $mensaje = buildEmailHtml(
            $mensaje, 
            [
                'user_id' => null, 
                'dudas' => false,
                'beneficios' => false, 
                'test' => true
            ]
        );

        if( isset($NO_ENVIAR) ){
            showEmail( $mensaje );
            // sendEmailTest( "Confirmación de Solicitud para Conocer Cuidador - CUIDADOR", $mensaje );
        }else{
            wp_mail( $email_cuidador, "Confirmación de Solicitud para Conocer Cuidador", $mensaje);
        }


    /* Correo CUIDADOR */

        $mensaje = buildEmailTemplate(
            'conocer/admin/confirmar', 
            $INFORMACION
        );

        $mensaje = buildEmailHtml(
            $mensaje, 
            [
                'user_id' => null, 
                'dudas' => false,
                'beneficios' => false, 
                'test' => true
            ]
        );

        if( isset($NO_ENVIAR) ){
            showEmail( $mensaje );
            // sendEmailTest( "Confirmación de Solicitud para Conocer a ".$cuidador_name." - CUIDADOR", $mensaje );
        }else{
            kmimos_mails_administradores_new("Confirmación de Solicitud para Conocer a ".$cuidador_name, $mensaje);
        }

    setSessionCode();

    $CONTENIDO .= "<div class='msg_acciones'>
        <strong>¡Todo esta listo!</strong><br>
        La solicitud para conocer cuidador <strong>#".$id_orden."</strong>, ha sido confirmada exitosamente de acuerdo a tu petición.
    </div>";