<?php
	$diagnostico = [
		"diagnostic" => $diagnostico,
		"notes" => $notas
	];
	$res = update_diagnostics($id, $diagnostico);
	$cita_id = $id;
	$cargas = [ "examen" => true, "recipe" => true, "diagnostico" => true ];
	$res = update_data_reserva($cita_id, $cargas, [ "diagnostico" => $diagnostico ]);
	// $r = $wpdb->query("UPDATE {$pf}reservas SET cargas = '{$cargas}', extras = '{$extras}' WHERE cita_id = '{$cita_id}' ");
	$r = [
		"status" => true,
		"seccion" => "diagnostico",
		"res" => $res
	];
	die( json_encode( $r ) );

/*	$extras = (array) json_decode( $wpdb->get_var("SELECT extras FROM {$pf}reservas WHERE cita_id = '{$cita_id}' ") );
	$extras['diagnostico'] = $diagnostico;
	$extras = json_encode($extras, JSON_UNESCAPED_UNICODE);

	$reserva = $wpdb->get_row("SELECT * FROM {$pf}reservas WHERE cita_id = '{$cita_id}' ");
	$cargas = [
        "examen" => true,
        "recipe" => true,
        "diagnostico" => true
    ];
	if( $reserva->cargas != '' ){
		$cargas = (array) json_decode($reserva->cargas);
	}
	$cargas["diagnostico"] = true;
	$cargas = json_encode($cargas, JSON_UNESCAPED_UNICODE);*/
?>