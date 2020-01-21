<?php
	$data['data'] = [];

	/*
		1.	Cita confirmada
		2.	Arribo al domicilio
		3.	Finalización de la cita
		4.	Cita cancelada
		5.	Cita finalizada con Calificación
	*/

	$tipo = strtolower( get_usermeta( $user_id, "tipo_usuario", true ) );

	$reservas = $wpdb->get_results( "SELECT * FROM {$pf}reservas WHERE user_id = '{$user_id}' ORDER BY id DESC" );
	foreach ($reservas as $key => $reserva) {
		$i = json_decode($reserva->data);

		$medico = $wpdb->get_row( "SELECT * FROM {$pf}veterinarios WHERE veterinario_id = '{$reserva->veterinario_id}' " );
		$info_vete = json_decode($medico->api);

		$fecha = date("d/m/Y", strtotime($i->cita_fecha) ).' a las '.date("h:ia", strtotime($i->cita_fecha) );

		$acciones = '
			<i onclick="_ver( jQuery(this) )" class="far fa-eye" data-accion="ver" data-id="'.$reserva->id.'" title="Ver" ></i>
		';

		switch ( $reserva->status ) {
			case 1:
				$acciones .= '
					<i onclick="_cancelar( jQuery(this) )" class="far fa-trash-alt" data-accion="cancelar" data-id="'.$reserva->id.'" title="Cancelar" ></i>
				';
			break;
			case 2:
				$acciones .= '
					<i onclick="_cancelar( jQuery(this) )" class="far fa-trash-alt" data-accion="cancelar" data-id="'.$reserva->id.'" title="Cancelar" ></i>
				';
			break;
			case 3:
				$acciones .= '
					<i onclick="_valorar( jQuery(this) )" class="fas fa-clipboard-check" data-accion="calificar" data-id="'.$reserva->id.'" title="Calificar"></i>
				';
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
?>