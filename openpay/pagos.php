<?php

	include("../wp-load.php");

	include("openpay/Openpay.php");
	include("../wp-content/themes/kmimos/procesos/funciones/config.php");

	// Inicialización OpenPay
	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( false );

	$searchParams = array(
	    'creation[gte]' => '2013-11-01',
	    'creation[lte]' => '2017-12-31',
	    'payout_type' => 'AUTOMATIC',
	    'offset' => 0,
	    'limit' => 2
	);

	$response = OpenpayApiConnector::request('get', '', $searchParams);
		
	echo "<pre>";
		print_r( $response["dispersion_balance"] );
	echo "</pre>";

	/*

	$payoutList = $openpay->payouts->getList( $searchParams );

	echo "<pre>";
		print_r( $payoutList );
	echo "</pre>";
	*/
?>