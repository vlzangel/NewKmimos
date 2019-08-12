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
    	'creation[gte]' => '2019-12-01',
    	'creation[lte]' => '2019-12-31',
    	'limit' => 1000
    );
	
	$customerList = $openpay->customers->getList($findDataRequest);

	
	$ORDER = get_user_meta(367, 'ORDER', true);
	if( $ORDER+0 == 0 ){
		// $_SESSION["ORDER"] = 2;
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

	/*
		$order_id = str_pad('1', 6, "0", STR_PAD_LEFT);

		$feeDataRequest = array(
		    'customer_id' => 'ajsz254ilsdoe37wh5jc',
		    'amount' => '1125',
		    'description' => 'Cobro de Comisión',
		    'order_id' => 'ORDEN-'.$order_id
		);
		$fee = $openpay->fees->create($feeDataRequest);

		  3074,10 + 6646,25		01/19
		  9720,35 + 216206,51	12/18
		225926,86 + 157659,39	11/18
		383586.25 + 70821,51	10/18
		454407,76 + 33995		09/18
		488402,76 + 33176,25	08/18
		521579,01 + 229733,78	07/18
		751312,79 + 73866,28	06/18
		825179,07 + 7930,5	    05/18
		833109,57


		UPDATE `wp_usermeta` SET `meta_key` = '_openpay_customer_id_bk' WHERE `wp_usermeta`.`meta_key` = '_openpay_customer_id'
	*/


?>