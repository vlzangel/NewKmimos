<?php
    include( dirname(dirname(dirname(dirname(dirname(__DIR__)))))."/wp-config.php");

    extract($_POST);
    
    global $wpdb;

    $db = $wpdb;

    $home = $db->get_var("SELECT option_value FROM wp_options WHERE option_name = 'siteurl'");

    $USER = $db->get_row("SELECT * FROM wp_users WHERE user_email = '{$email}'");

    if( $ID === false ){

    }else{
        $keyKmimos = md5($USER->ID);
        $url_activate = $home."restablecer/?r=".$keyKmimos;
        $mensaje = kv_get_email_html(
            'recuperar', 
            [
                "URL_IMGS" => $home."/wp-content/themes/kmimos/images/emails",
                "url"         => $url_activate,
                "name"        => get_user_meta($USER->ID, "first_name", true)
            ]
        );
        wp_mail($USER->user_email,  'Cambiar contraseña en Kmimos', $mensaje);
        $response['sts'] = 1;
        $response['msg'] = 'Hemos enviado los pasos para restablecer la contraseña a tu correo.';
        echo json_encode($response);
    }

    exit();

?>