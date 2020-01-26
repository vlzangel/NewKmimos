<?php
	session_start();

	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
	include_once($raiz."/wp-load.php");
	date_default_timezone_set('America/Mexico_City');

	extract( $_POST );

	/*
	$_infos = $_SESSION['medicos_info'];
	$info = [];
	foreach ($_infos[ $id ] as $key => $value) {
		switch ( $key ) {
			case 'medicInfo':
				$temp = [];
				foreach ($value as $key2 => $value2) {
					$value2 = preg_replace("/[\r\n|\n|\r]+/", " ", $value2);
	    			$value2 = str_replace('"', '', $value2);
					$temp[$key2] = $value2;
				}
				$info[ $key ] = $temp;
			break;
			case 'certifications':
				$value = preg_replace("/[\r\n|\n|\r]+/", " ", $value);
	    		$value = str_replace('"', '', $value);
				$info[ $key ] = $temp;
			break;
			default:
				$info[ $key ] = $value;
			break;
		}
	}

	$user_id = existe_veterinario($info["email"]);
	if( $user_id == null ){
		$creacion_veterinario = new_veterinario([
			'kv_email' => $info['email'],
			'kv_nombre' => $info['firstName'].' '.$info['lastName'],
			'kv_telf_movil' => $info['phone'],
			'kv_telf_fijo' => $info['phone']
		], $info);

		if( $creacion_veterinario['status'] ){
			$user_id = $creacion_veterinario['user_id'];
			$update_id = update_veterinario($user_id, [
				"veterinario_id" => $info["id"]
			]);
		}
	}

	if( $user_id+0 > 0 ){
		$update_agenda = update_veterinario($user_id, [
			"agenda" => $info["agenda"],
			"api" => $info
		]);
	}

	extract( get_dias_meses() );

	$agenda = [];
	foreach ($info["agenda"] as $key => $item) {
		$start = strtotime( str_replace("Z", "", $item->start));
		$fi = date('d/m/Y', $start);
		$ff = $dias[ date('w', $start) ].', '.date('d', $start).' de '.$meses[ date('n', $start) ].' de '.date('Y', $start).' a las '.date('h:i a', $start);
		$hi = date('h:i a', $start);
		$agenda[ $fi ]['fecha'] = $ff;
		$agenda[ $fi ]['items'][] = [
			$hi,
			$ff,
			date('Y-m-d H:i', $start)
		];
	}
	$info["agenda"] = $agenda;
	$info["firstName"] = set_format_name($info["firstName"]);
	$info["lastName"] = set_format_name($info["lastName"]);
	$info["rating"] = set_format_ranking($info["rating"]);
	$info["price"] = number_format($info["price"], 2, ',', ',');
	*/

	global $wpdb;
	global $vlz;
	extract($vlz);

	$veterinario = $wpdb->get_row("SELECT * FROM {$pf}veterinarios WHERE id = '{$id}' ");
	$data = json_decode($veterinario->data);

	$info = [];

	extract(get_dias_meses());

	$hoy = $_dias[ date('N')-1 ][0];
	$agenda = [];
	$_agenda = (array) json_decode($veterinario->agenda);

	$actuales = [];
	if( isset($_agenda[ $hoy ]) ){
		foreach ($_agenda[ $hoy ] as $key => $item) {
			for ($_i=0; $_i < 5; $_i++) { 
				$actual = ( $_i == 0 ) ? time() : strtotime ('+'.$_i.' days');
				$actuales[] = $actual;
				$ini = strtotime( date("Y-m-d", $actual).' '.$item->ini );
				$fin = strtotime( date("Y-m-d", $actual).' '.$item->fin );
				for ($i=$ini; $i < $fin; $i+=2700) { 
					if( time() <= $i ){
						$start = $i;
						$fi = date('d/m/Y', $start);
						$ff = $dias[ date('N', $start)-1 ].', '.date('d', $start).' de '.$meses[ date('n', $start) ].' de '.date('Y', $start).' a las '.date('h:i a', $start);
						$hi = date('h:i a', $start);
						$agenda[ $fi ]['fecha'] = $ff;
						$agenda[ $fi ]['items'][] = [
							$hi,
							$ff,
							date('Y-m-d H:i', $start)
						];
					}
				}
			}
		}
	}
	$info["actuales"] = $actuales;
	$info["agenda"] = $agenda;

	$info["firstName"] = set_format_name($data->kv_nombre);
	// $info["lastName"] = set_format_name($info["lastName"]);
	$info["rating"] = set_format_ranking($veterinario->rating);
	$info["price"] = number_format($veterinario->precio, 2, ',', ',');
	// $info["agenda"] = [];

	if( $data->kv_cursos_realizados != '' ){
		$info['medicInfo']['courses'] = $data->kv_cursos_realizados;
	}

	if( $data->kv_trabajos != '' ){
		$info['medicInfo']['formerExperience'] = $data->kv_trabajos;
	}

	if( $data->kv_otros_estudios != '' ){
		$info['medicInfo']['otherStudies'] = $data->kv_otros_estudios;
	}


	echo json_encode([
		$info
	]);
	
	die();
?>