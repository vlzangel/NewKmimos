<?php
	session_start();
	include dirname(dirname(dirname(dirname(__DIR__))))."/wp-load.php";
	echo get_destacados_new();
?>