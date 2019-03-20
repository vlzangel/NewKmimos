<?php
	include '../wp-load.php';

	global $wpdb;

	$hoy = time();

	$fecha = date('Y-m-d H:i:s');
	// $time = strtotime ( '-3 week' , strtotime ( $fecha ) ) ;
	$time = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
	$time = date('Y-m-d H:i:s', $time);

	$SQL = "
		SELECT 
			*
		FROM 
			wp_kmimos_subscribe
		WHERE
			`time` >= '{$time}'
	";

	$suscritos = $wpdb->get_results($SQL);

	$suscritos_array = [];

	foreach ($suscritos as $key => $info) {
		$suscritos_array[ $info->source ][] = $info->email;
	}

	$credenciales = $wpdb->get_var("SELECT data FROM campaing WHERE id = 1");
	require_once __DIR__.'/campaing/csrest_campaigns.php';
	$credenciales = json_decode( $credenciales );

	$auth = (array) $credenciales->auth;
	$lists = (array) $credenciales->lists;

	require_once __DIR__.'/campaing/csrest_subscribers.php';

	foreach ($suscritos_array as $key => $correos) {
		$_list = '';
		switch ($key) {
			case 'petco':
				$_list = 'petco_popup';
			break;
			case 'home':
				$_list = 'newsletter_home';
			break;
			case 'blog':
				$_list = 'newsletter_home';
			break;
		}

		if( $_list != '' ){
			$list = new CS_REST_Subscribers($lists[$_list], $auth);

			foreach ($correos as $key_2 => $correo) {
				$r = $list->add([
					"EmailAddress" => $correo,
				    "Resubscribe" => true,
				    "RestartSubscriptionBasedAutoresponders" => true,
				    "ConsentToTrack" => "Yes"
				]);
			}
		}
	}

	echo "Proceso completado!";

?>