<?php
	session_start();

	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
	include_once($raiz."/wp-load.php");
	date_default_timezone_set('America/Mexico_City');

	extract( $_POST );
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


	echo json_encode([
		$info,
		$creacion_veterinario,
		$update_id,
		$update_agenda,
		$user_id
	]);
	
	die();
?>