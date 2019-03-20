<?php
	include( '../lib/panel.php' );

	$data = $panel->resumen();

	echo json_encode($data, JSON_UNESCAPED_UNICODE);