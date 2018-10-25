<?php

	if($_POST){
		extract($_POST);
	}

	$sql  = "SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'user_favorites'";
	$favoritos = $db->get_var($sql, "meta_value");

    $favoritos = (array) json_decode($favoritos);

	$index = 0;
	foreach ($favoritos as $key => $value) {
		if( $value == $cuidador_id ){
			$index = $key;
		}
	}
	unset($favoritos[$index]);

	$favoritos = json_encode($favoritos);

	$sql = "UPDATE wp_usermeta SET meta_value = '".$favoritos."' WHERE user_id = '".$user_id."' AND meta_key = 'user_favorites';";

	$db->query( utf8_decode($sql) );

	$respuesta = array(
		"status" => "OK",
		"sql"	 => $sql
	);

