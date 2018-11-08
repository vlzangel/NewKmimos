<?php
	
	extract($_GET);

	include dirname(__DIR__)."/wp-load.php";
	global $wpdb;
	$credenciales = $wpdb->get_var("SELECT data FROM campaing WHERE id = 1");

	require_once __DIR__.'/campaing/csrest_campaigns.php';

/*	$data = array(
		'auth' => array(
			'access_token' => "AUTr8b+NQpBPowMkSjArXeExNQ==",
			'refresh_token' => "AXsKGf+zs59Nq3zXbsnVx2ExNQ=="
		),
		'lists' => array(
			'petco_popup' => '4c6ef95e717057c865845737d91be72d',
			'newsletter_home' => 'aabaca4317656fa19cf4c36e6bbf3597',
		)
	);

	echo json_encode($data);*/

	$credenciales = json_decode( $credenciales );

	$auth = (array) $credenciales->auth;
	$lists = (array) $credenciales->lists;

	if( isset($lists[ $list ]) ){
		require_once __DIR__.'/campaing/csrest_subscribers.php';
		$sc = new CS_REST_Subscribers($lists[ $list ], $auth);
		$r = $sc->add([
			"EmailAddress" => $email,
		    "Resubscribe" => true,
		    "RestartSubscriptionBasedAutoresponders" => true,
		    "ConsentToTrack" => "Yes"
		]);
	}
?>