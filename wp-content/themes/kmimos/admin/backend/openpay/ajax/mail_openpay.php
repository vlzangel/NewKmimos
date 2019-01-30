<?php
    error_reporting(E_ALL);

    ini_set('display_errors', 'On');

    $CORREO_OPENPAY = "true";
    
    extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    global $wpdb;

    $wpdb->query("
        UPDATE 
            solicitudes_openpay 
        SET
            status = 'Correo Enviado'
        WHERE 
            solicitante = '{$user_id}' AND
            reserva = '{$reserva}'
    ;");

    $info = json_decode( $json );

    $info = $info[0];

    $file = dirname(dirname(dirname(dirname(__DIR__)))).'/template/mail/status/fallida_openpay.php';
    $mensaje = file_get_contents($file);

    $data = [
        "ID" => $info->cliente->id,
        "CLIENTE" => $info->cliente->nombre,
        "CREACION" => $info->cliente->creacion,
        "EMAIL" => $info->cliente->email,
        "DIRECCION" => $info->cliente->direccion,

        "TITULAR" => $info->tarjeta->titular,
        "TARJETA" => $info->tarjeta->numero,
        "EXPIRACION" => $info->tarjeta->expiracion,
        "TIPO" => $info->tarjeta->tipo,
        "BANCO" => $info->tarjeta->banco,
    ];

    foreach ($data as $key => $value) {
        $mensaje = str_replace('['.$key.']', $value, $mensaje);
    }
    
    
    add_action('phpmailer_init','send_smtp_email');
    function send_smtp_email( $phpmailer ) {
        $phpmailer->From = "desarrollokmimos@gmail.com";
        $phpmailer->FromName = "Soporte Kmimos";
    }

    $headers_admins = array(
        'BCC: vlzangel91@gmail.com',
        'BCC: chaudaryy@gmail.com',
        /*
        'BCC: e.viera@kmimos.la',
        'BCC: a.vera@kmimos.la',
        */
    );
    
    wp_mail( "soporte@openpay.mx", "Solicitud de desbloqueo de tarjeta - Kmimos", $mensaje, $headers_admins); // Soporte de Openpay

    /*
    wp_mail( "vlzangel91@gmail.com", "Solicitud de desbloqueo de tarjeta - Kmimos", $mensaje); // Angel
    wp_mail( "chaudaryy@gmail.com", "Solicitud de desbloqueo de tarjeta - Kmimos", $mensaje); // Yrcel

    wp_mail( "e.viera@kmimos.la", "Solicitud de desbloqueo de tarjeta - Kmimos", $mensaje); // Eyderman
    wp_mail( "a.vera@kmimos.la", "Solicitud de desbloqueo de tarjeta - Kmimos", $mensaje); // Alfredo
    */

    // print_r( $info );
?>