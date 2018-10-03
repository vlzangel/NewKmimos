<?php
	session_start();

	$pines = unserialize( $_SESSION['pines_array'] );
	foreach ($pines as $key => $value) {
		unset($pines[ $key ]["nom"] );
	}

	echo json_encode( $pines, JSON_UNESCAPED_UNICODE );
?>