<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
	ob_start();
		include_once($raiz."/wp-load.php");
		$load = ob_get_contents();
	ob_end_clean();
	
	global $wpdb;
	extract($_POST);

	$r = new_veterinario($_POST, [] );

	if( !$r['status'] ){
		die( json_encode([
			"status" => false,
			"error" => $r['error'],
		]) );
	}

	$user_id = $r['user_id'];

	extract($r);

	/* Mediqo */

		$kv_estado = $wpdb->get_var("SELECT name FROM states WHERE id = ".$kv_estado);
		$kv_delegacion = $wpdb->get_var("SELECT name FROM locations WHERE id = ".$kv_delegacion);
		$kv_colonia = $wpdb->get_var("SELECT name FROM colonias WHERE id = ".$kv_colonia);

		$param = [
			'name' => $kv_nombre,
	        'email' => $kv_email,
			'birthdate' => $kv_fecha,
			'gender' => $kv_genero,
			'documentId' => $kv_dni,
			'referenceCode' => $kv_referencia,
			'street' => $kv_calle,
			'interior' => $kv_interior,
			'colony' => $kv_colonia,
			'municipality' => $kv_delegacion,
			'addressState' => $kv_estado,
			'cp' => $kv_postal,
			'landline' => $kv_telf_fijo,
			'mobile' => $kv_telf_movil,
			'degree' => $kv_titulo,
			'licenseNumber' => $kv_cedula,
			'university' => $kv_universidad,
			'internship' => $kv_internado,
			'socialService' => $kv_servicio_social,
			'extraCurricular' => $kv_cursos_realizados,
			'otherStudies' => $kv_otros_estudios,
			'workBio' => $kv_trabajos,
			'hasInsuranceNetwork' => ( $kv_red_seguro == 'Si' ),
			'insuranceNetwork' => $kv_red_seguros,
			'hasSpecialty' => ( $kv_tiene_otra_especialidad == 'Si' ),
			'specialtyName' => $kv_red_otra_especialidad,
			'specialtyLicense' => $kv_red_otra_cedula,
			'specialtyUniversity' => $kv_red_otra_universidad,
			'hasCar' => ( $kv_tiene_auto == 'Si' ),
			'hasDriverLicense' => ( $kv_tiene_licencia == 'Si' ),
			'hasScheduleDisponibility' => ( $kv_tiene_disponibilidad == 'Si' ),
			'hasInsurance' => ( $kv_seguro_responsabilidad == 'Si' ),
			'insuranceCompany' => $kv_seguro_empresa,
			'insuranceNumber' => $kv_no_poliza,
			'firstReferenceName' => $kv_primera_ref_nombre,
			'firstReferencePhone' => $kv_primera_ref_telefono,
			'firstReferenceMail' => $kv_primera_ref_email,
			'rfc' => $kv_rfc,
			'languages' => $kv_idiomas
		];

		if( $kv_segunda_ref_nombre != '' ){
			$param['secondReferenceName'] = $kv_segunda_ref_nombre;
			$param['secondReferencePhone'] = $kv_segunda_ref_telefono;
			$param['secondReferenceMail'] = $kv_segunda_ref_email;
		}

		if( $kv_tercera_ref_nombre != '' ){
			$param['thirdReferenceName'] = $kv_tercera_ref_nombre;
			$param['thirdReferencePhone'] = $kv_tercera_ref_telefono;
			$param['thirdReferenceMail'] = $kv_tercera_ref_email;
		}

		
		$res = create_medic($param);
		if( $res['status'] == 'ok' ){
			update_user_meta($user_id, '_mediqo_resp', 'ok');
			update_user_meta($user_id, '_mediqo_medic_id', $res['id']);
		}else{
			update_user_meta($user_id, '_mediqo_resp', 'ko');
			update_user_meta($user_id, '_mediqo_medic_id', 'No creado');
		}
		

	/* Mediqo */

    $mensaje = kv_get_email_html(
        'KMIVET/veterinario/nuevo', 
        [
            "KV_URL_IMGS" => getTema().'/KMIVET/img',
            "URL"         => get_home_url(),
            "NAME"        => $kv_nombre,
            "EMAIL"       => $kv_email,
            "PASS"        => $r['random_password'],
        ]
    );

    $header = [
    	'BCC: a.veloz@kmimos.la',
    	'BCC: y.chaudary@kmimos.la',
    ];

    wp_mail($kv_email, 'Kmivet - Gracias por registrarte como veterinario!', $mensaje, $header);

	echo json_encode([
		"status" => true,
		"usuario" => $user_id,
	]);
?>