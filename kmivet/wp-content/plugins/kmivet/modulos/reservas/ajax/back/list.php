<?php
	$data = ['data' => []];
	$registros = $wpdb->get_results("SELECT * FROM {$pf}{$mod}");
	foreach ($registros as $key => $item) {
		$info = json_decode($item->data);

		$user = $wpdb->get_row("SELECT * FROM {$pf}pacientes WHERE user_id = ".$item->user_id);
		$i = json_decode($user->data);

		$fecha = date("d/m/Y H:i", strtotime($info->cita_fecha) );

		$status = get_status_reserva($item->status);

		if( $item->status ){
			$status .= " ({$item->observaciones})";
		}

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

			'<div style="text-transform: capitalize;">'.$i->first_name.' '.$i->last_name.'</div>',
			$i->user_email,
			$i->user_mobile.' / '.$i->user_phone,

			"-",
			"-",
			"-"
		];
	}
	echo json_encode( $data );
?>