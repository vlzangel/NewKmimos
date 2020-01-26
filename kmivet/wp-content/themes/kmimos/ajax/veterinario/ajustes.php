<?php

	$r = $wpdb->query("UPDATE {$pf}veterinarios SET precio = '{$precio}' WHERE user_id = '{$user_id}' ");
	
	if( $r === false ){
		die( json_encode( [
			'status' => false,
			'error'  => 'Actualización fallida',
			'extra'  => $sql
		] ) );
	}

	die( json_encode( [
		'status' => true
	] ) );
?>