<?php
 
	include dirname(__DIR__)."/wp-load.php";
	global $wpdb;
	$credenciales = $wpdb->get_var("SELECT data FROM campaing WHERE id = 1");

	require_once __DIR__.'/campaing/csrest_campaigns.php';
 
	$credenciales = json_decode($credenciales);
	$data = array(
		'auth' => (array) $credenciales->auth,
		'lists' => (array) $credenciales->lists
	);
 	
 	extract($_GET);

  	$campaing = new CS_REST_Campaigns( $campaing_id, $data['auth'] );

	$response = $campaing->get_recipients();
	$total_recipients = $response->response->TotalNumberOfRecords;

	if( isset($echo) ){
		print_r( json_encode(['total_recipients'=>$total_recipients]) );
	}

	// Crear campana - return ID 
	// $r = $campaing->create('b7b62759c43ff163d0adb4872caeb3fe',array(
	// 	'Subject' => 'Prueba',
	// 	'Name' => 'test2 20190101 prueba',
	// 	'FromName' => 'prueba',
	// 	'FromEmail' => 'i.cocchini@kmimos.la',
	// 	'ReplyTo' => 'i.cocchini@kmimos.la',
	// 	'HtmlUrl' => 'https://www.kmimos.com.mx/prueba/',
	// 	'ListIDs' => ['7a5765cdc7ad7eca336994bd06245475'],
	// ));
	// print_r($r);