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

	function set_format_ranking($ranking){
		$ranking += 0;
		if( $ranking > 5 ){ $ranking = 5; }
		if( $ranking < 1 ){ $ranking = 1; }
		$_ranking = '';
		for ($i=1; $i <= $ranking; $i++) {  $_ranking .= '<span class="active"></span>'; }
		if( $ranking < 5 ){ for ($i=$ranking; $i < 5; $i++) {  $_ranking .= '<span></span>'; } }
		return $_ranking;
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
	$info["rating"] = set_format_ranking($info["rating"]);
	$info["price"] = number_format($info["price"], 2, ',', ',');
	echo json_encode( $info );
	die();
?>