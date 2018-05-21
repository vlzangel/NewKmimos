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

	$num_noches_recompra = getNochesRecomprasClientesNuevos( $desde, $hasta );

	if( !isset($recompras['info']->num_rows) ){
		$recompras = [];
	}
	
	// print_r(json_encode($recompras['rows']));	

echo '<pre>';
print_r($num_noches_recompra);