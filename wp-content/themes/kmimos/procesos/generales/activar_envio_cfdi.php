<?php
	include("../../../../../vlz_config.php");
	include("../funciones/db.php");

	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn);

	extract($_POST);
	if( isset($user_id) && $user_id > 0 ){	
		$db->query( "DELETE FROM wp_usermeta WHERE meta_key = 'auto_facturar' and user_id = {$user_id} " );
		$db->query( "INSERT INTO wp_usermeta ( user_id, meta_key, meta_value ) VALUES ( '{$user_id}', 'auto_facturar', 'on' ); " );
		echo 'SI';
		exit();
	}
	echo 'NO';
