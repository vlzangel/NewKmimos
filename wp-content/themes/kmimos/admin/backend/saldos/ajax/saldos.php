<?php

    date_default_timezone_set('America/Mexico_City');

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    $data = '{"data":[';
    $clientes = $db->get_results("SELECT ID, user_email FROM wp_users");
    $temp_global = array();
    foreach ($clientes as $cliente) {
        $saldo = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$cliente->ID} AND meta_key = 'kmisaldo' ");
        $saldo += 0;
        $temp_global[] = "[".implode(",", array(
            '"'.$cliente->ID.'"',
            '"'.$cliente->user_email.'"',
            '"<span onclick=\'abrir_link( jQuery(this) );\' class=\'enlaces\' data-id=\''.$cliente->ID.'\' data-titulo=\'Establecer Saldo\' data-modal=\'updateSaldo\' >$'.number_format($saldo, 2, ',', '.').'</span>"'
        ))."]";
    }
    $data .= implode(",", $temp_global).']}';
    echo $data;

?>