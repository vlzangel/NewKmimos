<?php
	include 'wp-load.php';

	echo $mensaje = kv_get_email_html(
        'KMIVET/cliente/nuevo', 
        [
        	"KV_URL_IMGS" 	=> getTema().'/KMIVET/img',
        	"URL" 			=> get_home_url(),
        	"NAME" 			=> 'Angel Veloz',
        	"EMAIL" 		=> 'angel@mail.com',
        	"PASS" 			=> 'Clave',
        ]
    );
?>