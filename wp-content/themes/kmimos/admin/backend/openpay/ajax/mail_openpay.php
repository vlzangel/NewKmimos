<?php
    error_reporting(0);
	
	extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

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

    wp_mail( "a.veloz@kmimos.la", "Solicitud de desbloqueo de tarjeta - Kmimos", $mensaje);
    // wp_mail( "chaudaryy@gmail.com", "Actualización de Status", $mensaje);

	print_r( $info );
?>