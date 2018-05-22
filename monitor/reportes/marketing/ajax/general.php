<?php

require_once( dirname(dirname(__DIR__)).'/class/general.php' );
require_once( dirname(dirname(__DIR__)).'/class/marketing.php' );

// ******************************************
// Procesar datos
// ******************************************

	$hoy = date('Y-m-d');

	$desde = date('Y-m-d', strtotime( '-12 month', strtotime($hoy) ));
	if( isset($_POST['desde']) && !empty($_POST['desde']) ){
		$desde = $_POST['desde'];
	}

	$hasta = $hoy;
	if( isset($_POST['hasta']) && !empty($_POST['hasta']) ){
		$hasta = $_POST['hasta'];
	}

	$c = new marketing();
 
	$plataformas = $c->get_plataforma();
 