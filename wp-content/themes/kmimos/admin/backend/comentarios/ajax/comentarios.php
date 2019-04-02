<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');

    include_once('../lib/comentarios.php');

    $data = array(
        "data" => array()
    );

    extract( $_POST );


    $cuidadores = $comentarios->get_cuidadores();
 

    if( $cuidadores != false ){
        $i = 0;
        foreach ($cuidadores as $cuidador) {

            $trust = $comentarios->get_criterio_valoracion( $cuidador->user_id, 'trust');
            $cleanliness = $comentarios->get_criterio_valoracion( $cuidador->user_id, 'cleanliness');
            $punctuality = $comentarios->get_criterio_valoracion( $cuidador->user_id, 'punctuality');
            $care = $comentarios->get_criterio_valoracion( $cuidador->user_id, 'care');
            $total = $comentarios->get_criterio_general( $cuidador->user_id );
            $reservas = $comentarios->get_reservas_confimadas( $cuidador->user_id );

            $data["data"][] = array(
                ++$i,
                $cuidador->user_id,
                utf8_encode($cuidador->email),
                utf8_encode($cuidador->nombre),
                utf8_encode($cuidador->apellido),
               
                $trust->maximo,
                $trust->minimo,
                number_format( $trust->promedio, 2),
                
                $punctuality->maximo,
                $punctuality->minimo,
                number_format( $punctuality->promedio,2),

                $cleanliness->maximo,
                $cleanliness->minimo,
                number_format( $cleanliness->promedio,2),

                $care->maximo,
                $care->minimo,
                number_format( $care->promedio,2),

                $total->maximo,
                $total->minimo,
                number_format( $total->promedio,2),

                $reservas['total'],
                $reservas['list'],
            );
 
        }
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>