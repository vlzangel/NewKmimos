<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
	include($raiz."/wp-load.php");

	extract($_POST);
	date_default_timezone_set('America/Mexico_City');

	global $wpdb;

	if( !isset($_SESSION) ){ session_start(); }

	$user = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}users WHERE user_login = '{$usu}' OR user_email = '{$usu}' ");

	if( $user == null ){
		die(
			json_encode(array( 
	  			'login' => false, 
	  			'mes'   => "Email y contraseña invalidos.",
	  			'user'   => $user
		  	))
		);
	}
 	
	$tipo = get_user_meta($user->ID, 'tipo_usuario', true);

	switch ( strtolower($tipo) ) {
    	case 'administrador':
    	case 'paciente':
    		$info = array();
		    $info['user_login']     = sanitize_user($usu, true);
		    $info['user_password']  = sanitize_text_field($clv);
		    $info['remember']  = ( $check == 'active' ) ? true : false;

		    $user_signon = wp_signon( $info, true );
		    if ( is_wp_error( $user_signon )) {
		    	die(
					json_encode( array( 
			  			'login' => false, 
			  			'mes'   => "Email y contraseña invalidos.",
				  	) )
				);
		    }else{
		    	wp_set_current_user( $user_signon->ID, $usu );
		    	die(
					json_encode( array( 
			  			'login' => true, 
			  			'mes'   => "Login Exitoso!",
				  	) )
				);
		    }
    	break;

    	case 'veterinario':

    		$info = array();
		    $info['user_login']     = sanitize_user($usu, true);
		    $info['user_password']  = sanitize_text_field($clv);
		    $info['remember']  = ( $check == 'active' ) ? true : false;

		    $tipo = get_user_meta($user->ID, '_mediqo_active', true);
			if( $tipo+0 == 0){

		    	// Deberia entrar por aqui solo para la activacion

				$params = [ "email" => $usu, "password" => $clv ];
				$res = validar_medico($params);
				$_INFO_ADICIONAL = $res;
				if( $res['status'] == 'ok' ){
					$tipo = get_user_meta($user->ID, '_mediqo_active', true);
					if( $tipo+0 == 0){
					    update_user_meta($user->ID, '_mediqo_medic_id', $res['id']);
					    update_user_meta($user->ID, '_mediqo_active', time() );
						$wpdb->query("UPDATE {$wpdb->prefix}kmivet_veterinarios SET veterinario_id = '{$res['id']}', status = 1 WHERE user_id = '{$user->ID}'");
					}

					$res = set_location($res['id'], [
						"lat" => $lat,
						"lng" => $lng
					]);

					update_user_meta($user->ID, '_mediqo_medic_clv', $clv);

					$user = get_user_by( 'id', $user->ID ); 
					if( $user ) {
					    wp_set_current_user( $user->ID, $user->user_login );
					    wp_set_auth_cookie( $user->ID );
					}
					die(
						json_encode( array( 
				  			'login' => true, 
				  			'mes'   => "Login Exitoso!",
				  			'user'   => $user,
				  			'extra'   => $res,
					  	) )
					);
				}else{
					$res['status'] = 'ko';
					die(
						json_encode( array( 
				  			'login'  => false, 
				  			'mes'    => "Email o contraseña invalido",
				  			'params' => $params,
				  			'res'    => $res,
					  	) )
					);			
				}
			}

		    $user_signon = wp_signon( $info, true );
		    if ( is_wp_error( $user_signon )) {

		    	// Deberia entrar por aqui siempre el veterinario

		    	$params = [ "email" => $usu, "password" => $clv ];
				$res = validar_medico($params);
				$_INFO_ADICIONAL = $res;
				if( $res['status'] == 'ok' ){
					$tipo = get_user_meta($user->ID, '_mediqo_active', true);
					if( $tipo+0 == 0){
					    update_user_meta($user->ID, '_mediqo_medic_id', $res['id']);
					    update_user_meta($user->ID, '_mediqo_active', time() );
						$wpdb->query("UPDATE {$wpdb->prefix}kmivet_veterinarios SET veterinario_id = '{$res['id']}', status = 1 WHERE user_id = '{$user->ID}'");
					}
					$user = get_user_by( 'id', $user->ID ); 
					if( $user ) {
					    wp_set_current_user( $user->ID, $user->user_login );
					    wp_set_auth_cookie( $user->ID );
					}

					update_user_meta($user->ID, '_mediqo_medic_clv', $clv);

					$res = set_location($res['id'], [
						"lat" => $lat,
						"lng" => $lng
					]);

					die(
						json_encode( array( 
				  			'login' => true, 
				  			'mes'   => "Login Exitoso!",
					  	) )
					);
				}else{
					$res['status'] = 'ko';
					die(
						json_encode( array( 
				  			'login' => false, 
				  			'mes'   => "Email o contraseña invalido",
				  			'params'   => $params,
					  	) )
					);			
				}


		    }else{

		    	$veterinario_id = get_user_meta($user->ID, "_mediqo_medic_id", true);
		    	$res = set_location($veterinario_id, [
					"lat" => $lat,
					"lng" => $lng
				]);

		    	wp_set_current_user( $user_signon->ID, $usu );
		    	die(
					json_encode( array( 
			  			'login' => true, 
			  			'mes'   => "Login Exitoso!",
			  			"extra" => $res
				  	) )
				);
		    }

		    /*
    		$user = get_user_by( 'id', $user->ID ); 
			if( $user ) {
			    wp_set_current_user( $user->ID, $user->user_login );
			    wp_set_auth_cookie( $user->ID );
			}
			die(
				json_encode( array( 
		  			'login' => true, 
		  			'mes'   => "Login Exitoso!",
		  			'user'   => $user
			  	) )
			);
			*/

    	break;
    }

	die(
		json_encode(array( 
  			'login' => false, 
  			'mes'   => "Email y contraseña no encontrados",
  			'user'   => $user,
  			'tipo'   => $tipo,
	  	))
	);

	/*
	$user = get_user_by( 'email', $usu );
    if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status ){
        $usu = $user->user_login;
    }else{
        $usu = sanitize_user($usu, true);
    }

    // $wpdb->query("UPDATE {$wpdb->prefix}users SET user_pass = '".md5($clv)."' WHERE user_email = '{$usu}'");
   
    $info = array();
    $info['user_login']     = sanitize_user($usu, true);
    $info['user_password']  = sanitize_text_field($clv);
    $info['remember']  = ( $check == 'active' ) ? true : false;

    $user_signon = wp_signon( $info, true );

    $valido = 0;
    $_USER_ID = 0;
    $_INFO_ADICIONAL = '';

	if ( is_wp_error( $user_signon )) {

	} else {
		$valido = 1;
		wp_set_current_user( $user_signon->ID, $usu );
	}

	switch ( $valido ) {

		case 1:
			wp_set_auth_cookie($user_signon->ID, $info['remember']);
		  	echo json_encode( array( 
	  			'login' => true, 
	  			'mes'   => "Login Exitoso!",
	  			"extra" => $_INFO_ADICIONAL
	  		));
		break;

		case 2:
			echo json_encode(array( 
	  			'login' => false, 
	  			'mes'   => "Su usuario se encuentra inhabilitado",
	  			"extra" => $_INFO_ADICIONAL
		  	));
		break;

		case 3:
			echo json_encode(array( 
	  			'login' => false, 
	  			'mes'   => "Error conectando al API",
	  			"extra" => $_INFO_ADICIONAL
		  	));
		break;

		case 4:
			echo json_encode(array( 
	  			'login' => false, 
	  			'mes'   => "No encontrado",
	  			"extra" => $_INFO_ADICIONAL
		  	));
		break;
		
		default:
			echo json_encode(array( 
	  			'login' => false, 
	  			'mes'   => "Email y contraseña invalidos.",
	  			"extra" => $_INFO_ADICIONAL
		  	));
		break;

	}
	*/
	exit();
?>