<?php
	
	require_once(dirname(dirname(dirname(dirname(__DIR__)))).'/lib/pagos/pagos_cuidador.php');
	extract($_POST);
	$reservas = $pagos->detalle_disponible( $ID );

// print_r($reservas);

	$SQL_TRA='';
	$total_a_pagar = 0;
	$reservas_a_pagar = [];
	foreach ($reservas['detalle'] as $key => $val) {

		if( in_array($key, $reservas_selected) ){
			$total_a_pagar += $val;
			$reservas_a_pagar[] = [
				'reserva' => $key,
				'monto' => $val,
			];
			// registro transaccion
			$SQL_TRA .= "INSERT INTO `cuidadores_transacciones`(
				`tipo`,
				`user_id`,
				`reserva_id`,
				`referencia`,
				`descripcion`,
				`monto`
			) VALUES (
				'PAGO_C',
				{$ID},
				{$key},
				'P{$key}',
				'Pago reserva #{$key}',
				{$val}
			);";
		}
	}

	// feed 
	if( $total_a_pagar > 10 ){
		$total_a_pagar -= 10;
	}

	$cuidador = $pagos->db->get_row('SELECT * FROM cuidadores WHERE user_id = {$ID}');
	$banco = unserialize($cuidador->banco);

	// registro de pago en reporte
	$SQL_PAGO = "INSERT INTO `cuidadores_pagos`( 
		`admin_id`, 
		`user_id`, 
		`total`, 
		`cantidad`, 
		`estatus`, 
		`detalle`, 
		`autorizado`, 
		`openpay_id`, 
		`observaciones`, 
		`banco`,
		`cuenta`, 
		`titular`
	) VALUES (
		0, 
		{$ID}, 
		{$total_a_pagar}, 
		".count($reservas_a_pagar).", 
		'pendiente', 
		'".serialize($reservas_a_pagar)."', 
		'',
		'',
		'Solicitud de retiro por el cuidador ( Descuento fee: $10 )', 
		'".$banco['banco']."', 
		'".$banco['cuenta']."', 
		'".$banco['titular']."'
	);";

	$pagos->db->query($SQL_PAGO);
	$pagos->db->multi_query($SQL_TRA);

	echo $SQL_PAGO;
	echo '<hr>';
	echo $SQL_TRA;

