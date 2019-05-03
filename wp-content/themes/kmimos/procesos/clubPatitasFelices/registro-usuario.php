<?php

	include ( '../../../../../wp-load.php' );
	session_start();

	if( isset($_POST["g-recaptcha-response"] ) && !empty($_POST["g-recaptcha-response"]) ){

	// Parametros
		global $wpdb;

		$nombre  = $_POST['nombre'];
		$apellido  = $_POST['apellido'];
		$email = $_POST['email'];
	 	$meta = explode('@', $email);
		$username = $meta[0];
		$clave='';

		// Verificar si existe el email
		$user = get_user_by( 'email', $email );	

		$mail_seccion_usuario ='';

		$URL_SITE = get_home_url();
		//$URL_SITE = 'http://kmimosmx.sytes.net/QA2/';

	 	// Registro de Usuario en Kmimos
		if(!isset($user->ID)){
	        //$clave = wp_generate_password( 5, false );
			$clave = 'Kmi'.date('mi');
			/*
		    $password = md5($clave);
		    $user_id  = wp_create_user( $username, $password, $email );
		    wp_update_user( array( 'ID' => $user_id, 'display_name' => "{$nombre}" ));		
			*/
	        $hoy = date("Y-m-d H:i:s");

	        $new_user = "
	            INSERT INTO wp_users VALUES (
	                NULL,
	                '".$email."',
	                '".md5($clave)."',
	                '".$email."',
	                '".$email."',
	                '',
	                '".$hoy."',
	                '',
	                0,
	                '".$nombre." ".$apellido."'
	            );
	        ";
	        $wpdb->query( utf8_decode( $new_user ) );
			$user = get_user_by( 'email', $email );	
			if( isset($user->ID) && $user->ID > 0 ){
				$user_id = $user->ID;
				// Registrado desde el landing page
				update_user_meta( $user_id, 'first_name', $nombre );
				update_user_meta( $user_id, 'last_name', $apellido );
				update_user_meta( $user_id, 'user_referred', 'Amigo/Familiar' );
				update_user_meta( $user_id, 'user_mobile', '' );
				update_user_meta( $user_id, "landing-club-patitas", date('Y-m-d H:i:s') ); 		

			    $user = new WP_User( $user_id );
			    $user->set_role( 'subscriber' );

			    //MESSAGE
		        $mail_file = realpath('../../template/mail/clubPatitas/parte/nuevo_usuario.php');
		        $mail_seccion_usuario = file_get_contents($mail_file);

		        //USER LOGIN
		        if (!isset($_SESSION)) { session_start(); }
		        $user = get_user_by( 'id', $user_id );
		        wp_set_current_user($user_id, $user->user_login);
		        wp_set_auth_cookie($user_id);

		        // REGISTRO EN REFERIDOS
				$r = $wpdb->get_row( "SELECT * FROM list_subscribe WHERE source = 'CPF' and email = '".$email."'" );
				if( !isset($r->email) ){
					# Insertar registro
					$sql = "INSERT INTO list_subscribe( source, email, phone) VALUES ( 'CPF', '{$email}', '' ) ";
					$wpdb->query( $sql );
				}

			}
		}else{

		}

	 	// Registro de Usuario en Club de patitas felices
		$sts = 1;
		$msg ='';
	 	$cupon = get_user_meta( $user->ID, 'club-patitas-cupon', true );
	 	if( empty($cupon) || $cupon == null ){
			// generar cupon
			if( $user->ID > 0 ){
				$cupon = substr(trim($nombre), 0,1);
				$cupon .= substr(trim($apellido), 0,1);
				$cupon .= $user->ID;
				$cupon = strtoupper($cupon);
				$id = kmimos_crear_cupon( $cupon, 150 ); 		
				if( $id > 0 ){
					update_user_meta( $user->ID, 'club-patitas-cupon', utf8_encode($cupon) );

				    //MESSAGE
			        $mail_file = realpath('../../template/mail/clubPatitas/nuevo_miembro.php');

			        $message_mail = file_get_contents($mail_file);

			        $message_mail = str_replace('[NUEVOS_USUARIOS]', $mail_seccion_usuario, $message_mail);
			        $message_mail = str_replace('[IMG_URL]', $URL_SITE."/wp-content/themes/kmimos/images", $message_mail);

			        $message_mail = str_replace('[name]', $nombre.' '.$apellido, $message_mail);
			        $message_mail = str_replace('[email]', $email, $message_mail);
			        $message_mail = str_replace('[pass]', $clave, $message_mail);
			        $message_mail = str_replace('[url]', site_url(), $message_mail);
			        $message_mail = str_replace('[CUPON]', $cupon, $message_mail);

			        wp_mail( $email, "¡Bienvenid@ al club!", $message_mail);
			        wp_mail( 'italococchini@gmail.com', "¡Bienvenid@ al club!", $message_mail);

			        $_SESSION['CPF'] = "OK";
				}
			}
	 	}else{
	 		$sts = 0;
	 		$msg ='Ya eres miembro del Club, debes iniciar sesion para ver tus creditos';
	        $_SESSION['CPF'] = "OK";
	 	}
	}else{
		$sts = 0;
		$msg="Debes validar que no eres un robot";
	}
	
 	if( isset($_POST['redirect']) && $_POST['redirect'] == 1 ){
 		$url = get_home_url().'/club-patitas-felices';
	 	if( $sts == 1 ){
	 		$url .= '/compartir';
	 	}
	 	header('location:'.$url);
 	}else{
		echo json_encode(['sts'=>$sts,'msg'=>$msg]);
 	}
