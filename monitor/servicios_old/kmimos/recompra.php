<?php

	require_once(dirname(dirname(__DIR__)).'/cron/kmimos/funciones.php');

	$desde = date('Y-m-d');
	if( isset($_GET['d']) && !empty($_GET['d']) ){
		$desde = $_GET['d'];
	}

	$hasta = $desde;
	if( isset($_GET['h']) && !empty($_GET['h']) ){
		$hasta = $_GET['h'];
	}

	$recompras = getRecompras( $desde, $hasta );
	$num_noches_recompra = getReservasRecompra( $desde, $hasta );

	if( !isset($recompras['info']->num_rows) ){
		$recompras = [];
	}
	
	$resultado = [
		'recompra' => $recompras['rows'],
		'noches_total_new_cliente' => $num_noches_recompra,
	];
 
	print_r(json_encode($resultado));
 