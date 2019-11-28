<?php
	session_start();
	extract( $_POST );
	$_infos = $_SESSION['medicos_info'];

	$info = [];
	foreach ($_infos[ $id ] as $key => $value) {
		$info[ $key ] = $value;
	}

	$dias = [
		"Lunes",
		"Martes",
		"Miércoles",
		"Jueves",
		"Viernes",
		"Sábado",
		"Domingo"
	];

	$meses = [
		"",
		"Enero",
		"Febrero",
		"Marzo",
		"Abril",
		"Mayo",
		"Junio",
		"Julio",
		"Agosto",
		"Septiembre",
		"Octubre",
		"Noviembre",
		"Diciembre",
	];

	$agenda = [];
	foreach ($info["agenda"] as $key => $item) {
		$start = strtotime( str_replace("Z", "", $item->start));
		$fi = date('d/m/Y', $start);
		$ff = $dias[ date('w', $start) ].', '.date('d', $start).' '.$meses[ date('n', $start) ];
		$hi = date('h:i a', $start);
		$agenda[ $fi ]['fecha'] = $ff;
		$agenda[ $fi ]['items'][] = [
			$hi,
			str_replace("Z", "", $item->start)
		];
	}

	$info["agenda"] = $agenda;
	
	echo json_encode( $info );
	die();
?>