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
		wp_set_current_user( $user_signon->ID, $usu );
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

	exit();
?>