<?php
	$res = update_diagnostics($id, [
		"diagnostic" => $diagnostico,
		"notes" => $notas
	]);

	$cita_id = $id;

	$reserva = $wpdb->get_row("UPDATE {$pf}reservas WHERE cita_id = '{$cita_id}' ");
	$cargas = [
        "examen" => true,
        "recipe" => true,
        "diagnostico" => true
    ];
	if( $reserva->cargas != '' ){
		$cargas = (array) json_decode($reserva->cargas);
	}
	$cargas["diagnostico"] = true;
	$cargas = json_encode($cargas, JSON_UNESCAPED_UNICODE);
	$r = $wpdb->query("UPDATE {$pf}reservas SET cargas = '{$cargas}' WHERE cita_id = '{$cita_id}' ");

	$r = [
		"status" => true,
		"seccion" => "diagnostico",
		"res" => $res
	];
	die( json_encode( $r ) );
?>