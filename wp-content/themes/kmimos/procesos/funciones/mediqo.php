<?php
	function mediqo_request($url, $params){
		// $url = 'https://api.mediqo.mx/'.$url;
		$url = 'https://13.59.244.182/'.$url;
		$ch = curl_init($url);
		$payload = json_encode($params);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

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
?>