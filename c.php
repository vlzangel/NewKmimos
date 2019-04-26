<?php
	include 'wp-load.php';
	
	if( !isset($_SESSION) ){ session_start(); }

	global $wpdb;

	$cuidadores = $wpdb->get_results("SELECT * FROM cuidadores WHERE activo = 1");

	$cuida = [];

	foreach ($cuidadores as $key => $cuidador) {
		$estados = explode("=", $cuidador->estados);
		$municipios = explode("=", $cuidador->municipios);
		if( count( $estados ) > 3 || count( $municipios ) > 3 ){
			/*
			$cuida[] = [
				$cuidador->nombre." ".$cuidador->apellido,
				$cuidador->titulo,
				$cuidador->email,
				$cuidador->telefono,
				$cuidador->estados,
				$cuidador->municipios
			];
			*/
			$cuida[] = $cuidador->id;
		}
	}

	/*
	echo "Total: ".count($cuidadores)."<br>";
	echo "Con mult: ".count($cuida)."<br>";

	echo "<pre>";
		print_r( $cuida );
	echo "</pre>";
	*/

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
?>