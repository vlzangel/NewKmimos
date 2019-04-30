<?php
	session_start();
	include dirname(dirname(dirname(dirname(__DIR__))))."/wp-load.php";
	extract($_POST);
	echo get_resultados_new($page*10);

	echo "<pre>";
		print_r( $_SESSION['sql'] );
	echo "</pre>";
?>

