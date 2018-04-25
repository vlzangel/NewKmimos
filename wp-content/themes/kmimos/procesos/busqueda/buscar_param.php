<?php
	session_start();
	include(realpath(__DIR__."/../../../../../vlz_config.php"));
	include(realpath(__DIR__."/../funciones/db.php"));

	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn); 

	$_POST = @array_filter($_POST);
	$_SESSION['busqueda'] = serialize($_POST);
	$home = $db->get_var("SELECT option_value FROM wp_options WHERE option_name = 'siteurl'");

  /*  echo "<pre>";
    	print_r($_SESSION);
    echo "</pre>";*/

	header("location: {$home}busqueda/");
?>