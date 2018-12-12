<?php
    $raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($raiz."/vlz_config.php");
    include_once("../funciones/db.php");
//    include_once($raiz."/wp-load.php");
    
    $db = new db( new mysqli($host, $user, $pass, $db) );

    extract( $_GET );

    $accion = '';
    if( $a == 1 ){
        $db->query("UPDATE wp_posts SET post_status = 'publish' WHERE post_status = 'pending' AND post_author = '{$u}';");
        $db->query("UPDATE cuidadores SET activo = '1' WHERE user_id = '{$u}';");

        $accion = 'Activar Cuidador';

    }else{
        $db->query("UPDATE wp_posts SET post_status = 'pending' WHERE post_status = 'publish' AND post_author = '{$u}';");
        $db->query("UPDATE cuidadores SET activo = '0' WHERE user_id = '{$u}';");

        $accion = 'Desactivar Cuidador';
    }

    $filename = dirname(__DIR__).'/activar_cuidador.php';
	$db->query("INSERT INTO seglog ( user_id, tabla, row_id, accion, filename ) VALUES (
         {$user_id},
        'cuidadores',
         {$u},
        '{$accion}',
        '{$filename}'
    )");

	header( "location: ".$_SERVER['HTTP_REFERER'] );
?>