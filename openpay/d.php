<?php
	include("../wp-load.php");

	include("openpay/Openpay.php");
	include("../wp-content/themes/kmimos/procesos/funciones/config.php");

	global $wpdb;

	// Inicialización OpenPay
	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( true );
	
	$customer = $openpay->customers->get("aiwvnryfprsofurmzyg0");
	$charge = $customer->charges->get("trivsn5prjgtplwpgjy0");

	echo "<pre>";
		print_r($charge);
	echo "</pre>";

?>