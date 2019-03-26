<?php
	date_default_timezone_set('America/Mexico_City');
    extract($_POST);

    $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = '".$post_id."'");

    if( $test_conocer != 'b' ){
        $saldo_conocer = get_cupos_conocer_registro($user_id);
        if( $saldo_conocer->usos == 0 ){
            echo json_encode([
                'error' => 'Error, debe recargar para poder realizar más solicitudes!',
                'cuidador' => $cuidador->url
            ]);
            exit;
        }
    }

    $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = '".$post_id."'");
    if( $cuidador->activo == 0 ){
        $data = array( 'error' => 'Error, este cuidador esta inactivo!' );
        echo json_encode($data);
        exit;
    }

	if( $user_id+0 == 0 ){
		$info = kmimos_get_info_syte();
		$data = array( 'error' => 'Ha ocurrido un error, debe iniciar sesión para intentarlo nuevamente. Si el error persiste favor comunicarse al número: '.$info["telefono_sincosto"] );
		echo json_encode($data);
		exit;
	}

    $cuidador_post   = get_post($post_id);
    $nombre_cuidador = $cuidador_post->post_title;

    $inicio = "08";
    $fin    = "22";
    $rango  = 6;

    $hora_actual = strtotime("now");
    $xhora_actual = date("H", $hora_actual);

    if( ($xhora_actual-$rango) < $inicio ){
        $hoy = date("d-m-Y", $hora_actual);
        $hoy = explode("-", $hoy);
        $hoy = strtotime($hoy[0]."-".$hoy[1]."-".$hoy[2]." ".$inicio.":00:00");
        $ayer = date("d-m-Y", strtotime("-1 day"));
        $ayer = explode("-", $ayer);
        $ayer = strtotime($ayer[0]."-".$ayer[1]."-".$ayer[2]." ".$fin.":00:00");
        $exceso = $hoy-($hora_actual-($rango*3600));
        $fecha_cancelacion = $ayer-$exceso;
    }else{
        $fecha_cancelacion = ($hora_actual-($rango*3600));
    }

    $hoy = date("Y-m-d H:i:s");
    $token = md5($hoy);

    $sql = "
        INSERT INTO
            wp_posts 
        VALUES (
            NULL, 
            '{$user_id}', 
            '{$hoy}',
            '{$hoy}',
            '', 
            'Solicitud conocer cuidador ".$nombre_cuidador." del ".$hoy."',
            '', 
            'pending', 
            'closed', 
            'closed', 
            '', 
            'solicitud-{$token}', 
            '', 
            '', 
            '{$hoy}',
            '{$hoy}',
            '', 
            0,
            'http://www.kmimos.com.mx/',
            0, 
            'request', 
            '', 
            0
        );
    ";
    $wpdb->query($sql);
	$id_orden = $wpdb->insert_id;

	$new_postmeta = array(
		'request_type'          => 1,
		'request_status'        => 1,
		'requester_user'        => $user_id,
		'requested_petsitter'   => $post_id,
		'request_date'          => date('d-m-Y'),
		'request_time'          => date('H:i:s'),
		'request_next'          => $rango,
		'next_time'             => date("d-m-Y H:i:s", $fecha_cancelacion),
		'meeting_when'          => $meeting_when,
		'meeting_time'          => $meeting_time,
		'meeting_where'         => $meeting_where,
		'pet_ids'               => serialize($pet_ids),
		'service_start'         => $_POST['service_start'],
		'service_end'           => $_POST['service_end']
	);

	foreach($new_postmeta as $key => $value){
		update_post_meta($id_orden, $key, $value);
	}

    set_uso_banner([
        "user_id" => $user_id,
        "type" => "conocer",
        "conocer_id" => $id_orden
    ]);
?>