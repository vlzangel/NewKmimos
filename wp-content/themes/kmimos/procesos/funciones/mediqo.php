<?php
	function mediqo_request($url, $params){
		$url = 'https://api.mediqo.mx/'.$url;
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
					'info' => $resultado->message
				];
			}
		}else{
			$mediqo_id = $data_cliente['_mediqo_customer_id'];
		}
	    return [
			'status' => 'ok',
			'id' => $mediqo_id
		];
	}

	function add_appointments($params){
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
			    "dueTo": "2019-12-17 18:30",
			    "lat": 19.449481,
			    "lng": -99.165957, 
			    "extraPatient": "Extra patient",
			    "extraPatientAge": 22,
			    "extraPatientGender": 1
			}
		*/

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