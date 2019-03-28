<?php
	include( '../lib/panel.php' );
	$data = $panel->leads();
	echo json_encode($data, JSON_UNESCAPED_UNICODE);