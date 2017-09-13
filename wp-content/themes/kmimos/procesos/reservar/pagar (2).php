<?php
	$path = dirname(dirname(__DIR__));

	include( $path."/app/config.php");
	include( $path."/app/db.php");
	include( $path."/app/servicios/reservar.php");
	include( $path."/app/lib/openpay/Openpay.php");

	extract($_POST);

	$data = explode("===", $_POST["carrito"]);
	$info = array();
	foreach ($data as $key => $value) {
		$info[] = json_decode($value);
	}

	extract($info);
	extract($data);

	$num_mascotas = 0;
	foreach ($info[2] as $key => $value) {
		if( count($value) > 0 ){
			$num_mascotas += $value[0];
		}		
	}

	$time = time();
    $hoy = date("Y-m-d H:i:s", $time);
    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $inicio = strtotime( $info[1]->inicio );
    $hoy_f = date('d', $inicio)." ".$meses[date('n', $inicio)-1]. ", ".date('Y', $inicio) ;

    if( $tipo_pago != "deposito" ){
	    $deposito = array(
			"enable" => "no"
	    );
    }else{
	    $deposito = array(
	    	"deposit" => ( $info[0]->total - ( $info[0]->total / 1.2) ),
			"enable" => "yes",
			"ratio" => 1,
			"remaining" => ( $info[0]->total / 1.2),
			"total" => $info[0]->total
	    );
    }

    // $deposito = unserialize( 'a:5:{s:6:"enable";s:3:"yes";s:7:"deposit";i:0;s:9:"remaining";i:960;s:5:"total";i:960;s:5:"ratio";d:1;}' );
    $tamanos = array(
    	"pequenos" => "Pequeñas",
    	"medianos" => "Medianas",
    	"grandes"  => "Grandes",
    	"gigantes" => "Gigantes"
    );
    $mascotas = array();

    foreach ($info[2] as $key => $value) {
    	if( is_array($value) ){
    		if( $value[0] > 0 ){
    			$mascotas[ "Mascotas ".$tamanos[ $key ] ] = $value[0];
    		}
    	}
    }

    if( $info[1]->duracion > 1 ){
    	$info[1]->duracion .= " Noches";
    }else{
    	$info[1]->duracion .= " Noche";
    }

	$data_reserva = array(
		"servicio" 				=> $info[0]->servicio,
		"titulo_servicio" 		=> $info[0]->titulo,
		"cliente" 				=> $info[0]->cliente,
		"cuidador" 				=> $info[0]->cuidador,
		"hoy" 					=> $hoy,
		"fecha_formato" 		=> $hoy_f,
		"token" 				=> time(),
		"inicio" 				=> date("Ymd", strtotime( $info[1]->inicio ) ),
		"fin" 					=> date("Ymd", strtotime( $info[1]->fin ) ),
		"monto" 				=> $info[0]->total,
		"num_mascotas" 			=> $num_mascotas,
		"metodo_pago" 			=> $metodo_pago,
		"metodo_pago_titulo" 	=> $metodo_pago,
		"moneda" 				=> "MXN",
		"duracion_formato" 		=> $info[1]->duracion,
		"mascotas" 				=> $mascotas,
		"deposito" 				=> $deposito,
		"status_reserva" 		=> "unpaid",
		"status_orden" 			=> "wc-pending"
	);

	$reservar = new Reservas($db, $data_reserva);

	$id_orden = $reservar->new_reserva();

	echo json_encode(array(
		"post"  => $_POST,
		"info"  => $info,
		"array"  => $data_reserva,
		"id_orden"  => $id_orden,
		"sql" => $reservar->sql
	));

	/*
	if( $deviceIdHiddenFieldName != "" ){

		

		$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);

		$nombre 	= "Angel";
		$apellido 	= "Veloz";
		$email 		= "vlzangel91@gmail.com";
		$telefono 	= "+584243128807";
		$direccion 	= "Ciudad de México";
		$estado 	= "Ciudad de México";
		$municipio 	= "Miguel Hidalgo";
		$postal  	= 11010;

	   	if( $cliente_openpay != "" ){
	   		$customer = $openpay->customers->get( $cliente_openpay );
	   	}else{
	   		$customerData = array(
				'name' 				=> $nombre,
				'last_name' 		=> $apellido,
				'email' 			=> $email,
				'requires_account' 	=> false,
				'phone_number' 		=> $telefono,
				'address' => array(
					'line1' 		=> $direccion,
					'state' 		=> $estado,
					'city' 			=> $municipio,
					'postal_code' 	=> $postal,
					'country_code' 	=> 'MX'
				)
		   	);
		   	$customer = $openpay->customers->add($customerData);
	   	}

	   	switch ( $metodo_pago ) {
	   		case 'tarjeta':
	   			
	   			if( $token_id != "" ){
	   				$cardDataRequest = array(
					    'holder_name' => $holder_name,
					    'card_number' => $card_number,
					    'cvv2' => $cvv2,
					    'expiration_month' => $expiration_month,
					    'expiration_year' => $expiration_year,
					    'device_session_id' => $deviceIdHiddenFieldName,
					    'address' => array(
					            'line1' => $customer->address->line1,
					            'line2' => $customer->address->line2,
					            'line3' => $customer->address->line3,
					            'postal_code' => $customer->address->postal_code,
					            'state' => $customer->address->state,
					            'city' => $customer->address->city,
					            'country_code' => 'MX'
					    )
					);

					$cardList = $customer->cards->getList( array() );

					$card = "";

					if( count($cardList) == 0 ){
						try {
				            $card = $customer->cards->add($cardDataRequest);
				        } catch (Exception $e) {
				          	print_r("Entro 1");
				          	print_r($e);
				        }
					}else{
						$no_existe = true;
						$card_num = substr($card_number, 0, 6)."XXXXXX".substr($card_number, -4);
						foreach ($cardList as $key => $card) {
							if( $card_num == $card->card_number ){
								$no_existe = false;
							}
						}
						if( $no_existe ){
							try {
					            $card = $customer->cards->add($cardDataRequest);
					        } catch (Exception $e) {
				          		print_r("Entro 2");
					          	print_r($e);
					        }
						}
					}

					$chargeData = array(
					    'method' 			=> 'card',
					    'source_id' 		=> $card->id,
					    'amount' 			=> (float) $info[0]->total,
					    'order_id' 			=> $id_orden,
					    'description' 		=> "Pago de pruebas",
					    'device_session_id' => $_POST["deviceIdHiddenFieldName"]
				    );

					$charge = "";

					try {
			            $charge = $customer->charges->create($chargeData);
			        } catch (Exception $e) {
		          		print_r("Entro 3");
			          	print_r($e);
			        }
					
	   				echo json_encode(array(
	   					"user_id" => $customer->id
					));

	   			}else{
	   				echo json_encode(array(
						"Error" => "Sin tokens",
						"Data"  => $_POST
					));
	   			}

   			break;

	   		case 'tienda':
	   			$due_date = date('Y-m-d\TH:i:s', strtotime('+ 24 hours'));

	   			$chargeRequest = array(
				    'method' => 'store',
				    'amount' => (float) $info[0]->total,
				    'description' => 'Cargo con tienda',
				    'order_id' => $id_orden,
				    'due_date' => $due_date
				);

				$charge = $customer->charges->create($chargeRequest);

   				echo json_encode(array(
   					"user_id" => $customer->id,
					"pdf" => "https://sandbox-dashboard.openpay.mx/paynet-pdf/".$MERCHANT_ID."/".$charge->payment_method->reference,
					"barcode_url"  => $charge->payment_method->barcode_url
				));

   			break;

	   	}

	}else{
		echo json_encode(array(
			"Error" => "Sin ID de dispositivo",
			"Data"  => $_POST
		));
	}
	*/

?>