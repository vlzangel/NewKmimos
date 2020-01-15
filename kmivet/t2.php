<?php
	include 'wp-load.php';

	echo kv_get_email_html(
        'KMIVET/reservas/nueva_veterinario', 
        [
            "KV_URL_IMGS"        => getTema().'/KMIVET/img',
            "URL"                => get_home_url(),

            "CONSULTA_ID"        => 39,

            "NAME_VETERINARIO"   => "Angel Veloz",

            "NAME_CLIENTE"       => "Pedro Perez",
            "TELEFONOS_CLIENTE"  => "1234567898",
            "CORREO_CLIENTE"     => "pedro@mail.com",

            "TIPO_SERVICIO"      => 'CONSULTA A DOMICILIO',
            "FECHA"             => "27/01/2020",
            "HORA"              => "01:30pm",
            "TIPO_PAGO"         => 'Pago por Tarjeta',
            "TOTAL"             => number_format(350, 2, ',', '.'),
        ]
    );
?>