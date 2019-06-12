<?php
	include 'wp-load.php';

	$INFORMACION = [
		"CLIENTE" => "Ángel Veloz",
		"RESRVA_ID" => "52354",
		"CUIDADOR_NAME" => "Pedro Peréz",
	];

	$mensaje = buildEmailTemplate(
        'correos/confirma_disponibilidad', 
        $INFORMACION
    );

	$mensaje = buildEmailHtml(
        $mensaje, 
        [
            'user_id' => 367, 
            'barras_ayuda' => false,
            'test' => false,
            'dudas' => false,
            'beneficios' => false,
        ]
    );

    echo $mensaje;
?>