<?php
		
	session_start();

	include dirname(dirname(__DIR__)).'/wp-load.php';

	global $wpdb;

	$_SESSION["landing_test"] = 'b';
	$ult_landing = $_SESSION["landing_test"];

	$param = ( !empty($_SERVER['QUERY_STRING']) && isset($_GET['utm_campaign']) )? '&'.$_SERVER['QUERY_STRING'] : '&utm_source=web&utm_medium=banner&utm_campaign=petco_kmimos&utm_term=white_label_petco' ;	
	
	$url = get_home_url().'/?g=1&wlabel=petco'.$param;

	header('Location:'.$url );

	exit();
?>

