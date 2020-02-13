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

    // EMAIL al CLIENTE //
        $mensaje = kv_get_email_html(
            'KMIVET/reservas/nueva_cliente', 
            $INFORMACION
        );
        wp_mail('mary.garciag@gmail.com', 'Kmivet - Nueva Solicitud de Consulta', $mensaje);


    // EMAIL al CUIDADOR //
        $mensaje = kv_get_email_html(
            'KMIVET/reservas/nueva_veterinario', 
            $INFORMACION
        );
        wp_mail('mary.garciag@gmail.com', 'Kmivet - Nueva Solicitud de Consulta', $mensaje);

    // EMAIL al ADMINISTRADOR //
        $mensaje = kv_get_email_html(
            'KMIVET/reservas/nueva_admin', 
            $INFORMACION
        );
        $header = kv_get_emails_admin();
        wp_mail('mary.garciag@gmail.com', 'Kmivet - Nueva Solicitud de Consulta', $mensaje, $header);

    echo $mensaje;
?>