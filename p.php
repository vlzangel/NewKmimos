<?php
	include 'wp-load.php';
	
	if( !isset($_SESSION) ){ session_start(); }

	global $wpdb;

	// update_ubicacion();
	// update_titulo();
	// update_cuidador_url();
	// update_servicios();
	// pre_carga_data_cuidadores();
	// echo serialize([ "subscriber" => true ]);
	// update_titulo();
	// update_precios_paseos();

	/*
	echo '<pre>';
		print_r($_SESSION["DATA_CUIDADORES"]);
	echo '</pre>';
	*/

	/*
	$cuidadores = $wpdb->get_results("SELECT * FROM cuidadores");
	$cambios_total = 0;
	foreach ($cuidadores as $indice => $cuidador) {
		if( $cuidador->hospedaje_desde > 0 && $cuidador->hospedaje_desde < 40 ){
			$cuidador->hospedaje_desde = 40;
			$wpdb->query("UPDATE cuidadores SET hospedaje_desde = '40' WHERE id = ".$cuidador->id);
		}
		if( $cuidador->paseos_desde > 0 && $cuidador->paseos_desde < 40 ){
			$cuidador->paseos_desde = 40;
			$wpdb->query("UPDATE cuidadores SET paseos_desde = '40' WHERE id = ".$cuidador->id);
		}
		$adicionales = unserialize($cuidador->adicionales);
		$cambio = false;
		foreach ($adicionales as $key => $value) {
			if( count($value) > 3 ){
				foreach ($value as $key_2 => $value_2 ) {
					if( $value_2 > 0 && $value_2 < 40 ){
						$adicionales[ $key ][ $key_2 ] = 40;
						$cambio = true;
					}
				}
			}
		}
		if( $cambio ){
			$cambios_total++;
			$cuidadores[ $indice ]->adicionales = serialize( $adicionales );
			$wpdb->query("UPDATE cuidadores SET adicionales = '".serialize( $adicionales )."' WHERE id = ".$cuidador->id);
		}
	}
	echo $cambios_total;

	*/


	$SQL = "
		SELECT 
			r.ID,
			m_m.meta_value AS mascotas,
			m_i.meta_value AS inicio,
			m_f.meta_value AS fin,
			p.post_name AS servicio
		FROM
			wp_posts AS r
		INNER JOIN wp_postmeta AS m_i ON (r.ID = m_i.post_id)
		INNER JOIN wp_postmeta AS m_f ON (r.ID = m_f.post_id)
		INNER JOIN wp_postmeta AS m_m ON (r.ID = m_m.post_id)
		INNER JOIN wp_postmeta AS m_p ON (r.ID = m_p.post_id)
		INNER JOIN wp_posts    AS p   ON (p.ID = m_p.meta_value  )
		WHERE
			r.post_type = 'wc_booking' AND r.post_status = 'confirmed' AND
			m_m.meta_key = '_booking_persons' AND 
			m_i.meta_key = '_booking_start' AND 
			m_f.meta_key = '_booking_end'
		ORDER BY r.post_date ASC
	";
	$reservas = $wpdb->get_results($SQL);
	$min_date = '';
	$max_date = '';
	$reservas_por_dia = [];
	$reservas_por_mes = [];
	$reservas_acumulado = [];
	$total = 0;

    foreach ($reservas as $key => $reserva) {
    	$reserva->inicio = ( strlen($reserva->inicio) > 8 ) ? substr($reserva->inicio, 0, 8) : $reserva->inicio;
    	$reserva->fin = ( strlen($reserva->fin) > 8 ) ? substr($reserva->fin, 0, 8) : $reserva->fin;
    	$ini = strtotime($reserva->inicio);
    	$fin = strtotime($reserva->fin);
    	$_mas = unserialize($reserva->mascotas);
    	$mas = 0;
    	foreach ($_mas as $key => $value) {
    		$mas += $value;
    	}
    	$tipo = explode("-", $reserva->servicio);
    	$tipo = $tipo[0];
    	$fin = ( $tipo == 'hospedaje' ) ? $fin-86400 : $fin;
    	for ($i=$ini; $i <= $fin; $i+=86400) { 
    		$anio = date("Y", $i);
    		if( $anio > 2015){
	    		$min_date = ($min_date == '' || $min_date > $i) ? $i : $min_date;
	    		$max_date = ($max_date == '' || $max_date < $i) ? $i : $max_date;
    		}
    		$mes_id = $anio = date("Y-m", $i);
    		if( $mes_id == "2018-10" ){
    			$mes_oct += $mas;
    		}
    		$reservas_por_mes[ $mes_id ] += $mas;
			$reservas_por_dia[ date("Y-m-d", $i) ] += $mas;
    	}
    }

    ksort($reservas_por_dia);
    ksort($reservas_por_mes);
    ksort($reservas_acumulado);

    foreach ($reservas_por_mes as $key => $value) {
    	$total += $value;
    	$reservas_acumulado[ $key ] = $total;
    }

	$data = [];
    $data['byMonth']=[];
    $data['byDay']=[];
    $data['total']=[];



    foreach ($reservas_por_dia as $key => $value) {
    	$data['byDay'][] = [
    		"date" => $key,
    		"value" => $value
    	];
    }

    foreach ($reservas_por_mes as $key => $value) {
    	$data['byMonth'][] = [
    		"date" => $key,
    		"value" => $value
    	];
    }
    
    foreach ($reservas_acumulado as $key => $value) {
    	$data['total'][] = [
    		"date" => $key,
    		"value" => $value
    	];
    }

    $data['date'] = array_keys($reservas_por_dia);

    echo "<pre>";
    	print_r( $data );
    echo "</pre>";

    /*

    // foreach ($reservas_por_dia as $i => $reserva) {
    for ($i = $min_date; $i <= $max_date; $i += 86400) { 

    	$cant_reservas = 0;
		// if( isset( $reservas_por_dia[ $i ] ) ){
			$cant_reservas = $reservas_por_dia[ $i ];

			// Por dia
			$fecha = date("Y-m-d", $i); 
			$_data['byDay'][$fecha]['value'] = $cant_reservas;
			$_data['byDay'][$fecha]['date'] = $fecha;

			// Por mes
			$mes_id = date("Y-m", $i); 
			if( isset($_data['byMonth'][ $mes_id ]) ){
				$_data['byMonth'][$mes_id]['value'] += $cant_reservas;
			}else{
				$_data['byMonth'][$mes_id]['value'] = $cant_reservas;
				$_data['byMonth'][$mes_id]['date'] = $mes_id;
			}

			// Acumulado
			$total_reservas += $cant_reservas;
			if( isset($_data['total'][$mes_id]) ){
				$_data['total'][$mes_id]['value'] = $total_reservas;
			}else{
				$_data['total'][$mes_id]['value'] = $total_reservas;
				$_data['total'][$mes_id]['date'] = $mes_id;
			}
		// }
    }

    /*
	// ksort($_data['byDay']);
	$data['byDay'] = array_values($_data['byDay']);
	$data['date'] = array_keys($_data['byDay']);

	// ksort($_data['byMonth']);
	$data['byMonth'] = array_values($_data['byMonth']);

	//ksort($_data['total']);
	$data['total'] = array_values($_data['total']);
	*/

?>