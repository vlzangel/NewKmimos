<?php
	extract($_POST);
	include dirname(__DIR__)."/wp-load.php";
	global $wpdb;
	$credenciales = $wpdb->get_var("SELECT data FROM campaing WHERE id = 1");
	require_once __DIR__.'/campaing/csrest_campaigns.php';
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