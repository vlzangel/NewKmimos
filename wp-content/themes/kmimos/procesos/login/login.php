<?php
	include("../../../../../wp-load.php");
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
    $info['remember']  = ( $check == 'active' )? true : false ;

    $user_signon = wp_signon( $info, true );

	if ( is_wp_error( $user_signon )) {
	  	echo json_encode( 
	  		array( 
	  			'login' => false, 
	  			'mes'   => "Email y contraseña invalidos."
	  		)
	  	);
	} else {
	  	wp_set_auth_cookie($user_signon->ID, $info['remember']);

	  	$user = new WP_User( $user_signon->ID );
	
	  	if( $user->roles[0] == "vendor" ){

	  		global $wpdb;

	  		if( !isset($_SESSION) ){ session_start(); }

	  		$sql = "
				SELECT 
					count(*)
				FROM 
					wp_posts AS posts
				LEFT JOIN wp_postmeta AS metas_reserva ON ( posts.ID = metas_reserva.post_id AND metas_reserva.meta_key='_booking_product_id' )
				LEFT JOIN wp_postmeta AS inicio ON ( posts.ID = inicio.post_id AND inicio.meta_key='_booking_start' )
				LEFT JOIN wp_postmeta AS fin ON ( posts.ID = fin.post_id AND fin.meta_key='_booking_end' )
				LEFT JOIN wp_posts AS producto ON ( producto.ID = metas_reserva.meta_value )
				LEFT JOIN wp_posts AS orden ON ( orden.ID = posts.post_parent )
				WHERE 
					posts.post_type      = 'wc_booking' AND 
					posts.post_status    = 'confirmed'  AND
					(
						fin.meta_value       > NOW()  AND
						inicio.meta_value    <= NOW()
					) AND
					producto.post_author = '{$user_signon->ID}'
				ORDER BY posts.ID DESC
			";

			$reservas_activas = $wpdb->get_var($sql);

			if( $reservas_activas+0 > 0 ){
				$_SESSION["recordar_subir_fotos"] = true;
			}

	  	}

	  	echo json_encode( 
	  		array( 
	  			'login' => true, 
	  			'mes'   => "Login Exitoso!"
	  		)
	  	);
	}

	exit;
?>