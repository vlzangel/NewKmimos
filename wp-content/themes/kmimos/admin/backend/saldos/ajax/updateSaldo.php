<?php
	
	extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    $_saldo = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$ID} AND meta_key = 'kmisaldo' ");
    if( $_saldo === false ){
        echo "INSERT INTO wp_usermeta VALUES (NULL, '{$ID}', 'kmisaldo', {$saldo}') ";
    	$db->query("INSERT INTO wp_usermeta VALUES (NULL, '{$ID}', 'kmisaldo', '{$saldo}') ");
    }else{
    	$db->query("UPDATE wp_usermeta SET meta_value = '{$saldo}' WHERE user_id = {$ID} ");
    }

	exit;
?>