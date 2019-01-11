<?php
	include dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/wp-load.php';

	extract($_POST);

	global $wpdb;

	$pedidos = $wpdb->get_results("SELECT * FROM conocer_pedidos WHERE status = 'Pagado' AND user_id = '{$user_id}' ORDER BY id DESC");

	$pagos = [];
	foreach ($pedidos as $key => $value) {
		$pagos[] = [
			"transaccion_id" => $value->transaccion_id,
			"fecha" => $value->fecha,
			"disponible" => $value->usos
		];
	}

	echo json_encode($pagos);
?>