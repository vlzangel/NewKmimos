<?php
	
	session_start();

	$busqueda = $_SESSION['busqueda'];

	$clave = md5( $_SESSION['busqueda'] );

	$pines = unserialize($_SESSION[ $clave ]['pines_array']);

	echo json_encode($pines);
?>