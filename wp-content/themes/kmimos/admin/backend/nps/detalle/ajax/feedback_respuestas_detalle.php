<?php
    session_start();
    date_default_timezone_set('America/Mexico_City');


    $data = array(
        "data" => array()
    );

    include_once(dirname(__DIR__).'/lib/nps.php');
    $encuestas = $nps->get_encuesta_byId( $_POST['id'] );

    $estatus = $nps->get_estatus(); 

    $tipo = [
        'promoters' => 'success',
        'pasivos' => 'warning',
        'detractores' => 'danger',
    ];

    if( $encuestas != false ){
        $count=0;
        foreach ($encuestas as $encuesta) {
            $nombre = '';
            $apellido = '';

            $user_id = $nps->db->get_var("SELECT ID FROM wp_users WHERE user_email = '".$encuesta->email."' ");
            if( $user_id > 0 ){
                $nombre = $nps->db->get_var( "SELECT meta_value FROM wp_usermeta WHERE meta_key='first_name' AND user_id = {$user_id}" );
                $apellido = $nps->db->get_var( "SELECT meta_value FROM wp_usermeta WHERE meta_key='last_name' AND user_id = {$user_id}" );
            }
  
            $data["data"][] = array(
                ++$count,
                utf8_encode($nombre),
                utf8_encode($apellido),
                $encuesta->email,
                date('Y-m-d', strtotime($encuesta->fecha)),
                '<div class="text-center alert alert-'.$tipo[$encuesta->tipo].'" >'.$encuesta->puntos.'</div>'
            );
        }
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);

