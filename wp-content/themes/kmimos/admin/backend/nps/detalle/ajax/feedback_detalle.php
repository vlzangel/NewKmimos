<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once(dirname(__DIR__).'/lib/nps.php');

    $score = $nps->get_score_nps_detalle( $_POST['id'] );

    echo json_encode($score, JSON_UNESCAPED_UNICODE);