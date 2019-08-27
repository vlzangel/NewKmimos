<?php
	include dirname(__DIR__)."/wp-load.php";

	global $wpdb;

	$sql = "SELECT * FROM wp_users WHERE ID NOT IN (
		SELECT user_id FROM wp_usermeta WHERE meta_key = 'registrado_desde' AND meta_value = 'pagina'
	)";

	/*
	$registros_app = [];
	$_usuarios = $wpdb->get_results($sql);
	foreach ($_usuarios as $usuario) {
		$user_id = $usuario->ID;
		$registro = get_user_meta($user_id, 'registrado_desde', true);
		if( $registro != 'pagina' ){
			$registros_app[] = $usuario->user_email;
		}
	}

	echo "<pre>";
		print_r( $registros_app );
	echo "</pre>";
	*/
?>