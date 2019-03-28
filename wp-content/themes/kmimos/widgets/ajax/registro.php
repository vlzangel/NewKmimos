<?php
	include( '../lib/panel.php' );
	$data = $panel->registro();
	echo json_encode($data, JSON_UNESCAPED_UNICODE);