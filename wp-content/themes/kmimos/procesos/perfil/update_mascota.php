<?php 

	if($portada != ""){
		$tmp_user_id = ($user_id) - 5000;
	    $sub_path = "/wp-content/uploads/mypet/{$tmp_user_id}/";
	    $dir = $raiz.$sub_path;
	    @mkdir($dir);
	    $path_origen = $raiz."/imgs/Temp/".$portada;
	    $path_destino = $dir.$portada;
	    if( file_exists($path_origen) ){
	        copy($path_origen, $path_destino);
	        unlink($path_origen);
	    }

	    $img_anterior = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$pet_id} AND meta_key = 'photo_pet';", "meta_value");
	    if( file_exists($raiz.$img_anterior) ){
	        unlink($raiz.$img_anterior);
	    }
	    if($img_anterior == ""){
	    	$img_portada = "INSERT INTO wp_postmeta VALUES (NULL, '{$pet_id}', 'photo_pet', '{$sub_path}{$portada}')";
	    }else{
	   		$img_portada = "UPDATE wp_postmeta SET meta_value = '{$sub_path}{$portada}' WHERE post_id = {$pet_id} AND meta_key = 'photo_pet';";
	    }
	}

	$sql  = "UPDATE wp_posts 	SET post_title = '{$pet_name}' 			WHERE ID 	  = {$pet_id};";
	$sql .= "UPDATE wp_postmeta SET meta_value = '{$pet_name}' 			WHERE post_id = {$pet_id} AND meta_key = 'name_pet';";
	$sql .= "UPDATE wp_postmeta SET meta_value = '{$pet_breed}' 		WHERE post_id = {$pet_id} AND meta_key = 'breed_pet';";
	$sql .= "UPDATE wp_postmeta SET meta_value = '{$pet_colors}' 		WHERE post_id = {$pet_id} AND meta_key = 'colors_pet';";
	$sql .= "UPDATE wp_postmeta SET meta_value = '{$pet_birthdate}' 	WHERE post_id = {$pet_id} AND meta_key = 'birthdate_pet';";
	$sql .= "UPDATE wp_postmeta SET meta_value = '{$pet_size}' 			WHERE post_id = {$pet_id} AND meta_key = 'size_pet';";
	$sql .= "UPDATE wp_postmeta SET meta_value = '{$pet_gender}'  		WHERE post_id = {$pet_id} AND meta_key = 'gender_pet';";
	$sql .= "UPDATE wp_postmeta SET meta_value = '{$pet_sterilized}' 	WHERE post_id = {$pet_id} AND meta_key = 'pet_sterilized';";
	$sql .= "UPDATE wp_postmeta SET meta_value = '{$pet_sociable}' 		WHERE post_id = {$pet_id} AND meta_key = 'pet_sociable';";
	$sql .= "UPDATE wp_postmeta SET meta_value = '{$aggresive_humans}' 	WHERE post_id = {$pet_id} AND meta_key = 'aggressive_with_humans';";
	$sql .= "UPDATE wp_postmeta SET meta_value = '{$aggresive_pets}' 	WHERE post_id = {$pet_id} AND meta_key = 'aggressive_with_pets';";
	$sql .= "UPDATE wp_postmeta SET meta_value = '{$pet_observations}' 	WHERE post_id = {$pet_id} AND meta_key = 'about_pet';";

	$sql .= "UPDATE wp_term_relationships SET term_taxonomy_id = '{$pet_type}' 	WHERE object_id = {$pet_id};";

	$sql .= $img_portada;

	$db->query_multiple( utf8_decode($sql) );

	$respuesta = array(
		"status" => "OK",
		"sql"	 => $sql
	);
?>