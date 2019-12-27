<?php
	include dirname(__DIR__).'/wp-load.php';

	$mensaje = buildEmailTemplate(
        'KMIVET/reservas/nueva', 
        [
        	"KV_URL_IMGS"   		=> getTema().'/KMIVET/img',
        	"CONSULTA_ID"   		=> 120,
        	"EMAIL" 				=> 'a.veloz@kmimos.la',
        	"NOMBRE_CLIENTE" 		=> 'angel veloz',

        	"AVATAR_VETERINARIO" 	=> getTema().'/KMIVET/img/noimg.png',
        	"NOMBRE_VETERINARIO" 	=> 'angel veloz',
        	"TELEFONOS_CUIDADOR" 	=> '1234567985',
        	"CORREO_CUIDADOR" 		=> 'correo@mail.com',


        	"FECHA" 				=> '27/12/2019 18:30',
        	"PRECIO" 				=> 250,


        	"URL" 					=> get_home_url().'/kmivet/',
        ]
    );

	echo kv_get_email_html($mensaje);
?>