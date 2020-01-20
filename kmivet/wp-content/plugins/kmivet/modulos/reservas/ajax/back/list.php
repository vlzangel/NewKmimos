<?php
	$data = ['data' => []];
	$registros = $wpdb->get_results("SELECT * FROM {$pf}{$mod}");
	foreach ($registros as $key => $item) {
		$info = json_decode($item->data);

		$user = $wpdb->get_row("SELECT * FROM {$pf}pacientes WHERE user_id = ".$item->user_id);
		$i = json_decode($user->data);

		$fecha = date("d/m/Y H:i", strtotime($info->cita_fecha) );

		$status = get_status_reserva($item->status);

		if( $item->status == 4 ){
			$status .= " ({$item->observaciones})";
		}

		$medico = $wpdb->get_row( "SELECT * FROM {$pf}veterinarios WHERE veterinario_id = '{$item->veterinario_id}' " );
		$info_vete = json_decode($medico->api);

		$data['data'][] = [
			'<div class="align_right">'.$item->id.'</div>',
			'
				<div class="align_center">
					<!-- <span class="vlz_boton" > <i class="far fa-eye" onclick="_ver('.$item->id.')"></i> </span> -->
					<span class="vlz_boton" > <i class="fas fa-pencil-alt" onclick="_acceso_admin('.$item->user_id.')"></i> </span>
					<!-- <span class="vlz_boton vlz_boton_delete" > <i class="far fa-trash-alt" onclick="_delete('.$item->id.')"></i> </span> -->
				</div>
			',
			$status,
			$fecha,
			$info->cita_direccion,

			ucfirst($i->first_name.' '.$i->last_name),
			$i->user_email,
			$i->user_mobile.' / '.$i->user_phone,

			ucfirst($info_vete->firstName.' '.$info_vete->lastName),
			$info_vete->email,
			$info_vete->phone
		];
	}
	echo json_encode( $data );
?>