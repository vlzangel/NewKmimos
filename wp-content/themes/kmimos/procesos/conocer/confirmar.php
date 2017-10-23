<?php
	

	$wpdb->query("UPDATE wp_postmeta SET meta_value = '2' WHERE post_id = $id_orden AND meta_key = 'request_status';");
	$wpdb->query("UPDATE wp_posts SET post_status = 'publish' WHERE ID = '{$id_orden}';");

	$metas_solicitud = get_post_meta($id_orden); 

	/* Cuidador */
    	$cuidador_name 	= $wpdb->get_var("SELECT post_title FROM wp_posts WHERE ID = '".$metas_solicitud['requested_petsitter'][0]."'");
		$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = '".$metas_solicitud['requested_petsitter'][0]."'");
		$email_cuidador = $cuidador->email;

    /* Cliente */

	    $cliente = $metas_solicitud['requester_user'][0];
		$metas_cliente = get_user_meta($cliente);
		$cliente_name = $metas_cliente["first_name"][0];

		$user = get_user_by( 'id', $cliente );
		$email_cliente = $user->data->user_email;

	$file = $PATH_TEMPLATE.'/template/mail/conocer/confirmar_cliente.php';
    $mensaje_cliente = file_get_contents($file);

    $mensaje_cliente = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cliente);
    $mensaje_cliente = str_replace('[name_cuidador]', $cuidador_name, $mensaje_cliente);
    $mensaje_cliente = str_replace('[name_cliente]', $cliente_name, $mensaje_cliente);


    $mensaje_cliente = get_email_html($mensaje_cliente, false, false);

    wp_mail( $email_cliente, "Confirmación de Solicitud para Conocer Cuidador", $mensaje_cliente);



	$file = $PATH_TEMPLATE.'/template/mail/conocer/confirmar_cuidador.php';
    $mensaje_cuidador = file_get_contents($file);

    $mensaje_cuidador = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cuidador);
    $mensaje_cuidador = str_replace('[name_cuidador]', $cuidador_name, $mensaje_cuidador);
    $mensaje_cuidador = str_replace('[name_cliente]', $cliente_name, $mensaje_cuidador);

    echo $mensaje_cuidador = get_email_html($mensaje_cuidador, false, false);

    wp_mail( $email_cuidador, "Confirmación de Solicitud para Conocerte", $mensaje_cuidador);
?>