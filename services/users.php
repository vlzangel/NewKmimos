<?php 
	include( dirname(__DIR__)."/wp-load.php");

	extract($_GET);
	extract($_POST);

	global $wpdb;

	switch ( $funcion ) {

		case 'is_user':
			$es = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email = '{$email}' ");
			echo $es;
		break;
		
	}
	
	exit();
?>