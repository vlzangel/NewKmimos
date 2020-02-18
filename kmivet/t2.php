<?php
	include 'wp-load.php';

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

    $INFORMACION["AVATAR_URL"] = "http://localhost/kmimos/kmivet/wp-content/themes/kmimos/images/image.png";
    $INFORMACION["DIAGNOSTICO"] = "Lorem ipsum es el texto que se usa habitualmente en diseño gráfico en demostraciones de tipografías o de borradores de diseño para probar el diseño visual antes de insertar el texto final.";
    $INFORMACION["DIAGNOSTICO_NOTA"] = "Lorem ipsum es el texto que se usa habitualmente en diseño gráfico en demostraciones de tipografías o de borradores de diseño para probar el diseño visual antes de insertar el texto final.";
    $INFORMACION["TRATAMIENTO"] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at erat ornare, euismod neque id, gravida metus. Vivamus accumsan quam eget fermentum lacinia. Aenean porttitor scelerisque arcu id scelerisque.";

    echo $mensaje = kv_get_email_html(
        'KMIVET/reservas/confirmacion_cliente', 
        $INFORMACION
    );

/*    $cita_id = '78c9689f6d4f412b851f56e50fcfb53b';

    $appointment = get_appointment($cita_id);*/

    // $INFORMACION["AVATAR_URL"] = kmimos_get_foto($veterinario->user_id);
/*    $INFORMACION["DIAGNOSTICO"] = $appointment['result']->diagnostic->diagnostic->title;
    $INFORMACION["DIAGNOSTICO_NOTA"] = $appointment['result']->diagnostic->notes;
    $INFORMACION["TRATAMIENTO"] = $appointment['result']->treatment;


    echo '<pre>';
        print_r( $INFORMACION );
        print_r( $appointment );
    echo '</pre>';*/



    /*
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

    $INFORMACION["AVATAR_URL"] = "http://localhost/kmimos/kmivet/wp-content/themes/kmimos/images/image.png";
    $INFORMACION["DIAGNOSTICO"] = "Lorem ipsum es el texto que se usa habitualmente en diseño gráfico en demostraciones de tipografías o de borradores de diseño para probar el diseño visual antes de insertar el texto final.";
    $INFORMACION["TRATAMIENTO"] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at erat ornare, euismod neque id, gravida metus. Vivamus accumsan quam eget fermentum lacinia. Aenean porttitor scelerisque arcu id scelerisque.";

    echo $mensaje = kv_get_email_html(
        'KMIVET/reservas/confirmacion_cliente', 
        $INFORMACION
    );
    // wp_mail('mary.garciag@gmail.com', 'Kmivet - Consulta Cancelada', $mensaje);

    /*
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
    

    */


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