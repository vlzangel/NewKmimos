<?php
	header('Access-Control-Allow-Origin: *');
	require_once( dirname(dirname(__DIR__)) .'/class/ventas.php');

	extract($_POST);

	$v = new ventas();

	$datos = $v->get_datos($desde, $hasta);

	$usuarios = $v->get_usuarios( $desde, $hasta );

	$marketing = $c->sumar_campanas( $desde, $hasta );

	$estatus = 0;
	if( !empty($datos) ){
		$estatus = 1;
	}

	print_r(json_encode([
		'estatus' => $estatus, 
		'datos' => $datos,
		'marketing' => $marketing,
		'usuario' => $usuarios,
	], JSON_UNESCAPED_UNICODE));

