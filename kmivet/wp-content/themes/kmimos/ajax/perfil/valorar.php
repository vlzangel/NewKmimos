<?php
	die( json_encode([
		'status' => $wpdb->query("UPDATE {$pf}reservas SET status = 5, calificacion = '{$valor}', observaciones = '{$mensaje}' WHERE id = '{$id}' ")
	] ) );

	die( json_encode($_POST) );
?>