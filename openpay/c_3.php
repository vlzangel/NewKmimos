<?php

	session_start();

	include("../wp-load.php");

	include("openpay/Openpay.php");
	include("../wp-content/themes/kmimos/procesos/funciones/config.php");

	global $wpdb;

	// Inicialización OpenPay
	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( true );

	$findDataRequest = array(
    	'creation[gte]' => '2018-01-01',
    	'creation[lte]' => '2018-06-01',
    	'limit' => 5000
    );
	
	$customerList = $openpay->customers->getList($findDataRequest);

	
	$ORDER = get_user_meta(367, 'ORDER', true);
	if( $ORDER+0 == 0 ){
		update_user_meta(367, 'ORDER', 9);
	}

	$total = 0;
	echo $ORDER = get_user_meta(367, 'ORDER', true);
	

	echo "<pre>";
		foreach ($customerList as $key => $customer) {
			if( $customer->balance > 0 ){
				$total += $customer->balance;
				print_r($customer->id." - ".$customer->name.": ".$customer->balance."<br>");

				
				$order_id = str_pad($ORDER, 6, "0", STR_PAD_LEFT);

				$feeDataRequest = array(
				    'customer_id' => $customer->id,
				    'amount' => $customer->balance,
				    'description' => 'Cobro de Comisión',
				    'order_id' => 'ORDEN-'.$order_id
				);

				print_r( $feeDataRequest );
				echo "<br><br>";
				// $fee = $openpay->fees->create($feeDataRequest);

				$ORDER++;
				
			}
		}

		// $_SESSION["ORDER"] = $ORDER;

		// update_user_meta(367, 'ORDER', $ORDER);

		echo "Total: ".$total;
	echo "</pre>";
?>