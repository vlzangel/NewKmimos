<?php
	
	include __DIR__.'/wp-load.php';

	global $wpdb;

	$campaing = $wpdb->get_row("SELECT * FROM vlz_campaing WHERE id = 22");

	echo $campaing->plantilla.'<br><br><br>';

	$info_validacion = base64_encode( json_encode( [
		"id" => $campaing->id,
		"type" => "img",
		"format" => "png",
		"email" => $email
	] ) );
	$mensaje = $campaing->plantilla.'<img src="'.get_home_url().'/campaing_2/'.$info_validacion.'/'.md5($info_validacion).'.png" />';
	$mensaje = add_seguimiento_($mensaje, [
		"campaing" => $campaing->id,
		"email" => trim($email),
	], true);
	$info_desuscribir = base64_encode( json_encode( [
		"campaing_id" => $campaing->id,
		"email" => $email
	] ) );
	$mensaje = str_replace("#FIN_SUSCRIPCION#", get_home_url().'/campaing_2/'.$info_desuscribir.'/end', $mensaje);


	// wp_mail('vlzangel91@gmail.com', 'Test', $mensaje);

?>