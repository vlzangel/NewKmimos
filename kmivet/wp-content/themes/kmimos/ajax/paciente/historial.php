<?php
	$data['data'] = [];

	$tipo = strtolower( get_usermeta( $user_id, "tipo_usuario", true ) );

	$reservas = $wpdb->get_results( "SELECT * FROM {$pf}reservas WHERE user_id = '{$user_id}' ORDER BY id DESC" );
	foreach ($reservas as $key => $reserva) {
		$i = json_decode($reserva->data);

		// $medico = $wpdb->get_row( "SELECT * FROM {$pf}veterinarios WHERE veterinario_id = '{$reserva->veterinario_id}' " );
		// $info_vete = json_decode($medico->data);

		$vet = get_medic($reserva->veterinario_id);
		$vet = $vet['res'];

		$fecha = date("d/m/Y", strtotime($i->cita_fecha) ).' a las '.date("h:ia", strtotime($i->cita_fecha) );

		$acciones = '<span class="btn_table"> <i onclick="_ver( jQuery(this) )" class="far fa-eye" data-accion="ver" data-id="'.$reserva->id.'" title="Ver" ></i> </span>';
		$acciones =  '';
		switch ( $reserva->status ) {
			case 1:
				$acciones .= '<span class="btn_table btn_cancelar"> <i onclick="_cancelar( jQuery(this) )" class="far fa-trash-alt" data-accion="cancelar" data-id="'.$reserva->cita_id.'" title="Cancelar" ></i> </span>';
			break;
			case 2:
				$acciones .= '<span class="btn_table btn_cancelar"> <i onclick="_cancelar( jQuery(this) )" class="far fa-trash-alt" data-accion="cancelar" data-id="'.$reserva->cita_id.'" title="Cancelar" ></i> </span>';
			break;
			case 3:
				$acciones .= '<span class="btn_table"> <i onclick="_valorar( jQuery(this) )" class="fas fa-clipboard-check" data-accion="calificar" data-id="'.$reserva->cita_id.'" title="Calificar"></i> </span>';
			break;
			case 4:
			break;
			case 5:
				$acciones .= '
					<span class="btn_table"> <i onclick="_examen_ver(jQuery(this))" class="far fa-eye" data-id="'.$reserva->cita_id.'" title="Ver Examen" ></i> </span>
				';
			break;
		}

		$data['data'][] = [
			$reserva->id,
			'<div style="text-transform: capitalize;">'.$vet->firstName.' '.$vet->lastName.'</div>'
			/* '<div><small>'.$vet->email.'</small></div>'.
			'<div><small>'.$vet->phone.'</small></div>' */,
			$fecha,
			get_status_reserva( $reserva->status ),
			'',
			$acciones
		];
	}
	die( json_encode($data) );
?>