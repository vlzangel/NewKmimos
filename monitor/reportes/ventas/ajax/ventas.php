<?php
	header('Access-Control-Allow-Origin: *');
	require_once( dirname(dirname(__DIR__)) .'/class/ventas.php');

	extract($_POST);

	$v = new ventas();


	$datos = $v->get_datos($desde, $hasta);

	$usuarios = $v->get_usuarios( $desde, $hasta );

	$hoy = $desde;
	$recompras = [];
	for ($i=0; $hoy <= $hasta ; $i++) { 
		$data_recompra = $v->get_recompras( $desde, $hoy );	
		if( isset($data_recompra[0]) ){
			$recompras[] = $data_recompra[0];
		}	
		$hoy = date( "Y-m-d", strtotime( "$hoy +1 day" ) );
	}

	$estatus = 0;
	if( !empty($datos) ){
		$estatus = 1;
	}

	print_r(json_encode([
		'estatus' => $estatus, 
		'datos' => $datos,
		'recompra' => $recompras,
		'usuario' => $usuarios,
	], JSON_UNESCAPED_UNICODE));

