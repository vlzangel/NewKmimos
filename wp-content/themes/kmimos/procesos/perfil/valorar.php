<?php
	
    date_default_timezone_set('America/Mexico_City');
    $hoy = date("Y-m-d H:i:s");

    $email = $db->get_var("SELECT user_email FROM wp_users WHERE ID = {$user_id}");
    $nombre = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'first_name'");
    $apellido = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'last_name'");

    $sql = "
    	INSERT INTO 
    		wp_comments 
    	VALUES (
    		NULL, 
	    	'{$petsitter_id}', 
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

	$db->query( utf8_decode($sql) );
	$coment_id = $db->insert_id();

	$sql  = "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'care', '{$cuidado}'); ";
	$sql .= "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'punctuality', '{$puntualidad}'); ";
	$sql .= "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'cleanliness', '{$limpieza}'); ";
	$sql .= "INSERT INTO wp_commentmeta VALUES (NULL, '{$coment_id}', 'trust', '{$confianza}'); ";

	$sql .= "INSERT INTO wp_postmeta VALUES (NULL, '{$post_id}', 'customer_comment', '{$coment_id}'); ";

	$db->query_multiple( utf8_decode($sql) );

	$HTML = '
		<div style="margin: 0px auto; width: 600px;font-size: 17px;">
			<div style="margin-bottom: 10px;">
				Una nueva valoraci&oacute;n para el cuidador <a style="text-decoration: none;" href="[LINK_CUIDADOR]">"[CUIDADOR]"</a> está esperando tu aprobación.
			</div> 

			<div style="font-weight: 600; margin-bottom: 10px;">
				Valoraci&oacute;n: 
			</div> 

			<div style="margin-bottom: 25px;">
				[COMENTARIO]
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

	$comentario = $comentarios;

	$cuidador = $db->get_row("SELECT * FROM wp_posts WHERE ID = {$petsitter_id}");

	$HTML = str_replace("[CUIDADOR]", $cuidador->post_title, $HTML);
	$HTML = str_replace("[COMENTARIO]", $comentario, $HTML);
	$HTML = str_replace("[LINK_CUIDADOR]", get_home_url()."/petsitters/".$cuidador->post_name, $HTML);

	// wp_mail("contactomex@kmimos.la", "Nueva Valoraci&oacute;n para: ".$cuidador->post_title, $HTML);
	wp_mail("soporte.kmimos@gmail.com", "Nueva Valoración para: ".$cuidador->post_title, $HTML);

	$respuesta = array(
		"status" => "OK"
	);
?>