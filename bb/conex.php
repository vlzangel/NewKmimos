<?php
	include("db.php");

	$user = 'root';
	$pass = 'kmimos';
	$host = 'localhost';
	$db = 'kmimos_bb';

	$db = new db( new mysqli($host, $user, $pass, $db) );

?>