<?php

	function get_data_user($user_id){
		global $wpdb;
		$data_cliente = array();
	    $xdata_cliente = $wpdb->get_results("
		SELECT 
			meta_key, meta_value 
		FROM 
			wp_usermeta 
		WHERE
			user_id = {$user_id} AND (
				meta_key = 'first_name' OR
				meta_key = 'last_name'  OR
				meta_key = '_openpay_customer_id'
			)"
	    );

	    foreach ($xdata_cliente as $key => $value) {
	    	$data_cliente[ $value->meta_key ] = ($value->meta_value);
	    }

	    $data_cliente[ 'email' ] = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = '{$user_id}' ");

	    return $data_cliente;
	}

	function get_openpay_customer($user_id, $openpay){
		$customer = '';
		$data_cliente = get_data_user($user_id);
		foreach ($data_cliente as $key => $value) {
			if( $data_cliente[$key] == "" ){
				$data_cliente[$key] = "_";
			}
		}

		$nombre 	= $data_cliente["first_name"];
		$apellido 	= $data_cliente["last_name"];
		$email 		= $data_cliente["email"];

		$cliente_openpay = $data_cliente["_openpay_customer_id"];
		if( $cliente_openpay == "" ){
			try {
				$customerData = array(
			     	'name' => $nombre.' '.$apellido,
			     	'email' => $email,
			     	'requires_account' => false,
			  	);
				$customer = $openpay->customers->add($customerData);
				$cliente_openpay = $customer->id;
				update_user_meta($user_id, "_openpay_customer_id", $customer->id);
			} catch (Exception $e) {
				return [
					'status' => 'error',
					'info' => $e->getErrorCode()
				];
			}
	    }

	    try {
			$customer = $openpay->customers->get($cliente_openpay);
		} catch (Exception $e) {
			try {
		    	$customerData = array(
			     	'name' => $nombre.' '.$apellido,
			     	'email' => $email,
		     		'requires_account' => false,
			  	);
				$customer = $openpay->customers->add($customerData);
				$cliente_openpay = $customer->id;
				update_user_meta($pagar->cliente, "_openpay_customer_id", $customer->id);
			} catch (Exception $e) {
				return [
					'status' => 'error',
					'info' => $e->getErrorCode()
				];
			}
	    }

	    return [
			'status' => 'ok',
			'info' => $customer
		];
	}
?>