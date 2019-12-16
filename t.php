<?php
	include 'wp-load.php';

	ini_set('display_errors', 'On');
	error_reporting(E_ALL);

	function get_data_user($user_id){
		global $wpdb;
		$data_cliente = array();
	    $xdata_cliente = $wpdb->get_results("
			SELECT meta_key, meta_value 
			FROM wp_usermeta 
			WHERE user_id = {$user_id} AND ( meta_key = 'first_name' OR meta_key = 'last_name'  OR meta_key = '_mediqo_customer_id' )"
	    );
	    foreach ($xdata_cliente as $key => $value) { $data_cliente[ $value->meta_key ] = ($value->meta_value); }
	    $data_cliente[ 'email' ] = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = '{$user_id}' ");
	    return $data_cliente;
	}

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
			update_user_meta($user_id, "_mediqo_customer_id", $mediqo_id);
		}else{
			$mediqo_id = $data_cliente['_mediqo_customer_id'];
		}
	    return [
			'status' => 'ok',
			'info' => $mediqo_id
		];
	}

	$data_cliente = get_data_user(367);
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

	echo "<pre>";
		print_r( json_decode($resultado) );
	echo "</pre>";
?>