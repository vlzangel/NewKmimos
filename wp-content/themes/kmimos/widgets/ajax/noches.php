<?php
	include( '../lib/panel.php' );
	$data = $panel->noches();
	echo json_encode($data, JSON_UNESCAPED_UNICODE);