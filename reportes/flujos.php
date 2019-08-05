<?php
	include dirname(__DIR__).'/wp-load.php';

	global $wpdb;

	$campaings = $wpdb->get_results("SELECT * FROM vlz_campaing ORDER BY creada ASC"); //  WHERE data LIKE '%hacer_despues\":\"0%'

	$flujos = [];
	/*
		$flujos[10] = [];
		$flujos[10][11] = [];
		$flujos[10][11][13] = [];
		$flujos[10][11][14] = [];
		$flujos[10][12] = [];
		$flujos[10][12][15] = [];
	*/

	foreach ($campaings as $key => $campaing) {
		$data = json_decode($campaing->data);
		if( $data->hacer_despues+0 == 0 ){
			$flujos[ $campaing->id ] = null;
		}else{
			$data = json_decode($campaing->data);
			$flujos[ $campaing->id ] = $data->campaing_anterior;
		}
	}

	echo "<pre>";
		print_r($flujos);
	echo "</pre>";
?>