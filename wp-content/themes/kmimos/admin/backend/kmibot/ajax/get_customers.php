<?php
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");
    date_default_timezone_set('America/Mexico_City');
    global $wpdb;
    extract($_POST);

    $SQL = "
        SELECT c.ID
        FROM wp_users AS c
        WHERE c.user_email LIKE '%{$email}%'
    ";

    $ID = $wpdb->get_var($SQL);

    if( is_numeric($ID) ){
        echo json_encode([
            "respuesta" => "si",
        ]);
    }else{
        echo json_encode([
            "respuesta" => "no",
        ]);
    }

?>