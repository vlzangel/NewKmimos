<?php
	// session_destroy();
	session_start();
	extract( $_POST );

	$_infos = $_SESSION['medicos_info'];

	$url = "https://api.mediqo.mx/medics/?specialty={$specialty}&lat={$lat}&lng={$lng}&tz=America/Mexico_City";
	$hash = md5( $url );
	if( isset($_SESSION[ $hash ]) ){
		$res = $_SESSION[ $hash ];
	}else{
		$medicos = json_decode( file_get_contents("https://api.mediqo.mx/medics/?specialty={$specialty}&lat={$lat}&lng={$lng}") );
		$medicos = $medicos->objects;
		$_medicos = [];
		foreach ($medicos as $key => $medico) {
			$img = ( isset( $medico->profilePic ) != "" ) ? $medico->profilePic : 'http://www.psi-software.com/wp-content/uploads/2015/07/silhouette-250x250.png';
			$uni = ( isset($medico->medicInfo->university) ) ? $medico->medicInfo->university : '';
			$_medicos[] = [
				"id" => $medico->id,
				"name" => $medico->firstName.' '.$medico->lastName,
				"img" => $img,
				"univ" => $uni,
				"price" => $medico->price
			];
			$_infos[ $medico->id ] = $medico;
		}
		$res = json_encode( $_medicos );
		$_SESSION[ $hash ] = $res;
		$_SESSION[ 'medicos_info' ] = $_infos;
	}
	echo ( $res );
	die();
?>