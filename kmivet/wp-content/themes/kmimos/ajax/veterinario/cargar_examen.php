<?php
	$respuestas = [];
	foreach ($_POST as $key => $resp) {
		if( substr($key, 0, 4) == 'preg' ){
			$respuestas[] = [
				"id" => substr($key, 5),
				"content" => $resp 
			];
		}
	}

	$res = put_answers($id, [
		'answers' => $respuestas
	]);

	$r = [
		"status" => true,
	];

	die( json_encode( $r ) );
?>