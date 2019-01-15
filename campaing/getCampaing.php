<?php
 
	include dirname(__DIR__)."/wp-load.php";
	global $wpdb;

	$credenciales = $wpdb->get_var("SELECT data FROM campaing WHERE id = 1");

	require_once __DIR__.'/campaing/csrest_clients.php';
 
	$credenciales = json_decode($credenciales);
	$data = array(
		'auth' => (array) $credenciales->auth,
		'lists' => (array) $credenciales->lists
	);

	$ID = "b7b62759c43ff163d0adb4872caeb3fe";

  	$campaing = new CS_REST_Clients( $ID, $data['auth'] );

	$response = $campaing->get_campaigns();
 
	print_r( json_encode($response->response));
 

