<?php
	require_once 'lib/vendor/autoload.php';

	MercadoPago\SDK::initialize(); 
	$config = MercadoPago\SDK::config(); 

//	Sandbox
	MercadoPago\SDK::setAccessToken("TEST-163023365316267-021914-c0d0782be83745a7ad3a11eaf96626e5-405963188");


//	Produccion
//	MercadoPago\SDK::setAccessToken("APP_USR-2991639478302021-031407-38ae7fc607447a8292f043f819dcddfb-396489252");
