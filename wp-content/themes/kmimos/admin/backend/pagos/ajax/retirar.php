<?php

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");
    global $wpdb;

    // ini_set('display_errors', 'On');
    // error_reporting(E_ALL);

    extract($_POST);
	
	$raiz_tema = dirname(dirname(dirname(dirname(__DIR__))));

	include( $raiz_tema."/lib/openpay2/Openpay.php");
	include( $raiz_tema."/procesos/funciones/config.php");

	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( false );

	$retiro_id = $wpdb->get_var("SELECT id FROM retiros_openpay ORDER BY id DESC LIMIT 0, 1");
	$retiro_id = ( empty($retiro_id) ) ? 1 : $retiro_id+1;
	
	try{

		$payoutRequest = array(
		    'method' => 'bank_account',
		    'bank_account' => array(
		        'clabe' => '012180001085050555',
		        'holder_name' => 'Kmimos S.A.P.I. de C.V.'
		    ),
		    'amount' => number_format($monto, 2, '.', ''),
		    'description' => 'Retiro de saldo',
		    'order_id' => 'ORDEN-'.$retiro_id
		);

		$payout = $openpay->payouts->create($payoutRequest);

		
		$user = wp_get_current_user();
		$admin = $user->ID;

		$wpdb->query("INSERT INTO retiros_openpay VALUES (
			NULL,
			'{$admin}',
			'{$payout->id}',
			NOW()
		)");

		echo json_encode([
			"status" => "ok",
			"respuesta" => "Retiro generado exitosamente!"
		]);
		

		die();
		
	}catch(OpenpayApiError $c){
        $error = [
        	$c->getRequestId(),
        	$c->getHttpCode(),
        	$c->getErrorCode(),
        	$c->getDescription()
        ];
    }

    
    echo json_encode([
		"status" => "error",
		"respuesta" => $error
    ]);
	

    die();
    

?>