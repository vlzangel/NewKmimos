<?php
	session_start();

	$pines = unserialize( $_SESSION['pines_array'] );

	echo json_encode( $pines, JSON_UNESCAPED_UNICODE );
?>