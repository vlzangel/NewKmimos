<?php
	/*
	include 'wp-load.php';
	
	if( !isset($_SESSION) ){ session_start(); }

	global $wpdb;

	$cuidadores = $wpdb->get_results("SELECT * FROM cuidadores WHERE activo = 1");

	$cuida = [];

	foreach ($cuidadores as $key => $cuidador) {
		$estados = explode("=", $cuidador->estados);
		$municipios = explode("=", $cuidador->municipios);
		if( count( $estados ) > 3 || count( $municipios ) > 3 ){
			$cuida[] = $cuidador->id;
		}
	}
	*/

	date_default_timezone_set('America/Mexico_City');

	echo "Fecha: ".date("d/m/Y H:i:s", 1556563873)."<br>";
	echo "Fecha Ahora: ".date("d/m/Y H:i:s", ( time() + 60 ) );

	/*
	$ids = implode(",", $cuida);

	echo "
		SELECT
			nombre,
			apellido,
			titulo,
			email,
			telefono,
			estados,
			municipios
		FROM
			cuidadores
		WHERE
			id IN ({$ids})
	";
	*/
?>