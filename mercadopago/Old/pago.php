<?php
 
  require_once 'vendor/autoload.php';
   
  
  MercadoPago\MercadoPagoSdk::initialize(); 
  $config = MercadoPago\MercadoPagoSdk::config();
  
  $config->set('TEST-163023365316267-021914-c0d0782be83745a7ad3a11eaf96626e5-405963188', 'TEST-163023365316267-021914-c0d0782be83745a7ad3a11eaf96626e5-405963188');
  
  $payment = new MercadoPago\Payment();
  
  $payment->transaction_amount = 100;
  $payment->token = "f9100f7e9ba98bc9777bfe321774ed5f";
  $payment->description = "Title of what you are paying for";
  $payment->installments = 1;
  $payment->payment_method_id = "visa";
  
  $payer = new MercadoPago\Payer();
  $payer->email = "mail@joelibaceta.com";
  
  $payment->payer = $payer;
  $payment->save(); 
  
  echo $payment->status;
  echo $payment->status_detail;
  
  echo "\n";
  
  echo "PaymentId: " . $payment->id . "\n";
  


/*

	# Create an item object
	$item = new MercadoPago\Item();
		$item->id = "1234";
		$item->title = "Small Plastic Knife";
		$item->quantity = 5;
		$item->currency_id = "MXN";
		$item->unit_price = 23.21;

	# Create a payer object
	$payer = new MercadoPago\Payer();
		$payer->name = "Charles";
		$payer->surname = "Cano";
		$payer->email = "charles@yahoo.com";
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

	# Create a preference object
	$preference = new MercadoPago\Preference();
		$preference->items = array($item);
		$preference->payer = $payer;
		$preference->save();


	# Payment
    $payment = new MercadoPago\Payment();
	    $payment->token = $token;
	    $payment->installments = $installments;
	    $payment->payment_method_id = $payment_method_id;
	    $payment->issuer_id = $issuer_id;
	    $payment->transaction_amount = 180;
	    $payment->description = "Rustic Linen Coat";
	    $payment->payer = array(
	    	"email" => "camden.bauch@hotmail.com"
	    );
	    $payment->save();
	    echo $payment->status;
*/