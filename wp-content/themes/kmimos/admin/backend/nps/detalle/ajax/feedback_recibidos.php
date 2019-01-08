<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once(dirname(__DIR__).'/lib/nps.php');

    $encuesta = $nps->get_pregunta_byId( $_POST['id'] );
    $feedback = $nps->feedback_byId( $_POST['id'] );

    $recibidos = count($feedback);
    $total = (int) $encuesta->total_receptores - $recibidos;

    $data = [
    	'total' => number_format((($total * 100)/$encuesta->total_receptores), 2),
    	'recibidos' => number_format((($recibidos * 100)/$encuesta->total_receptores), 2),
    ];

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
