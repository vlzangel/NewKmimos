<?php
	session_start();

	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
	include_once($raiz."/wp-load.php");
	date_default_timezone_set('America/Mexico_City');

	extract( $_POST );

	/*
	$_infos = $_SESSION['medicos_info'];
	$medicos = get_medics($specialty, $lat, $lng);
	$medicos = $medicos['res']->objects;
	$_medicos = [];
	foreach ($medicos as $key => $medico) {
		$img = ( ( $medico->profilePic ) != "" ) ? $medico->profilePic : 'http://www.psi-software.com/wp-content/uploads/2015/07/silhouette-250x250.png';
		$uni = ( isset($medico->medicInfo->university) ) ? $medico->medicInfo->university : '';
		$nombre = set_format_name( $medico->firstName.' '.$medico->lastName);
		$_medicos[] = [
			"id" => $medico->id,
			"name" => $medico->firstName.' '.$medico->lastName,
			"img" => $img,
			"univ" => $uni,
			"price" => $medico->price,
			"ranking" => set_format_ranking($medico->rating),
			"price" => set_format_precio($medico->price),
			"slug" => set_format_slug( $medico->firstName.' '.$medico->lastName ),
		];
		$medico->firstName = set_format_name($medico->firstName);
		$medico->lastName = set_format_name($medico->lastName);
		$_infos[ $medico->id ] = $medico;
	}
	$res = json_encode( $_medicos );
	$_SESSION[ $hash ] = $res;
	$_SESSION[ 'medicos_info' ] = $_infos;
	*/

	global $wpdb;
	global $vlz;
	extract($vlz);

	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	$tipo_usuario = strtolower( get_usermeta( $user_id, "tipo_usuario", true ) );
	$no_incluirme = '';
	if( $tipo_usuario == 'veterinario' ){
		$no_incluirme = " AND user_id != '{$user_id}' ";
	}

	$veterinarios = $wpdb->get_results("SELECT * FROM {$pf}veterinarios WHERE status = 1 AND precio > 0 AND agenda != NULL {$no_incluirme}");
	$res = [];
	foreach ($veterinarios as $medico) {
		$info = json_decode($medico->data);
		$img = $t.'/images/image.png';
		$res[] = [
			"id" => $medico->id,
			"name" => $info->kv_nombre,
			"img" => $img,
			"univ" => $info->kv_universidad,
			"price" => $medico->precio,
			"ranking" => set_format_ranking($medico->rating),
			"price" => set_format_precio($medico->precio),
			"slug" => set_format_slug( $info->kv_nombre ),
		];
	}

	die( json_encode($res) );
?>