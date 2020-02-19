<?php
	$cita_id = $id;
	$rcs = change_status($cita_id, [ "status" => 3, "description" => "Cita finalizada" ]);
	if( $rcs['status'] == 'ok' ){ 
		$r = $wpdb->query("UPDATE {$pf}reservas SET status = 3 WHERE cita_id = '{$cita_id}' ");

	    $reserva = $wpdb->get_row("SELECT * FROM {$pf}reservas WHERE cita_id = '{$cita_id}' ");
	    $veterinario = $wpdb->get_row("SELECT * FROM {$pf}veterinarios WHERE veterinario_id = '{$reserva->veterinario_id}' ");
	    $INFORMACION = (array) json_decode( $reserva->info_email );
	    $appointment = get_appointment($cita_id);

	    $INFORMACION["AVATAR_URL"] = kmimos_get_foto($veterinario->user_id);
	    $INFORMACION["DIAGNOSTICO"] = $appointment['result']->diagnostic->diagnostic->title;
	    $INFORMACION["DIAGNOSTICO_NOTA"] = $appointment['result']->diagnostic->notes;
	    $INFORMACION["TRATAMIENTO"] = $appointment['result']->treatment;

	    $PDF = file_get_contents( get_home_url().'/test/?cita_id='.$cita_id );

	    $INFORMACION["PDF"] = $PDF;

	    $res = update_data_reserva($cita_id, [], [ "recipe" => $PDF ]);

	    /*
		$mensaje = kv_get_email_html(
	        'KMIVET/reservas/confirmacion_cliente', 
	        $INFORMACION
	    );
	    wp_mail($INFORMACION['CORREO_CLIENTE'], 'Kmivet - Consulta Completada', $mensaje);

		$mensaje = kv_get_email_html(
	        'KMIVET/reservas/confirmacion_cliente', 
	        $INFORMACION
	    );
	    wp_mail($INFORMACION['CORREO_VETERINARIO'], 'Kmivet - Consulta Completada', $mensaje);
	    */

		$mensaje = kv_get_email_html(
	        'KMIVET/reservas/confirmacion_cliente', 
	        $INFORMACION
	    );
	    $admins = get_admins();
	    /*
	    wp_mail($admins['admin'], 'Kmivet - Consulta Completada', $mensaje, $admins['otros']);
	    */
	    wp_mail($admins['admin'], 'Kmivet - Consulta Completada', $mensaje);
		
		if( $r ){
			die( json_encode([
				'status' => true
			] ) );
		}else{
			die( json_encode([
				'status' => false,
				'error' => 'Error cambiando el estatus en kmivet',
				'pdf' => $res,
			] ) );
		}
	}else{
        die( json_encode([ 
        	'status' => $res, 
        	'error' => 'Error cambiando el estatus en el API' 
        ]));
    }
?>