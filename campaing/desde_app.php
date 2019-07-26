<?php
	
	extract($_POST);

	include dirname(__DIR__)."/wp-load.php";
	global $wpdb;
	$credenciales = $wpdb->get_var("SELECT data FROM campaing WHERE id = 1");

	$_registros = $wpdb->get_results("
		SELECT u.*, m.* 
		FROM wp_users AS u
		INNER JOIN wp_usermeta AS m ON ( u.ID = m.user_id )
		WHERE 
			m.meta_key LIKE '%device%'
		ORDER BY u.ID DESC ");

	$no_usar = [
		'ricardogonzor@gmail.com',
		'ainat1505003@gmail.com',
		'hectordgalindo28@gmail.com',
		'raulislasromero@gmail.com',
		'projectboracay2@gmail.com',
		'brenaricio@icloud.com',
		'dffitzg@gmail.com',
		'nancyib0504@gmail.com',
		'rxplus2018rxplus2018@gmail.com',
		'Johnny1545@Icloud.com',
		'yukith14@hotmail.com',
		'natssdiazz@gmail.com',
	];

	$registros = [];
	foreach ($_registros as $key => $value) {
		$re = get_user_meta($value->ID, 'registrado_desde', true);
		$suscrito = get_user_meta($value->ID, 'suscrito_singup', true);
		if( $suscrito != 'YES' && $re != 'pagina' && !in_array($value->user_email, $no_usar) ){
			$registros[$value->ID] = $value->user_email;
		}
	}

	/*	
	$credenciales = json_decode($credenciales);
	$lists = (array) $credenciales->lists;
	$lists['singup'] = 'bf1e0f3fe94e8d9c6d95e50ecd56df34';
	$credenciales->lists = $lists;
	$credenciales = json_encode($credenciales);
	$wpdb->query("UPDATE campaing SET data = '{$credenciales}' WHERE id = 1");
	$credenciales = json_decode($credenciales);
	echo "<pre>";
		print_r($registros);
		echo "<br>";
		print_r( ($credenciales) );
	echo "</pre>";
	exit();
	*/

	$list = 'singup';

	require_once __DIR__.'/campaing/csrest_campaigns.php';

	$credenciales = json_decode( $credenciales );

	$auth = (array) $credenciales->auth;
	$lists = (array) $credenciales->lists;

	echo "<pre>";
		print_r($registros);
		echo "<br>";
		print_r( $lists[ $list ] );
	echo "</pre>";

	if( isset($lists[ $list ]) ){
		require_once __DIR__.'/campaing/csrest_subscribers.php';
		$sc = new CS_REST_Subscribers($lists[ $list ], $auth);
		foreach ($registros as $user_id => $email) {
			$r = $sc->add([
				"EmailAddress" => $email,
			    "Resubscribe" => true,
			    "RestartSubscriptionBasedAutoresponders" => true,
			    "ConsentToTrack" => "Yes"
			]);
			update_user_meta($user_id, 'suscrito_singup', 'YES');
		}
	}

?>