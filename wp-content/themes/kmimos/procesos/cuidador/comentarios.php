<?php

	$load = realpath('../../../../../wp-load.php');
	if(file_exists($load)){
		include_once($load);
	}

	include_once(realpath('../../../../../vlz_config.php'));
	include_once(dirname(__DIR__).'/funciones/db.php');
	include_once(dirname(__DIR__).'/funciones/generales.php');
	extract($_POST);

	$sql = "
		SELECT
			comentario.user_id AS cliente_id,
			comentario.comment_ID AS comment_ID,
			comentario.comment_author_email AS cliente_email,
			comentario.comment_author AS cliente,
			comentario.comment_content AS contenido,
			comentario.comment_date AS fecha
		FROM
			wp_comments	AS comentario
		WHERE
			comentario.comment_post_ID = '{$servicio}' AND
    		comentario.comment_approved = '1'
		ORDER BY comentario.comment_ID DESC
	";

	$resultado = array();
	$comentarios = $wpdb->get_results($sql);
	foreach ($comentarios as $comentario) {

		$puntuaciones = array(
			"punctuality" => 0,
			"trust" => 0,
			"cleanliness" => 0,
			"care" => 0
		);

		$metas = $wpdb->get_results("SELECT * FROM wp_commentmeta WHERE comment_id = ".$comentario->comment_ID);

		if( $metas !== false ){
			foreach ($metas as  $meta) {
				$puntuaciones[$meta->meta_key] = $meta->meta_value;
			}
		}

		$user_id = $comentario->cliente_id;

		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $inicio = strtotime( $comentario->fecha );
	    $fecha = date('d', $inicio)." ".$meses[date('n', $inicio)-1]. ", ".date('Y', $inicio) ;

	    if( $user_id+0 > 0 ){
	    	$img = kmimos_get_foto($user_id);
	    }else{
	    	$img = getTema()."/images/noimg.png";
	    }

		$resultado[] = array(
			"cliente"	=> ($comentario->cliente),
			"img"	=> $img,
			"contenido" => ($comentario->contenido),
			"fecha" => ($fecha),
			"puntualidad" => ($puntuaciones["punctuality"]),
			"confianza" => ($puntuaciones["trust"]),
			"limpieza" => ($puntuaciones["cleanliness"]),
			"cuidado" => ($puntuaciones["care"])
		);
	}

	echo json_encode($resultado);
?>


