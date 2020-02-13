<?php
	include 'wp-load.php';

    $cita_id = 114;

    $INFORMACION = [
        "KV_URL_IMGS"        => getTema().'/KMIVET/img',
        "URL"                => get_home_url(),
        "URL_CANCELAR"       => get_home_url().'/citas/cancelar/'.$cita_id,
        "NAME_VETERINARIO"      => "Medico Pruebas",
        "TELEFONOS_VETERINARIO" => "1234567898",
        "CORREO_VETERINARIO"    => "y.chaudary@kmimos.la",
        "NAME_CLIENTE"       => "Paciente Prueba",
        "TELEFONOS_CLIENTE"  => "9876543215",
        "CORREO_CLIENTE"     => "pacienteprueba1@gmail.com",
        "CONSULTA_ID"        => $cita_id,
        "TIPO_SERVICIO"      => 'CONSULTA A DOMICILIO',
        "FECHA"             => "12/02",
        "HORA"              => "10:50pm",
        "TIPO_PAGO"         => 'Pago por Tarjeta',
        "TOTAL"             => number_format(350, 2, ',', '.')
    ];

    $medicos = get_medics(
        'edcd1f3119674477b70b4304000ffa30',
        19.440658,
        -99.165991
    );

    $RECOMENDACIONES = ''; $cont = 0;
    foreach ($medicos["res"]->objects as $key => $medico) {
        $img = ( ( $medico->profilePic ) != "" ) ? $medico->profilePic : 'http://www.psi-software.com/wp-content/uploads/2015/07/silhouette-250x250.png';
        $RECOMENDACIONES .= buildEmailTemplate('KMIVET/partes/recomendacion', [
            'IMG' => $img,
            'NAME' => email_set_format_name( $medico->firstName.' '.$medico->lastName),
            'RATE' => email_set_format_ranking( getTema().'/KMIVET/img', $medico->rating),
            'PRECIO' => email_set_format_precio($medico->price)
        ]);
        $cont++;
        if( $cont > 2 ){
            break;
        }
    }

    $INFORMACION['RECOMENDACIONES'] = $RECOMENDACIONES;

    $cancelado_por = "cliente";

    $mensaje = kv_get_email_html(
        'KMIVET/reservas/'.$cancelado_por.'/cancelacion_cliente', 
        $INFORMACION
    );
    wp_mail('mary.garciag@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);

    $mensaje = kv_get_email_html(
        'KMIVET/reservas/'.$cancelado_por.'/cancelacion_veterinario', 
        $INFORMACION
    );
    wp_mail('mary.garciag@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);

    $mensaje = kv_get_email_html(
        'KMIVET/reservas/'.$cancelado_por.'/cancelacion_admin', 
        $INFORMACION
    );
    wp_mail('mary.garciag@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);




    $cancelado_por = "veterinario";

    $mensaje = kv_get_email_html(
        'KMIVET/reservas/'.$cancelado_por.'/cancelacion_cliente', 
        $INFORMACION
    );
    wp_mail('mary.garciag@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);

    $mensaje = kv_get_email_html(
        'KMIVET/reservas/'.$cancelado_por.'/cancelacion_veterinario', 
        $INFORMACION
    );
    wp_mail('mary.garciag@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);

    $mensaje = kv_get_email_html(
        'KMIVET/reservas/'.$cancelado_por.'/cancelacion_admin', 
        $INFORMACION
    );
    wp_mail('mary.garciag@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);




    $cancelado_por = "admin";

    $mensaje = kv_get_email_html(
        'KMIVET/reservas/'.$cancelado_por.'/cancelacion_cliente', 
        $INFORMACION
    );
    wp_mail('mary.garciag@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);

    $mensaje = kv_get_email_html(
        'KMIVET/reservas/'.$cancelado_por.'/cancelacion_veterinario', 
        $INFORMACION
    );
    wp_mail('mary.garciag@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);

    $mensaje = kv_get_email_html(
        'KMIVET/reservas/'.$cancelado_por.'/cancelacion_admin', 
        $INFORMACION
    );
    wp_mail('mary.garciag@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);

    /*
        $mensaje = kv_get_email_html(
            'KMIVET/reservas/nueva_cliente', 
            $INFORMACION
        );
        wp_mail('mary.garciag@gmail.com', 'Kmivet - Nueva Solicitud de Consulta', $mensaje);

        $mensaje = kv_get_email_html(
            'KMIVET/reservas/nueva_veterinario', 
            $INFORMACION
        );
        wp_mail('mary.garciag@gmail.com', 'Kmivet - Nueva Solicitud de Consulta', $mensaje);

        $mensaje = kv_get_email_html(
            'KMIVET/reservas/nueva_admin', 
            $INFORMACION
        );
        wp_mail('mary.garciag@gmail.com', 'Kmivet - Nueva Solicitud de Consulta', $mensaje);
    */
?>