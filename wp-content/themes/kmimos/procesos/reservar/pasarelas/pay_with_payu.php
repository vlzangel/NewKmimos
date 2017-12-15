<?php

	$PayU_file = realpath( dirname(dirname(dirname(__DIR__) ) )."/lib/payu/PayU.php" ) ;
	$tdc = realpath( dirname(dirname(dirname(__DIR__) ) )."/lib/payu/validarTDC.php" ) ;
	if( file_exists($PayU_file) && file_exists($tdc) ){
		include( $PayU_file );
		include( $tdc );
	}else{
	    echo json_encode(array(
			"error" => $id_orden,
			"tipo_error" => $error,
			"status" => "Error, Libreria no encontrada",
			"code" => $state
		));
		exit();
	}

	$PayuP = [];
	$PayuP['pais'] = ucfirst(  get_region( 'pais_cod_iso' ) );
	$PayuP['moneda'] = get_region( 'moneda_cod' );
	// -- Reserva
	$PayuP['id_orden'] = $id_orden.'_'.date('Ymd\THis');
	$PayuP['monto'] =  ceil( $pagar->total );
	// -- Clientes
	$PayuP['cliente']['ID'] = $pagar->cliente;
	$PayuP['cliente']['dni'] = '';
	$PayuP['cliente']['name'] = $nombre;
	$PayuP['cliente']['email'] = $email;
	$PayuP['cliente']['telef'] = $telefono;
	$PayuP['cliente']['calle1'] = $direccion;
	$PayuP['cliente']['calle2'] = 'sin datos';
	$PayuP['cliente']['ciudad'] = $ciudad;
	$PayuP['cliente']['estado'] = $estado;
	$PayuP['cliente']['pais'] = get_region('pais_cod_iso');
	$PayuP['cliente']['telef'] = $telefono;
	$PayuP['cliente']['postal'] = '000000';
	$PayuP["PayuDeviceSessionId"] = md5(session_id().microtime()); //$PayuDeviceSessionId;

	$payu = new PayU();
	switch ( $pagar->tipo ) {
		case 'tarjeta':

			$charge = ""; 
			$error = "";
			$state = "";
			$code = "";

			// -- Agregar Parametros Adicionales
/*
			$year_now = date("Y");
			$year_now = substr( $year_now, (strlen($tarjeta->anio)-4) );
*/			$year_now = '20'.$tarjeta->anio; // Temporal 
			 	
			$tdc = new fngccvalidator();
			$tdc_name = $tdc->CreditCard($tarjeta->numero, '', true);

			$PayuP["creditCard"]["name"] = $tarjeta->nombre;
			$PayuP["creditCard"]["number"] = $tarjeta->numero;
			$PayuP["creditCard"]["securityCode"] = $tarjeta->codigo;
			$PayuP["creditCard"]["payment_method"] = strtoupper($tdc_name['type']);
			$PayuP["creditCard"]["expirationDate"] = $year_now.'/'.$tarjeta->mes;

			try {
				$charge = $payu->AutorizacionCaptura( $PayuP );	
				if( $charge->code == 'SUCCESS' ){
					$state = $charge->transactionResponse->responseCode;
				}
	        } catch (Exception $e) {
				print_r($e);
	        }

			if ( $state == 'APPROVED' ) {

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
    
				include(__DIR__."/../emails/index.php");

	        }else{

	            echo json_encode(array(
					"error" => $id_orden,
					"tipo_error" => $error,
					"status" => "Error, pago fallido",
					"code" => $state
				));

	        }

		break;

		case 'tienda':

			$charge = ""; 
			$error = "";
			$pdf = "";
			$code = "";
			$state = "";

			// -- Calcular Fecha limite para pago en tienda
			$due_date = date('Y-m-d\TH:i:s', strtotime('+ 48 hours'));

			// -- Agregar Parametros Adicionales
			$PayuP["pais_cod_iso"] =  get_region('pais_cod_iso');
			$PayuP["paymentMethod"] =  strtoupper($pagar->tienda);
			$PayuP["expirationDate"] = $due_date;

			try {
				$charge = $payu->Autorizacion( $PayuP );
				if( $charge->code == 'SUCCESS' ){
					$pdf = $charge->transactionResponse->extraParameters->URL_PAYMENT_RECEIPT_PDF;
					$pdf .= '&extra=reference/'.$charge->transactionResponse->extraParameters->REFERENCE;
					$state = $charge->transactionResponse->responseCode;
				}			
	        } catch (Exception $e) {
				print_r($e);
	        }

	        if( $state == 'PENDING_TRANSACTION_CONFIRMATION' && !empty($pdf) ){
				$db->query("UPDATE wp_posts SET post_status = 'wc-on-hold' WHERE ID = {$id_orden};");
				$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_payu_pdf', '{$pdf}');");
				$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$id_orden}, '_payu_tienda_vence', '{$due_date}');");

				echo json_encode(array(
					"user_id" => $pagar->cliente,
					"pdf" => $pdf,
					"barcode_url"  => $charge->transactionResponse->extraParameters->REFERENCE,
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
			}else{
				 echo json_encode(array(
					"error" => $id_orden,
					"tipo_error" => $error,
					"status" => "Error, pago fallido",
					"code" => $state,
					"charge" =>$charge
				));
			}

		break;

	}






