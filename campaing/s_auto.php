<?php

	include dirname(__DIR__)."/wp-load.php";

	global $wpdb;

	$SQL = "
		SELECT 
			usuarios.user_email
		FROM 
			{$wpdb->prefix}users AS usuarios
        LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=usuarios.ID AND usermeta.meta_key='_wlabel')
        LEFT JOIN wp_usermeta AS usermeta_2 ON (usermeta_2.user_id=usuarios.ID AND usermeta_2.meta_key='user_referred')
		WHERE
			( usermeta.meta_value LIKE '%petco%' OR usermeta_2.meta_value LIKE '%petco%' ) AND
			usuarios.user_registered >= '2018-09-01 00:00:00'
		GROUP BY usuarios.ID DESC";
	$registros = $wpdb->get_results($SQL);

	require_once __DIR__.'/campaing/csrest_campaigns.php';
	$credenciales = $wpdb->get_var("SELECT data FROM campaing WHERE id = 1");
	$credenciales = json_decode( $credenciales );
	$auth = (array) $credenciales->auth;
	$lists = (array) $credenciales->lists;
	
	require_once __DIR__.'/campaing/csrest_subscribers.php';

	$list = "petco_registro";

	foreach ($registros as $key => $registro) {
		$email = $registro->user_email;
		if( isset($lists[ $list ]) ){
			$sc = new CS_REST_Subscribers($lists[ $list ], $auth);
			$r = $sc->add([
				"EmailAddress" => $email,
			    "Resubscribe" => true,
			    "RestartSubscriptionBasedAutoresponders" => true,
			    "ConsentToTrack" => "Yes"
			]);
		}
	}
?>