<?php
    error_reporting(0);

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
    
    // $mensaje = get_email_html($mensaje);

    /*
    function my_phpmailer_init_smtp($phpmailer){
        $phpmailer->Mailer = "smtp";
        $phpmailer->SMTPSecure = "tls";
        $phpmailer->Host = "smtp.gmail.com";
        $phpmailer->Port = 587;
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = "desarrollokmimos@gmail.com";
        $phpmailer->Password = "Kmimos2017";
        
        $phpmailer->smtpConnect([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        $phpmailer->IsHTML(true);

        $phpmailer = apply_filters('wp_mail_smtp_custom_options', $phpmailer);
    }
    add_action('phpmailer_init','my_phpmailer_init_smtp');
    */

    add_action('phpmailer_init','send_smtp_email');
    function send_smtp_email( $phpmailer ) {
        $phpmailer->isSMTP();
        $phpmailer->Host = "smtp.gmail.com";
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = "587";
        $phpmailer->Username = "desarrollokmimos@gmail.com";
        $phpmailer->Password = "Kmimos2017";
        $phpmailer->SMTPSecure = "tls";
        
        $phpmailer->smtpConnect([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        $phpmailer->IsHTML(true);
     
        $phpmailer->From = "desarrollokmimos@gmail.com";
        $phpmailer->FromName = "Soporte Kmimos";
    }

    wp_mail( "vlzangel91@gmail.com", "Solicitud de desbloqueo de tarjeta - Kmimos", $mensaje);
    wp_mail( "chaudaryy@gmail.com", "Solicitud de desbloqueo de tarjeta - Kmimos", $mensaje);

    // wp_mail( "chaudaryy@gmail.com", "Solicitud de desbloqueo de tarjeta - Kmimos", $mensaje);

	print_r( $info );
?>