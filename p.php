<?php
	include 'wp-load.php';
	
	if( !isset($_SESSION) ){ session_start(); }


	echo "<pre>"; 
		// print_r( pre_carga_data_cuidadores() );
		print_r( $_SESSION );
	echo "</pre>";
?>