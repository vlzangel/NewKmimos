<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');


    $data = array(
        "data" => array()
    );

    include_once(dirname(__DIR__).'/lib/nps.php');
    $encuestas = $nps->get_encuesta_byId( $_POST['id'] );

    $tipo = [
        'promoters' => 'success',
        'pasivos' => 'warning',
        'detractores' => 'danger',
    ];


    if( $encuestas != false ){
        foreach ($encuestas as $encuesta) {
            $code = md5( $encuesta->pregunta . $encuesta->email );
            $data["data"][] = array(
                '<div class="link" data-target="load-comentarios" data-code="'.$code.'">'.utf8_encode($encuesta->email).'</div>'.
                '<small>'.date('Y-m-d', strtotime($encuesta->fecha)).'</small>',
                '<div style="vertical-align:middle;" class="text-center alert alert-'.$tipo[$encuesta->tipo].'" >'.$encuesta->puntos.'</div>'
            );

        }
    }


    echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>