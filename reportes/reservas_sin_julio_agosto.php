<?php
	include dirname(__DIR__)."/wp-load.php";

	$name = "no_reservaron_".substr(md5( time() ), -10, -1).".xls";

	header('Content-type: application/vnd.ms-excel; charset=utf-8' );
	header(sprintf( 'Content-Disposition: attachment; filename=%s', $name ) );

	global $wpdb;

	$_usuarios = $wpdb->get_results("
		SELECT 
			u.ID, u.user_email,
			nombre.meta_value AS nombre, 
			apellido.meta_value AS apellido,
			movil.meta_value AS movil,
			telf.meta_value AS telf
		FROM wp_users AS u
		INNER JOIN wp_usermeta AS nombre ON (u.ID = nombre.user_id AND nombre.meta_key = 'first_name' )
		INNER JOIN wp_usermeta AS apellido ON (u.ID = apellido.user_id AND apellido.meta_key = 'last_name' )
		INNER JOIN wp_usermeta AS movil ON (u.ID = movil.user_id AND movil.meta_key = 'user_mobile' )
		INNER JOIN wp_usermeta AS telf ON (u.ID = telf.user_id AND telf.meta_key = 'user_phone' )
	");

	$users = [];
	foreach ($_usuarios as $key => $value) {

		$telf = [];
		if( $value->movil != "" ){
			$telf[] = "52".$value->movil;
		}
		if( $value->telf != "" ){
			$telf[] = "52".$value->telf;
		}

		if( !array_key_exists($value->ID, $users) ){
			$users[$value->ID] = [
				$value->nombre,
				$value->apellido,
				$value->user_email,
				$telf[0],
				$telf[1]
			];
		}
	}

	$data = $wpdb->get_results("
		SELECT 
			p.ID, p.post_status, p.post_author AS autor,
			ini.meta_value AS ini, 
			fin.meta_value AS fin
		FROM wp_posts AS p
		INNER JOIN wp_postmeta AS ini ON (p.ID = ini.post_id AND ini.meta_key = '_booking_start' )
		INNER JOIN wp_postmeta AS fin ON (p.ID = fin.post_id AND fin.meta_key = '_booking_end' )
		WHERE p.post_type = 'wc_booking' AND p.post_status = 'confirmed' AND p.post_date >= '2018-12-01'
	");

	$reservaron_hasta_ahora = [];
	$reservaron_J_A = [];
	$no_reservaron_J_A = [];

	// $diciembre = strtotime("2018-12-01");

	$julio = strtotime("2019-07-01");
	$agosto = strtotime("2019-08-31");

	foreach ($data as $key => $reserva) {
		$ini = strtotime( $reserva->ini );
		$fin = strtotime( $reserva->fin );

		if( 
			( $julio <= $ini && $ini <= $agosto ) ||
			( $julio <= $fin && $fin <= $agosto )
		){
			if( !in_array($reserva->autor, $reservaron_J_A) ){
				$reservaron_J_A[] = $reserva->autor;
			}
		}

		if( !in_array($reserva->autor, $reservaron_hasta_ahora) ){
			$reservaron_hasta_ahora[] = $reserva->autor;
		}
	}

	foreach ($reservaron_hasta_ahora as $key => $value) {
		if( !in_array($value, $reservaron_J_A)){
			$no_reservaron_J_A[] = $value;
		}
	}

	$HTML = '<table border="1" cellpadding="2" cellspacing="0" width="100%">
	<caption> <strong> No han reservado </strong> </caption>';
	foreach ($no_reservaron_J_A as $key => $value) {
		$us = $users[ $value ];
		if( $us[0] != "" ){
			$HTML .= '<tr>';
				$HTML .= '<td>'.($key+1).'</td>';
				$HTML .= '<td>'.$us[0].'</td>';
				$HTML .= '<td>'.$us[1].'</td>';
				$HTML .= '<td>'.$us[2].'</td>';
				$HTML .= '<td>'.$us[3].'</td>';
				$HTML .= '<td>'.$us[4].'</td>';
			$HTML .= '</tr>';
		}
	}
	$HTML .= '</table>';

	echo utf8_decode($HTML);
?>