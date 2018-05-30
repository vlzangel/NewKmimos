<?php
	
	require_once('../reportes/class/ventas.php');

	$desde = date('Y-m-d');
	if( isset($_GET['desde']) && !empty($_GET['desde']) ){
		$desde = $_GET['desde'];
	}

	$hasta = $desde;
	if( isset($_GET['hasta']) && !empty($_GET['hasta']) ){
		$hasta = $_GET['hasta'];
	}

	$c = new ventas();
	$data = $c->getData($desde, $hasta);
	
	print_r( json_encode($data, JSON_UNESCAPED_UNICODE) );
