<?php
	include("../wp-load.php");

	include("openpay/Openpay.php");
	//include("../wp-content/themes/kmimos/procesos/funciones/config.php");

	global $wpdb;

    date_default_timezone_set('America/Mexico_City');

    $limite = date("Y-m-d", strtotime("-4 day"));

    $MERCHANT_ID = "mbagfbv0xahlop5kxrui";
	$OPENPAY_KEY_SECRET = "sk_b485a174f8d34df3b52e05c7a9d8cb22";
	$OPENPAY_KEY_PUBLIC = "pk_dacadd3820984bf494e0f5c08f361022";

	// Inicialización OpenPay
	$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
	Openpay::setProductionMode( true );
		
/*	$findDataRequest = array(
	    'creation[gte]' => $limite,
	    'offset' => 0,
	    'limit' => 10000
    );

	$chargeList = $openpay->charges->getList($findDataRequest);

	$ordenes = array();
	foreach ($chargeList as $value) {
		$ordenes[] = $value->order_id;
	}*/

	$customer = $openpay->customers->get("atl5u6fqz0q2wpzznixv");
	$charge = $customer->charges->get("trbkkojmjbqklwt2sdzr");

	echo "<pre>";
		print_r($charge->order_id);
		print_r($charge->status);
	echo "</pre>";

?>