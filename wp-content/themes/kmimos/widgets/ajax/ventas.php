<?php
	include( '../lib/panel.php' );
	$data = $panel->ventas();
	echo json_encode($data, JSON_UNESCAPED_UNICODE);