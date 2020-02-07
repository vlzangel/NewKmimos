<?php
	$cita_id = $id;
	$rcs = change_status($cita_id, [ "status" => 5, "description" => $mensaje, "source" => "1" ]);
	if( $rcs['status'] == 'ok' ){ 

		$cita = $wpdb->get_row("SELECT * FROM {$pf}reservas WHERE cita_id = '{$cita_id}' ");

		$rcv = calificar_veterinario( $cita->veterinario_id, [ 
			"patient" => $cita->paciente_id, 
			"appointment" => $cita_id, 
			"rating" => $valor, 
			"comment" => $mensaje
		] );

		if( $rcv['status'] == 'ok' ){
			$r = $wpdb->query("UPDATE {$pf}reservas SET status = 5, calificacion = '{$valor}', observaciones = '{$mensaje}' WHERE cita_id = '{$cita_id}' ");
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
				'status' => false,
				'error' => 'Error enviando la calificación al API' 
			] ) );
		}

	}else{
        die( json_encode([ 
        	'status' => $res, 
        	'error' => 'Error cambiando el estatus en el API' 
        ]));
    }
?>
