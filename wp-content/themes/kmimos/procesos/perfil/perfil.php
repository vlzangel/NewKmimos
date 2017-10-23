<?php 
	
	if($portada != ""){
	    $dir = $raiz."/wp-content/uploads/{$sub_path}/";
	    @mkdir($dir);
	    $path_origen = $raiz."/imgs/Temp/".$portada;
	    $path_destino = $dir.$portada;
	    if( file_exists($path_origen) ){
	        copy($path_origen, $path_destino);
	        unlink($path_origen);
	    }

	    $img_anterior = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'name_photo';");
	    if( $img_anterior != false ){
		    if( file_exists($dir.$img_anterior) ){
		        unlink($dir.$img_anterior);
		    }

		    $img_portada = "UPDATE wp_usermeta SET meta_value = '{$portada}' WHERE user_id = {$user_id} AND meta_key = 'name_photo';";
	    }else{
	    	$img_portada = "INSERT INTO wp_usermeta VALUES ( NULL, {$user_id}, 'name_photo', '{$portada}');";
	    }
	}

	$sql  = "UPDATE wp_users SET display_name = '{$nickname}' WHERE ID = {$user_id}; ";
	$sql .= "UPDATE wp_usermeta SET meta_value = '{$first_name}' WHERE user_id = {$user_id} AND meta_key = 'first_name';";
	$sql .= "UPDATE wp_usermeta SET meta_value = '{$last_name}' WHERE user_id = {$user_id} AND meta_key = 'last_name';";
	$sql .= "UPDATE wp_usermeta SET meta_value = '{$phone}' WHERE user_id = {$user_id} AND meta_key = 'user_phone';";
	$sql .= "UPDATE wp_usermeta SET meta_value = '{$mobile}' WHERE user_id = {$user_id} AND meta_key = 'user_mobile';";
	$sql .= "UPDATE wp_usermeta SET meta_value = '{$referred}' WHERE user_id = {$user_id} AND meta_key = 'user_referred';";
	$sql .= "UPDATE wp_usermeta SET meta_value = '{$descr}' WHERE user_id = {$user_id} AND meta_key = 'description';";
	$sql .= "UPDATE wp_usermeta SET meta_value = '{$nickname}' WHERE user_id = {$user_id} AND meta_key = 'nickname';";
	if( isset($img_portada) ){
		$sql .= $img_portada;
	}

	$description = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'description'");
	if( $description == false ){
		$sql .= "INSERT INTO wp_usermeta VALUES (NULL, '{$user_id}', 'description', '{$descr}');";
	}

	$pass_change = "";
	if( $password != '' ){
		$password = md5($password);
		$sql .= "UPDATE wp_users SET user_pass = '{$password}' WHERE ID = {$user_id}; ";
		$pass_change = "SI";
	}

	$db->query_multiple( utf8_decode($sql) );

	if( $password != '' ){
		include_once($raiz."/wp-load.php");

		$info = array();
	    $info['user_login']     = sanitize_user($username, true);
	    $info['user_password']  = sanitize_text_field($password2);

	    $user_signon = wp_signon( $info, true );
	    wp_set_auth_cookie($user_signon->ID);
	}

	$respuesta = array(
		"status" 	  => "OK",
		"username"	  => $username,
		"pass_change" => $pass_change
	);
?>