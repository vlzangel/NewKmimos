<?php
	$data = ['data' => []];
	$registros = $wpdb->get_results("SELECT * FROM {$pf}{$mod}");
	foreach ($registros as $key => $item) {
		$info = json_decode($item->data);

		$data['data'][] = [
			$item->id,
			$info->nombre,
			'
				<div class="align_right">
					<span class="vlz_boton" > <i class="far fa-eye" onclick="_ver('.$item->id.')"></i> </span>
					<span class="vlz_boton" > <i class="fas fa-pencil-alt" onclick="_edit('.$item->id.')"></i> </span>
					<span class="vlz_boton vlz_boton_delete" > <i class="far fa-trash-alt" onclick="_delete('.$item->id.')"></i> </span>
				</div>
			'
		];
	}
	echo json_encode( $data );
?>