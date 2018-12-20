<?php
	include dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/wp-load.php';

	extract($_POST);

	update_user_meta($user_id, 'status_user', $status);

	echo json_encode(["status" => $status]);
?>