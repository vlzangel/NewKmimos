<?php
	if( $motivo == 'otro' ){ $motivo = $otro_motivo; }

	$res = $wpdb->query("UPDATE wp_kmivet_reservas SET status = 4, observaciones = '{$motivo}' WHERE cita_id = '{$cita_id}' ");

	if( $res != false ){
		$rcs = change_status($cita_id, [ "status" => 4, "description" => $motivo ]);

		if( $rcs['status'] == 'ok' ){

			// TODO: Enviar correo de cancelación

			$info_email = $wpdb->que_var("SELECT info_email FROM wp_kmivet_reservas WHERE cita_id = '{$cita_id}' ");

			$INFORMACION = json_decode( $info_email );

			/* EMAIL al CLIENTE */
		    	$mensaje = kv_get_email_html(
			        'KMIVET/reservas/cancelacion_cliente', 
			        $INFORMACION
			    );
		        wp_mail($cliente_email, 'Kmivet - Nueva Solicitud de Consulta', $mensaje);


		    /* EMAIL al CUIDADOR 
		    	$mensaje = kv_get_email_html(
			        'KMIVET/reservas/cancelacion_veterinario', 
			        $INFORMACION
			    );
		        wp_mail($_medico['email'], 'Kmivet - Nueva Solicitud de Consulta', $mensaje);

		    /* EMAIL al ADMINISTRADOR 
		    	$mensaje = kv_get_email_html(
			        'KMIVET/reservas/cancelacion_admin', 
			        $INFORMACION
			    );
			    $header = kv_get_emails_admin();
		        wp_mail('soporte.kmimos@gmail.com', 'Kmivet - Nueva Solicitud de Consulta', $mensaje, $header);
			*/

		        
			die( json_encode([ 'status' => true ]) );
		}else{
			die( json_encode([ 'status' => $res, 'error' => 'Error cambiando el estatus en el API' ]) );
		}
	}else{
		die( json_encode([ 'status' => false, 'error' => 'Error cambiando el estatus en kmivet' ]) );
	}

	die( json_encode([ 'status' => false, 'error' => 'Error inesperado' ]) );
?>