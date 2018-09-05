<?php

    $INFORMACION["HEADER"] = "conocer";
    $INFORMACION["DATOS_CUIDADOR"] = preg_replace("#<tr class='dir'(.*?)tr>#s", "", $INFORMACION["DATOS_CUIDADOR"]);

    /* Correo CUIDADOR */

        $mensaje = buildEmailTemplate(
            'conocer/cuidador/nueva', 
            $INFORMACION
        );

        $mensaje = buildEmailHtml(
            $mensaje, 
            [
                'user_id' => null, 
                'dudas' => false,
                'test' => true
            ]
        );

        if( isset($NO_ENVIAR) ){
            showEmail( $mensaje );
            // sendEmailTest( $asunto." - CUIDADOR", $mensaje );
        }else{
            wp_mail( $email_cuidador, $asunto, $mensaje);
        }

    /* Correo CLIENTE */

        $mensaje = buildEmailTemplate(
            'conocer/cliente/nueva', 
            $INFORMACION
        );

        $mensaje = buildEmailHtml(
            $mensaje, 
            [
                'user_id' => null, 
                'dudas' => false,
                'test' => true
            ]
        );

        if( isset($NO_ENVIAR) ){
            showEmail( $mensaje );
            // sendEmailTest( $asunto." - CLIENTE", $mensaje );
        }else{
            wp_mail( $email_cliente, $asunto, $mensaje);
        }

    /* Correo ADMINISTRADOR */

        $mensaje = buildEmailTemplate(
            'conocer/admin/nueva', 
            $INFORMACION
        );

        $mensaje = buildEmailHtml(
            $mensaje, 
            [
                'user_id' => null, 
                'dudas' => false,
                'test' => true
            ]
        );

        if( isset($NO_ENVIAR) ){
            showEmail( $mensaje );
            // sendEmailTest( $asunto." - ADMINISTRADOR", $mensaje );
        }else{
            kmimos_mails_administradores_new($asunto, $mensaje);
        }


    setSessionCode();

    $data = array(
        'n_solicitud' => $request_id,
        'nombre' => $nombre_cuidador,
        'telefono' => $telf_cuidador,
        'email' => $email_cuidador,
        'error' => ''
    );

    echo json_encode($data);


