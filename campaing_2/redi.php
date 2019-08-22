<?php
	error_reporting(0);
	include dirname(__DIR__).'/campaing/db.php';
	$info = (array) json_decode(base64_decode( $_GET['info']));
	extract($info);
	
	echo "<pre>";
		print_r( $info );
	echo "</pre>";
	

	// header("location: ".$info['url']);
?>