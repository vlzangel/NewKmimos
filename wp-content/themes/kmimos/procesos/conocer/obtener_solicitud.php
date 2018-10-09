<?php

	date_default_timezone_set('America/Mexico_City');

    /*
        Data General
    */

        $request_id = $id_orden;
		$user_id = $metas_solicitud["requester_user"][0];
		$post_id = $metas_solicitud["requested_petsitter"][0];
		$_POST['meeting_when'] = $metas_solicitud["meeting_when"][0];
		$_POST['meeting_time'] = $metas_solicitud["meeting_time"][0];
		$_POST['meeting_where'] = $metas_solicitud["meeting_where"][0];
		$_POST['service_start'] = $metas_solicitud["service_start"][0];
		$_POST['service_end'] = $metas_solicitud["service_end"][0];

		$pet_ids = unserialize( unserialize( $metas_solicitud["pet_ids"][0] ) );
		
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

		$email_cuidador = $cuidador->email;
		$email_cliente = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = '".$user_id."'");

		$email_admin    = get_option( 'admin_email' );

		$metas_cuidador = get_user_meta($cuidador->user_id);

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

			$mascotas_plantilla = $PATH_TEMPLATE.'/template/mail/reservar/partes/mascotas.php';
		    $mascotas_plantilla = file_get_contents($mascotas_plantilla);
		    $detalles_mascotas = "";

			foreach ($mascotas as $key => $mascota) {
				
				$data_mascota = get_post_meta($mascota->ID);
                $_temp = array();
                switch ( $data_mascota["pet_type"][0] ){
                    case '2605':
                        foreach ($data_mascota as $key => $value) {
                            switch ($key) {
                                case 'pet_sociable':
                                    if( $value[0] == 1 ){ $_temp[] = "Sociable"; }else{ $_temp[] = "No sociable"; }
                                break;
                                case 'aggressive_with_pets':
                                    if( $value[0] == 1 ){ $_temp[] = "Agresivo con perros"; }
                                break;
                                case 'aggressive_with_humans':
                                    if( $value[0] == 1 ){ $_temp[] = "Agresivo con humanos"; }
                                break;
                            }
                        }
                        $tamanio_mascota = $tamanos_array[ $data_mascota['size_pet'][0] ];
                        $raza = $wpdb->get_var("SELECT nombre FROM razas WHERE id=".$data_mascota['breed_pet'][0]);
                        $tipo = "dog";
                    break;
                    case '2608':
                        $_temp[] = "Sociable";
                        $tamanio_mascota = "N/A";
                        $raza = "Gato";
                        $tipo = "cat";
                    break;
                }
                $data_mascota['birthdate_pet'][0] = str_replace("/", "-", $data_mascota['birthdate_pet'][0]);
                $anio = strtotime($data_mascota['birthdate_pet'][0]);
                $edad_time = strtotime(date("Y-m-d"))-$anio;

                $edad = (date("Y", $edad_time)-1970)." año(s) ".date("m", $edad_time)." mes(es)";

                $temp = str_replace('[NOMBRE]', $data_mascota['name_pet'][0], $mascotas_plantilla);
				$temp = str_replace('[TYPE]', $tipo, $temp);
				$temp = str_replace('[RAZA]', $raza, $temp);
				$temp = str_replace('[EDAD]', $edad, $temp);
				$temp = str_replace('[TAMANO]', $tamanio_mascota, $temp);
				$temp = str_replace('[CONDUCTA]', implode("<br>", $_temp), $temp);
				$detalles_mascotas .= $temp;

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