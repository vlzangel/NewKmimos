<?php
	$data = ['data' => []];
	$registros = $wpdb->get_results("SELECT * FROM {$pf}{$mod}");
	foreach ($registros as $key => $item) {
		$info = json_decode($item->data);
		$edad = ( date("Y") - date('Y', strtotime($info->kv_fecha)  ) );

		$status = 'Activo';
		if( $item->status == 0 ){
			$status = 'Inactivo';
			$accion = '<a href="#" onclick="_activar('.$item->user_id.')" title="Activar Veterinario">Activar</a>';
		}else{
			$accion = '<a href="#" onclick="_desactivar('.$item->user_id.')" title="Desactivar Veterinario">Desactivar </a>';			
		}

		$data['data'][] = [
			$item->id,
			'
				<div class="align_center">
					'.$accion.'
					<!-- <span class="vlz_boton vlz_boton_delete" > <i class="far fa-trash-alt" onclick="_delete('.$item->id.')"></i> </span> -->
				</div>
			',
			$status,
			ucfirst($info->kv_nombre),
			$info->kv_email,
			$info->kv_telf_fijo.' / '.$info->kv_telf_movil,
			$edad.' años',
			'$'.$item->precio
		];
	}
	echo json_encode( $data );
?>