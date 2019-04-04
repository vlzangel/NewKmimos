<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

    session_start();
    date_default_timezone_set('America/Mexico_City');

    include('../wp-load.php');
	global $wpdb;

	$hoy = date('Y-m-d', strtotime("NOW"));

	$sql = "
		SELECT * 
		FROM nps_feedback_cuidador 
		WHERE estatus = 1 
			AND ( segundo_intento = '{$hoy}' OR tercer_intento = '{$hoy}' );
	";
	$reservas = $wpdb->get_results($sql);

	foreach($reservas as $key => $reserva){


		$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE estatus = 1 user_id = ".$reserva->cuidador_id);
		$user = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE user_email = ".$reserva->email );

		$nombre  = get_post_meta($user->ID, 'first_name', true);
		$nombre .= get_post_meta($user->ID, 'last_name', true);

		// Asunto segundo intento
		$asunto = '¡Queremos mejorar para ti y tu mejor amigo! 🐶😺 Ayúdanos a contestar esta breve encuesta de 1 minuto sobre tu experiencia con Kmimos'; 
		$estatus = 1;

		// Asunto tercer intento
		if( $reserva->intentos_cant == 2 ){
			$asunto = 'Ayuda a cientos de peluditos 🐶😺 que se quedan a diario con Kmimos compartiendo tu experiencia. ¡Siempre queremos mejorar para ti!'; 
			$estatus = 0;
		}


		// Construir y enviar email
		$mensaje = buildEmailTemplate(
			'nps/feedback_cliente',
			[
				'id'=> $reserva->id,
				'email' => $reserva->email,
				'nombre' => $nombre,
				'IMG_URL' => get_recurso('img/NPS'),
			]
		);

		// Enviar email

		// $reserva->email = 'italococchini@gmail.com'; //testing

		if( wp_mail($reserva->email, $asunto, $mensaje) ){
			$wpdb->query( "UPDATE nps_feedback_cuidador SET estatus = {$estatus}, intentos_cant = (intentos_cant + 1) WHERE id =".$reserva->id );
		}

	}