<?php
    error_reporting(0);
	
	extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    global $wpdb;


    $current_user = wp_get_current_user();
    $admin_user_id = $current_user->ID;

    $admin_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$admin_user_id}");
    $metas_admin = get_user_meta($admin_user_id);

    $file = $PATH_TEMPLATE.'/template/mail/status/fallida_openpay.php';
    $mensaje = file_get_contents($file);

    $mensaje = str_replace('[RESERVA]', $reserva->ID, $mensaje);

    $mensaje = str_replace('[ADMIN]', $metas_admin["first_name"][0]." ".$metas_admin["last_name"][0], $mensaje);
    $mensaje = str_replace('[ADMIN_EMAIL]', $admin_email, $mensaje);

    $mensaje = str_replace('[CLIENTE]', $metas_cliente["first_name"][0]." ".$metas_cliente["last_name"][0], $mensaje);
    $mensaje = str_replace('[CLIENTE_EMAIL]', $cliente_email, $mensaje);

    $mensaje = str_replace('[CUIDADOR]', $metas_cuidador["first_name"][0]." ".$metas_cuidador["last_name"][0], $mensaje);
    $mensaje = str_replace('[CUIDADOR_EMAIL]', $cuidador_email, $mensaje);

    $mensaje = str_replace('[ORIGINAL]', $status_actual, $mensaje);
    $mensaje = str_replace('[FINAL]', $new_status, $mensaje);
    $mensaje = str_replace('[FECHA]', date("d/m/Y H:i a") , $mensaje);
    
    $mensaje = get_email_html($mensaje);

    wp_mail( "a.veloz@kmimos.la", "Actualización de Status", $mensaje);
    // wp_mail( "chaudaryy@gmail.com", "Actualización de Status", $mensaje);

	exit;
?>