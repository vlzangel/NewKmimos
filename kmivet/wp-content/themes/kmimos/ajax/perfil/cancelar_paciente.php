<?php
	// $r = $wpdb->get_row("SELECT * FROM {$pf}reservas WHERE id = '{$id}'");
	die( json_encode([
		'status' => $wpdb->query("UPDATE {$pf}reservas SET status = 4, observaciones = 'Cancelado por el paciente' WHERE id = '{$id}' ")
	] ) );
?>