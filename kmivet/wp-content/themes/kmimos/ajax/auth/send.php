<?php
	$user_id = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email = '{$email}' ");
	$tipo = get_user_meta($user_id, 'tipo_usuario', true);
	update_user_meta($user_id, '_token_notification', $uid);
	if( $tipo == 'veterinario' ){
		$veterinario_id = $wpdb->get_var("SELECT veterinario_id FROM wp_kmivet_veterinarios WHERE user_id = '{$user_id}' ");
		$r = send_token_veterinario($veterinario_id, $uid);
	}else{
		$paciente_id = get_user_meta($user_id, '_mediqo_customer_id', true);
		$r = send_token_paciente($paciente_id, $uid);
	}
	update_user_meta($user_id, '_token_send', json_encode($r));

	die(json_encode([
		"status" => true,
		"res" => $r
	]));
?>