<?php

include_once("../../lib/payu/PayU.php");

$parameters = array(

	PayUParameters::ACCOUNT_ID => "512321",			//Ingrese aquí el identificador de la cuenta.
	PayUParameters::REFERENCE_CODE => $id_orden,	//Ingrese aquí el código de referencia.
	PayUParameters::DESCRIPTION => "Payment Reserva: {id_orden}",	//Ingrese aquí la descripción.

	// -----------------------------------
    // -- Valores --
	// -----------------------------------
    PayUParameters::VALUE => $pagar->total,	        //Ingrese aquí el valor de la transacción.
    PayUParameters::TAX_VALUE => "0", 				//IVA Default: 19%
    PayUParameters::TAX_RETURN_BASE => "0",			//Monto Base para el calculo del IVA
	PayUParameters::CURRENCY => "COP",				//Ingrese aquí la moneda.

	// -----------------------------------
	// -- Comprador
	// -----------------------------------
	PayUParameters::BUYER_NAME => $nombre,	//Ingrese aquí el nombre del comprador.
	PayUParameters::BUYER_EMAIL => $email,	//Ingrese aquí el email del comprador.
	PayUParameters::BUYER_CONTACT_PHONE => $telefono,		//Ingrese aquí el teléfono de contacto 
	PayUParameters::BUYER_DNI => "5400000000001",			//Ingrese aquí el documento de contacto del comprador.
	// -- Direccion
	PayUParameters::BUYER_STREET => $direccion,
	PayUParameters::BUYER_STREET_2 => "",
	PayUParameters::BUYER_CITY => $municipio,
	PayUParameters::BUYER_STATE => $estado,
	PayUParameters::BUYER_COUNTRY => "CO",
	PayUParameters::BUYER_POSTAL_CODE => $postal,
	PayUParameters::BUYER_PHONE => $telefono,

	// -----------------------------------
	// -- Pagador 
	// -----------------------------------
	PayUParameters::PAYER_NAME => $nombre,	//Ingrese aquí el nombre del pagador.
	PayUParameters::PAYER_EMAIL => $email,	//Ingrese aquí el email del pagador.
	PayUParameters::PAYER_CONTACT_PHONE => $telefono,		//Ingrese aquí el teléfono de contacto del pagador.
	PayUParameters::PAYER_DNI => "5400000000001",			//Ingrese aquí el documento de contacto del pagador.
	// -- Direccion
	PayUParameters::PAYER_STREET => $direccion,
	PayUParameters::PAYER_STREET_2 => "",
	PayUParameters::PAYER_CITY => $municipio,
	PayUParameters::PAYER_STATE => $estado,
	PayUParameters::PAYER_COUNTRY => "CO",
	PayUParameters::PAYER_POSTAL_CODE => $postal,
	PayUParameters::PAYER_PHONE => $telefono,

	// -----------------------------------
	// -- Datos de la tarjeta de crédito 
	// -----------------------------------
	PayUParameters::CREDIT_CARD_NUMBER => "4097440000000004",			//número de la tarjeta de crédito
	PayUParameters::CREDIT_CARD_EXPIRATION_DATE => "2014/12",			//fecha de vencimiento de la tarjeta de crédito
	PayUParameters::CREDIT_CARD_SECURITY_CODE=> "321",					//código de seguridad de la tarjeta de crédito
	PayUParameters::PAYMENT_METHOD => "VISA",							//VISA||MASTERCARD||AMEX||DINERS
	PayUParameters::INSTALLMENTS_NUMBER => "1",							//Ingrese aquí el número de cuotas.
	// -- Direccion
	PayUParameters::COUNTRY => PayUCountries::CO,							//Ingrese aquí el nombre del pais.
	PayUParameters::DEVICE_SESSION_ID => $pagar->deviceIdHiddenFieldName,	//Session id del device.
	PayUParameters::IP_ADDRESS => $_SERVER["REMOTE_ADDR"],					//IP del pagadador
	PayUParameters::PAYER_COOKIE => session_id(),							//Cookie de la sesión actual.
	PayUParameters::USER_AGENT => $_SERVER['HTTP_USER_AGENT'],				//Cookie de la sesión

);

$charge = PayUPayments::doAuthorizationAndCapture($parameters);

/*
if ($payu_response) {

	$payu_response->transactionResponse->orderId;
	$payu_response->transactionResponse->transactionId;
	$payu_response->transactionResponse->state;

	if ($payu_response->transactionResponse->state=="PENDING") {
		$payu_response->transactionResponse->pendingReason;
	}

	$payu_response->transactionResponse->paymentNetworkResponseCode;
	$payu_response->transactionResponse->paymentNetworkResponseErrorMessage;
	$payu_response->transactionResponse->trazabilityCode;
	$payu_response->transactionResponse->responseCode;
	$payu_response->transactionResponse->responseMessage;
}
*/
 