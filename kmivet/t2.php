<?php
	include 'wp-load.php';

    $mensaje = kv_get_email_html(
        'KMIVET/veterinario/nuevo', 
        [
            "KV_URL_IMGS" => getTema().'/KMIVET/img',
            "URL"         => get_home_url(),
            "NAME"        => "ANALID LÓPEZ NAVARRETE",
            "EMAIL"       => "anahi.kmimos@gmail.com",
            "PASS"        => ''
        ]
    );

    $header = [
    	'BCC: a.veloz@kmimos.la',
    	'BCC: y.chaudary@kmimos.la',
    ];

    echo $mensaje;
?>