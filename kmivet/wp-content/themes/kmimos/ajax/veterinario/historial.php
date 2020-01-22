<?php
	$data['data'] = [];

	/*
		1.	Cita confirmada
		2.	Arribo al domicilio
		3.	Finalización de la cita
		4.	Cita cancelada
		5.	Cita finalizada con Calificación
	*/

	$veterinario_id = $wpdb->get_var("SELECT veterinario_id FROM {$pf}veterinarios WHERE user_id = '{$user_id}'");

	$reservas = $wpdb->get_results( "SELECT * FROM {$pf}reservas WHERE veterinario_id = '{$veterinario_id}' ORDER BY id DESC" );
	foreach ($reservas as $key => $reserva) {
		$i = json_decode($reserva->data);

		$medico = $wpdb->get_row( "SELECT * FROM {$pf}veterinarios WHERE veterinario_id = '{$reserva->veterinario_id}' " );
		$info_vete = json_decode($medico->api);

		$fecha = date("d/m/Y", strtotime($i->cita_fecha) ).' a las '.date("h:ia", strtotime($i->cita_fecha) );

		$acciones = '
			<span class="btn_table"> <i onclick="_ver( jQuery(this) )" class="far fa-eye" data-accion="ver" data-id="'.$reserva->id.'" title="Ver" ></i> </span>
		';

		switch ( $reserva->status ) {
			case 1: // 
				$acciones .= '
					<span class="btn_table"> <i onclick="_arribar( jQuery(this) )" class="fas fa-plane-arrival" data-accion="arribar" data-id="'.$reserva->cita_id.'" title="Arribo al domicilio" ></i> </span>
					<span class="btn_table btn_cancelar"> <i onclick="_cancelar( jQuery(this) )" class="far fa-trash-alt" data-accion="cancelar" data-id="'.$reserva->cita_id.'" title="Cancelar" ></i> </span>
				';
			break;
			case 2: // <i class="fas fa-check"></i>
				$acciones .= '
					<span class="btn_table"> <i onclick="_finalizar( jQuery(this) )" class="fas fa-check" data-accion="finalizar" data-id="'.$reserva->cita_id.'" title="Finalizar cita" ></i> </span>
					<span class="btn_table btn_cancelar"> <i onclick="_cancelar( jQuery(this) )" class="far fa-trash-alt" data-accion="cancelar" data-id="'.$reserva->cita_id.'" title="Cancelar" ></i> </span>
				';
			break;
			case 3:
				// $acciones .= '-';
			break;
			case 4:
				// $acciones = '-';
			break;
		}

		$data['data'][] = [
			$reserva->id,
			'<div style="text-transform: capitalize;">'.$info_vete->firstName.' '.$info_vete->lastName.'</div>'.
			'<div><small>'.$info_vete->email.'</small></div>'.
			'<div><small>'.$info_vete->phone.'</small></div>',
			$fecha,
			get_status_reserva( $reserva->status ),
			$acciones
		];
	}
	echo json_encode($data);

	// fas fa-circle-notch
?>