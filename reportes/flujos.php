<?php
	include dirname(__DIR__).'/wp-load.php';

	global $wpdb;

	$campaings = $wpdb->get_results("SELECT * FROM vlz_campaing ORDER BY creada ASC");
	$flujos = [];
	foreach ($campaings as $key => $campaing) {
		$data = json_decode($campaing->data);
		if( $data->hacer_despues+0 == 0 ){
			$flujos[ $campaing->id ] = [
				$data->data->titulo
			];
		}else{
			$data = json_decode($campaing->data);
			$flujos[ $campaing->id ] = [
				$data->data->titulo,
				$data->campaing_anterior
			];
		}
	}
?>