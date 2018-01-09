<?php 
	include( dirname(__DIR__)."/vlz_config.php");

	extract($_GET);
	extract($_POST);

	$db = new db( new mysqli($host, $user, $pass, $db) );

	switch ( $funcion ) {

		case 'is_user':
			$es = $db->get_var("SELECT ID FROM wp_users WHERE user_email = '{$email}' ");
			echo $es;
		break;
		
	}
	
	exit();
?>