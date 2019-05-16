<?php

    date_default_timezone_set('America/Mexico_City');

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));

    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    $data["data"] = array();

    $r = $db->get_results("
        SELECT 
            c.*,
            email.user_email AS email,
            nombre.meta_value AS nombre,
            apellido.meta_value AS apellido
        FROM 
            consolidados AS c
        INNER JOIN wp_users AS email ON ( email.ID = c.user_id ) 
        INNER JOIN wp_usermeta AS nombre ON ( nombre.user_id = c.user_id AND nombre.meta_key = 'first_name' ) 
        INNER JOIN wp_usermeta AS apellido ON ( apellido.user_id = c.user_id AND apellido.meta_key = 'last_name' ) 
    ");

    if( $r != false ){

        foreach ($r as $key => $value) {

            $user = utf8_encode("{$value->nombre} {$value->apellido} ({$value->email})");
            $value->modificado = date( "d/m/Y h:i a", strtotime($value->modificado) );

            $data["data"][] = array(
                $value->id,
                $value->comentarios,
                $value->modificado,
                $user
            );
        }
    }

    echo json_encode($data);

?>