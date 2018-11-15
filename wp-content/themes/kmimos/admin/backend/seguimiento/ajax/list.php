<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');
    include_once('../lib/procesos.php');

    $data = array(
        "data" => array()
    );

    $actual = time();

    extract( $_POST );

    $usos = $Procesos->get_usos( $desde, $hasta );


    if( $usos != false ){
        foreach ($usos as $uso) {
 
            // Datos de las mascotas
            // $cliente_mascotas = $Clientes->get_mascotas($uso->id);

            $data["data"][] = array(
                $uso->id,
                utf8_encode($Procesos->get_cliente( $uso->user_id )),
                implode(" - ", json_decode($uso->reservas)),
                implode(" - ", json_decode($uso->conocer)),
                date('d/m/Y', strtotime($uso->fecha))
            );

        }
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
