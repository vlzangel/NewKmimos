<?php
	session_start();
	// include 'wp-load.php';

	// $_temp = pre_carga_data_cuidadores();
	// $_SESSION["DATA_CUIDADORES"] = $_temp[0];
	// $_SESSION["CUIDADORES_USER_ID"] = $_temp[1];

	unset($_SESSION["DATA_CUIDADORES"]);
	unset($_SESSION["CUIDADORES_USER_ID"]);
?>