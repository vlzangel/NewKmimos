<?php
	session_start();
	extract($_POST);
	if( $_SESSION['test_conocer'] == 'c' ){
		$_SESSION[ 'tcc_contador' ] = time();
		echo $_SESSION[ 'tcc_contador' ];
	}
	exit();
?>