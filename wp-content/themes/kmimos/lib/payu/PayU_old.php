<?php

class PayU {

	// -- Habilitar Sandbox
	protected $isTest = true; 

	// -- PayU Configuracion
	public function getConfig( $ref='', $monto='', $moneda='COP' ){

		// -- Cargar Configuracion
		$config = [
			'sandbox' => [
				'apiKey' => '4Vj8eK4rloUd272L48hsrarnUA',
				'apiLogin' => 'pRRXKOl8ikMmt9u',
				'merchantId' => '512321',
				'isTest' => 'true',
				'PaymentsCustomUrl' => 'https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi',
				'ReportsCustomUrl' => 'https://sandbox.api.payulatam.com/reports-api/4.0/service.cgi',
				'SubscriptionsCustomUrl' => 'https://sandbox.api.payulatam.com/payments-api/rest/v4.3/',
			],
			'produccion' => [
				'apiKey' => 'xxxxxxxxxxxxxxxxxxxxx',
				'apiLogin' => 'xxxxxxxxxxxxxx',
				'merchantId' => '000000',
				'isTest' => 'false',
				'PaymentsCustomUrl' => 'https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi',
				'ReportsCustomUrl' => 'https://sandbox.api.payulatam.com/reports-api/4.0/service.cgi',
				'SubscriptionsCustomUrl' => 'https://sandbox.api.payulatam.com/payments-api/rest/v4.3/',
			],
		];

		$result = ( $this->isTest )? $config['sandbox'] : $config['produccion'] ;

		// -- Create signature
		$signature = '';
		if( !empty($ref) && !empty($monto) && !empty($moneda) ){
			$code = $result['ApiKey'];
			$code .= '~'.$result['merchantId'];
			$code .= '~'.$ref;
			$code .= '~'.$$monto;
			$code .= '~'.$moneda;

			$signature = md5($code);
		}
		$result['signature'] = $signature;

		return $result;
	}

	// -- Pago con TDC
	public function AutorizacionCaptura( $datos ){
		$config = $this->getConfig( 
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
		$cofg["transaction"]["order"]["accountId"] = $config['merchantId'];
		$cofg["transaction"]["order"]["referenceCode"] =  $data['id_orden'];
		$cofg["transaction"]["order"]["description"] = 'Tarjeta Compra Numero '.$data['id_orden'];
		$cofg["transaction"]["order"]["language"] = "es";
		$cofg["transaction"]["order"]["signature"] = $config['signature'];
		$cofg["transaction"]["order"]["notifyUrl"] = "http://www.tes.com/confirmation";

		// -- Datos de Costo de Servicio      
		$cofg["transaction"]["order"]["additionalValues"]["TX_VALUE"]["value"] = $data['monto'];
		$cofg["transaction"]["order"]["additionalValues"]["TX_VALUE"]["currency"] = $data['moneda'];

		// -- Datos de Impuesto      
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX"]["value"] = 0;
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX"]["currency"] = $data['moneda'];

		// -- Datos de Impuesto Base     
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX_RETURN_BASE"]["value"] = 0;
		$cofg["transaction"]["order"]["additionalValues"]["TX_TAX_RETURN_BASE"]["currency"] = $data['moneda'];

		// -- Datos de Comprador
		$cofg["transaction"]["order"]["buyer"]["merchantBuyerId"] = $data['cliente']['ID'];
		$cofg["transaction"]["order"]["buyer"]["fullName"] = $data['cliente']['name'];
		$cofg["transaction"]["order"]["buyer"]["emailAddress"] = $data['cliente']['email'];
		$cofg["transaction"]["order"]["buyer"]["contactPhone"] = $data['cliente']['telef'];
		$cofg["transaction"]["order"]["buyer"]["dniNumber"] = $data['cliente']['dni'];

		// -- Datos de Comprador - Direccion
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["street1"] = $data['cliente']['calle1'];
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["street2"] = $data['cliente']['calle2'];
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["city"] = $data['cliente']['ciudad'];
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["state"] = $data['cliente']['estado'];
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["country"] = $data['cliente']['pais'];
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["postalCode"] = "000000";
		$cofg["transaction"]["order"]["buyer"]["shippingAddress"]["phone"] = $data['cliente']['telef'];

		// -- Datos de Direccion de la Orden
		$cofg["transaction"]["order"]["shippingAddress"]["street1"] = $data['cliente']['calle1'];
		$cofg["transaction"]["order"]["shippingAddress"]["street2"] = $data['cliente']['calle2'];
		$cofg["transaction"]["order"]["shippingAddress"]["city"] = $data['cliente']['ciudad'];
		$cofg["transaction"]["order"]["shippingAddress"]["state"] = $data['cliente']['estado'];
		$cofg["transaction"]["order"]["shippingAddress"]["country"] = $data['cliente']['pais'];
		$cofg["transaction"]["order"]["shippingAddress"]["postalCode"] = "0000000";
		$cofg["transaction"]["order"]["shippingAddress"]["phone"] = $data['cliente']['telef'];
		 
		// -- Datos de Pagador 
		$cofg["transaction"]["payer"]["merchantPayerId"] = $data['cliente']['ID'];
		$cofg["transaction"]["payer"]["fullName"] = $data['cliente']['name'];
		$cofg["transaction"]["payer"]["emailAddress"] = $data['cliente']['email'];
		$cofg["transaction"]["payer"]["contactPhone"] = $data['cliente']['telef'];
		$cofg["transaction"]["payer"]["dniNumber"] = $data['cliente']['dni'];

		// -- Datos de Pagador - Direccion 
		$cofg["transaction"]["payer"]["billingAddress"]["street1"] = $data['cliente']['calle1'];
		$cofg["transaction"]["payer"]["billingAddress"]["street2"] = $data['cliente']['calle2'];
		$cofg["transaction"]["payer"]["billingAddress"]["city"] = $data['cliente']['ciudad'];
		$cofg["transaction"]["payer"]["billingAddress"]["state"] = $data['cliente']['estado'];
		$cofg["transaction"]["payer"]["billingAddress"]["country"] = $data['cliente']['pais'];
		$cofg["transaction"]["payer"]["billingAddress"]["postalCode"] = "000000";
		$cofg["transaction"]["payer"]["billingAddress"]["phone"] = $data['cliente']['telef'];

		// -- Datos de Tarjeta de Credito
		$cofg["transaction"]["creditCard"]["number"] = "4097440000000004";
		$cofg["transaction"]["creditCard"]["securityCode"] = "321";
		$cofg["transaction"]["creditCard"]["expirationDate"] = "2019/12";
		$cofg["transaction"]["creditCard"]["name"] = "REJECTED";
		$cofg["transaction"]["paymentMethod"] = "VISA";

		// -- Datos de Session y Configuracion
		$cofg["transaction"]["extraParameters"]["INSTALLMENTS_NUMBER"] = 1;
		$cofg["transaction"]["type"] = "AUTHORIZATION_AND_CAPTURE";
		$cofg["transaction"]["paymentCountry"] = $data['pais'];
		$cofg["transaction"]["deviceSessionId"] = md5(session_id().microtime());
		$cofg["transaction"]["ipAddress"] = $_SERVER['REMOTE_ADDR'];
		$cofg["transaction"]["cookie"] = $_SERVER['HTTP_COOKIE'];
		$cofg["transaction"]["userAgent"] = $_SERVER['HTTP_USER_AGENT'];
		$cofg["test"] = false;


		$r = $this->request( 
			$config['PaymentsCustomUrl'], 
			json_encode($cofg, JSON_UNESCAPED_UNICODE)
		);


print_r(json_encode($cofg, JSON_UNESCAPED_UNICODE));
print_r(json_decode($r->body) );
exit();


		return $r;
	}

	// -- Pago en Tienda
	public function Autorizacion( $datos ){
		$config = $this->getConfig();
	}

	// -- Procesar Pagos
	public function Captura( $datos ){
		$config = $this->getConfig();
	}

	public function request( $url, $data ){

		include(realpath( dirname(__DIR__)."/Requests/Requests.php" ));
		Requests::register_autoloader();
		$headers = Array(
			'Content-Type'=> 'application/json; charset=UTF-8',	
			'Accept'=>'application/json'
		);
		$request = Requests::post($url, $headers,  $data );
 	
		return $request;
	}
}