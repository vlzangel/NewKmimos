<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once(dirname(__DIR__).'/lib/nps.php');

    $score = $nps->feedback_byId( $_POST['id'] );

    $data = array();

    if( count($score) > 0 ){

    	// Agrupar por fecha
    	$group = [];
    	foreach ( $score as $row ){

    		$fecha = date('Y-m-d', strtotime($row->fecha));
    		$row->tipo = strtolower( $row->tipo );

    		// Agregar tipo de respuesta
    		if( array_key_exists( $fecha, $group ) ){
	    		$group[ $fecha ][ 'total' ]++;
	    		$group[ $fecha ][ $row->tipo .'_ptos']++;
    		}else{
	    		// Default
	    		$group[ $fecha ]['date'] = $fecha;
	    		$group[ $fecha ]['promoters_porcentaje'] = 0;
	    		$group[ $fecha ]['promoters_ptos'] = 0;
	    		$group[ $fecha ]['pasivos_porcentaje'] = 0;
	    		$group[ $fecha ]['pasivos_ptos'] = 0;
	    		$group[ $fecha ]['detractores_porcentaje'] = 0;
	    		$group[ $fecha ]['detractores_ptos'] = 0;

	    		$group[ $fecha ]['total'] = 1;
	    		$group[ $fecha ][ $row->tipo .'_ptos'] = 1;
	    		$group[ $fecha ][ $row->tipo .'_porcentaje'] = 0;
    		}
			
			$group[ $fecha ][ 'promoters_porcentaje'] = ($group[$fecha]['promoters_ptos'] * 100)/$group[ $fecha ]['total']; 
			$group[ $fecha ][ 'pasivos_porcentaje'] = ($group[$fecha]['pasivos_ptos'] * 100)/$group[ $fecha ]['total']; 
			$group[ $fecha ][ 'detractores_porcentaje'] = ($group[$fecha]['detractores_ptos'] * 100)/$group[ $fecha ]['total']; 

			// format number
			$group[ $fecha ][ 'promoters_porcentaje'] = number_format($group[ $fecha ][ 'promoters_porcentaje'], 2);
			$group[ $fecha ][ 'pasivos_porcentaje'] =  number_format($group[ $fecha ][ 'pasivos_porcentaje'], 2);
			$group[ $fecha ][ 'detractores_porcentaje'] = number_format($group[ $fecha ][ 'detractores_porcentaje'], 2);

            // calcular Score NPS
            $group[ $fecha ]['score_nps'] = number_format( $group[ $fecha ]['promoters_porcentaje'] - $group[ $fecha ]['detractores_porcentaje'], 2 );

    	}
    	ksort($group);
    	if( count($group) > 0 ){
    		foreach ($group as $value) {
		    	$data[] = $value;
    		}
    	}

    }

	echo json_encode($data, JSON_UNESCAPED_UNICODE);