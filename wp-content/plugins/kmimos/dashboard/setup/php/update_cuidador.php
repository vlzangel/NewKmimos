<?php
	extract($_POST);

	$path = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
    require($path.'/wp-config.php');
    require($path.'/wp-content/themes/kmimos/procesos/funciones/db.php');

	$db = new db( new mysqli($host, $user, $pass, $db) );

	$atributos = unserialize( $db->get_var("SELECT atributos FROM cuidadores WHERE id = {$cuidador}") );

	$atributos['destacado'] = $destacado;
	$atributos['flash'] = $flash;
	$atributos['geo'] = $geo;
	$atributos['destacado_home'] = $destacado_home;
	$atributos['msg_destacado'] = htmlentities($msg_destacado);

	$atributos = serialize($atributos);
	$db->query("UPDATE cuidadores SET atributos = '{$atributos}' WHERE id = {$cuidador};");

	exit;