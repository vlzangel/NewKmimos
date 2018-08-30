<?php
	
    $file = $PATH_TEMPLATE.'/template/mail/conocer/cliente/confirmar.php';
    $mensaje_cliente = file_get_contents($file);
    
	$wpdb->query("UPDATE wp_postmeta SET meta_value = '2' WHERE post_id = $id_orden AND meta_key = 'request_status';");
	$wpdb->query("UPDATE wp_posts SET post_status = 'publish' WHERE ID = '{$id_orden}';");

    $mensaje_cliente = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cliente);
    $mensaje_cliente = str_replace('[name_cuidador]', $cuidador_name, $mensaje_cliente);
    $mensaje_cliente = str_replace('[name_cliente]', $cliente_name, $mensaje_cliente);

    $fin = strtotime( str_replace("/", "-", $_POST['service_end']) );

    $datos_cuidador = $PATH_TEMPLATE.'/template/mail/reservar/partes/datos_cuidador.php';
    $datos_cuidador = file_get_contents($datos_cuidador);
    $datos_cuidador = preg_replace("#<tr class='dir'(.*?)tr>#s", "", $datos_cuidador);
    $mensaje_cliente = str_replace('[DATOS_CUIDADOR]', $datos_cuidador, $mensaje_cliente);

    $mensaje_cliente = str_replace('[HEADER]', "conocer", $mensaje_cliente);
    $mensaje_cliente = str_replace('[name]', $cliente_web, $mensaje_cliente);
    $mensaje_cliente = str_replace('[avatar_cuidador]', kmimos_get_foto($cuidador->user_id), $mensaje_cliente);
    $mensaje_cliente = str_replace('[name_cuidador]', $nombre_cuidador, $mensaje_cliente);
    $mensaje_cliente = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cliente);
    $mensaje_cliente = str_replace('[telefonos_cuidador]', $telf_cuidador, $mensaje_cliente);
    $mensaje_cliente = str_replace('[correo_cuidador]', $email_cuidador, $mensaje_cliente);
    $mensaje_cliente = str_replace('[id_solicitud]', $request_id, $mensaje_cliente);
    $mensaje_cliente = str_replace('[fecha]', $_POST['meeting_when'], $mensaje_cliente);
    $mensaje_cliente = str_replace('[hora]', $_POST['meeting_time'], $mensaje_cliente);
    $mensaje_cliente = str_replace('[lugar]', $_POST['meeting_where'], $mensaje_cliente);
    $mensaje_cliente = str_replace('[desde]', date("d/m", strtotime( str_replace("/", "-", $metas_solicitud["service_start"][0]) )), $mensaje_cliente);
    $mensaje_cliente = str_replace('[hasta]', date("d/m", $fin), $mensaje_cliente);
    $mensaje_cliente = str_replace('[anio]', date("Y", $fin), $mensaje_cliente);

    $mensaje_cliente = get_email_html($mensaje_cliente, false, false, $cliente, false );

    if( isset($NO_ENVIAR) ){
        echo $mensaje_cliente;
    }else{
        // wp_mail( "a.veloz@kmimos.la",  "Conf. Cliente [{$email_cliente}]", $mensaje_cliente);
        wp_mail( $email_cliente, "Confirmación de Solicitud para Conocer Cuidador", $mensaje_cliente);     
    } 
    
    $file = $PATH_TEMPLATE.'/template/mail/conocer/cuidador/confirmar.php';
    $mensaje_cuidador = file_get_contents($file);


    $mensaje_cuidador = str_replace('[HEADER]', "conocer", $mensaje_cuidador);
    $mensaje_cuidador = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cuidador);
    $mensaje_cuidador = str_replace('[name_cuidador]', $cuidador_name, $mensaje_cuidador);
    $mensaje_cuidador = str_replace('[name_cliente]', $cliente_name, $mensaje_cuidador);

    $mensaje_cuidador = get_email_html($mensaje_cuidador, false, false, $cliente, false );  

    if( isset($NO_ENVIAR) ){
        echo $mensaje_cuidador;
    }else{
        // wp_mail( "a.veloz@kmimos.la",  "Conf. Cuidador [{$email_cuidador}]", $mensaje_cuidador);
        wp_mail( $email_cuidador, "Confirmación de Solicitud para Conocerte", $mensaje_cuidador);       
    } 

	$file = $PATH_TEMPLATE.'/template/mail/conocer/admin/confirmar.php';
    $mensaje_admin = file_get_contents($file);

    $mensaje_admin = str_replace('[HEADER]', "conocer", $mensaje_admin);
    $mensaje_admin = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_admin);
    $mensaje_admin = str_replace('[id_solicitud]', $id_orden, $mensaje_admin);
    $mensaje_admin = str_replace('[name_cuidador]', $cuidador_name, $mensaje_admin);
    $mensaje_admin = str_replace('[name_cliente]', $cliente_name, $mensaje_admin);

    $mensaje_admin = get_email_html($mensaje_admin, false, false, $cliente, false );   

    $mensaje_admin = str_replace(get_home_url(), "http://kmimosmx.sytes.net/QA2/", $mensaje_admin); 

    if( isset($NO_ENVIAR) ){
        echo $mensaje_admin;
    }else{
        kmimos_mails_administradores_new("Confirmación de Solicitud para Conocer a ".$cuidador_name, $mensaje_admin);
    } 
    
    $CONTENIDO .= "<div class='msg_acciones'>
        <strong>¡Todo esta listo!</strong><br>
        La solicitud para conocer cuidador <strong>#".$id_orden."</strong>, ha sido confirmada exitosamente de acuerdo a tu petición.
    </div>";