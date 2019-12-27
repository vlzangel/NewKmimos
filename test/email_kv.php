<?php
	include dirname(__DIR__).'/wp-load.php';

	$mensaje = buildEmailTemplate(
        'KMIVET/medico/registro', 
        [
        	"EMAIL" => 'a.veloz@kmimos.la',
        	"NOMBRE" => 'Angel Veloz',
        	"CLAVE" => 'sdfs5f1',
        	"URL" => get_home_url().'/kmivet/',
        ]
    );

	echo kv_get_email_html($mensaje);
?>