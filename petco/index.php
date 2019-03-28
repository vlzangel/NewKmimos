<?php
		
	session_start();

	include dirname(__DIR__).'/wp-load.php';

	global $wpdb;

	if( isset($_SESSION["landing_test"]) ){
		$ult_landing = $_SESSION["landing_test"];
	}else{
		$ult_landing = $wpdb->get_var("SELECT landing FROM landing_test ORDER BY id DESC LIMIT 0, 1");
		switch ( $ult_landing ) {
			case 'a':
				$ult_landing = 'b';
			break;
			
			/*
			case 'b':
				$ult_landing = 'c';
			break;
			case 'c':
				$ult_landing = 'd';
			break;
			*/
			
			default:
				$ult_landing = 'a';
			break;
		}

		$_SESSION["landing_test"] = $ult_landing;

		$current_user = wp_get_current_user();
    	$user_id = $current_user->ID;

		$wpdb->query("INSERT INTO landing_test VALUES (NULL, {$user_id}, '{$ult_landing}')");
	}

	$param = ( !empty($_SERVER['QUERY_STRING']) && isset($_GET['utm_campaign']) )? '&'.$_SERVER['QUERY_STRING'] : '&utm_source=web&utm_medium=banner&utm_campaign=petco_kmimos&utm_term=white_label_petco' ;	
	
	if( $ult_landing == 'a' ){
		$url = get_home_url().'/?wlabel=petco'.$param;	
	}else{
		$url = get_home_url().'/?landing='.$ult_landing.'&wlabel=petco'.$param;	
	}	

	header('Location:'.$url );

	exit();
?>

