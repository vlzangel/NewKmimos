<?php
	extract($_GET);
	
	include(((dirname(dirname(dirname(dirname(dirname(__DIR__)))))))."/wp-load.php");

	global $wpdb;
	
	$PATH_TEMPLATE = ((dirname(dirname(__DIR__))));

	$info = kmimos_get_info_syte();
	add_filter( 'wp_mail_from_name', function( $name ) { global $info; return $info["titulo"]; });
    add_filter( 'wp_mail_from', function( $email ) { global $info; return $info["email"]; });

	$continuar = true;
	if( !isset($_POST["post_id"]) ){
	    $status = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = $id_orden AND meta_key = 'request_status';");
		if( $status != 1 ){
			$estado = array(
				2 => "Confirmada",
				3 => "Cancelada",
				4 => "Cancelada"
			);
			$msg = "
				<div class='msg_acciones'>

					<div style='margin-bottom: 15px; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000;'>
						<div style='font-family: Arial; font-size: 20px; font-weight: bold; letter-spacing: 0.4px; color: #6b1c9b; padding-bottom: 10px; text-align: left;'>
							Lo sentimos,</strong>
						</div>	
					    <div style='font-family: Arial; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding-bottom: 10px; text-align: left;'>
					    	Te notificamos que la solicitud N° <strong>".$id_orden."</strong> ya ha sido ".$estado[$status]." anteriormente.
					    </div>
					    <div style='font-family: Arial; font-size: 14px; line-height: 1.07; letter-spacing: 0.3px; color: #000000; padding-bottom: 0px; text-align: left;'>
					    	Por tal motivo ya no es posible realizar cambios en el estatus de la misma.
					    </div>
					</div>
				</div>
			";
	   		$CONTENIDO .= $msg;
	   		$continuar = false;
		}
	}

	if( isset($NO_ENVIAR) ){ $continuar = true; }

	if( $continuar ){

	    if( $acc == "" && isset($_POST["post_id"]) ){
	    	include(__DIR__."/crear_solicitud.php");
	    }

		$metas_solicitud = get_post_meta($id_orden); 

		/* Cuidador */
	    	$cuidador_name 	= $wpdb->get_var("SELECT post_title FROM wp_posts WHERE ID = '".$metas_solicitud['requested_petsitter'][0]."'");
			$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = '".$metas_solicitud['requested_petsitter'][0]."'");
			$email_cuidador = $cuidador->email;

	    /* Cliente */
		    $cliente = $metas_solicitud['requester_user'][0];
			$metas_cliente = get_user_meta($cliente);
			$cliente_name = $metas_cliente["first_name"][0]." ".$metas_cliente["last_name"][0];
			$email_cliente = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = '".$cliente."'");

        $_datos_cliente = getTemplate("reservar/partes/datos_cliente");
        $_datos_cuidador = getTemplate("reservar/partes/datos_cuidador");

	    include(__DIR__."/obtener_solicitud.php");

        $_SESSION["USER_ID_CLIENTE_CORREOS"] = $metas_solicitud['requester_user'][0];

		$INFORMACION = [
            // GENERALES

                'HEADER'                => "",
                'id_solicitud'          => $request_id,
                'fecha'                 => $_POST['meeting_when'],
                'hora'                  => $_POST['meeting_time'],
                'lugar'                 => $_POST['meeting_where'],
                'desde'                 => date("d/m", strtotime( str_replace("/", "-", $_POST['service_start']) )),
                'hasta'                 => date("d/m", $fin),
                'anio'                  => date("Y", $fin),
                'MASCOTAS'              => $detalles_mascotas,

                'ACEPTAR'               => get_home_url().'/perfil-usuario/solicitudes/confirmar/'.$request_id,
                'RECHAZAR'              => get_home_url().'/perfil-usuario/solicitudes/cancelar/'.$request_id,

            // CLIENTE
                'DATOS_CLIENTE'         => $_datos_cliente,
                'NAME_CLIENTE'          => $cliente_name,
                'AVATAR_CLIENTE'        => kmimos_get_foto($cliente),
                'TELEFONOS_CLIENTE'     => $telf_cliente,
                'CORREO_CLIENTE'        => $email_cliente,
                
            // CUIDADOR
                'DATOS_CUIDADOR'        => preg_replace("#<tr class='dir'(.*?)tr>#s", "", $_datos_cuidador),
                'NAME_CUIDADOR'         => $nombre_cuidador,
                'AVATAR_CUIDADOR'       => kmimos_get_foto($cuidador->user_id),
                'TELEFONOS_CUIDADOR'    => $telf_cuidador,
                'CORREO_CUIDADOR'       => $email_cuidador,
        ];

	    if( $acc == "" ){
	    	include(__DIR__."/nueva.php");
	    }

	    if( $acc == "CFM" ){
	    	include(__DIR__."/confirmar.php");
	    }

	    if( $acc == "CCL" ){
	    	include(__DIR__."/cancelar.php");
	    }
	    
	}
?>