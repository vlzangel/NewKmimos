<?php
	include dirname(__DIR__)."/wp-load.php";

	
	$name = "cuidadores_".substr(md5( time() ), -10, -1).".xls";
	header('Content-type: application/vnd.ms-excel; charset=utf-8' );
	header(sprintf( 'Content-Disposition: attachment; filename=%s', $name ) );
	
	extract($_GET);

	if( !isset($c) ){
		$c = 30;
	}

	global $wpdb;

	$hoy = date("Ymd")."000000";;
	$dos_dias = date("Ymd", strtotime("+".$c." day") )."000000";

	$sql = "SELECT * FROM wp_postmeta WHERE meta_key = '_booking_start' AND ( meta_value >= '$hoy' AND meta_value <= '$dos_dias' ) ";

	$cuidadores = [];
	$reservas = $wpdb->get_results($sql);
	foreach ($reservas as $reserva) {
		$servicio_id = get_post_meta($reserva->post_id, '_booking_product_id', true);
		$cuidador_id = $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = {$servicio_id} ");

		if( $cuidador_id != 8631 ){

			$SQL_CANT = "
				SELECT COUNT(*) 
				FROM wp_posts AS p 
				INNER JOIN wp_postmeta AS m ON ( p.ID = m.post_id AND m.meta_key = '_booking_product_id' ) 
				INNER JOIN wp_posts AS s ON ( s.ID = m.meta_value AND s.post_author = '{$cuidador_id}' ) 
				WHERE p.post_type = 'wc_booking' AND p.post_status = 'confirmed'
			";

			$cant_reservas = $wpdb->get_var($SQL_CANT);

			// echo $SQL_CANT."<br><br>";

			if( $cant_reservas <= 2 ){
				$cuidador = get_user_meta($cuidador_id, 'first_name', true)." ".get_user_meta($cuidador_id, 'last_name', true);
				$cuidador_telf_1 = get_user_meta($cuidador_id, 'user_phone', true);
				$cuidador_telf_2 = get_user_meta($cuidador_id, 'user_mobile', true);
				$cuidador_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$cuidador_id} ");

				$cuidadores[] = [
					$cuidador,
					$cuidador_telf_1,
					$cuidador_telf_2,
					$cuidador_email,
					$cant_reservas
				];
			}
		}
	}

	// echo $sql."<br>";

	/*
	echo "<pre>";
		print_r( $cuidadores );
	echo "</pre>";
	*/

	$HTML = '<table border="1" cellpadding="2" cellspacing="0" width="100%">
	<caption>Cuidadores con sus primeras reservas</caption>';
	$HTML .= '<tr>';
		$HTML .= '<th>Nombre</th>';
		$HTML .= '<th>Teléfono</th>';
		$HTML .= '<th>Celular</th>';
		$HTML .= '<th>Email</th>';
		$HTML .= '<th># reservas</th>';
	$HTML .= '</tr>';
	foreach ($cuidadores as $info) {
		$HTML .= '<tr>';
			$HTML .= '<td>'.$info[0].'</td>';
			$HTML .= '<td>'.$info[1].'</td>';
			$HTML .= '<td>'.$info[2].'</td>';
			$HTML .= '<td>'.$info[3].'</td>';
			$HTML .= '<td>'.$info[4].'</td>';
		$HTML .= '</tr>';
	}
	$HTML .= '</table>';

	echo utf8_decode($HTML);
?>