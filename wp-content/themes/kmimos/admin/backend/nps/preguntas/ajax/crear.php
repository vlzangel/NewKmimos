<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once(dirname(__DIR__).'/lib/nps.php');

    $id = 0;
    if( $_POST['campana_id'] > 0 ){
        $id = $nps->update( $_POST );
    }else{
        $id = $nps->create( $_POST );
    }

    $data=[
    	'id' => 0,
    	'msg' => 'No se completo el registro de la campa&ntilde;a',
    ];
    
    if( $id > 0 ){
    	$data['id'] = $id; 
    	$data['msg'] = ''; 
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);