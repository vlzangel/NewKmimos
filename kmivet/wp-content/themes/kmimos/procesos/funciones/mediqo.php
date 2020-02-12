<?php
	function mediqo_request($url, $params, $type = 'POST'){
		// $url = 'https://api.mediqo.mx/'.$url;
		// $url = 'http://3.86.249.47/'.$url;
		$url = 'http://pruebas.kmimos.com.mx/'.$url;

		switch ( $type ) {
			case 'GET':
				$result = file_get_contents($url);
			break;

			case 'POST':
				$ch = curl_init($url);
				$payload = json_encode($params);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				curl_close($ch);
			break;

			case 'PUT':
				$ch = curl_init($url);
				$payload = json_encode($params);
				// curl_setopt($ch, CURLOPT_PUT, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPGET, FALSE);
				$result = curl_exec($ch);
				curl_close($ch);
			break;
			
			default:
				return false;
			break;
		}

		return $result;
	}

	/* Auth */

	function send_token_paciente($paciente_id, $token){
		$r = mediqo_request('patients/'.$paciente_id.'/notification_token', ['notificationToken' => $token], 'PUT');
		$r = json_decode($r);
		if( $r->status == 'OK' ){
			return ['status' => 'ok', "r" => $r];
		}
	    return ['status' => 'ko', "r" => $r];
	}

	function send_token_veterinario($paciente_id, $token){
		$r = mediqo_request('medics/'.$paciente_id.'/notification_token', ['notificationToken' => $token], 'PUT');
		$r = json_decode($r);
		if( $r->status == 'OK' ){
			return ['status' => 'ok'];
		}
	    return ['status' => 'ko'];
	}


	/* Pacientes */

	function get_mediqo_customer($user_id){
		$customer = '';
		$_mediqo_customer_id = get_user_meta($user_id, '_mediqo_customer_id', true);
		if( $_mediqo_customer_id == '' ){
			return [
				'status' => 'ko'
			];
		}
	    return [
			'status' => 'ok',
			'id'  => $_mediqo_customer_id
		];
	}

	function validar_paciente($user_id, $email){
		$params['network'] = 3;
		$params['socialId'] = $email;
		$resultado = mediqo_request('patients/look_up', $params);
		$resultado = json_decode($resultado);
		if( $resultado->status == 'FAIL' ){
			return false;
		}else{
			update_user_meta($user_id, "_mediqo_customer_id", $resultado->object->id);
			return $resultado->object->id;
		}
	}

	function crear_paciente($user_id, $params){
		$params['network'] = 3;
		$params['socialId'] = $params['email'];
		$params['phone'] = ( $params['phone'] == '' ) ? '5551234567' : $params['phone'];
		$params['birthday'] = ( $params['birthday'] == '' ) ? date("Y-m-d", strtotime("- 25 year")) : $params['birthday'];
		$resultado = mediqo_request('patients/', $params);
		$resultado = json_decode($resultado);
		$mediqo_id = $resultado->object->id;
		if( $mediqo_id != null ){
			update_user_meta($user_id, "_mediqo_customer_id", $mediqo_id);
			return true;
		}else{
			if( !validar_paciente($user_id, $params['email']) ) {
				return true;
			}
			update_user_meta($user_id, "_mediqo_error_creando_cliente", json_encode([
				'info' => $resultado,
				'res' => $data_cliente
			]));
			return false;
		}
	}

	/* Consultas */

	// Parametros para crear consulta en mediqo //
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

	function add_appointments($params){
		$_resultado = mediqo_request('appointments/', $params);
		$resultado = json_decode($_resultado);
		$cita_id = $resultado->object->id;
		if( $cita_id == null ){
		    return [
				'status' => 'ko',
				'info' => $resultado,
				'resultado' => $_resultado,
			];
		}
	    return [
			'status' => 'ok',
			'id' => $cita_id
		];
	}

	function get_appointment($appointment_id){
		$resultado = mediqo_request('appointments/'.$appointment_id.'/full', [], 'GET');
	    return [
	    	"result" => json_decode($resultado)->object,
	    	"d" => $resultado,
	    	"u" => 'appointments/'.$appointment_id.'/full'
	    ];
	}

	function change_status($id, $params){
		$params['source'] = 1;
		$resultado = mediqo_request('appointments/'.$id.'/status', $params);
		$resultado = json_decode($resultado);
		$cita_id = $resultado->object->id;
		if( $cita_id == null ){
		    return [
				'status' => 'ko',
				'info' => $resultado
			];
		}
	    return [
			'status' => 'ok',
			'id' => $cita_id
		];
	}

	function calificar_veterinario($veterinario_id, $params){
		$params['source'] = 1;
		$resultado = mediqo_request('medics/'.$veterinario_id.'/rate', $params);
		$resultado = json_decode($resultado);
		if( $resultado->status != 'OK' ){
		    return [
				'status' => 'ko',
				'info' => $resultado
			];
		}
	    return [
			'status' => 'ok'
		];
	}

	function get_answers($id){
		$params['source'] = 1;
		$resultado = mediqo_request('appointments/'.$id.'/answers', [], 'GET');
		$resultado = json_decode($resultado);
	    return $resultado->objects;
	}

	function search_medicine($query){
		$params['source'] = 1;
		$resultado = mediqo_request('prescriptions/?q='.$query, [], 'GET');
		$resultado = json_decode($resultado);
	    return $resultado->objects;
	}

	function add_medicine($appointment_id, $medicine_id, $indications){
		$resultado = mediqo_request('appointments/'.$appointment_id.'/prescriptions', [
			'medicines' => [
				[
					"id" => $medicine_id,
					"indication" =>  $indications
				]
			],
			'appointment' => $appointment_id
		]);
	    return [
	    	"r" => json_decode($resultado),
	    	"p" => [
	    		'url' => 'appointments/'.$appointment_id.'/prescriptions',
	    		'method' => 'PUT',
	    		'params' => [
					'medicines' => [
						[
							"id" => $medicine_id,
							"indication" =>  $indications
						]
					],
					'appointment' => $appointment_id
				]
	    	]
	    ];
	}

	function add_tratamiento($appointment_id, $tratamiento){
		$resultado = mediqo_request('appointments/'.$appointment_id.'/treatment', [
			'treatment' => $tratamiento
		]);
	    return [
	    	"r" => json_decode($resultado),
	    	"p" => [
	    		'url' => 'appointments/'.$appointment_id.'/prescriptions',
	    		'method' => 'PUT',
	    		'params' => [
					'medicines' => [
						[
							"id" => $medicine_id,
							"indication" =>  $indications
						]
					],
					'appointment' => $appointment_id
				]
	    	]
	    ];
	}

	function get_medicines($appointment_id){
		$resultado = mediqo_request('appointments/'.$appointment_id.'/prescriptions', [], 'GET');
	    return [
	    	"r" => json_decode($resultado)->objects
	    ];
	}

	function put_answers($id, $params){
		$params['source'] = 1;
		$resultado = mediqo_request('appointments/'.$id.'/answers', $params);
		$resultado = json_decode($resultado);
	    return $resultado->objects;
	}

	function get_list_diagnostic($id, $level){
		$resultado = mediqo_request('diagnostics?level='.$level.'&parent='.$id, [], 'GET');
	    return [
	    	"result" => json_decode($resultado)->objects,
	    	"x" => $resultado,
	    	"u" => 'diagnostics?level='.$level.'&id='.$id,
	    ];
	}

	function update_diagnostics($id, $params){
		$params['source'] = 1;
		$resultado = mediqo_request('appointments/'.$id.'/diagnostics', $params);
		$resultado = json_decode($resultado);
	    return $resultado;
	}

	/* Medicos */

	function create_medic($params){
		$resultado = mediqo_request('registration/api/medic_registration', $params);

		add_user_meta(367, 'registro_vet', $resultado);

		$resultado = json_decode($resultado);
		$id = $resultado->object->id;
		if( $resultado->status != 'OK' ){
		    return [
				'status' => 'ko',
				'info' => $resultado,
			];
		}
	    return [
			'status' => 'ok',
			'id' => $id,
			'res' => $resultado,
		];
	}

	function validar_medico($params){
		$resultado = mediqo_request('medics/validate', $params);
		$resultado = json_decode($resultado);
		$id = $resultado->object->id;
		if( $resultado->status != 'OK' ){
		    return [
				'status' => 'ko',
				'info' => $resultado,
			];
		}
	    return [
			'status' => 'ok',
			'id' => $id,
			'res' => $resultado,
		];
	}

	function get_medic($mediqo_id){
		$resultado = mediqo_request('medics/'.$mediqo_id, [], 'GET');
		$resultado = json_decode($resultado);
		$id = $resultado->object->id;
		if( $resultado->status != 'OK' ){
		    return [
				'status' => 'ko',
				'info' => $resultado,
			];
		}
	    return [
			'status' => 'ok',
			'id' => $id,
			'res' => $resultado->object
		];
	}

	function set_location($mediqo_id, $params){
		$resultado = mediqo_request('medics/'.$mediqo_id, $params, 'PUT');
		$resultado = json_decode($resultado);
		$id = $resultado->object->id;
		if( $resultado->status != 'OK' ){
		    return [
				'status' => 'ko',
				'info' => $resultado,
			];
		}
	    return [
			'status' => 'ok',
			'id' => $id,
			'res' => $resultado,
		];
	}

	function get_medics($specialty, $lat, $lng){
		$resultado = mediqo_request('medics/?specialty='.$specialty.'&lat='.$lat.'&lng='.$lng, [], 'GET');
		// echo $resultado;
		$resultado = json_decode($resultado);
		$id = $resultado->object->id;
		if( $resultado->status != 'OK' ){
		    return [
				'status' => 'ko',
				'info' => $resultado,
			];
		}
	    return [
			'status' => 'ok',
			'id' => $id,
			'res' => $resultado,
		];
	}

	/* Especialidades */

	function get_specialties(){
		$resultado = mediqo_request('medics/specialty/', [], 'GET');
		return json_decode($resultado);
	}

?>