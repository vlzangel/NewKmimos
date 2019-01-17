<?php
	include("../../../../../wp-load.php");
	extract($_POST);

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
    $info['remember']  = ( $check == 'active' ) ? true : false ;

    $user_signon = wp_signon( $info, true );

    $CPF = 0;
	if ( is_wp_error( $user_signon )) {
	  	echo json_encode( 
	  		array( 
	  			'login' => false, 
	  			'mes'   => "Email y contraseña invalidos.",
	  			'CPF'   => $CPF,
	  		)
	  	);
	} else {
		$status_user = get_user_meta($user_signon->ID, 'status_user', true);
		$cupon_cpf = get_user_meta($user_signon->ID, 'club-patitas-cupon', true);
		if( !empty($cupon_cpf) ){
			$CPF = 1;
		}

		if( $status_user == 'inactivo' ){
		  	echo json_encode( 
		  		array( 
		  			'login' => false, 
		  			'mes'   => "Email y contraseña invalidos.",
		  			'CPF' => $CPF,
		  		)
		  	);
		}else{
			$_SESSION["sesion_proceso"] = $_POST["proceso"];
		  	wp_set_auth_cookie($user_signon->ID, $info['remember']);
		  	$user = new WP_User( $user_signon->ID );
		
		  	if( $user->roles[0] == "vendor" ){
		  		tiene_fotos_por_subir($user_signon->ID, true);
		  	}
		  	echo json_encode( 
		  		array( 
		  			'login' => true, 
		  			'mes'   => "Login Exitoso!",
		  			'CPF' => $CPF,
		  		)
		  	);
		}
	}

	exit;
?>