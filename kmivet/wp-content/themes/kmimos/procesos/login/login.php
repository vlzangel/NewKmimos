<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
	include($raiz."/wp-load.php");

	extract($_POST);
	date_default_timezone_set('America/Mexico_City');

	global $wpdb;

	if( !isset($_SESSION) ){ session_start(); }
 	
	$user = get_user_by( 'email', $usu );
    if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status ){
        $usu = $user->user_login;
    }else{
        $usu = sanitize_user($usu, true);
    }
   
    $info = array();
    $info['user_login']     = sanitize_user($usu, true);
    $info['user_password']  = sanitize_text_field($clv);
    $info['remember']  = ( $check == 'active' ) ? true : false;

    $user_signon = wp_signon( $info, true );

    $valido = 0;
    $_USER_ID = 0;
    $_INFO_ADICIONAL = '';

	if ( is_wp_error( $user_signon )) {

		$user = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}users WHERE user_login = '".$info['user_login']."' ");
		if( $user !== null ){
			$tipo = get_user_meta($user->ID, "user_type", true);

			switch ( $tipo ) {
				case "veterinario":
					$is_active = get_user_meta($user->ID, "_mediqo_active", true);
					if( $is_active === false ){
						$res = validar_medico([
							"email" => $user->user_email,
							"password" => $info['user_password']
						]);
						$_INFO_ADICIONAL = $res;
						if( $res['status'] == 'ok' ){
							wp_set_current_user( $user->ID, $user->user_login );
							$valido = 1;
						    $_USER_ID = $user->ID;
						    update_user_meta($_USER_ID, '_mediqo_medic_id', $res['id']);
							update_user_meta($_USER_ID, '_mediqo_active', time() );
						}
					}
					
				break;
			}

		}

	} else {
		$valido = 1;
		$_USER_ID = $user_signon->ID;
	}

	if( $valido == 1 ){
		$status_user = get_user_meta($user_signon->ID, 'status_user', true);
		if( $status_user == 'inactivo' ){
		  	$valido = 2;
		}else{
			$valido = 1;
		}
	}


	switch ( $valido ) {

		case 1:
			wp_set_auth_cookie($_USER_ID, $info['remember']);
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
		
		default:
			echo json_encode(array( 
	  			'login' => false, 
	  			'mes'   => "Email y contraseña invalidos.",
	  			"extra" => $_INFO_ADICIONAL
		  	));
		break;

	}

	exit();
?>