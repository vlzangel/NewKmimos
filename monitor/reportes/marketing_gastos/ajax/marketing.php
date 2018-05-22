<?php

require_once( dirname(dirname(__DIR__)).'/class/general.php' );
require_once( dirname(dirname(__DIR__)).'/class/marketing.php' );


$c = new marketing();

// Datos para mostrar
$data = [];

$datos = $c->get_datos($where);

foreach ($datos as $val) {

	$data["data"][] = array(
		$val['fecha'],
		$val['nombre'],
		"$".number_format($val['costo'], 2,',','.'),
		$val['plataforma'],
		str_replace('_', ' y ', $val['tipo']),
		$val['canal'],
		'
		<button class="btn btn-sm btn-danger" data-target="delete" data-id="'.$val['id'].'"><i class="fa fa-close"></i></button></button>
		<button class="btn btn-sm btn-info" data-target="update" data-id="'.$val['id'].'"><i class="fa fa-pencil"></i></button></button>
		'
	);
}
print_r(json_encode($data));
