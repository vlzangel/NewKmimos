<?php
	require_once 'lib/vendor/autoload.php';

	MercadoPago\SDK::initialize(); 
	$config = MercadoPago\SDK::config(); 

	MercadoPago\SDK::setAccessToken("TEST-163023365316267-021914-c0d0782be83745a7ad3a11eaf96626e5-405963188");

