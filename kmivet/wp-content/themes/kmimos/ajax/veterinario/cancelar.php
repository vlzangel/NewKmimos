<?php
	$r = cancelar_cita([
		"motivo" => $motivo,
		"otro_motivo" => $otro_motivo,
		"cita_id" => $id,
		"cancelado_por" => "veterinario"
	]);
	die( json_encode( $r ) );
?>