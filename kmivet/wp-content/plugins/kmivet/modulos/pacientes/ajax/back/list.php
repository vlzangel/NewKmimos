<?php
	$data = ['data' => []];
	$registros = $wpdb->get_results("SELECT * FROM {$pf}{$mod}");
	foreach ($registros as $key => $item) {
		$info = json_decode($item->data);

		$data['data'][] = [
			'<div class="align_right">'.$item->id.'</div>',
			'
				<div class="align_center">
					<!-- <span class="vlz_boton" > <i class="far fa-eye" onclick="_ver('.$item->id.')"></i> </span> -->
					<span class="vlz_boton" > <i class="fas fa-pencil-alt" onclick="_acceso_admin('.$item->user_id.')"></i> </span>
					<span class="vlz_boton vlz_boton_delete" > <i class="far fa-trash-alt" onclick="_delete('.$item->id.')"></i> </span>
				</div>
			',
			'<div style="text-transform: capitalize;">'.$info->first_name.' '.$info->last_name.'</div>',
			$info->user_email,
			$info->user_mobile.' / '.$info->user_phone,
			ucfirst($info->user_gender),
			$info->user_age.' años',
			$info->user_referred
		];
	}
	echo json_encode( $data );
?>