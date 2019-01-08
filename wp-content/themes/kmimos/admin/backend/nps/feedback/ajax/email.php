<?php
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))));
    include_once($raiz."/wp-load.php");
    include_once(dirname(__DIR__).'/lib/nps.php');

    extract($_POST);
 
	// Construir y enviar email
	$mensaje = buildEmailTemplate(
		'nps/feedback',
		[
			'respuesta' => $comentario,
		]
	);

	$mensaje = buildEmailHtml(
		$mensaje, 
		[]
	);

	$sts = 0;
	if( wp_mail( $email, "Kmimos Feedback", $mensaje ) ){
		$sts = 1;
		$wpdb->query( "INSERT INTO nps_comentario (pregunta_id, tipo, comentario, code) 
	    		VALUES ( {$respuesta_id}, 'admin', '{$comentario}', '{$code}' ) " );

	}
    echo json_encode(['sts'=>$sts], JSON_UNESCAPED_UNICODE);
