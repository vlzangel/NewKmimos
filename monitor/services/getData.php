<?php

	require_once('../conf/database.php');
	$db = new db();

	$desde = date('Y-m-d');
	if( isset($_POST['d']) && !empty($_POST['d']) ){
		$desde = $_POST['d'];
	}

	$hasta = $desde;
	if( isset($_POST['h']) && !empty($_POST['h']) ){
		$hasta = $_POST['h'];
	}

	// Buscar datos del Registro Diario
	$sql = "
		SELECT fecha, cliente, reserva 
		FROM monitor_diario
		WHERE fecha >= '{$desde}' AND fecha <= '{$hasta}'
	";
	
	$resultado = $db->select( $sql );

	$data = [];
	foreach ($resultado as $registro) {
		$fecha = str_replace('-', '', $registro['fecha']);
		$data[ $fecha ] = $registro;		
	}

	print_r( json_encode($data, JSON_UNESCAPED_UNICODE) );
