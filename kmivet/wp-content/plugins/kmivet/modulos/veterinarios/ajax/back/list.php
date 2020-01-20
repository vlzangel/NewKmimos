<?php
	$data = ['data' => []];
	$registros = $wpdb->get_results("SELECT * FROM {$pf}{$mod}");
	foreach ($registros as $key => $item) {
		$info = json_decode($item->data);
		if( $item->veterinario_id != '' ){
			$medico = $wpdb->get_row( "SELECT * FROM {$pf}veterinarios WHERE veterinario_id = '{$item->veterinario_id}' " );
			$info_vete = json_decode( $medico->api );
			$edad = ( date("Y") - date('Y', strtotime($info_vete->birthday)  ) );
			$data['data'][] = [
				$item->id,
				'
					<div class="align_center">
						<!-- <span class="vlz_boton" > <i class="far fa-eye" onclick="_ver('.$item->id.')"></i> </span> -->
						<span class="vlz_boton" > <i class="fas fa-pencil-alt" onclick="_acceso_admin('.$item->user_id.')"></i> </span>
						<span class="vlz_boton vlz_boton_delete" > <i class="far fa-trash-alt" onclick="_delete('.$item->id.')"></i> </span>
					</div>
				',
				ucfirst($info_vete->firstName.' '.$info_vete->lastName),
				$info_vete->email,
				$info_vete->phone,
				$edad.' años',
				'$'.$info_vete->price
			];
		}
	}
	echo json_encode( $data );
?>