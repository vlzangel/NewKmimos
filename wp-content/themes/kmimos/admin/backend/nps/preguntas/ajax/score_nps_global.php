<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once(dirname(__DIR__).'/lib/nps.php');

    $score = $nps->get_score_nps_global();

    $progress = '<div class="progress-bar progress-bar-default" style="width: 0%"></div>';

    if( $score['total_rows'] > 0 ){	
		$progress = '<div class="progress-bar progress-bar-success" style="width: '.$score['promoters']['porcentaje'].'%"> '.$score['promoters']['porcentaje'].'% </div>';
		$progress .= '<div class="progress-bar progress-bar-warning" style="width: '.$score['pasivos']['porcentaje'].'%"> '.$score['pasivos']['porcentaje'].'% </div>';
		$progress .= '<div class="progress-bar progress-bar-danger"  style="width: '.$score['detractores']['porcentaje'].'%"> '.$score['detractores']['porcentaje'].'% </div>';
    }

    $score['progress'] = $progress;

    
    echo json_encode($score, JSON_UNESCAPED_UNICODE);