<?php
	$rcs = change_status($cita_id, [ "status" => 5, "description" => $mensaje, "source" => "1" ]);
	if( $rcs['status'] == 'ok' ){ 
		$r = $wpdb->query("UPDATE {$pf}reservas SET status = 5, calificacion = '{$valor}', observaciones = '{$mensaje}' WHERE id = '{$id}' ");
		if( $r ){
			die( json_encode([
				'status' => true
			] ) );
		}else{
			die( json_encode([
				'status' => false,
				'error' => 'Error cambiando el estatus en kmivet' 
			] ) );
		}
	}else{
        die( json_encode([ 
        	'status' => $res, 
        	'error' => 'Error cambiando el estatus en el API' 
        ]));
    }
?>