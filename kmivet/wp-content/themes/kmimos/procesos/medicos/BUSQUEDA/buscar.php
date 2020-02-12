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

	$medicos = $medicos['res']->objects;
	$_medicos = [];
	foreach ($medicos as $key => $medico) {
		$_veterinarios[] = md5( $medico->email );
		$_medicos[ $medico->email ] = [
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
	
	$_veterinarios = [];
	$veterinarios = $wpdb->get_results("SELECT * FROM {$pf}veterinarios WHERE status = 1 AND ( agenda != '' && agenda LIKE '%{$hoy}%' ) {$no_incluirme}"); // AND precio > 0
	$res = [];
	foreach ($veterinarios as $_medico) {
		$info = json_decode($_medico->data);
		$_veterinarios[] = $_medico->email;
		$precio = ( isset( $_medicos[ $_medico->email ] ) ) ? $_medicos[ $_medico->email ]['price'] : -1;
		$img = kmimos_get_foto($_medico->user_id);
		if( $_medicos[ $_medico->email ]['rating'] != '' ){
			$res[] = [
				"id" => $_medico->id,
				"veterinario_id" => $_medico->veterinario_id,
				"name" => $_medicos[ $_medico->email ]['name'],
				"img" => $img,
				"univ" => $_medicos[ $_medico->email ]['university'],
				"ranking" => $_medicos[ $_medico->email ]['rating'],
				"price" => $_medicos[ $_medico->email ]['price'],
				"slug" => set_format_slug( $info->kv_nombre ),
				"hoy" => $hoy,
			];
		}
	}

	die( json_encode(
		[
			$res,
			$_params
		]
	) );
?>