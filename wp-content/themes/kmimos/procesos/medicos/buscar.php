<?php
	// session_destroy();
	session_start();
	extract( $_POST );

	function set_format_slug($cadena){
		// $cadena = utf8_encode( $cadena );
		$originales = [ 'Á','É','Í','Ó','Ú' ];
        $modificadas = [ 'a','e','i','o','u' ];
        foreach ($originales as $key => $value) {
        	$cadena = str_replace($value, $modificadas[ $key ], $cadena);
        }
        return strtolower($cadena);
	}

	function set_format_name($cadena){
		$originales = [ 'Á','É','Í','Ó','Ú', 'Ñ' ];
        $modificadas = [ '&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&ntilde;' ];
        foreach ($originales as $key => $value) {
        	$cadena = str_replace($value, $modificadas[ $key ], $cadena);
        }
        return mb_strtolower($cadena, 'UTF-8');
	}

	function set_format_precio($price){
		$temp = explode('.', $price);
		if( !isset($temp[1]) ){ $temp[1] = '00'; }
		return '<span>MXN$</span> <strong>'.$temp[0].',</strong><span>'.$temp[1].'</span>';
	}

	function set_format_ranking($ranking){
		$ranking += 0;
		if( $ranking > 5 ){ $ranking = 5; }
		if( $ranking < 1 ){ $ranking = 1; }
		$_ranking = '';
		for ($i=1; $i <= $ranking; $i++) {  $_ranking .= '<span class="active"></span>'; }
		if( $ranking < 5 ){ for ($i=$ranking; $i < 5; $i++) {  $_ranking .= '<span></span>'; } }
		return $_ranking;
	}

	$_infos = $_SESSION['medicos_info'];

	$url = "https://api.mediqo.mx/medics/?specialty={$specialty}&lat={$lat}&lng={$lng}&tz=America/Mexico_City";
	$hash = md5( $url );
/*	if( isset($_SESSION[ $hash ]) ){
		$res = $_SESSION[ $hash ];
	}else{*/
		$medicos = json_decode( file_get_contents("https://api.mediqo.mx/medics/?specialty={$specialty}&lat={$lat}&lng={$lng}") );
		$medicos = $medicos->objects;
		$_medicos = [];
		foreach ($medicos as $key => $medico) {
			$img = ( ( $medico->profilePic ) != "" ) ? $medico->profilePic : 'http://www.psi-software.com/wp-content/uploads/2015/07/silhouette-250x250.png';
			$uni = ( isset($medico->medicInfo->university) ) ? $medico->medicInfo->university : '';
			$nombre = set_format_name( $medico->firstName.' '.$medico->lastName);
			$_medicos[] = [
				"id" => $medico->id,
				"name" => $nombre,
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
	// }
	//echo ( "https://api.mediqo.mx/medics/?specialty={$specialty}&lat={$lat}&lng={$lng}" );
	print_r( $res );
	die();
?>