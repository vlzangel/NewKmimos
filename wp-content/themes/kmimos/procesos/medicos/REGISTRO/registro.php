<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
	ob_start();
		include_once($raiz."/wp-load.php");
		$load = ob_get_contents();
	ob_end_clean();
	
	global $wpdb;
	extract($_POST);

	$wpdb->query("INSERT INTO wp_kmivet_medicos VALUES(
		NULL,
		'{$kv_email}',
		'{$kv_dni}',
		NOW()
	)");

	$medico_id = $wpdb->insert_id;

	$data = json_encode($_POST, JSON_UNESCAPED_UNICODE);

	$wpdb->query("INSERT INTO wp_kmivet_data_medicos VALUES(
		NULL,
		'{$medico_id}',
		'{$data}'
	)");

	$user_id = username_exists( $kv_email );
	if ( ! $user_id && false == email_exists( $kv_email ) ) {
	    $random_password = wp_generate_password( $length = 5, $include_standard_special_chars = false );
	    $user_id = wp_create_user( $kv_email, $random_password, $kv_email );

	    update_user_meta($user_id, 'first_name', $kv_nombre);
	    update_user_meta($user_id, 'user_mobile', $kv_telf_movil);
	    update_user_meta($user_id, 'user_phone', $kv_telf_fijo);
	    update_user_meta($user_id, 'clave_temp', $random_password);
	    update_user_meta($user_id, 'user_referred', 'kmivet');

	    $info = array();
	    $info['user_login']     = sanitize_user($kv_email, true);
	    $info['user_password']  = sanitize_text_field($random_password);
	    $info['remember']  		= true;
	    $user_signon = wp_signon( $info, true );
	    wp_set_auth_cookie($user_signon->ID, true);

	    $usuario = 'si';
	} else {
		$random_password = "La misma clave de tu usuario de kmimos.";
	    $usuario = 'no';
	}

	$mensaje = kv_get_email_html('KMIVET/registro/nuevo', 
        [
        	"KV_URL_IMGS"   => getTema().'/KMIVET/img',
        	"EMAIL"   		=> $kv_email,
        	"NOMBRE"  		=> $kv_nombre,
        	"CLAVE"   		=> $random_password,
        	"URL" 	  		=> get_home_url().'/kmivet/',
        ]
    );

    $header = [
    	'BCC: a.veloz@kmimos.la',
    	'BCC: y.chaudary@kmimos.la',
    ];

    wp_mail($kv_email, 'Kmivet - Gracias por registrarte como veterinario!', $mensaje, $header);

	echo json_encode([
		"status" => true,
		"usuario" => $usuario,
	]);
?>