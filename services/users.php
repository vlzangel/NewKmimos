<?php 
	include( dirname(__DIR__)."/vlz_config.php");
	include( __DIR__."/db.php");

	extract($_GET);
	extract($_POST);

	$db = new db( new mysqli($host, $user, $pass, $db) );

	switch ( $funcion ) {

		case 'is_user':
			$es = $db->get_var("SELECT ID FROM wp_users WHERE user_email = '{$email}' ");
			echo $es;
		break;

		case 'is_user_array':
			$resultado = array();
			foreach ($emails as $key => $email) {
				$es = $db->get_var("SELECT ID FROM wp_users WHERE user_email = '{$email}' ");
				$resultado[ $email ] = $es+0;
			}
			echo json_encode($resultado);
		break;
		
	}
	
	exit();
?>