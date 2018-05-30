<?php

require_once( dirname(__DIR__).'/class/ventas.php' );

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

	$c = new ventas();

	// Meses en letras
	$meses = $c->getMeses();

	// Plataformas
	$plataformas = $c->get_plataforma();

	// Datos para mostrar
	$data = [];

	// html Opciones del Menu - plataformas
	$menu = $c->get_html_menu_plataformas();


	// Cargar datos de la plataforma seleccionada
	$sucursal = 'global';
	$datos_by_sucursal = [];
	$_action[] = $sucursal;
	if( isset($_POST['sucursal']) ){
		$_action = explode('.', $_POST['sucursal']);
	}

