<?php
	// session_destroy();
	session_start();
	extract( $_POST );

	$_SESSION['medicos_serch'] = $_POST;
?>