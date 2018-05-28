<?php
	header('Access-Control-Allow-Origin: *');
	require_once( dirname(dirname(__DIR__)) .'/class/ventas.php');

	extract($_POST);

	$v = new ventas();

	$datos = $v->get_datos($desde, $hasta);

	$estatus = 0;
	if( !empty($datos) ){
		$estatus = 1;
	}

	print_r(json_encode([
		'estatus' => $estatus, 
		'datos' => $datos
	], JSON_UNESCAPED_UNICODE));

