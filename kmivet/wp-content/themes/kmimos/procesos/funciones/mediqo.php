<?php
	function mediqo_request($url, $params, $type = 'POST'){
		$url = 'https://api.mediqo.mx/'.$url;
		// $url = '13.59.244.182/'.$url;
		
		if( $type == 'POST' ){
			$ch = curl_init($url);
			$payload = json_encode($params);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
		}else{
			$result = file_get_contents($url);
		}

		return $result;
	}

	/* Pacientes */

	function get_mediqo_customer($user_id){
		$customer = '';
		$data_cliente = get_data_user($user_id);
		if( $data_cliente['_mediqo_customer_id'] == '' ){
			$password = substr(md5(time()), 0, 8);
			$resultado = mediqo_request('patients/', [
				'network' => 3,
				'socialId' => $data_cliente['email'],
				'firstName' => $data_cliente[ 'first_name' ],
				'lastName' => $data_cliente[ 'last_name' ],
				'email' => $data_cliente['email'],
				'phone' => '5551234567',
				'birthday' => date("Y-m-d", strtotime("- 25 year")),
				'password' => $password
			]);
			$resultado = json_decode($resultado);
			$mediqo_id = $resultado->object->id;
			if( $mediqo_id != null ){
				update_user_meta($user_id, "_mediqo_customer_id", $mediqo_id);
			}else{
			    return [
					'status' => 'ko',
					'info' => $resultado,
					'res' => $data_cliente,
					'user_id' => $user_id
				];
			}
		}else{
			$mediqo_id = $data_cliente['_mediqo_customer_id'];
		}
		
	    return [
			'status' => 'ok',
			'id'  => $mediqo_id,
			'res' => $data_cliente
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
			update_user_meta($user_id, "_mediqo_error_creando_cliente", json_encode([
				'info' => $resultado,
				'res' => $data_cliente
			]));
			return false;
		}
	}

	/* Consultas */

	function add_appointments($params){
		$resultado = mediqo_request('appointments/', $params);
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

	/* Medicos */

	function create_medic($params){
		$resultado = mediqo_request('registration/api/medic_registration', $params);
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
		$resultado = mediqo_request('medics/'.$mediqo_id, []);
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
?>