<?php
	session_start();

	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
	include_once($raiz."/wp-load.php");
	date_default_timezone_set('America/Mexico_City');

	extract( $_POST );

	$_SESSION['search'] = [
		"specialty" => $specialty, 
		"lat" => $lat, 
		"lng" => $lng
	];

	$medicos = get_medics($specialty, $lat, $lng);

	$_params = [
		"specialty" => $specialty, 
		"lat" => $lat, 
		"lng" => $lng
	];


	$_veterinarios = [];

	$medicos = $medicos['res']->objects;
	$_medicos = [];
	foreach ($medicos as $key => $medico) {
		$_veterinarios[] = md5( $medico->email );
		$_medicos[ md5($medico->email) ] = [
			'email' => $medico->email,
			'price' => $medico->price,
			'name' => $medico->firstName.' '.$medico->lastName,
			"rating" => set_format_ranking($medico->rating),
			"price" => set_format_precio($medico->price),
			"university" => $medico->medicInfo->university,
		];
	}

	global $wpdb;
	global $vlz;
	extract($vlz);

	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$tipo_usuario = strtolower( get_usermeta( $user_id, "tipo_usuario", true ) );
	$no_incluirme = ( $tipo_usuario == 'veterinario' ) ? " AND user_id != '{$user_id}' " : '';

	extract(get_dias_meses());

	$hoy = $_dias[ date("N")-1 ][0];
	
	$veterinarios = $wpdb->get_results("SELECT * FROM {$pf}veterinarios WHERE status = 1 {$no_incluirme}"); //  WHERE status = 1 {$no_incluirme} AND precio > 0 // AND ( agenda != '' && agenda LIKE '%{$hoy}%' )
	$res = [];
	foreach ($veterinarios as $_medico) {

		$img = kmimos_get_foto($_medico->user_id);

		$token = md5($_medico->email);

		if( array_key_exists($token, $_medicos)){
			$res[] = [
				"id" => $_medico->id,
				"veterinario_id" => $_medico->veterinario_id,
				"name" => $_medicos[ md5($_medico->email) ]['name'],
				"img" => $img,
				"univ" => $_medicos[ md5($_medico->email) ]['university'],
				"ranking" => $_medicos[ md5($_medico->email) ]['rating'],
				"price" => $_medicos[ md5($_medico->email) ]['price'],
				"slug" => '',
				"hoy" => $hoy,
			];
		}

	}

	die( json_encode(
		[
			$res,
			$_medicos,
			$veterinarios
		]
	) );
?>