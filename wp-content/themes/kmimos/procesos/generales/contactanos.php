<?php

 	include(realpath("../../../../../wp-load.php"));

    header('Content-Type: application/json; charset=UTF-8;');
    global $_REQUEST;


    $fields = ['nombres', 'email', 'asunto', 'contenido'];
    foreach ($fields as $val) {
    	if( !isset( $val, $_POST ) || empty($_POST[$val]) ){
		    echo json_encode(['code'=>'NOT_SEND']);
    		exit();
    	}
    }

    extract($_POST);

    $list_email = [
    	'italococchini@gmail.com'
    ];

    $texto = "
    	Email: $email <br>
    	Nombre: $nombres <br>
    	Contenido:<br> $contenido
    ";

    $estatus = 0;
    foreach ($list_email as $email) {
	    if( wp_mail( $email, $asunto, $texto ) ){
	    	$estatus = 1;
	    }
    }

    echo json_encode(['code'=>'OK']);