<?php

	$PayU_file = realpath( dirname(dirname(dirname(__DIR__) ) )."/lib/payu/PayU.php" ) ;
	include( $PayU_file );
	$payu = new PayU();

	$PayuP = [];
	$PayuP['pais'] = ucfirst(  get_region( 'pais' ) );
	$PayuP['moneda'] = get_region( 'moneda_cod' );
	// -- Reserva
	$PayuP['id_orden'] = $id_orden ;
	$PayuP['monto'] =  $pagar->total;
	// -- Clientes
	$PayuP['cliente']['ID'] = get_current_user();
	$PayuP['cliente']['dni'] = '100000000000001';
	$PayuP['cliente']['name'] = $nombre;
	$PayuP['cliente']['email'] = $email;
	$PayuP['cliente']['telef'] = $telefono;
	$PayuP['cliente']['calle1'] = $direccion;
	$PayuP['cliente']['calle2'] = $direccion;
	$PayuP['cliente']['ciudad'] = $estado;
	$PayuP['cliente']['estado'] = $estado;
	$PayuP['cliente']['pais'] = ucfirst(REGION);
	$PayuP['cliente']['telef'] = $telefono;
	// -- Valores temporales
	$PayuP['cliente']['postal'] = '110711';
	$PayuP["creditCard"]["name"] = 'REJECETDE';
	$PayuP["creditCard"]["number"] = '4097440000000004';
	$PayuP["creditCard"]["securityCode"] = '123';
	$PayuP["creditCard"]["payment_method"] = 'VISA';
	$PayuP["creditCard"]["expirationDate"] = '2019/03';

	switch ( $pagar->tipo ) {
		case 'tarjeta':

			$charge = ""; 
			$error = "";

			try {
				$charge = $payu->AutorizacionCaptura( $PayuP );		
	        } catch (Exception $e) {
				print_r($e);
	        }

			if ($charge != false) {

				if( $deposito["enable"] == "yes" ){
					$db->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = {$id_orden};");
				}else{
					$db->query("UPDATE wp_posts SET post_status = 'paid' WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
					$db->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = {$id_orden};");
				}

	            echo json_encode(array(
					"order_id" => $id_orden
				));

			    if( isset($_SESSION[$id_session] ) ){
			    	update_cupos( array(
				    	"servicio" => $_SESSION[$id_session]["servicio"],
				    	"tipo" => $parametros["pagar"]->tipo_servicio,
			    		"autor" => $parametros["pagar"]->cuidador,
				    	"inicio" => strtotime($_SESSION[$id_session]["fechas"]["inicio"]),
				    	"fin" => strtotime($_SESSION[$id_session]["fechas"]["fin"]),
				    	"cantidad" => $_SESSION[$id_session]["variaciones"]["cupos"]
				    ), "-");
					$_SESSION[$id_session] = "";
					unset($_SESSION[$id_session]);
				}

				update_cupos( array(
			    	"servicio" => $parametros["pagar"]->servicio,
			    	"tipo" => $parametros["pagar"]->tipo_servicio,
			    	"autor" => $parametros["pagar"]->cuidador,
			    	"inicio" => strtotime($parametros["fechas"]->inicio),
			    	"fin" => strtotime($parametros["fechas"]->fin),
			    	"cantidad" => $cupos_a_decrementar
			    ), "+");
    
				// include(__DIR__."/../emails/index.php");

	        }else{

	            echo json_encode(array(
					"error" => $id_orden,
					"tipo_error" => $error,
					"status" => "Error, pago fallido"
				));

	        }

		break;

		case 'tienda':
/*			
			$due_date = date('Y-m-d\TH:i:s', strtotime('+ 48 hours'));

			$chargeRequest = array(
			    'method' => 'store',
			    'amount' => (float) $pagar->total,
			    'description' => 'Tienda',
			    'order_id' => $id_orden,
			    'due_date' => $due_date
			);

			$charge = $customer->charges->create($chargeRequest);

			$pdf = $OPENPAY_URL."/paynet-pdf/".$MERCHANT_ID."/".$charge->payment_method->reference;

			$db->query("UPDATE wp_posts SET post_status = 'wc-on-hold' WHERE ID = {$id_orden};");
			$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_openpay_pdf', '{$pdf}');");
			$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_openpay_tienda_vence', '{$due_date}');");

				echo json_encode(array(
					"user_id" => $customer->id,
				"pdf" => $pdf,
				"barcode_url"  => $charge->payment_method->barcode_url,
				"order_id" => $id_orden
			));
	    
		    if( isset($_SESSION[$id_session] ) ){
		    	update_cupos( array(
			    	"servicio" => $_SESSION[$id_session]["servicio"],
			    	"tipo" => $parametros["pagar"]->tipo_servicio,
		    		"autor" => $parametros["pagar"]->cuidador,
			    	"inicio" => strtotime($_SESSION[$id_session]["fechas"]["inicio"]),
			    	"fin" => strtotime($_SESSION[$id_session]["fechas"]["fin"]),
			    	"cantidad" => $_SESSION[$id_session]["variaciones"]["cupos"]
			    ), "-");
				$_SESSION[$id_session] = "";
				unset($_SESSION[$id_session]);
			}

			update_cupos( array(
		    	"servicio" => $parametros["pagar"]->servicio,
		    	"tipo" => $parametros["pagar"]->tipo_servicio,
		    	"autor" => $parametros["pagar"]->cuidador,
		    	"inicio" => strtotime($parametros["fechas"]->inicio),
		    	"fin" => strtotime($parametros["fechas"]->fin),
		    	"cantidad" => $cupos_a_decrementar
		    ), "+");

			include(__DIR__."/../emails/index.php");
*/
		break;

	}
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
 