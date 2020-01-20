<?php
	$data['data'] = [];
	$reservas = $wpdb->get_results( "SELECT * FROM {$pf}reservas WHERE user_id = '{$user_id}' ORDER BY id DESC" );
	foreach ($reservas as $key => $reserva) {
		$i = json_decode($reserva->data);

		$medico = $wpdb->get_row( "SELECT * FROM {$pf}veterinarios WHERE veterinario_id = '{$reserva->veterinario_id}' " );
		$info_vete = json_decode($medico->api);

		$fecha = date("d/m/Y", strtotime($i->cita_fecha) ).' a las '.date("h:ia", strtotime($i->cita_fecha) );
		$data['data'][] = [
			$reserva->id,
			'<div style="text-transform: capitalize;">'.$info_vete->firstName.' '.$info_vete->lastName.'</div>'.
			'<div><small>'.$info_vete->email.'</small></div>'.
			'<div><small>'.$info_vete->phone.'</small></div>',
			$fecha = $fecha,
			get_status_reserva( $reserva->status ),
			'-'
		];
	}
	echo json_encode($data);
?>