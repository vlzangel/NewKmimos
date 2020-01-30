<?php
	session_start();

	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
	include_once($raiz."/wp-load.php");
	date_default_timezone_set('America/Mexico_City');

	extract( $_POST );

	/*
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

	$medicos = get_medics($_SESSION['search']['specialty'], $_SESSION['search']['lat'], $_SESSION['search']['lng']);
	$medicos = $medicos['res']->objects;
	$_agenda = "";
	$_medico_actual = '';
	foreach ($medicos as $key => $medico) {
		$_medicos[ $medico->email ] = $medico->price;
		if( $veterinario->email == $medico->email ){
			$_agenda = $medico->agenda;
			$_medico_actual = $medico;
		}
	}
	
	$agenda = [];
	foreach ($_agenda as $key => $item) {
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
	$info["email"] = $veterinario->email;

	$info["agenda"] = $agenda;
	$info["veterinario_id"] = $veterinario->veterinario_id;

	$info["profilePic"] = kmimos_get_foto($veterinario->user_id);;
	/*
	$info["firstName"] = set_format_name($data->kv_nombre);
	$info["rating"] = set_format_ranking($veterinario->rating);
	*/

	$info["firstName"] = set_format_name($_medico_actual->firstName).' '.set_format_name($_medico_actual->lastName);
	$info["rating"] = set_format_ranking($_medico_actual->rating);

	$info["price"] = number_format( $_medicos[ $veterinario->email ] , 2, ',', ',');

	if( $data->kv_cursos_realizados != '' ){
		$info['medicInfo']['courses'] = ''; //$data->kv_cursos_realizados;
	}

	if( $data->kv_trabajos != '' ){
		$info['medicInfo']['formerExperience'] = ''; //$data->kv_trabajos;
	}

	if( $data->kv_otros_estudios != '' ){
		$info['medicInfo']['otherStudies'] = ''; //$data->kv_otros_estudios;
	}


	echo json_encode([
		$info,
		$_medico_actual
	]);
	
	die();
?>