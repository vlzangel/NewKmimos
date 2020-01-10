<?php
	extract($_POST);

	$sql  = "SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'user_favorites'";
	$favoritos = $db->get_var($sql, "meta_value");

    $_favoritos = (array) json_decode($favoritos);

	$fav = [];
	foreach ($_favoritos as $key => $value) {
		if( $value != "" && $value != $cuidador_id ){
			$fav[] = $value;
		}
	}

	$fav = json_encode($fav);

	$sql = "UPDATE wp_usermeta SET meta_value = '".$fav."' WHERE user_id = '".$user_id."' AND meta_key = 'user_favorites';";

	$db->query( utf8_decode($sql) );

	$respuesta = array(
		"status" => "OK",
		"sql"	 => $sql
	);

