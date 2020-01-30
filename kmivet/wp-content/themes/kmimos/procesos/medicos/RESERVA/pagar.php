<?php
	session_start();

	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
	include_once($raiz."/wp-load.php");
	date_default_timezone_set('America/Mexico_City');
	
	if( !isset($_SESSION)){ session_start(); }

	include_once(dirname(dirname(__DIR__))."/funciones/openpay.php");
	include_once(dirname(dirname(__DIR__))."/funciones/mediqo.php");

	// ini_set('display_errors', 'On');
	// error_reporting(E_ALL);

	global $wpdb;
	extract($_POST);

	$paciente = get_mediqo_customer($user_id); // Obtenemos el customer_id de mediqo
	if( $paciente['status'] == 'ko' ){
		die(json_encode([
    		"error" => true,
    		"msg"  => 'No podemos obtener acceso a su cuenta de Mediqo, por favor contacte al equipo de soporte de Kmivet para brindarle la asistencia correspondiente.',
    		"res"  => $paciente
    	]));
	}else{
		$paciente_id = $paciente['id'];
	}

	/*
		{
			"payment": {
				"number": "4111111111111111",
				"firstName": "A",
				"lastName": "J",
				"token": "tok_2mRhVv96mLWiUp3cY"
			},
			"medic": "4794a05285f74bd0980b88243782ab5d",
			"patient": "9ab288447ce846359a56f8300559b3d0",
			"specialty": "4076b2429a17427692085abb38c6ff1d",
			"dueTo": "2019-10-17 18:30",
			"lat": 19.449481,
			"lng": -99.165957,
			"extraPatient": "Extra patient",
			"extraPatientAge": 22,
			"extraPatientGender": 1
		}
	*/

	$veterinario_id = 

	$params = [
		"payment" => [
			"number" => "4111111111111111",
			"firstName" => "A",
			"lastName" => "J",
			"token" => $cita_token
		],
		'medic' => $medico_id,
		'patient' => $paciente_id,
		'specialty' => $specialty_id,
		'dueTo' => $cita_fecha,
		/*'paymentType' => 0,
		'appointmentType' => 1,
		'isCash' => true,*/
		'address' => $cita_direccion,
	];
	if( $cita_latitud != '' ){
		$params['lat'] = $cita_latitud;
		$params['lng'] = $cita_longitud;
	}

	// Creamos consulta en mediqo //
	$appointment = add_appointments($params);
	$data = json_encode([ $params, $appointment ]);

	if( $appointment['status'] == 'ko' ){
		die(json_encode([
    		"error" => true,
    		"msg"  => $appointment['info']->message,
    		"info"  => $appointment,
    		"params"  => $params,
    	]));
	}else{

		$_POST['appointment_id'] = $appointment['id'];
		$_POST['paciente_id'] = $appointment['id'];

		$cita = new_cita($_POST); // Creación de cita en status pendiente
		if( !$cita['status'] ){
			die(json_encode([
	    		"code" => 0,
	    		"msg"  => 'No se pudo crear el registro de la reserva',
	    		"info" => $cita['info']
	    	]));
		}
		$cita_id = $cita['id'];
		
		$_infos  = $_SESSION['medicos_info'];
		$_medico = [];
		foreach ($_infos[ $medico_id ] as $key => $value) { $_medico[ $key ] = $value; }
		$cliente = get_user_meta($user_id, 'first_name', true).' '.get_user_meta($user_id, 'last_name', true);
		$cliente_email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$user_id);
		$telefono_cliente = get_user_meta($user_id, 'user_mobile', true).' / '.get_user_meta($user_id, 'user_phone', true);
	    
    	$fecha_cita = date("d/m", strtotime($cita_fecha));
    	$hora_cita  = date("h:ia", strtotime($cita_fecha));

	    $INFORMACION = [
        	"KV_URL_IMGS" 		 => getTema().'/KMIVET/img',
        	"URL" 				 => get_home_url(),
        	"URL_CANCELAR"		 => get_home_url().'/citas/cancelar/'.$cita_id,
        	"NAME_VETERINARIO" 	 	=> $_medico['firstName'].' '.$_medico['lastName'],
        	"TELEFONOS_VETERINARIO" => $_medico['phone'],
        	"CORREO_VETERINARIO" 	=> $_medico['email'],
        	"NAME_CLIENTE" 		 => $cliente,
        	"TELEFONOS_CLIENTE"  => $telefono_cliente,
        	"CORREO_CLIENTE" 	 => $cliente_email,
        	"CONSULTA_ID" 		 => $cita_id,
        	"TIPO_SERVICIO" 	 => 'CONSULTA A DOMICILIO',
        	"FECHA" 	 		=> $fecha_cita,
        	"HORA" 	 			=> $hora_cita,
        	"TIPO_PAGO" 		=> 'Pago por Tarjeta',
        	"TOTAL" 			=> number_format($cita_precio, 2, ',', '.'),
        	"LAT_MEDIC" 		=> $_medico["lat"],
        	"LNG_MEDIC" 		=> $_medico["lng"],
        	"SPE_MEDIC" 		=> $specialty_id
        ];

        $info_email = json_encode($INFORMACION, JSON_UNESCAPED_UNICODE);

        $wpdb->query("UPDATE wp_kmivet_reservas SET info_email = '{$info_email}' WHERE id = '{$cita_id}' ");

		/*
	    // EMAIL al CLIENTE //
	    	$mensaje = kv_get_email_html(
		        'KMIVET/reservas/nueva_cliente', 
		        $INFORMACION
		    );
	        wp_mail($cliente_email, 'Kmivet - Nueva Solicitud de Consulta', $mensaje);


	    // EMAIL al CUIDADOR //
	    	$mensaje = kv_get_email_html(
		        'KMIVET/reservas/nueva_veterinario', 
		        $INFORMACION
		    );
	        wp_mail($_medico['email'], 'Kmivet - Nueva Solicitud de Consulta', $mensaje);

	    // EMAIL al ADMINISTRADOR //
	    	$mensaje = kv_get_email_html(
		        'KMIVET/reservas/nueva_admin', 
		        $INFORMACION
		    );
		    $header = kv_get_emails_admin();
	        wp_mail('soporte.kmimos@gmail.com', 'Kmivet - Nueva Solicitud de Consulta', $mensaje, $header);
	    */

	    die(json_encode([
			"msg" => "Pago realizado exitosamente",
			"cid" => $cita_id,
			"error" => false,
		]));

	}

	die( json_encode([
		"error" => 6,
		"msg"  => 'Error inesperado'
	]));
?>