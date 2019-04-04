<?php    
error_reporting(E_ALL);
ini_set('display_errors', '1');

    session_start();
    date_default_timezone_set('America/Mexico_City');
    include('../wp-load.php');
	global $wpdb;

	$yesterday = date( "Ymd", strtotime("NOW -1 day") )."235959";

	$sql = "
		SELECT m.meta_value as '_booking_end', p.*
		FROM wp_posts as p
			INNER JOIN wp_postmeta as m ON m.post_id = p.ID AND m.meta_key = '_booking_end'
		WHERE p.post_type = 'wc_booking'
			AND p.post_status in ('confirmed', 'completed')
			AND m.meta_value = '{$yesterday}' 
		ORDER BY m.meta_value DESC;
	";

	$reservas = $wpdb->get_results($sql);
	foreach($reservas as $key => $reserva){

		$_metas_reserva = get_post_meta($reserva->ID);
		$_metas_orden = get_post_meta($reserva->post_parent);

		// Services
		$servicio = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = ".$_metas_reserva['_booking_product_id'][0]);
		
		// Reserva
		$reserva_status = $reserva->post_status;
		$orden_status = $wpdb->get_var("SELECT post_status FROM $wpdb->posts WHERE ID = ".$reserva->post_parent);

		// Usuario
		$user = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE ID = ".$reserva->post_author );

		// Cuidador
		$cuidador_user_id = $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = ".$_metas_reserva['_booking_product_id'][0]);
		$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = ".$cuidador_user_id);

		// Variables
		$creada = strtotime( $reserva->post_date );
		$inicio = strtotime( $_metas_reserva['_booking_start'][0] );
		$fin    = strtotime( $_metas_reserva['_booking_end'][0] );

		// Validar y enviar email
		if( $reserva->post_status=='complete' || ( $reserva->post_status=='confirmed' && strtotime($_metas_reserva['_booking_end'][0]) < time() )){

			$primer = date('Y-m-d', strtotime("NOW"));
			$segundo = date('Y-m-d', strtotime($primer." +7 day "));
			$tercer = date('Y-m-d', strtotime($segundo." +14 day "));

			$_sql="select * from nps_feedback_cuidador where reserva_id = ".$reserva->ID;
			$sended = $wpdb->get_row($_sql);
			if( !isset($sender->reserva_id ) ){
				$sql = "INSERT INTO nps_feedback_cuidador( 
						email, 
						reserva_id, 
						cuidador_id, 
						primer_intento,
						segundo_intento,
						tercer_intento, 
						intentos_cant, 
						estatus
					) VALUES ( 
						'".$user->user_email."',
						".$reserva->ID.",
						'".$cuidador->user_id."',
						'".$primer."',
						'".$segundo."',
						'".$tercer."',
						1,
						1
					);
				";
				$wpdb->query( $sql );

				$id = $wpdb->get_var( "SELECT id FROM nps_feedback_cuidador WHERE reserva_id =".$reserva->ID );
				$nombre  = get_post_meta($user->ID, 'first_name', true);
				$nombre .= get_post_meta($user->ID, 'last_name', true);
				
				// Construir y enviar email
				$mensaje = buildEmailTemplate(
					'nps/feedback_cliente',
					[
						'id' => $reserva->id,
						'email' => $user->user_email,
						'nombre' => $nombre,
						'IMG_URL' => get_recurso('img/NPS'),
					]
				);

				if( wp_mail( $user->user_email, '¿Cómo cuidamos a tu peludo 🐶😺? Ayúdanos a mejorar contestando esta breve encuesta sobre tu reserva con Kmimos.', $BODY) ){
				}
			}
		}
	}