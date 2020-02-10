<?php
	$res = get_medicines($appointment_id);
	die( json_encode( [
		"status" => ( count($res) > 0 ),
		"lista" => $res
	] ) );
?>