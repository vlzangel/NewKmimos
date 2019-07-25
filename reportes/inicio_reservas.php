<?php
	include dirname(__DIR__)."/wp-load.php";

    date_default_timezone_set('America/Mexico_City');

	
	$name = "cant_reservas_".substr(md5( time() ), -10, -1).".xls";
	header('Content-type: application/vnd.ms-excel; charset=utf-8' );
	header(sprintf( 'Content-Disposition: attachment; filename=%s', $name ) );
	
	extract($_GET);

	global $wpdb;

	$hoy = date("Ymd")."000000";
	$fe = date("Ymd", strtotime($f) );
	$dos_dias = $fe."000000";

	$sql = "
		SELECT m.* 
		FROM wp_postmeta AS m 
		INNER JOIN wp_posts AS p ON ( m.post_id = p.ID )
		WHERE 
			m.meta_key = '_booking_start' AND ( m.meta_value >= '$hoy' AND m.meta_value <= '$dos_dias' ) AND
			p.post_status = 'confirmed' ";
	$dias = [];
	$reservas = $wpdb->get_results($sql);
	foreach ($reservas as $reserva) {
		$dias[$reserva->meta_value]++;
	}

	ksort($dias);

	/*
	echo "<pre>";
		print_r($dias);
	echo "</pre>";
	*/

	// exit();

	$HTML = '<table border="1" cellpadding="2" cellspacing="0" width="100%">
	<caption>RESERVAS PROXIMAS</caption>';
	$HTML .= '<tr>';
		$HTML .= '<th>DIA</th>';
		$HTML .= '<th>CANTIDAD</th>';
	$HTML .= '</tr>';
	foreach ($dias as $dia => $cant) {
		$fecha = date("d/m/Y", strtotime($dia));
		$HTML .= '<tr>';
			$HTML .= '<td>'.$fecha.'</td>';
			$HTML .= '<td>'.$cant.'</td>';
		$HTML .= '</tr>';
	}
	$HTML .= '</table>';

	echo utf8_decode($HTML);
?>