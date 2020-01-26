<?php
	$data = ['data' => []];
	$registros = $wpdb->get_results("SELECT * FROM {$pf}{$mod}");
	foreach ($registros as $key => $item) {
		$info = json_decode($item->data);
		$edad = ( date("Y") - date('Y', strtotime($info->kv_fecha)  ) );
		$data['data'][] = [
			$item->id,
			'
				<div class="align_center">
					<!-- <span class="vlz_boton" > <i class="far fa-eye" onclick="_ver('.$item->id.')"></i> </span> -->
					<span class="vlz_boton" > <i class="fas fa-pencil-alt" onclick="_acceso_admin('.$item->user_id.')"></i> </span>
					<span class="vlz_boton vlz_boton_delete" > <i class="far fa-trash-alt" onclick="_delete('.$item->id.')"></i> </span>
				</div>
			',
			ucfirst($info->kv_nombre),
			$info->kv_email,
			$info->kv_telf_fijo.' / '.$info->kv_telf_movil,
			$edad.' años',
			'$'.$item->precio
		];
	}
	echo json_encode( $data );
?>