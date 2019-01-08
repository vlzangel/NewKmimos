<?php
	
	session_start();
	include ( '../../../../../wp-load.php' );

    $data = array(
    	'sts' => 0
    );

    extract($_POST);
	
    if( isset($respuesta_id) && isset($respuesta) && isset($email) ){
	    if( !empty($respuesta_id) && !empty($respuesta) && !empty($email) ){
	    	$tipo_nps = '';
	    	if( $respuesta > 0 && $respuesta <= 6 ){
                $tipo_nps = 'detractores';
            }else if( $respuesta == 7 || $respuesta == 8 ){
                $tipo_nps = 'pasivos';
            }else if( $respuesta == 9 || $respuesta == 10 ){
                $tipo_nps = 'promoters';
            }
	    	$wpdb->query( "UPDATE nps_respuestas SET puntos = {$respuesta}, tipo = '{$tipo_nps}' WHERE id = {$respuesta_id};" );
	    	if( isset($observacion) && !empty($observacion) ){
	    		$wpdb->query( "INSERT INTO nps_comentario (pregunta_id, tipo, comentario, code) 
	    		VALUES ( {$respuesta_id}, 'cliente', '{$observacion}', '{$code}' ) " );
	    	}
	    	$data['sts'] = 1;
	    }
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
