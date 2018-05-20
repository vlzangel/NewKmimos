<?php

	require_once('../reportes/class/procesar.php');

	$desde = date('Y-m-d');
	if( isset($_POST['d']) && !empty($_POST['d']) ){
		$desde = $_POST['d'];
	}

	$hasta = $desde;
	if( isset($_POST['h']) && !empty($_POST['h']) ){
		$hasta = $_POST['h'];
	}

	$c = new procesar();
	$data = $c->getData($desde, $hasta);
	
	print_r( json_encode($data, JSON_UNESCAPED_UNICODE) );
