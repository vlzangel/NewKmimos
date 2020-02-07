<?php
	$cita_id = $id;
	$rcs = change_status($cita_id, [ "status" => 3, "description" => "Cita finalizada" ]);
	if( $rcs['status'] == 'ok' ){ 
		$r = $wpdb->query("UPDATE {$pf}reservas SET status = 3 WHERE cita_id = '{$cita_id}' ");
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