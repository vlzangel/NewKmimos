<?php
	require_once '/lib/vendor/autoload.php';

	MercadoPago\SDK::initialize(); 
	$config = MercadoPago\SDK::config(); 

	MercadoPago\SDK::setAccessToken("TEST-163023365316267-021914-c0d0782be83745a7ad3a11eaf96626e5-405963188");

	# Building an item
	$item1 = new MercadoPago\Item();
		$item1->id = "00001";
		$item1->title = "item 1"; 
		$item1->quantity = 2;
		$item1->unit_price = 100;

	$item2 = new MercadoPago\Item();
		$item2->id = "00001";
		$item2->title = "item 2"; 
		$item2->quantity = 2;
		$item2->unit_price = 100;

	# Create a payer object
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

	// Shipments
	$shipments = new MercadoPago\Shipments();
		$shipments->receiver_address = array(
		    "zip_code" => "76254",
		    "street_number" => 1819,
		    "street_name" => "Subida Concepción",
		    "floor" => 16,
		    "apartment" => "C"
		);

	# Preference
	$url = 'https://mx.kmimos.la/mercadopago/response/';
	$payment = new MercadoPago\Payment();
		$payment->site_id = 'MLM';
		$payment->installments = 1;
		$payment->capture = true;
		$payment->callback_url = $url . "callback.php";
		$payment->notification_url = $url . "notificacion.php";
		$payment->order = [
			'type' => 'mercadopago',
			'id' => '234',
		];

	# Preference
	$preference = new MercadoPago\Preference();
  		$preference->items = array($item1, $item2);
		$preference->external_reference = 'RESERVA_ID_001';
		$preference->payer = $payer;
		$preference->binary_mode = true;
		$preference->back_urls = array(
		    "success" => $url . "callback.php",
		    "failure" => $url . "callback.php",
		    "pending" => $url . "callback.php",
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

	echo $preference->init_point. '<br><pre>';

	print_r($preference);
