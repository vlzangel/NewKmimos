<?php

	$desde = date('Y-m-d');
	if( isset($_GET['d']) && !empty($_GET['d']) ){
		$desde = $_GET['d'];
	}

	$hasta = $desde;
	if( isset($_GET['h']) && !empty($_GET['h']) ){
		$hasta = $_GET['h'];
	}


	for ($i = 1; $i = 0 ; $i++) { 

		echo $_GET['d'] . "<br>";

		// require_once(dirname(dirname(__DIR__)).'/cron/kmimos/reservas.php');

		// require_once(dirname(dirname(__DIR__)).'/cron/kmimos/usuarios.php');

		$_GET['d'] = date( 'Y-m-d', strtotime("+1 day", $_GET['d']) );

	}

