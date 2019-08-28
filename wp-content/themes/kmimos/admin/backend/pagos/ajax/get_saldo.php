<?php
	
	$raiz_tema = dirname(dirname(dirname(dirname(__DIR__))));

	include( $raiz_tema."/lib/openpay/Openpay.php");
	include( $raiz_tema."/procesos/funciones/config.php");

	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( $OPENPAY_PRUEBAS == 0 );

	try{
		$params = array();
		$response = OpenpayApiConnector::request('get', '', $params);
			
		echo json_encode([
        	"status" => "ok",
			"saldo" => $response["dispersion_balance"],
			"saldo_txt" => number_format($response["dispersion_balance"], 2, '.', ',')
		]);
	}catch(Exception $e){
        echo json_encode([
        	"status" => "error",
			"respuesta" => $e
		]);
    }
?>