<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once(dirname(__DIR__).'/lib/nps.php');

    $encuesta = $nps->feedback_byId( $_POST['id'] );

    $total = 0;
    $suma = 0;
    $media = 0;

    if( count($encuesta) > 0 ){
	    foreach ($encuesta as $row) {
	    	$total++;
	    	$suma += $row->puntos;
	    }
	    $media = $suma / $total;
	    $media = number_format($media, 1);
	    $porcentaje = ( $suma * 100 ) / ($total*10);
	    $porcentaje = number_format($porcentaje, 1);
    }

    echo json_encode(['media'=>$media, 'porcentaje'=>$porcentaje], JSON_UNESCAPED_UNICODE);