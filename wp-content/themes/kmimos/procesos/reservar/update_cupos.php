<?php
	session_start();
	extract($_POST);
	echo json_encode( $_SESSION["cupos_".$c] );
?>