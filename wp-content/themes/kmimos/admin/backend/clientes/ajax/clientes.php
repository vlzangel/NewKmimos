<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');
    include_once('../lib/clientes.php');

    $data = array(
        "data" => array()
    );

    $actual = time();

    extract( $_POST );

    $clientes = $Clientes->get_clientes( $desde, $hasta );

    $sexo = [
        'M' => 'Mujer',
        'H' => 'Hombre',
    ];

    if( $clientes != false ){
        foreach ($clientes as $cliente) {
 
            // Datos de las mascotas
            $cliente_mascotas = $Clientes->get_mascotas($cliente->user_id);

            $mascotas = [
                'nombre' => '',
                'raza' => '',
                'edad' => '',
                'tamano' => '',
            ];

            if( !empty($cliente_mascotas) ){
                foreach ($cliente_mascotas as $mascota) {
                    $mascotas['nombre'] .= utf8_encode($mascota->nombre).'<br>';
                    $mascotas['raza'] .= utf8_encode($mascota->raza).'<br>';
                    $mascotas['edad'] .= $mascota->edad.'<br>';
                    $mascotas['tamano'] .= utf8_encode($mascota->tamano).'<br>';
                }
            }

            $data["data"][] = array(
                $cliente->user_id,
                date('Y-m-d',strtotime($cliente->fecha_registro)),
                utf8_encode($cliente->nombre),
                utf8_encode($cliente->apellido),
                $cliente->email,
                $cliente->telefono,
                $sexo[ $cliente->sexo ],
                utf8_encode($cliente->edad),
                $mascotas['nombre'],
                $mascotas['raza'],
                $mascotas['edad'],
                $mascotas['tamano']
            );

        }
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
