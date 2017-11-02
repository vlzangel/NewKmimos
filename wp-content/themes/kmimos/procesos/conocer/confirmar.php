<?php
	
    $file = $PATH_TEMPLATE.'/template/mail/conocer/confirmar_cliente.php';
    $mensaje_cliente = file_get_contents($file);
    
	$wpdb->query("UPDATE wp_postmeta SET meta_value = '2' WHERE post_id = $id_orden AND meta_key = 'request_status';");
	$wpdb->query("UPDATE wp_posts SET post_status = 'publish' WHERE ID = '{$id_orden}';");

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

    $mensaje_cuidador = get_email_html($mensaje_cuidador, false, false);

    wp_mail( $email_cuidador, "Confirmación de Solicitud para Conocerte", $mensaje_cuidador);

    kmimos_mails_administradores_new("Confirmación de Solicitud para Conocerte", $mensaje_cliente);

    $CONTENIDO .= "<div class='msg_acciones'>
        <strong>¡Todo esta listo!</strong><br>
        La solicitud para conocer cuidador <strong>#".$id_orden."</strong>, ha sido confirmada exitosamente de acuerdo a tu petición.
    </div>";
?>