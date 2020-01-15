<?php
	include 'wp-load.php';

    $cita_id = 39;

    $INFORMACION = [
        "KV_URL_IMGS"        => getTema().'/KMIVET/img',
        "URL"                => get_home_url(),
        "URL_CANCELAR"       => get_home_url().'/cita/cancelar/mail/'.$cita_id,
        "NAME_VETERINARIO"      => "Angel Veloz",
        "TELEFONOS_VETERINARIO" => "1234567898",
        "CORREO_VETERINARIO"    => "angel@mail.com",
        "NAME_CLIENTE"       => "Pedro Perez",
        "TELEFONOS_CLIENTE"  => "1234567898",
        "CORREO_CLIENTE"     => "pedro@mail.com",
        "CONSULTA_ID"        => $cita_id,
        "TIPO_SERVICIO"      => 'CONSULTA A DOMICILIO',
        "FECHA"             => "27/01/2020",
        "HORA"              => "01:30pm",
        "TIPO_PAGO"         => 'Pago por Tarjeta',
        "TOTAL"             => number_format(350, 2, ',', '.'),
    ];

    echo kv_get_email_html(
        'KMIVET/reservas/nueva_cliente', 
        $INFORMACION
    );

    echo kv_get_email_html(
        'KMIVET/reservas/nueva_veterinario', 
        $INFORMACION
    );

    echo kv_get_email_html(
        'KMIVET/reservas/nueva_admin', 
        $INFORMACION
    );
?>