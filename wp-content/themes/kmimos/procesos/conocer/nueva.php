<?php

    	date_default_timezone_set('America/Bogota');

	if( $id_orden+0 > 0 ){
	    include(__DIR__."/obtener_solicitud.php");
	}else{
	    include( dirname(dirname(dirname(dirname(dirname(__DIR__)))))."/vlz_config.php" );
	    include( dirname(dirname(dirname(dirname(dirname(__DIR__)))))."/wp-load.php" );

		global $wpdb;

	    include(__DIR__."/crear_solicitud.php");
	}

		/*
			Cuidador
		*/

		$info = kmimos_get_info_syte();

		$cuidador_file = realpath('../../template/mail/conocer/cuidador/nueva.php');
        $mensaje_cuidador = file_get_contents($cuidador_file);

        $fin = strtotime( str_replace("/", "-", $_POST['service_end']) );

        $detalles_mascotas = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $detalles_mascotas);

        $mensaje_cuidador = str_replace('[ACEPTAR]', get_home_url().'/perfil-usuario/solicitudes/confirmar/'.$request_id, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[CANCELAR]', get_home_url().'/perfil-usuario/solicitudes/cancelar/'.$request_id, $mensaje_cuidador);

        $mensaje_cuidador = str_replace('[name]', $cliente, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[avatar]', kmimos_get_foto($user_id), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[nombre_usuario]', $nombre_cuidador, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[telefonos]', $telf_cliente, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[email]', $email_cliente, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[id_solicitud]', $request_id, $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[fecha]', $_POST['meeting_when'], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[hora]', $_POST['meeting_time'], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[lugar]', $_POST['meeting_where'], $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[desde]', date("d/m", strtotime( str_replace("/", "-", $_POST['service_start']) )), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[hasta]', date("d/m", $fin), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[anio]', date("Y", $fin), $mensaje_cuidador);
        $mensaje_cuidador = str_replace('[MASCOTAS]', $detalles_mascotas, $mensaje_cuidador);

		$mensaje_cuidador = get_email_html($mensaje_cuidador, false);

        if( isset($NO_ENVIAR) ){
            echo $mensaje_cuidador;
        }else{
            wp_mail( $email_cuidador,  $asunto, $mensaje_cuidador);
        }
		

	/*
		Cliente
	*/

		$cliente_file = realpath('../../template/mail/conocer/cliente/nueva.php');
        $mensaje_cliente = file_get_contents($cliente_file);

        $mensaje_cliente = str_replace('[name]', $cliente_web, $mensaje_cliente);
        $mensaje_cliente = str_replace('[avatar]', kmimos_get_foto($cuidador->user_id), $mensaje_cliente);
        $mensaje_cliente = str_replace('[nombre_usuario]', $nombre_cuidador, $mensaje_cliente);
        $mensaje_cliente = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_cliente);
        $mensaje_cliente = str_replace('[telefonos]', $telf_cuidador, $mensaje_cliente);
        $mensaje_cliente = str_replace('[email]', $email_cuidador, $mensaje_cliente);
        $mensaje_cliente = str_replace('[id_solicitud]', $request_id, $mensaje_cliente);
        $mensaje_cliente = str_replace('[fecha]', $_POST['meeting_when'], $mensaje_cliente);
        $mensaje_cliente = str_replace('[hora]', $_POST['meeting_time'], $mensaje_cliente);
        $mensaje_cliente = str_replace('[lugar]', $_POST['meeting_where'], $mensaje_cliente);
        $mensaje_cliente = str_replace('[desde]', date("d/m", strtotime( str_replace("/", "-", $_POST['service_start']) )), $mensaje_cliente);
        $mensaje_cliente = str_replace('[hasta]', date("d/m", $fin), $mensaje_cliente);
        $mensaje_cliente = str_replace('[anio]', date("Y", $fin), $mensaje_cliente);

		$mensaje_cliente = get_email_html($mensaje_cliente, false);

        if( isset($NO_ENVIAR) ){
            echo $mensaje_cliente;
        }else{
            wp_mail( $email_cuidador,  $asunto, $mensaje_cliente);
        }

	/*
		Enviando E-mails Administradores
	*/

		$admin_file = realpath('../../template/mail/conocer/admin/nueva.php');
        $mensaje_admin = file_get_contents($admin_file);

        /* Generales */

	        $mensaje_admin = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $mensaje_admin);
	        $mensaje_admin = str_replace('[id_solicitud]', $request_id, $mensaje_admin);
	        $mensaje_admin = str_replace('[fecha]', $_POST['meeting_when'], $mensaje_admin);
	        $mensaje_admin = str_replace('[hora]', $_POST['meeting_time'], $mensaje_admin);
	        $mensaje_admin = str_replace('[lugar]', $_POST['meeting_where'], $mensaje_admin);
	        $mensaje_admin = str_replace('[desde]', date("d/m", strtotime( str_replace("/", "-", $_POST['service_start']) )), $mensaje_admin);
	        $mensaje_admin = str_replace('[hasta]', date("d/m", $fin), $mensaje_admin);
	        $mensaje_admin = str_replace('[anio]', date("Y", $fin), $mensaje_admin);
       		$mensaje_admin = str_replace('[MASCOTAS]', $detalles_mascotas, $mensaje_admin);

        /* Cliente */

	        $mensaje_admin = str_replace('[nombre_cliente]', $cliente_web, $mensaje_admin);
	        $mensaje_admin = str_replace('[avatar_cliente]', kmimos_get_foto($user_id), $mensaje_admin);
	        $mensaje_admin = str_replace('[telefonos_cliente]', $telf_cliente, $mensaje_admin);
	        $mensaje_admin = str_replace('[email_cliente]', $email_cliente, $mensaje_admin);

        /* Cuidador */
        
	        $mensaje_admin = str_replace('[avatar_cuidador]', kmimos_get_foto($cuidador->user_id), $mensaje_admin);
	        $mensaje_admin = str_replace('[nombre_cuidador]', $nombre_cuidador, $mensaje_admin);
	        $mensaje_admin = str_replace('[telefonos_cuidador]', $telf_cuidador, $mensaje_admin);
	        $mensaje_admin = str_replace('[email_cuidador]', $email_cuidador, $mensaje_admin);

		$mensaje_admin = get_email_html($mensaje_admin, false);

        if( isset($NO_ENVIAR) ){
            echo $mensaje_admin;
        }else{
            kmimos_mails_administradores_new($asunto, $mensaje_admin);
        }
		
        if( !isset($NO_ENVIAR) ){
			$data = array(
				'n_solicitud' => $request_id,
				'nombre' => $nombre_cuidador,
				'telefono' => $telf_cuidador,
				'email' => $email_cuidador,
				'error' => ''
			);

			echo json_encode($data);
		}


