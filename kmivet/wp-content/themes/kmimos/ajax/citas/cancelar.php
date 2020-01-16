<?php
	if( $motivo == 'otro' ){ $motivo = $otro_motivo; }

	$res = $wpdb->query("UPDATE wp_kmivet_reservas SET status = 4, observaciones = '{$motivo}' WHERE cita_id = '{$cita_id}' ");

	if( $res != false ){
		$rcs = change_status($cita_id, [ "status" => 4, "description" => $motivo ]);

		if( $rcs['status'] == 'ok' ){

			// TODO: Enviar correo de cancelación

			die( json_encode([ 'status' => true ]) );
		}else{
			die( json_encode([ 'status' => $res, 'error' => 'Error cambiando el estatus en el API' ]) );
		}
	}else{
		die( json_encode([ 'status' => false, 'error' => 'Error cambiando el estatus en kmivet' ]) );
	}

	die( json_encode([ 'status' => false, 'error' => 'Error inesperado' ]) );
?>