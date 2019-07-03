<?php
    session_start();
    require('../wp-load.php');
    date_default_timezone_set('America/Mexico_City');
    global $wpdb;

    $hoy = date("Y-m-d");

    $sql = "SELECT * FROM cupos WHERE fecha >= '{$hoy}' AND full = 1";
	$_cupos = $wpdb->get_results($sql);


	echo "<pre>";
		echo $sql."<br>";
		print_r( $_cupos );
	echo "</pre>";
	/*
	$sql = "
		UPDATE 
			cuidadores_bp
		SET
			flash = '{$cuidador[2]}',
			nacimiento = '{$cuidador[4]}',
			full_name = '{$cuidador[5]}',
			nombre = '{$cuidador[6]}',
			apellido = '{$cuidador[7]}',
			cuidador = '{$cuidador[8]}',
			estado = '{$cuidador[10]}',
			municipio = '{$cuidador[11]}',
			direccion = '{$cuidador[12]}',
			telefono = '{$cuidador[13]}',
			nos_conocio = '{$cuidador[14]}',
			estatus = '{$cuidador[15]}'
		WHERE
			email = '{$cuidador[9]}'
	";

	$wpdb->query($sql);
	*/

	echo "Listo";
?>