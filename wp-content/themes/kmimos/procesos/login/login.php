<?php
	$load = dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/wp-load.php';
	if(file_exists($load)){ include_once($load); }

	extract($_POST);

	$user = get_user_by( 'email', $usu );
    if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status ){
        $usu = $user->user_login;
    }else{
        $usu = sanitize_user($usu, true);
    }
    
	$info = array();
    $info['user_login']     = sanitize_user($usu, true);
    $info['user_password']  = sanitize_text_field($clv);

    $user_signon = wp_signon( $info, true );

	if ( is_wp_error( $user_signon )) {
	  	echo json_encode( 
	  		array( 
	  			'login' => false, 
	  			'mes'   => "Email y contraseña invalidos."
	  		)
	  	);
	} else {
	  	wp_set_auth_cookie($user_signon->ID);
	  	echo json_encode( 
	  		array( 
	  			'login' => true, 
	  			'mes'   => "Login Exitoso!"
	  		)
	  	);
	}

	exit;
?>