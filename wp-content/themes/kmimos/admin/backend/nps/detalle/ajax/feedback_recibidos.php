<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once(dirname(__DIR__).'/lib/nps.php');

    $encuesta = $nps->get_pregunta_byId( $_POST['id'] );
    $feedback = $nps->feedback_byId( $_POST['id'] );

    $total_receptores = $nps->get_remitentes_byId( $_POST['id'] );

    $total = (int) $total_receptores - $recibidos;
    $recibidos = count($feedback);

    $data = [
        'total' => 0,
        'recibidos' => 0,
    ];
    if( $total > 0 ){
        $data = [
            'total' => number_format((($total * 100)/$total), 2),
            'recibidos' => number_format((($recibidos * 100)/$total), 2),
        ];
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
