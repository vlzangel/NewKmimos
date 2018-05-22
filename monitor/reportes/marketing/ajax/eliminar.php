<?php
	include_once( dirname(dirname(dirname(__DIR__))).'/conf/database.php' );

	extract($_POST);

	if( isset($id) && $id > 0 ){
		$sql = "DELETE FROM monitor_marketing WHERE id = {$id}";
		$d = new db();
		$d->delete( $sql );
		echo json_encode(['sts'=>1]);
		exit();
	}

	echo json_encode(['sts'=>0]);	
