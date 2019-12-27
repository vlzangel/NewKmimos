<?php
	include dirname(__DIR__).'/wp-load.php';

	$mensaje = buildEmailTemplate(
        'KMIVET/reservas/nueva_cuidador', 
        [
        	"KV_URL_IMGS"   		=> getTema().'/KMIVET/img',
        	"CONSULTA_ID"   		=> 120,
        	"EMAIL" 				=> 'a.veloz@kmimos.la',
        	"NOMBRE_VETERINARIO"	=> 'angel veloz',

        	"NOMBRE_CLIENTE" 		=> 'angel veloz',
        	"TELEFONOS_CLIENTE" 	=> '1234567985',
        	"CORREO_CLIENTE" 		=> 'correo@mail.com',


        	"FECHA" 				=> '27/12/2019 18:30',
        	"PRECIO" 				=> 250,


        	"URL" 					=> get_home_url().'/kmivet/',
        ]
    );

	echo kv_get_email_html($mensaje);
?>