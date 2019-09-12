<?php
	
	include dirname(dirname(__DIR__)).'/wp-load.php';
    date_default_timezone_set('America/Mexico_City');
	global $wpdb;

	$campaings = $wpdb->get_row("SELECT * FROM vlz_campaing WHERE id = ".$_GET['id']);
	$d = json_decode($campaings->data);

	switch ( $_GET['tipo'] ) {
		case 'mensaje':
			
		break;
	}
	
?>