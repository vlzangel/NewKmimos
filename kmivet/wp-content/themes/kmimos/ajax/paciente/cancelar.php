<?php
	$r = cancelar_cita([
		"motivo" => 'Cancelado por el paciente',
		"cita_id" => $id,
		"cancelado_por" => "cliente"
	]);
	die( json_encode( $r ) );
?>