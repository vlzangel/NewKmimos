<?php
	error_reporting(0);

	require_once( dirname(dirname(dirname(dirname(dirname(__DIR__))))) .'/vlz_config.php');
	require_once( dirname(dirname(dirname(dirname(__DIR__)))) .'/themes/kmimos/procesos/funciones/db.php');
	$db = new db( new mysqli($host, $user, $pass, $db) );
?>