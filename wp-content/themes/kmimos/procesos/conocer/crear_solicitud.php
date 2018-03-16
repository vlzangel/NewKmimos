<?php

	date_default_timezone_set('America/Mexico_City');
    extract($_POST);

    /*
        Data General
    */

	$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = '".$post_id."'");
	if( $cuidador->activo == 0 ){
		$data = array(
			'error' => 'Error, este cuidador esta inactivo!'
		);
		echo json_encode($data);
		exit;
	}

	if( $user_id+0 == 0 ){

		$info = kmimos_get_info_syte();

		$data = array(
			'error' => 'Ha ocurrido un error, debe iniciar sesión para intentarlo nuevamente. Si el error persiste favor comunicarse al número: '.$info["telefono_sincosto"]
		);
		echo json_encode($data);
		exit;
	}

    $cuidador_post   = get_post($post_id);
    $nombre_cuidador = $cuidador_post->post_title;

    $datos_cuidador  = get_user_meta($cuidador_post->post_author);
    $telf_cuidador = $datos_cuidador["user_phone"][0];
    if( $telf_cuidador == "" ){
        $telf_cuidador = $datos_cuidador["user_mobile"][0];
    }
    if( $telf_cuidador == "" ){
        $telf_cuidador = "No registrado";
    }

    $datos_cliente = get_user_meta($user_id);

    $cliente_web  = $datos_cliente['first_name'][0];
    $cliente  = $datos_cliente['first_name'][0].' '.$datos_cliente['last_name'][0];

    $telf_cliente = $datos_cliente["user_phone"][0];
    if( isset($datos_cliente["user_mobile"][0]) ){
        $separador = (!empty($telf_cliente))? ' / ': "";
        $telf_cliente .= $separador . $datos_cliente["user_mobile"][0];
    }
    if( $telf_cliente == "" ){
        $telf_cliente = "No registrado";
    }

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
	$request_id = $wpdb->insert_id;

	$new_postmeta = array(
		'request_type'          => 1,
		'request_status'        => 1,
		'requester_user'        => $user_id,
		'requested_petsitter'   => $post_id,
		'request_date'          => date('d-m-Y'),
		'request_time'          => date('H:i:s'),
		'request_next'          => $rango,
		'next_time'             => date("d-m-Y H:i:s", $fecha_cancelacion),
		'meeting_when'          => $_POST['meeting_when'],
		'meeting_time'          => $_POST['meeting_time'],
		'meeting_where'         => $_POST['meeting_where'],
		'pet_ids'               => serialize($pet_ids),
		'service_start'         => $_POST['service_start'],
		'service_end'           => $_POST['service_end']
	);

	foreach($new_postmeta as $key => $value){
		update_post_meta($request_id, $key, $value);
	}

	$email_cuidador = $cuidador->email;
	$email_cliente  = $current_user->user_email;

	$email_admin    = get_option( 'admin_email' );

	$metas_cuidador = get_user_meta($cuidador->user_id);

	$telf_cuidador = $metas_cuidador["user_phone"][0];
	 if( isset($metas_cuidador["user_mobile"][0]) ){
		$separador = (!empty($telf_cuidador))? ' / ': "";
		$telf_cuidador .= $separador . $metas_cuidador["user_mobile"][0];
	}
	if( $telf_cuidador == "" ){
		$telf_cuidador = "No registrado";
	}

	$asunto     = 'Solicitud para conocer a cuidador';
	$headers[]  = 'From: Kmimos México <kmimos@kmimos.la>';

	$saludo_admin   = '<p><strong>Hola,</strong></p>';
	$service_id     = $_POST['type_service'];
	$service        = get_term( $service_id, 'product_cat' );

	$mascotas = $wpdb->get_results("SELECT * FROM wp_posts WHERE ID IN ( '".implode("','", $pet_ids)."' )");
	$detalles_mascotas = "";

	$comportamientos_array = array(
		"pet_sociable"           => "Sociables",
		"pet_sociable2"          => "No sociables",
		"aggressive_with_pets"   => "Agresivos con perros",
		"aggressive_with_humans" => "Agresivos con humanos",
	);
	$tamanos_array = array(
		"Pequeño",
		"Mediano",
		"Grande",
		"Gigante"
	);
	if( count($mascotas) > 0 ){
		foreach ($mascotas as $key => $mascota) {
			$data_mascota = get_post_meta($mascota->ID);

			$temp = array();
			foreach ($data_mascota as $key => $value) {

				switch ($key) {
					case 'pet_sociable':
						if( $value[0] == 1 ){
							$temp[] = "Sociable";
						}else{
							$temp[] = "No sociable";
						}
					break;
					case 'aggressive_with_pets':
						if( $value[0] == 1 ){
							$temp[] = "Agresivo con perros";
						}
					break;
					case 'aggressive_with_humans':
						if( $value[0] == 1 ){
							$temp[] = "Agresivo con humanos";
						}
					break;
				}

			}

			$data_mascota['birthdate_pet'][0] = str_replace("/", "-", $data_mascota['birthdate_pet'][0]);
            		$anio = strtotime($data_mascota['birthdate_pet'][0]);
            		$edad_time = strtotime(date("Y-m-d"))-$anio;
            		$edad = (date("Y", $edad_time)-1970)." año(s) ".date("m", $edad_time)." mes(es)";

			$raza = $wpdb->get_var("SELECT nombre FROM razas WHERE id=".$data_mascota['breed_pet'][0]);

			$detalles_mascotas .= "
	            <tr style='font-size: 12px;'>
					<td style='font-weight: 600; vertical-align: top; padding: 7px 0px;'>
						<img src='[URL_IMGS]/dog.png' style='width: 17px; padding: 0px 10px;' /> ".$data_mascota['name_pet'][0]."
					</td>
					<td style='padding: 7px; vertical-align: top;'>
	                    ".$raza."
					</td>
					<td style='padding: 7px; vertical-align: top;'>
	                    ".$edad."
					</td>
					<td style=' padding: 7px; vertical-align: top;'>
	                    ".$tamanos_array[ $data_mascota['size_pet'][0] ]."
					</td>
					<td style='padding: 7px; vertical-align: top;'>
	                    ".implode("<br>", $temp)."
					</td>
				</tr>
			";
		}
	}else{
		$detalles_mascotas .= "
			<tr style='font-weight: 400;'>
				<td colspan='5'>No tiene mascotas registradas.</td>
			</tr>
		";
	}
	$detalles_mascotas .= '';
?>