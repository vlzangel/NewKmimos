<?php
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))));

    include_once($raiz."/wp-load.php");

    //ini_set('display_errors', 'On');
   // error_reporting(E_ALL);

    extract($_POST);

    global $wpdb;
    $db = $wpdb;

    date_default_timezone_set('America/Mexico_City');
    $hoy = date("Y-m-d H:i:s");

    $respuesta = $db->get_row("SELECT * FROM nps_respuestas WHERE id = {$respuesta_id}");

    $email = $respuesta->email;
    $puntuacion = $respuesta->puntos;
    $comentarios = $db->get_var("SELECT comentario FROM nps_comentario WHERE code = '{$respuesta->code}'");

    $cuidador = $db->get_row("SELECT * FROM nps_feedback_cuidador WHERE email = '{$email}' ORDER BY id DESC LIMIT 0, 1");
    $cuidador_post = $cuidador->cuidador_id;
    $post_id = $cuidador->reserva_id;

    $comentado = get_post_meta($post_id, "customer_comment", true);

    if( $comentado !== false && $comentado != '' ){

    	echo json_encode([
    		"respuesta" => "Ya el cuidador recibió un valoración en este servicio"
    	]);

    }else{

	    $user_id = $db->get_var("SELECT ID FROM wp_users WHERE user_email = '{$email}'");
	    $nombre = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'first_name'");
	    $apellido = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'last_name'");

	    $sql = "SELECT * FROM wp_posts WHERE ID = ".$cuidador_post;
		$cuidador = $db->get_row($sql);

	    $sql = "
	    	INSERT INTO 
	    		wp_comments 
	    	VALUES (
	    		NULL, 
		    	'{$cuidador_post}', 
		    	'{$nombre} {$apellido}', 
		    	'{$email}', 
		    	'', 
		    	'', 
		    	'{$hoy}', 
		    	'{$hoy}', 
		    	'{$comentarios}', 
		    	'0', 
		    	'0', 
		    	'', 
		    	'', 
		    	'0', 
		    	'{$user_id}'
		   	)
		;";

		$db->query( ($sql) );
		$coment_id = $db->insert_id;

		switch ($puntuacion+0) {
			case 10:
				$cuidado		= 5;
				$puntualidad	= 5;
				$limpieza		= 5;
				$confianza		= 5;
			break;
			case 9:
				$cuidado		= 5;
				$puntualidad	= 5;
				$limpieza		= 5;
				$confianza		= 4;
			break;
			case 8:
				$cuidado		= 5;
				$puntualidad	= 5;
				$limpieza		= 4;
				$confianza		= 4;
			break;
			case 7:
				$cuidado		= 5;
				$puntualidad	= 4;
				$limpieza		= 4;
				$confianza		= 4;
			break;
			case 6:
				$cuidado		= 4;
				$puntualidad	= 4;
				$limpieza		= 4;
				$confianza		= 4;
			break;
			case 5:
				$cuidado		= 4;
				$puntualidad	= 4;
				$limpieza		= 4;
				$confianza		= 3;
			break;
			case 4:
				$cuidado		= 4;
				$puntualidad	= 4;
				$limpieza		= 3;
				$confianza		= 3;
			break;
			case 3:
				$cuidado		= 3;
				$puntualidad	= 3;
				$limpieza		= 3;
				$confianza		= 3;
			break;
			case 2:
				$cuidado		= 2;
				$puntualidad	= 2;
				$limpieza		= 2;
				$confianza		= 2;
			break;
			case 1:
				$cuidado		= 1;
				$puntualidad	= 1;
				$limpieza		= 1;
				$confianza		= 1;
			break;
		}

		$sql = "INSERT INTO wp_postmeta VALUES (NULL, '{$post_id}', 'customer_comment', '{$coment_id}'); ";
		$db->query( utf8_decode($sql) );

		$sql = "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'care', '{$cuidado}'); ";
		$db->query( utf8_decode($sql) );

		$sql = "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'punctuality', '{$puntualidad}'); ";
		$db->query( utf8_decode($sql) );

		$sql = "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'cleanliness', '{$limpieza}'); ";
		$db->query( utf8_decode($sql) );

		$sql = "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'trust', '{$confianza}'); ";
		$db->query( utf8_decode($sql) );

		vlz_actualizar_ratings($petsitter_id);

		$HTML = '
			<div style="margin: 0px auto; width: 600px;font-size: 13px; font-family: Arial;">
				<div style="margin-bottom: 10px;">
					Una nueva valoraci&oacute;n para el cuidador <a style="text-decoration: none;" href="'.get_home_url()."/petsitters/".$cuidador->post_name.'">"'.$cuidador->post_title.'"</a> está esperando tu aprobación.
				</div> 

				<div style="font-weight: 600;">
					Reserva: 
				</div>
				<div style="margin: 0px 10px 10px;">
					'.$post_id.'
				</div> 

				<div style="font-weight: 600;">
					Cliente: 
				</div>
				<div style="margin: 0px 10px 10px;">
					'.$nombre.' '.$apellido.'
				</div> 

				<div style="font-weight: 600;">
					Email cliente: 
				</div>
				<div style="margin: 0px 10px 10px;">
					'.$email.'
				</div> 

				<div style="font-weight: 600; margin-bottom: 10px;">
					Valoraci&oacute;n: 
				</div>
				<div style="margin: 0px 10px 10px;">
					<span style="font-weight: 600;">Cuidado</span>: '.$cuidado.'<br>
					<span style="font-weight: 600;">Puntualidad</span>: '.$puntualidad.'<br>
					<span style="font-weight: 600;">Limpieza</span>: '.$limpieza.'<br>
					<span style="font-weight: 600;">Confianza</span>: '.$confianza.'
				</div> 

				<div style="font-weight: 600; margin-bottom: 10px;">
					Comentarios: 
				</div> 

				<div style="margin-bottom: 25px;">
					'.$comentarios.'
				</div> 

				<div style="text-align: center;">
					<a style="display: inline-block; padding: 10px; text-decoration: none; border: solid 1px #CCC; padding: 5px 10px; font-size: 13px;" href="'.get_home_url().'/wp-admin/comment.php?action=approve&c='.$coment_id.'">
						Aprobarlo
					</a>
					<a style="display: inline-block; padding: 10px; text-decoration: none; border: solid 1px #CCC; padding: 5px 10px; font-size: 13px;" href="'.get_home_url().'/wp-admin/comment.php?action=trash&c='.$coment_id.'">
					Enviar a la papelera
					</a>
					<a style="display: inline-block; padding: 10px; text-decoration: none; border: solid 1px #CCC; padding: 5px 10px; font-size: 13px;" href="'.get_home_url().'/wp-admin/comment.php?action=spam&c='.$coment_id.'">
						Marcarlo como spam
					</a>
					<a style="display: inline-block; padding: 10px; text-decoration: none; border: solid 1px #CCC; padding: 5px 10px; font-size: 13px;" href="'.get_home_url().'/wp-admin/edit-comments.php?comment_status=moderated">
						M&aacute;s Moderaciones
					</a>
				</div> 

			</div> 
		';

		// wp_mail("contactomex@kmimos.la", "Nueva Valoraci&oacute;n para: ".$cuidador->post_title, $HTML);
		wp_mail("soporte.kmimos@gmail.com", "Nueva Valoración para: ".$cuidador->post_title, $HTML);
		// wp_mail("a.veloz@kmimos.la", "Nueva Valoración para: ".$cuidador->post_title, $HTML);

		$respuesta = array(
			"respuesta" => "Valoración enviada para aprobación"
		);

		echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    }
    
?>