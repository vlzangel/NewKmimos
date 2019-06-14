<?php

    include('../wp-load.php');

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	$mensaje = buildEmailTemplate(
		'nps/feedback_cliente',
		[
			'id' => "12354",
			'email' => "a.veloz@kmimos.la",
			'nombre' => "Ángel Veloz",
			'IMG_URL' => get_recurso('img/NPS'),
			'URL_HOME' => get_home_url(),
		]
	);

	/*
	print_r([
		'id' => "12354",
		'email' => "a.veloz@kmimos.la",
		'nombre' => "Ángel Veloz",
		'IMG_URL' => get_recurso('img/NPS'),
	]);
	*/
	
	wp_mail( "a.veloz@kmimos.la", '¿Cómo cuidamos a tu peludo 🐶😺? Ayúdanos a mejorar contestando esta breve encuesta sobre tu reserva con Kmimos.', $mensaje);
?>