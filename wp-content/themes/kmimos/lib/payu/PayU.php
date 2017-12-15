<?php

class PayU {

	// -- Habilitar Sandbox [ true: Sandbox || false: Produccion]
	protected $isTest = true; 

	// -- PayU Configuracion
	public function init( $ref='', $monto='', $moneda='' ){

		// SandBox: accountId [ Peru: 512323 || Colombia: 512321 ]
		// -- Cargar Configuracion
		$config = [
			'sandbox' => [
				'apiKey' => '4Vj8eK4rloUd272L48hsrarnUA',
				'apiLogin' => 'pRRXKOl8ikMmt9u',
				'merchantId' => '508029',
				'accountId' => '512323', 
				'isTest' => 'false',
				'confirmation' => get_home_url().'/cron/payu/request.php',
				'PaymentsCustomUrl' => 'https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi',
				'ReportsCustomUrl' => 'https://sandbox.api.payulatam.com/reports-api/4.0/service.cgi',
				'SubscriptionsCustomUrl' => 'https://sandbox.api.payulatam.com/payments-api/rest/v4.3/',
			],
			'produccion' => [
				'apiKey' => 'xxxxxxxxxxxxxxxxxxxxx',
				'apiLogin' => 'xxxxxxxxxxxxxx',
				'merchantId' => '000000',
				'accountId' => '000000',
				'isTest' => 'false',
				'confirmation' => get_home_url().'/cron/payu/request.php',
				'PaymentsCustomUrl' => 'https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi',
				'ReportsCustomUrl' => 'https://sandbox.api.payulatam.com/reports-api/4.0/service.cgi',
				'SubscriptionsCustomUrl' => 'https://sandbox.api.payulatam.com/payments-api/rest/v4.3/',
			],
		];

		$result = ( $this->isTest )? $config['sandbox'] : $config['produccion'] ;

		// -- Create signature
		$signature = '';
		if( !empty($ref) && !empty($monto) && !empty($moneda) ){
			$code = $result['apiKey'] . '~' . $result['merchantId'] . '~' . $ref . '~' . $monto . '~' . $moneda;		
			$signature = md5($code);
		}
		$result['signature'] = $signature;

		return $result;
	}

	// -- Pago con TDC
	public function AutorizacionCaptura( $datos ){
		$config = $this->init( 
			$datos['id_orden'],
			$datos['monto'],
			$datos['moneda']
		);

		$cofg = [];
		// -- Datos del API
		$cofg["language"] = "es";
		$cofg["command"] = "SUBMIT_TRANSACTION";
		$cofg["merchant"]["apiKey"] = $config['apiKey'];
		$cofg["merchant"]["apiLogin"] = $config['apiLogin'];

		// -- Datos de la Orden
		$cofg["transaction"]["order"]["accountId"] = $config['accountId'];
		$cofg["transaction"]["order"]["referenceCode"] =  $datos['id_orden'];
		$cofg["transaction"]["order"]["description"] = 'Tarjeta Compra Numero '.$datos['id_orden'];
		$cofg["transaction"]["order"]["language"] = "es";
		$cofg["transaction"]["order"]["signature"] = $config['signature'];
		$cofg["transaction"]["order"]["notifyUrl"] = $config['confirmation'];

		// -- Datos de Direccion de la Orden
		$cofg["transaction"]["order"]["shippingAddress"]["street1"] = $datos['cliente']['calle1'];
		$cofg["transaction"]["order"]["shippingAddress"]["street2"] = $datos['cliente']['calle2'];
		$cofg["transaction"]["order"]["shippingAddress"]["city"] = $datos['cliente']['ciudad'];
		$cofg["transaction"]["order"]["shippingAddress"]["state"] = $datos['cliente']['estado'];
		$cofg["transaction"]["order"]["shippingAddress"]["country"] = $datos['cliente']['pais'];
		$cofg["transaction"]["order"]["shippingAddress"]["postalCode"] = $datos['cliente']['postal'];
		$cofg["transaction"]["order"]["shippingAddress"]["phone"] = $datos['cliente']['telef'];

		// -- Datos de Costo de Servicio      
		$cofg["transaction"]["order"]["additionalValues"]["TX_VALUE"]["value"] = $datos['monto'];
		$cofg["transaction"]["order"]["additionalValues"]["TX_VALUE"]["currency"] = $datos['moneda'];

		// -- Datos de Impuesto      
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX"]["value"] = 0;
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX"]["currency"] = $datos['moneda'];

		// -- Datos de Impuesto Base     
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX_RETURN_BASE"]["value"] = 0;
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX_RETURN_BASE"]["currency"] = $datos['moneda'];

		// -- Datos de Comprador
		$cofg["transaction"]["order"]["buyer"]["merchantBuyerId"] = $datos['cliente']['ID'];
		$cofg["transaction"]["order"]["buyer"]["fullName"] = $datos['cliente']['name'];
		$cofg["transaction"]["order"]["buyer"]["emailAddress"] = $datos['cliente']['email'];
		$cofg["transaction"]["order"]["buyer"]["contactPhone"] = $datos['cliente']['telef'];
		$cofg["transaction"]["order"]["buyer"]["dniNumber"] = $datos['cliente']['dni'];

		// -- Datos de Comprador - Direccion
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["street1"] = $datos['cliente']['calle1'];
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["street2"] = $datos['cliente']['calle2'];
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["city"] = $datos['cliente']['ciudad'];
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["state"] = $datos['cliente']['estado'];
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["country"] = $datos['cliente']['pais'];
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["postalCode"] = $datos['cliente']['postal'];
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["phone"] = $datos['cliente']['telef'];
		 
		// -- Datos de Pagador 
		$cofg["transaction"]["payer"]["merchantPayerId"] = $datos['cliente']['ID'];
		
		$cofg["transaction"]["payer"]["emailAddress"] = $datos['cliente']['email'];
		$cofg["transaction"]["payer"]["contactPhone"] = $datos['cliente']['telef'];
		$cofg["transaction"]["payer"]["dniNumber"] = $datos['cliente']['dni'];

		// -- Datos de Pagador - Direccion 
		$cofg["transaction"]["payer"]["billingAddress"]["street1"] = $datos['cliente']['calle1'];
		$cofg["transaction"]["payer"]["billingAddress"]["street2"] = $datos['cliente']['calle2'];
		$cofg["transaction"]["payer"]["billingAddress"]["city"] = $datos['cliente']['ciudad'];
		$cofg["transaction"]["payer"]["billingAddress"]["state"] = $datos['cliente']['estado'];
		$cofg["transaction"]["payer"]["billingAddress"]["country"] = $datos['cliente']['pais'];
		$cofg["transaction"]["payer"]["billingAddress"]["postalCode"] = $datos['cliente']['postal'];
		$cofg["transaction"]["payer"]["billingAddress"]["phone"] = $datos['cliente']['telef'];

		// -- Datos de Tarjeta de Credito
		$cofg["transaction"]["creditCard"]["number"] = $datos['creditCard']['number'];
		$cofg["transaction"]["creditCard"]["securityCode"] = $datos['creditCard']['securityCode'];
		$cofg["transaction"]["creditCard"]["expirationDate"] = $datos['creditCard']['expirationDate'];
		$cofg["transaction"]["creditCard"]["name"] = $datos['creditCard']['name'];
		$cofg["transaction"]["paymentMethod"] = $datos['creditCard']['payment_method'];

		// -- Datos de Session y Configuracion
		$cofg["transaction"]["extraParameters"]["INSTALLMENTS_NUMBER"] = "1";
		$cofg["transaction"]["type"] = "AUTHORIZATION_AND_CAPTURE";
		$cofg["transaction"]["paymentCountry"] = $datos['pais'];
		$cofg["transaction"]["deviceSessionId"] = $datos['PayuDeviceSessionId'];
		$cofg["transaction"]["ipAddress"] = $_SERVER['REMOTE_ADDR'];
		$cofg["transaction"]["cookie"] = $_COOKIE['PHPSESSID'];
		$cofg["transaction"]["userAgent"] = $_SERVER['HTTP_USER_AGENT'];
		$cofg["test"] = $config['isTest'];

		$r = $this->request( 
			$config['PaymentsCustomUrl'], 
			json_encode($cofg, JSON_UNESCAPED_UNICODE)
		);
 
		return json_decode($r);
	}

	// -- Pago en Tienda
	public function Autorizacion( $datos ){
		$config = $this->init( 
			$datos['id_orden'],
			$datos['monto'],
			$datos['moneda']
		);

		$cofg = [];
		// -- Datos del API
		$cofg["language"] = "es";
		$cofg["command"] = "SUBMIT_TRANSACTION";
		$cofg["merchant"]["apiKey"] = $config['apiKey'];
		$cofg["merchant"]["apiLogin"] = $config['apiLogin'];

		// -- Datos de la Orden
		$cofg["transaction"]["order"]["accountId"] = $config['accountId'];
		$cofg["transaction"]["order"]["referenceCode"] =  $datos['id_orden'];
		$cofg["transaction"]["order"]["description"] = 'Compra Numero '.$datos['id_orden'];
		$cofg["transaction"]["order"]["language"] = "es";
		$cofg["transaction"]["order"]["signature"] = $config['signature'];
		$cofg["transaction"]["order"]["notifyUrl"] = $config['confirmation'];

		// -- Datos de Costo de Servicio      
		$cofg["transaction"]["order"]["additionalValues"]["TX_VALUE"]["value"] = str_replace('.', ',', $datos['monto'] );
		$cofg["transaction"]["order"]["additionalValues"]["TX_VALUE"]["currency"] = $datos['moneda'];

		// -- Datos de Impuesto      
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX"]["value"] = 0;
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX"]["currency"] = $datos['moneda'];

		// -- Datos de Impuesto Base     
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX_RETURN_BASE"]["value"] = 0;
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX_RETURN_BASE"]["currency"] = $datos['moneda'];

		// -- Datos de Comprador
		$cofg["transaction"]["order"]["buyer"]["fullName"] = $datos['cliente']['name'];
		$cofg["transaction"]["order"]["buyer"]["emailAddress"] = $datos['cliente']['email'];
		$cofg["transaction"]["payer"]["fullName"] = $datos['cliente']['name'];

		// -- Datos de Session y Configuracion
		$cofg["transaction"]["type"] = "AUTHORIZATION_AND_CAPTURE";
		$cofg["transaction"]["ipAddress"] = $_SERVER['REMOTE_ADDR'];
		$cofg["transaction"]["paymentCountry"] = $datos['pais'];
		$cofg["transaction"]["paymentMethod"] = $datos['paymentMethod'];
		$cofg["transaction"]["expirationDate"] = $datos['expirationDate'];

		$cofg["test"] = $config['isTest'];

		$r = $this->request( 
			$config['PaymentsCustomUrl'], 
			json_encode($cofg, JSON_UNESCAPED_UNICODE)
		);
	
		return json_decode($r);;
	}

	// -- Procesar Pagos
	public function Captura( $datos ){
		$config = $this->init();
	}

	public function getByOrderID( $order_id = '' ){
		$config = $this->init();

		$cofg = [];
		// -- Datos del API
		$cofg["test"] = $config['isTest'];
		$cofg["language"] = "es";
		$cofg["command"] = "ORDER_DETAIL";
		$cofg["merchant"]["apiKey"] = $config['apiKey'];
		$cofg["merchant"]["apiLogin"] = $config['apiLogin'];

		$cofg["details"]["orderId"] = $order_id;

		$r = $this->request( 
			$config['ReportsCustomUrl'], 
			json_encode($cofg, JSON_UNESCAPED_UNICODE)
		);

		return json_decode($r);
	}

	// -- Enviar solicitud
	public function request( $url, $data ){

		include(realpath( dirname(__DIR__)."/Requests/Requests.php" ));
		
		Requests::register_autoloader();
		$headers = Array(
			'Content-Type'=> 'application/json; charset=UTF-8',	
			'Accept'=>'application/json'
		);
		$request = Requests::post($url, $headers,  $data );
 	
		return (isset($request->body))? $request->body : '' ;
	}
}