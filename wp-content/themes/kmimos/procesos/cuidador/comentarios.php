<?php
	include("../../../../../vlz_config.php");
	include("../funciones/db.php");
	include("../funciones/generales.php");

	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn);

	extract($_POST);

	$sql = "
		SELECT
			comentario.user_id AS cliente_id,
			comentario.comment_author_email AS cliente_email,
			comentario.comment_author AS cliente,
			comentario.comment_content AS contenido,
			comentario.comment_date AS fecha,
			puntualidad.meta_value AS puntualidad_valor ,
			confianza.meta_value AS confianza_valor,
			limpieza.meta_value AS limpieza_valor,
			cuidado.meta_value AS cuidado_valor
		FROM
			wp_comments	AS comentario
		INNER JOIN wp_commentmeta AS puntualidad 	ON ( comentario.comment_ID = puntualidad.comment_id AND puntualidad.meta_key = 'punctuality')
		INNER JOIN wp_commentmeta AS confianza 		ON ( comentario.comment_ID = confianza.comment_id 	AND confianza.meta_key = 'trust')
		INNER JOIN wp_commentmeta AS limpieza 		ON ( comentario.comment_ID = limpieza.comment_id  	AND limpieza.meta_key = 'cleanliness')
		INNER JOIN wp_commentmeta AS cuidado 		ON ( comentario.comment_ID = cuidado.comment_id 	AND cuidado.meta_key = 'care')
		WHERE
			comentario.comment_post_ID = '{$servicio}'
		ORDER BY comentario.comment_ID DESC
	";

	$comentarios = $db->get_results($sql);

	$resultado = array();

	foreach ($comentarios as $comentario) {

		$user_id = $db->get_var("SELECT ID FROM wp_users WHERE user_email = '{$comentario->cliente_email}' ");

		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $inicio = strtotime( $comentario->fecha );
	    $fecha = date('d', $inicio)." ".$meses[date('n', $inicio)-1]. ", ".date('Y', $inicio) ;

		$resultado[] = array(
			"cliente"	=> utf8_encode($comentario->cliente),
			"img"	=> kmimos_get_foto($user_id),
			"contenido" => utf8_encode($comentario->contenido),
			"fecha" => ($fecha),
			"puntualidad" => ($comentario->puntualidad_valor),
			"confianza" => ($comentario->confianza_valor),
			"limpieza" => ($comentario->limpieza_valor),
			"cuidado" => ($comentario->cuidado_valor)
		);
	}

	echo json_encode($resultado);
?>