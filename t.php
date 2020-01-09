<?php
	include 'wp-load.php';

	include dirname(__FILE__).'/wp-content/themes/kmimos/procesos/funciones/mediqo.php';

	ini_set('display_errors', 'On');
	error_reporting(E_ALL);

	global $wpdb;

	$info = (array) json_decode('{"kv_nombre":"Mariela García","kv_email":"Mary.garciag@gmail.com","kv_email_no_usado":"on","kv_fecha":"2018-05-23","kv_genero":"1","kv_dni":"12345678","kv_rfc":"44566","kv_referencia":"543267","kv_referido":"Twitter","kv_calle":"5 de mayo","kv_interior":"Interior","kv_estado":"Jalisco","kv_delegacion":"Zapopan","kv_colonia":"1 de Mayo","kv_postal":"369852","kv_telf_fijo":"7874323","kv_telf_movil":"45346765","kv_titulo":"Titulo","kv_cedula":"123727373","kv_universidad":"UDM","kv_internado":"Internado","kv_servicio_social":"Servicio","kv_cursos_realizados":"Cursos","kv_otros_estudios":"Otros estudios","kv_trabajos":"Trabajos","kv_idiomas":"Idiomas","kv_red_seguro":"No","kv_red_seguros":"","kv_tiene_otra_especialidad":"No","kv_red_otra_especialidad":"","kv_red_otra_cedula":"","kv_red_otra_universidad":"","kv_tiene_auto":"Si","kv_tiene_licencia":"Si","kv_tiene_disponibilidad":"Si","kv_seguro_responsabilidad":"No","kv_seguro_empresa":"","kv_no_poliza":"","kv_primera_ref_nombre":"Pedro Pérez","kv_primera_ref_telefono":"567373836","kv_primera_ref_email":"Pedro.perez27273@gmail.com","kv_segunda_ref_nombre":"","kv_segunda_ref_telefono":"","kv_segunda_ref_email":"","kv_tercera_ref_nombre":"","kv_tercera_ref_telefono":"","kv_tercera_ref_email":"","kv_terminos":"on"}');

	extract( $info );

	$param = [
		'name' => $kv_nombre,
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

	/*
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
	*/

	/*
	if( $res['status'] == 'ok' ){
		update_user_meta($user_id, '_mediqo_medic_id', $res['id']);
	}else{
		update_user_meta($user_id, '_mediqo_medic_id', 'No creado');
	}
	*/

	echo "<pre>";
		print_r( $param );
	echo "</pre>";
?>