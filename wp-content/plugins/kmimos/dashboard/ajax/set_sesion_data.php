<?php
	if( !isset($_SESSION) ){ session_start(); }

	foreach ($_POST as $key => $value) {
		$_SESSION[$key] = $value;
	}

	print_r($_POST);
	print_r($_SESSION);

	exit();
?>	