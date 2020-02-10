<?php
	$res = add_medicine($appointment_id, $medicine_id, $indications);

	$cita_id = $appointment_id;

	$reserva = $wpdb->get_row("UPDATE {$pf}reservas WHERE cita_id = '{$cita_id}' ");
	$cargas = [
        "examen" => true,
        "recipe" => false,
        "diagnostico" => false
    ];
	if( $reserva->cargas != '' ){
		$cargas = (array) json_decode($reserva->cargas);
	}
	$cargas["recipe"] = true;
	$cargas = json_encode($cargas, JSON_UNESCAPED_UNICODE);
	$r = $wpdb->query("UPDATE {$pf}reservas SET cargas = '{$cargas}' WHERE cita_id = '{$cita_id}' ");

	die( json_encode([
		"status" => true,
		"seccion" => "recipe",
		"msg" => "Medicamento agregado exitosamente!",
		"extra" => $res
	]) );
?>