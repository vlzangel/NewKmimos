<?php

    date_default_timezone_set('America/Mexico_City');

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );
    $actual = time();
    extract($_POST);

    $data['serie'] = ( !empty($serie) )? $serie : '' ;
    $data['iva'] = ( !empty($iva) )? $iva : 0 ;
    $data['comision'] = ( !empty($comision) )? $comision : 0 ;

    $update = serialize($data);

    if( $db->query( "UPDATE facturas_configuracion SET value = '{$update}' WHERE codigo = 'cfdi_parametros' " ) ){
        echo json_encode(['estatus'=>'listo']);
    }else{
        echo json_encode(['estatus'=>'error']);
    }

