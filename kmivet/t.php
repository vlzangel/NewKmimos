<?php
	include 'wp-load.php';

	echo $mensaje = kv_get_email_html(
        'KMIVET/reservas/nueva_admin', 
        [
        	"KV_URL_IMGS" 		 => getTema().'/KMIVET/img',
        	"URL" 				 => get_home_url(),

        	"NAME_VETERINARIO" 	 	=> 'Pedro Perez',
        	"TELEFONOS_VETERINARIO" => '1234567',
        	"CORREO_VETERINARIO" 	=> 'angel@mail.com',

        	"CONSULTA_ID" 		 => '237921',

        	"TIPO_SERVICIO" 	 => 'CONSULTA A DOMICILIO',

        	"FECHA" 	 		=> '27/07',
        	"HORA" 	 			=> '03:00pm',
        	"TIPO_PAGO" 		=> 'Pago por Tarjeta',
        	"TOTAL" 			=> '1.800,00',

        	"NAME_CLIENTE" 		 => 'Angel Veloz',
        	"TELEFONOS_CLIENTE" => '1234567',
        	"CORREO_CLIENTE" 	=> 'angel@mail.com',
        ]
    );
?>