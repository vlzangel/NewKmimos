<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

	extract($_POST);

	include_once($raiz."/wp-load.php");

	error_reporting(0);

	include($accion.".php");

	echo json_encode($respuesta);

	exit;
?>