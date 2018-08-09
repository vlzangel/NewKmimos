<?php

    date_default_timezone_set('America/Mexico_City');

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );
    $actual = time();
 
    $result['solicitudes'] = count($_POST);  
    $result['correctos'] = 0;
    $result['incorrectos'] = 0;  
    foreach ($_POST as $key => $value) {
        if( $db->query( "UPDATE facturas_configuracion SET value = '{$value}' WHERE codigo = '{$key}' " ) ){
            $result['correctos']++;
        }else{
            $result['incorrectos']++;
            $result['error'] .= ','.$key; 
        }    
    }

    echo json_encode($result); 
