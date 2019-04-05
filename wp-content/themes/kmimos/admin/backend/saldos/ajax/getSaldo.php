<?php

    error_reporting(0);
	
	extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    $saldo += 0;

    $_user_ID = $db->get_var("SELECT ID FROM wp_users WHERE user_email = '{$email}' ");
    if( $_user_ID+0 == 0 ){
        echo "Email no encontrado!";
    }else{
        $_saldo = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$_user_ID} AND meta_key = 'kmisaldo' ");
        $_saldo += 0;
        echo "
            <div><label class='info_label'>Email: </label> <span>{$email}</span></div>
            <div><label class='info_label'>Saldo Actual: </label> <span class='montoActual'>$".number_format($_saldo, 2, ',', '.')." MXN</span></div>
            <div><label class='info_label'>Modificar saldo por: </label> <span class='montoModificado'>$".number_format($saldo, 2, ',', '.')." MXN</span></div>
        ";
    }
    
	exit;
?>