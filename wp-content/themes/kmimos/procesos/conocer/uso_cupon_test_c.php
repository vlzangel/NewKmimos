<?php
	session_start();
	extract($_POST);
	if( $_SESSION['test_conocer'] == 'c' ){
		$_SESSION['cupon_test_c'] = $usar;
	}else{
		$_SESSION['cupon_test_c'] = 'NO';
	}
	exit();
?>