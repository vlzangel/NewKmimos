<?php

    date_default_timezone_set('America/Mexico_City');

    extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    global $wpdb;

    $orden_status = $wpdb->query("
        DELETE FROM wp_postmeta WHERE post_id = '{$cupon_id}' AND meta_key = '_used_by' AND meta_value = '{$user_id}'
    ");

    $cupon = $wpdb->get_var("SELECT post_title FROM wp_posts WHERE ID = {$cupon_id}");

    $current_user = wp_get_current_user();
    $admin_user_id = $current_user->ID;

    $admin_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$admin_user_id}");
    $metas_admin = get_user_meta($admin_user_id);

    $cliente_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$user_id}");
    $metas_cliente = get_user_meta($user_id);


    $PATH_TEMPLATE = dirname(dirname(dirname(dirname(__DIR__))));
    $file = $PATH_TEMPLATE.'/template/mail/especiales/cupones/admin.php';
    $mensaje = file_get_contents($file);

    $mensaje = str_replace('[ADMIN]', $metas_admin["first_name"][0]." ".$metas_admin["last_name"][0], $mensaje);
    $mensaje = str_replace('[ADMIN_EMAIL]', $admin_email, $mensaje);

    $mensaje = str_replace('[CLIENTE]', $metas_cliente["first_name"][0]." ".$metas_cliente["last_name"][0], $mensaje);
    $mensaje = str_replace('[CLIENTE_EMAIL]', $cliente_email, $mensaje);

    $mensaje = str_replace('[CUPON]', $cupon, $mensaje);

    $mensaje = str_replace('[FECHA]', date("d/m/Y H:i a") , $mensaje);
    
    $mensaje = get_email_html($mensaje);

    wp_mail( "a.veloz@kmimos.la", "Liberación de Cupón", $mensaje);
    wp_mail( "chaudaryy@gmail.com", "Liberación de Cupón", $mensaje);

?>