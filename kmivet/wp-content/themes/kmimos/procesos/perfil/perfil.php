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

	    $img_anterior = $db->get_var("SELECT meta_key FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'name_photo';");
	    if( $img_anterior != false ){
		    if( file_exists($dir.$img_anterior) ){
		        unlink($dir.$img_anterior);
		    }
		   	$wpdb->query("UPDATE wp_usermeta SET meta_value = '{$portada}' WHERE user_id = {$user_id} AND meta_key = 'name_photo';");
	    }else{
	    	$wpdb->query("INSERT INTO wp_usermeta VALUES ( NULL, {$user_id}, 'name_photo', '{$portada}');");
	    }
	}


	update_user_meta($user_id, "first_name", utf8_decode($first_name) );
	update_user_meta($user_id, "last_name", utf8_decode($last_name));
	update_user_meta($user_id, "user_phone", $phone);
	update_user_meta($user_id, "user_mobile", $mobile);
	update_user_meta($user_id, "user_referred", $referred);
	update_user_meta($user_id, "description", utf8_decode($descr));
	update_user_meta($user_id, "nickname", utf8_decode($nickname));
	update_user_meta($user_id, "user_recibir_fotos", utf8_decode($user_recibir_fotos));

	$wpdb->query("UPDATE wp_users SET display_name = '{$nickname}' WHERE ID = {$user_id}; ");

	$description = $wpdb->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'description'");
	if( $description == false ){
		$wpdb->query("INSERT INTO wp_usermeta VALUES (NULL, '{$user_id}', 'description', '{$descr}');");
	}

	$pass_change = "";
	if( $password != '' ){
		$password = md5($password);
		$wpdb->query("UPDATE wp_users SET user_pass = '{$password}' WHERE ID = {$user_id}; ");
		$pass_change = "SI";
	}

/*	if( $password != '' ){
		$info = array();
	    $info['user_login']     = sanitize_user($username, true);
	    $info['user_password']  = sanitize_text_field($password2);
	    $user_signon = wp_signon( $info, true );
	    wp_set_auth_cookie($user_signon->ID);
	}*/

	$respuesta = array(
		"status" 	  => "OK",
	);
?>