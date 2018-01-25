<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

	extract($_POST);

	if( $core == "SI" ){
		include_once($raiz."/wp-load.php");
	}else{
		include_once("../funciones/generales.php");
	}
	
	include_once($raiz."/vlz_config.php");
	include_once("../funciones/db.php");
	$db = new db( new mysqli($host, $user, $pass, $db) );

	include($accion.".php");

	echo json_encode($respuesta);

	exit;
?>