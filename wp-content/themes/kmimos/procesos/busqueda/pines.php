<?php
	
	session_start();

	$busqueda = $_SESSION['busqueda'];

	$pines = unserialize($_SESSION['pines_array']);

	echo json_encode($pines);
?>