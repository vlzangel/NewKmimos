<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));

	include_once($raiz."/wp-load.php");

	date_default_timezone_set('America/Mexico_City');

	if( !isset($_SESSION)){ session_start(); }

	include_once($raiz."/vlz_config.php");
	include_once("../funciones/db.php");
	include_once("../funciones/config.php");
	include_once("../../lib/openpay/Openpay.php");

	include_once("reservar.php");

	$xdb = $db;
	$db = new db( new mysqli($host, $user, $pass, $db) );

	include_once("../funciones/generales.php");

	extract($_POST);

	$info = explode("===", $info);

	$parametros_label = array(
		"pagar",
		"tarjeta",
		"fechas",
		"cantidades",
		"transporte",
		"adicionales",
		"cupones",
	);

	$parametros = array();

	foreach ($info as $key => $value) {
		$parametros[ $parametros_label[ $key ] ] = json_decode( str_replace('\"', '"', $value) );
	}

	extract($parametros);

	$id_orden = 0;

	if( $pagar->id_fallida != 0 ){
		$id_orden = $pagar->id_fallida;
		$metodo = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_orden} AND meta_key = '_payment_method' ");
		if( $metodo != $pagar->tipo ){
			$tipos = array(
				"tienda" => "Tienda",
				"tarjeta" => "Tarjeta"
			);
			$db->get_var("UPDATE wp_postmeta SET meta_value = '{$pagar->tipo}' WHERE post_id = {$id_orden} AND meta_key = '_payment_method';");
			$db->get_var("UPDATE wp_postmeta SET meta_value = '{$tipos[$pagar->tipo]}' WHERE post_id = {$id_orden} AND meta_key = '_payment_method_title';");
		}
	}

	if( $pagar->reconstruir && $pagar->id_fallida != 0 ){
		$id_reserva = $db->get_var("SELECT ID FROM wp_posts WHERE post_parent = '{$id_orden}'");

		$db->query("DELETE FROM wp_posts WHERE ID IN ( '{$id_orden}', '{$id_reserva}' )");
		$db->query("DELETE FROM wp_postmeta WHERE post_id IN ( '{$id_orden}', '{$id_reserva}' )");
	}

	$informacion = serialize($parametros);

	$time = time();
    $hoy = date("Y-m-d H:i:s", $time);
    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $inicio = strtotime( $fechas->inicio );
    $fecha_formato = date('d', $inicio)." ".$meses[date('n', $inicio)-1]. ", ".date('Y', $inicio) ;

    $descuentos = 0;
    foreach ($cupones as $key => $value) {
    	$descuentos += $value[1];
    }

    if( $pagar->metodo != "deposito" ){
	    $deposito = array(
			"enable" => "no",
			"total" => $pagar->total
	    );

    }else{

	    /*$pre17 = ( $pagar->total - ( $pagar->total / 1.2) );
		$pagoCuidador = ( $pagar->total / 1.2);
		if( $pre17 <= $descuentos ){
			if( $pre17 < $descuentos ){
				$reciduo = $pre17-$descuentos;
				$pagoCuidador += $reciduo;
			}
			$pre17 = 0;
		}else{
			$pre17 -= $descuentos;
		}*/

		$pre17 = $pagar->deposito;
		$pagoCuidador = $pagar->pagoCuidador;

	    $deposito = array(
	    	"deposit" => $pre17,
			"enable" => "yes",
			"ratio" => 1,
			"remaining" => $pagoCuidador,
			"total" => $pagar->total
	    );
    }

    $tamanos = array(
    	"pequenos" => "Pequeñas",
    	"medianos" => "Medianas",
    	"grandes"  => "Grandes",
    	"gigantes" => "Gigantes"
    );

    $mascotas = array(); $num_mascotas = array();
    foreach ($cantidades as $key => $value) {
    	if( $key != "cantidad" ){
	    	if( is_array($value) ){
	    		if( $value[0] > 0 ){
	    			$mascotas[ "Mascotas ".$tamanos[ $key ] ] = $value[0];
	    			if( $value[0]+0 > 0 ){
		    			$mascota = $db->get_var("SELECT ID FROM wp_posts WHERE post_type = 'bookable_person' AND post_name LIKE '%{$key}%' AND post_parent = '{$pagar->servicio}' ");
		    			$num_mascotas[$mascota] = $value[0];
	    			}
	    		}
	    	}
    	}
    }

    $num_mascotas = serialize($num_mascotas);

    $diaNoche = "d&iacute;a";
	if( $pagar->tipo_servicio == "hospedaje" ){
		$diaNoche = "Noche";
	}

    if( $fechas->duracion > 1 ){
    	$fechas->duracion .= " ".$diaNoche."s";
    }else{
    	$fechas->duracion .= " ".$diaNoche;
    }

    function generarAdicionales($adicionales){
    	$resultado = array();
    	foreach ($adicionales as $key => $value) {
    		if( $value > 0 ){
	    		switch ($key) {
			        case 'bano':
			            $resultado["Baño (precio por mascota)"] = "Servicios Adicionales (precio por mascota) (&#36;".$value.")";
			        break;
			        
			        case 'corte':
			            $resultado["Corte de Pelo y Uñas (precio por mascota)"] = "Servicios Adicionales (precio por mascota) (&#36;".$value.")";
			        break;
			        
			        case 'visita_al_veterinario':
			            $resultado["Visita al Veterinario (precio por mascota)"] = "Servicios Adicionales (precio por mascota) (&#36;".$value.")";
			        break;
			        
			        case 'limpieza_dental':
			            $resultado["Limpieza Dental (precio por mascota)"] = "Servicios Adicionales (precio por mascota) (&#36;".$value.")";
			        break;
			        
			        case 'acupuntura':
			            $resultado["Acupuntura (precio por mascota)"] = "Servicios Adicionales (precio por mascota) (&#36;".$value.")";
			        break;
			    }
    		}
	    }
    	return $resultado;
    }

    $adicionales = generarAdicionales($adicionales);

    $titulo_pago = "Tarjeta";
    if( $pagar->tipo == "tienda" ){
    	$titulo_pago = "Tienda";
    }
    if( $pagar->tipo == "Saldo y/o Descuentos" ){
    	$titulo_pago = "Saldo y/o Descuentos";
    }

    $data_reserva = array(
		"servicio" 				=> $pagar->servicio,
		"titulo_servicio" 		=> $pagar->name_servicio,
		"cliente" 				=> $pagar->cliente,
		"cuidador" 				=> $pagar->cuidador,
		"hoy" 					=> $hoy,
		"fecha_formato" 		=> $fecha_formato,
		"token" 				=> time(),

		"inicio" 				=> date("Ymd", strtotime( $fechas->inicio ) ),
		"fin" 					=> date("Ymd", strtotime( $fechas->fin ) ),

		"checkin" 				=> $fechas->checkin,
		"checkout" 				=> $fechas->checkout,

		"monto" 				=> $pagar->total,
		"num_mascotas" 			=> $num_mascotas,
		"metodo_pago" 			=> $pagar->tipo,
		"metodo_pago_titulo" 	=> $titulo_pago,
		"moneda" 				=> "MXN",
		"duracion_formato" 		=> $fechas->duracion,
		"mascotas" 				=> $mascotas,
		"adicionales" 			=> $adicionales,
		"transporte" 			=> $transporte,
		"deposito" 				=> $deposito,
		"status_reserva" 		=> "unpaid",
		"status_orden" 			=> "wc-pending",

		"descuento"				=> $descuentos,
		"pre17"					=> $pre17,
		"pagoCuidador"			=> $pagoCuidador,
	);

    $data_cliente = array();
    $xdata_cliente = $db->get_results("
	SELECT 
		meta_key, meta_value 
	FROM 
		wp_usermeta 
	WHERE
		user_id = {$pagar->cliente} AND (
			meta_key = 'first_name' OR
			meta_key = 'last_name' OR
			meta_key = 'user_mobile' OR
			meta_key = 'user_phone' OR
			meta_key = 'billing_email' OR
			meta_key = 'billing_address_1' OR
			meta_key = 'billing_address_2' OR
			meta_key = 'billing_city' OR
			meta_key = 'billing_state' OR
			meta_key = 'billing_postcode' OR
			meta_key = '_openpay_customer_id'
		)"
    );

    foreach ($xdata_cliente as $key => $value) {
    	$data_cliente[ $value->meta_key ] = utf8_encode($value->meta_value);
    }

    $old_reserva = 0;
	$id_session = 'MR_'.$pagar->servicio."_".md5($pagar->cliente);

	if( ( $pagar->reconstruir && $pagar->id_fallida != 0) || ( $pagar->id_fallida == 0 ) ){
		
		$reservar = new Reservas($db, $data_reserva);
	    $id_orden = $reservar->new_reserva();
	    $reservar->aplicarCupones($id_orden, $cupones);

	    if( isset($_SESSION[$id_session] ) ){
			$new_reserva = $reservar->data["id_reserva"];
			$old_reserva = $_SESSION[$id_session]["reserva"];

			$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$new_reserva}, 'modificacion_de', '{$old_reserva}');");
			$db->query("INSERT INTO wp_postmeta VALUES (NULL, {$old_reserva}, 'reserva_modificada', '{$new_reserva}');");

			$old_order = $db->get_var("SELECT post_parent FROM wp_posts WHERE ID = '{$old_reserva}' ");
			$db->query("UPDATE wp_posts SET post_status = 'modified' WHERE ID IN ( '{$old_reserva}', '{$old_order}' );");
		}

	}

    $cupos_a_decrementar = $parametros["cantidades"]->cantidad;

    if( $pre17 == 0 && $deposito["enable"] == "yes"  ){
    	$db->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = {$id_orden};");
    	echo json_encode(array(
			"order_id" => $id_orden
		));

		update_cupos( array(
	    	"servicio" => $parametros["pagar"]->servicio,
	    	"tipo" => $parametros["pagar"]->tipo_servicio,
	    	"autor" => $parametros["pagar"]->cuidador,
	    	"inicio" => strtotime($parametros["fechas"]->inicio),
	    	"fin" => strtotime($parametros["fechas"]->fin),
	    	"cantidad" => $cupos_a_decrementar
	    ), "+");
	    
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

		include(__DIR__."/emails/index.php");

		exit;
    }

    if( $pre17 == 0 && $deposito["enable"] == "yes"  ){
    	$db->query("UPDATE wp_posts SET post_status = 'wc-partially-paid' WHERE ID = {$id_orden};");
    	echo json_encode(array(
			"order_id" => $id_orden
		));

		update_cupos( array(
	    	"servicio" => $parametros["pagar"]->servicio,
	    	"tipo" => $parametros["pagar"]->tipo_servicio,
	    	"autor" => $parametros["pagar"]->cuidador,
	    	"inicio" => strtotime($parametros["fechas"]->inicio),
	    	"fin" => strtotime($parametros["fechas"]->fin),
	    	"cantidad" => $cupos_a_decrementar
	    ), "+");
	    
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

		include(__DIR__."/emails/index.php");
		exit;
    }

    if( $pagar->total <= $descuentos ){
    	$db->query("UPDATE wp_posts SET post_status = 'paid' WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
		$db->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = {$id_orden};");
    	echo json_encode(array(
			"order_id" => $id_orden
		));

		update_cupos( array(
	    	"servicio" => $parametros["pagar"]->servicio,
	    	"tipo" => $parametros["pagar"]->tipo_servicio,
	    	"autor" => $parametros["pagar"]->cuidador,
	    	"inicio" => strtotime($parametros["fechas"]->inicio),
	    	"fin" => strtotime($parametros["fechas"]->fin),
	    	"cantidad" => $cupos_a_decrementar
	    ), "+");

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
	    
		include(__DIR__."/emails/index.php");
		exit;
    }

 
    if( $pagar->metodo != "deposito" ){
	    $pagar->total -= $descuentos;
    }else{
	    $pagar->total = $pre17;
    }
 

	if( $pagar->deviceIdHiddenFieldName != "" ){

		$openpay = Openpay::getInstance($MERCHANT_ID, $OPENPAY_KEY_SECRET);
		Openpay::setProductionMode( ($OPENPAY_PRUEBAS == 0) );

		foreach ($data_cliente as $key => $value) {
			if( $data_cliente[$key] == "" ){
				$data_cliente[$key] = "_";
			}
		}

		$nombre 	= $data_cliente["first_name"];
		$apellido 	= $data_cliente["last_name"];
		$email 		= $pagar->email;
		$telefono 	= $data_cliente["user_mobile"];
		$direccion 	= $data_cliente["billing_address_1"];
		$estado 	= $data_cliente["billing_state"];
		$municipio 	= $data_cliente["billing_city"];
		$postal  	= $data_cliente["billing_postcode"];

		$cliente_openpay = $data_cliente["_openpay_customer_id"];

		if( $id_invalido ){ $cliente_openpay = ""; }

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
					'line1' 		=> "Mexico ",
					'state' 		=> "DF",
					'city' 			=> "Mexico",
					'postal_code' 	=> "10100",
					'country_code' 	=> 'MX'
				)
		   	);
		   	$customer = $openpay->customers->add($customerData);

		   	$openpay_customer_id = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$pagar->cliente} AND meta_key = '_openpay_customer_id'");
		   	if( $openpay_customer_id != false ){
		   		$db->query("UPDATE wp_usermeta SET meta_value = '{$customer->id}' WHERE user_id = {$pagar->cliente} AND meta_key = '_openpay_customer_id';");
		   	}else{
		   		$db->query("INSERT INTO wp_usermeta VALUES (NULL, {$pagar->cliente}, '_openpay_customer_id', '{$customer->id}');");
		   	}
		   	
	   	}

	   	switch ( $pagar->tipo ) {
	   		case 'tarjeta':
	   			
	   			if( $pagar->token != "" ){

					$chargeData = array(
					    'method' 			=> 'card',
					    'source_id' 		=> $pagar->token,
					    'amount' 			=> (float) $pagar->total,
					    'order_id' 			=> $id_orden,
					    'description' 		=> "Tarjeta",
					    'device_session_id' => $pagar->deviceIdHiddenFieldName
				    );

					$charge = ""; $error = "";

					try {
			            $charge = $customer->charges->create($chargeData);
			        } catch (Exception $e) {
			        	$error = $e->getErrorCode();
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
		    
						include(__DIR__."/emails/index.php");

			        }else{

			            echo json_encode(array(
							"error" => $id_orden,
							"tipo_error" => $error,
							"status" => "Error, pago fallido"
						));

			        }

	   			}else{
	   				echo json_encode(array(
						"Error" => "Sin tokens",
						"Data"  => $_POST
					));
	   			}

   			break;

	   		case 'tienda':
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

				include(__DIR__."/emails/index.php");

   			break;

	   	}

	}else{
		echo json_encode(array(
			"Error" => "Sin ID de dispositivo",
			"Data"  => $_POST
		));
	}

	exit();

?>