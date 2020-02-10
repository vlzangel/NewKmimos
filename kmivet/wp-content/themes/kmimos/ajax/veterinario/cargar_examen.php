<?php
	$cita_id = $id;

	$respuestas = [];
	foreach ($_POST as $key => $resp) {
		if( substr($key, 0, 4) == 'preg' ){
			$respuestas[] = [
				"id" => substr($key, 5),
				"content" => $resp 
			];
		}
	}

	$res = put_answers($id, [
		'answers' => $respuestas
	]);

	$reserva = $wpdb->get_row("UPDATE {$pf}reservas WHERE cita_id = '{$cita_id}' ");
	$cargas = [
        "examen" => false,
        "diagnostico" => false,
        "recipe" => false
    ];
	if( $reserva->cargas != '' ){
		$cargas = (array) json_decode($reserva->cargas);
	}
	$cargas["examen"] = true;
	$cargas = json_encode($cargas, JSON_UNESCAPED_UNICODE);
	$r = $wpdb->query("UPDATE {$pf}reservas SET cargas = '{$cargas}' WHERE cita_id = '{$cita_id}' ");



	$r = [
		"status" => true,
		"seccion" => "examen"
	];
	die( json_encode( $r ) );
?>