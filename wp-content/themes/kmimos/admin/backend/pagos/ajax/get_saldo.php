<?php
	
	$raiz_tema = dirname(dirname(dirname(dirname(__DIR__))));

	include( $raiz_tema."/lib/openpay2/Openpay.php");
	include( $raiz_tema."/procesos/funciones/config.php");

	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( false );

	$params = array();
	$response = OpenpayApiConnector::request('get', '', $params);
		
	echo json_encode([
		$response["dispersion_balance"],
		number_format($response["dispersion_balance"], 2, '.', ','),
	]);
?>