<?php

   /* Correo CLIENTE */

        $mensaje = buildEmailTemplate(
            'conocer/cliente/pendiente_tienda', 
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
        }else{
            // wp_mail( $email_cliente, "Pago de Solicitudes", $mensaje);
        }

    /* Correo ADMINISTRADOR */

        $mensaje = buildEmailTemplate(
            'conocer/admin/pendiente_tienda', 
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
        }else{
            // kmimos_mails_administradores_new('Pago de Solicitudes por: '.$nombre_cliente, $mensaje);
        }

        // setSessionCode(); 
?>