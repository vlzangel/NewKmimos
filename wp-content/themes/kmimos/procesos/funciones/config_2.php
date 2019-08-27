<?php
	$OPENPAY_PRUEBAS = 1;

	$OPENPAY_URL = ( $OPENPAY_PRUEBAS == 1 ) ? "https://sandbox-dashboard.openpay.mx" : "https://dashboard.openpay.mx";
	
	$MERCHANT_ID = "mbagfbv0xahlop5kxrui";
	  
	/*
	$OPENPAY_KEY_SECRET = "sk_b485a174f8d34df3b52e05c7a9d8cb22";
	$OPENPAY_KEY_PUBLIC = "pk_dacadd3820984bf494e0f5c08f361022";
	*/

	$OPENPAY_KEY_SECRET = "sk_a9156e2892354be09d7693320b257523";
	$OPENPAY_KEY_PUBLIC = "pk_0e86300a4a014f15bc145eee19ffdb09";
	
	if( $OPENPAY_PRUEBAS == 1 ){
		$MERCHANT_ID = "mej4n9f1fsisxcpiyfsz";
		$OPENPAY_KEY_SECRET = "sk_684a7f8598784911a42ce52fb9df936f";
		$OPENPAY_KEY_PUBLIC = "pk_3b4f570da912439fab89303ab9f787a1";
	}
	
	/* Sandbox a.veloz
	if( $OPENPAY_PRUEBAS == 1 ){
		$MERCHANT_ID = "mbdcldmwlolrgxkd55an";
		$OPENPAY_KEY_SECRET = "sk_532855907c61452898d492aa521c8c9f";
		$OPENPAY_KEY_PUBLIC = "pk_94d15185c54841a68c568d14e0d0debd";
	}
	*/
?>