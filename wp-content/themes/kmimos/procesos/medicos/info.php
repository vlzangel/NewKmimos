<?php
	session_start();
	extract( $_POST );
	$_infos = $_SESSION['medicos_info'];

	function set_format_name($cadena){
		$originales = 'ÁÉÍÓÚÑ';
        $modificadas = 'áéíóúñ';
        $cadena = strtr($cadena, ($originales), $modificadas);
        return strtolower($cadena);
	}

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
		$ff = $dias[ date('w', $start) ].', '.date('d', $start).' de '.$meses[ date('n', $start) ].' de '.date('Y', $start).' a las '.date('h:i a', $start);
		$hi = date('h:i a', $start);
		$agenda[ $fi ]['fecha'] = $ff;
		$agenda[ $fi ]['items'][] = [
			$hi,
			$ff
		];
	}
	$info["agenda"] = $agenda;
	$info["firstName"] = set_format_name($info["firstName"]);
	$info["lastName"] = set_format_name($info["lastName"]);
	echo json_encode( $info );
	die();
?>