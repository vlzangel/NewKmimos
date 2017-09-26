<?php
	$raiz = dirname(__DIR__,5);
	include_once($raiz."/vlz_config.php");
	include_once("../funciones/db.php");

	$db = new db( new mysqli($host, $user, $pass, $db) );

	extract($_POST);

	include($accion.".php");

	echo json_encode($respuesta);

	exit;
?>