<?php
	include dirname(__DIR__)."/wp-load.php";

    date_default_timezone_set('America/Mexico_City');

	extract($_GET);

	global $wpdb;

	/*
	$sql = "
		SELECT * 
		FROM `wp_users` 
		WHERE `user_registered` < '2019-07-18 00:00:00'
	";
	$_usuarios = $wpdb->get_results($sql);
	$usuarios = [];
	foreach ($_usuarios as $usuario) {
		$usuarios[] = $usuario->ID;
		update_user_meta($usuario->ID, 'registrado_desde', 'pagina');
	}
	*/

	/*
	$sql = "
		SELECT * 
		FROM `wp_users` 
		WHERE `user_registered` >= '2019-07-18 00:00:00'
	";
	$_usuarios = $wpdb->get_results($sql);
	foreach ($_usuarios as $usuario) {
		$user_id = $usuario->ID;
		$device_type = get_user_meta($user_id, 'device_type', true);
		if( empty($device_type) ){
			update_user_meta($usuario->ID, 'registrado_desde', 'pagina');
		}
	}
	*/

	$sql = "
		SELECT * 
		FROM `wp_users`
	";
	$_usuarios = $wpdb->get_results($sql);
	foreach ($_usuarios as $usuario) {
		$user_id = $usuario->ID;
		update_user_meta($usuario->ID, 'registrado_desde', 'pagina');
	}
?>