<?php

require_once( dirname(dirname(__DIR__)).'/class/general.php' );
require_once( dirname(dirname(__DIR__)).'/class/marketing.php' );

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

// Datos para mostrar
$data = [];
$plataforma = 'kmimos_mx';

$datos = $c->get_total_gastos( $desde, $hasta, $plataforma );
$meses = $c->getMeses();
 
foreach ($datos as $val) {

	$anio = substr($val['fecha'], 2,4);
	$mes = $meses[substr($val['fecha'], 0,2)-1];

	$data["data"][] = array(
		$val['canal'],
		$mes.$anio,
		"$".number_format($val['costo'], 2,',','.'),
	);
}
print_r(json_encode($data));
