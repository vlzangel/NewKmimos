<?php
	include( '../lib/panel.php' );
	$data = $panel->noches_reservadas();
	echo json_encode($data, JSON_UNESCAPED_UNICODE);