<?php
	$data['data'] = [];

	extract(get_dias_meses());

	$veterinario = $wpdb->get_row( "SELECT * FROM {$pf}veterinarios WHERE user_id = '{$user_id}' ORDER BY id DESC" );
	$agenda = (array) json_decode($veterinario->agenda);

	foreach ($agenda as $dia => $horario) {
		
		$rangos = [];
		foreach ($horario as $key2 => $value2) {
			$rangos[] = '<div> <i onclick="_eliminar( jQuery(this) )" class="far fa-trash-alt" data-accion="eliminar" data-id="'.$dia.'_'.$key2.'" ></i> '.date("h:i a", strtotime($value2->ini)).' a '.date("h:i a", strtotime($value2->fin))."</div>";
		}
		$rangos = implode("", $rangos);

		$data['data'][] = [
			$dias_slug[ $dia ],
			$rangos
		];
	}

	die( json_encode($data) );

	// fas fa-circle-notch
?>