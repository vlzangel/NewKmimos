<?php
	include 'wp-load.php';
	
	if( !isset($_SESSION) ){ session_start(); }

	// $_temp = pre_carga_data_cuidadores();
	// $_SESSION["DATA_CUIDADORES"] = $_temp[0];
	// $_SESSION["CUIDADORES_USER_ID"] = $_temp[1];

	echo date("d/m/Y H:i:s", 1555967110);
	echo "<br><br>";
	echo date("d/m/Y H:i:s", 1555964790);
?>