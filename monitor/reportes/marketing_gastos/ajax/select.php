<?php

	include_once( dirname(dirname(dirname(__DIR__))).'/conf/database.php' );

	extract($_POST);

	if( isset($id) && $id > 0 ){
		$d = new db();

		$registro = $d->select( "SELECT * FROM monitor_marketing WHERE id = {$id} " );

		if( isset($registro[0]['id']) ){
			echo json_encode(['sts'=>1, 'data'=>$registro[0]]);
			exit();
		}
	}

	echo json_encode(['sts'=>0]);	
