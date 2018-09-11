<?php
	$kmimos_load = (dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}

	global $wpdb;

	extract($_POST);

	$info = $wpdb->get_row("SELECT * FROM terminos_aceptados WHERE user_id = '{$user_id}' ");

	if( $info == null ){
		echo json_encode(["error" => "si"]);
	}else{
		$info->fecha = date("d/m/Y H:i", strtotime( $info->fecha ) );
		echo json_encode([
			"error" => "no",
			"info" => $info
		]);
	}
?>