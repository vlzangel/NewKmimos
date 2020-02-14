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
    		"msg"  => 'No podemos obtener acceso a su cuenta de Kmivet, por favor contacte al equipo de soporte de Kmivet para brindarle la asistencia correspondiente.',
    		"res"  => $paciente
    	]));
	}else{
		$paciente_id = $paciente['id'];
	}

	$params = [
		"payment" => [
			"number" => $cita_tarjeta,
			"firstName" => $cita_nombre,
			"lastName" => $cita_nombre,
			"token" => $cita_token
		],
		'medic' => $medico_id,
		'patient' => $paciente_id,
		'specialty' => $specialty_id,
		'dueTo' => $cita_fecha,
		'address' => $cita_direccion,
		'isCash' => false,
		"extraPatient" => "Extra patient",
		"extraPatientAge" => 22,
		"extraPatientGender" => 1,
		"lat" => 20.667033,
		"lng" => -103.335986
	];

	// Creamos consulta en mediqo //
	/*
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
		*/

		$cita = new_cita($_POST); // Creación de cita en status pendiente
		if( !$cita['status'] ){
			die(json_encode([
	    		"code" => 0,
	    		"msg"  => 'No se pudo crear el registro de la reserva',
	    		"info" => $cita['info']
	    	]));
		}
		$cita_id = $cita['id'];
		

		$veterinario = get_medic($medico_id);

    	$fecha_cita = date("d/m", strtotime($cita_fecha));
    	$hora_cita  = date("h:ia", strtotime($cita_fecha));

	    $INFORMACION = [
        	"KV_URL_IMGS" 		 => getTema().'/KMIVET/img',
        	"URL" 				 => get_home_url(),
        	"URL_CANCELAR"		 => get_home_url().'/citas/cancelar/'.$cita_id,
        	"NAME_VETERINARIO" 	 	=> $veterinario->firstName.' '.$veterinario->lastName,
        	"TELEFONOS_VETERINARIO" => $veterinario->phone,
        	"CORREO_VETERINARIO" 	=> $veterinario->emai,
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
            $admins = get_admins();
            wp_mail($admins['admin'], 'Kmivet - Nueva Solicitud de Consulta', $mensaje, $admins['otros']);

	    die(json_encode([
			"msg" => "Pago realizado exitosamente",
			"cid" => $cita_id,
			"error" => false,
		]));

	// }

	die( json_encode([
		"error" => 6,
		"msg"  => 'Error inesperado'
	]));
?>