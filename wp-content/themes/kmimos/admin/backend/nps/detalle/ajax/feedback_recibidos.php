<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once(dirname(__DIR__).'/lib/nps.php');

    $encuesta = $nps->get_pregunta_byId( $_POST['id'] );
    $feedback = $nps->feedback_byId( $_POST['id'] );

    $total_receptores = $nps->get_remitentes_byId( $_POST['id'] );
    $total_receptores = (int) trim($total_receptores);

    $recibidos = count($feedback);
    $completado = (int) $total_receptores - $recibidos;

    $data = [
        'total' => 0,
        'completado' => 0,
        'recibidos' => 0,
    ];
    if( $total_receptores > 0 ){
        $data = [
            'total' => $total_receptores,
            'completado' => $completado,
            'recibidos' => $recibidos,
        ];
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
