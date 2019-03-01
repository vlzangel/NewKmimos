<?php
	require (dirname(dirname(dirname(dirname(__DIR__)))) . '/lib/mercadopago/mercadopago.php');
    include (dirname(dirname(dirname(dirname(__DIR__)))) . '/lib/Requests/Requests.php');

	# Parametros
	extract($_POST);
		$info = explode("===", $info);

		$parametros_label = array(
			"pagar",
			"tarjeta",
			"cantidades",
		);
		$parametros = array();

		foreach ($info as $key => $value) {
			if( array_key_exists($key, $parametros_label) ){
				$parametros[ $parametros_label[ $key ] ] = json_decode( str_replace('\"', '"', $value) );
			}
		}

		extract($parametros);

	# Crear Orden en Kmimos
	Requests::register_autoloader();
		if( $ruta == 'https://mx.kmimos.la/' ){
			$ruta = 'http://mx.kmimos.la';
		}
		$path = $ruta.'/wp-content/themes/kmimos/procesos/conocer/pagar.php';
        $reserva_data = Requests::post($path,array(),$_POST);
        $reserva = json_decode($reserva_data->body);

    # Crear solicitud de pago Mercadopago
	$res = ['status'=>'ERROR', 'links'=>''];
	if( isset($reserva->order_id) && $reserva->order_id > 0){
		# Servicios ( Items )
		$items = [];
		$obj = new MercadoPago\Item();
		$obj->id = "recarga";
		$obj->title = '3 Cupos x $10'; 
		$obj->description = '3 Cupos x $10';
		$obj->currency_id = 'MXN';
		$obj->quantity = 3;
		$obj->unit_price = 10;
		$items[] = $obj;

		# Create a payer object ( falta )
		$payer = new MercadoPago\Payer();
			$payer->email = "i.cocchini-buyer@kmimos.la";
			$payer->name = "italo";
			$payer->surname = "buyer";
			$payer->date_created = "2018-06-02T12:58:41.425-04:00";
			$payer->phone = array(
				"area_code" => "",
				"number" => "991 455 689"
			);
			$payer->identification = array(
				"type" => "DNI",
				"number" => "12345678"
			);
			$payer->address = array(
				"street_name" => "Subida Concepción",
				"street_number" => 1819,
				"zip_code" => "76254"
			);  

		# Shipments ( falta )
		$shipments = new MercadoPago\Shipments();
			$shipments->receiver_address = array(
			    "zip_code" => "76254",
			    "street_number" => 1819,
			    "street_name" => "Subida Concepción",
			    "floor" => 16,
			    "apartment" => "C"
			);

		# Payment
		$payment = new MercadoPago\Payment();
			$payment->site_id = 'MLM';
			$payment->installments = 1;
			$payment->capture = true;
			$payment->callback_url = $ruta . '/recarga/validar/?x=validar&km=mercadopago&t=callback';
			$payment->notification_url = $ruta . '/recarga/validar/?x=validar&km=mercadopago&t=notification';
			$payment->order = [
				'type' => 'mercadopago',
				'id' => '234',
			];

		# Preference
		$preference = new MercadoPago\Preference();
	  		$preference->items = $items;
			$preference->external_reference = $reserva->order_id;
			$preference->payer = $payer;
			$preference->binary_mode = true;
			$preference->back_urls = array(
			    "success" => $ruta . "/recarga/validar/?x=validar&km=mercadopago&t=success",
			    "failure" => $ruta . "/recarga/validar/?x=validar&km=mercadopago&t=failure",
			    "pending" => $ruta . "/recarga/validar/?x=validar&km=mercadopago&t=pending",
			);
			$preference->payment_methods = array(
			  "excluded_payment_methods" => array(
			    // array("id" => "credit_card"),
			    // array("id" => "prepaid_card"),
			    // array("id" => "ticket"),
			    // array("id" => "atm"),
			    // array("id" => "account_money"),
			    // array("id" => "digital_currency"),
			  ),
			  "installments" => 1
			);
			$preference->auto_return = "approved";

			$preference->save();

		# Respuesta
		if( $preference->init_point != '' ){
			$res = ['status'=>'CREATED', 'links'=>$preference->init_point];
		}
	}
	echo json_encode($res);