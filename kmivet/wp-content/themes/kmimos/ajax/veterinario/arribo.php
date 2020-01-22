<?php
	$rcs = change_status($cita_id, [ "status" => 2, "description" => "Veterinario llegando al domicilio" ]);
	if( $rcs['status'] == 'ok' ){ 
		$r = $wpdb->query("UPDATE {$pf}reservas SET status = 2 WHERE cita_id = '{$cita_id}' ");
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