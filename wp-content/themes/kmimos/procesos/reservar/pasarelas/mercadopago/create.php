<?php
	# Parametros
	extract($_POST);
	$tamanios = [
		'pequenos' => "Masc. Peq.",
		'medianos' => "Masc. Med.",
		'grandes'  => "Masc. Grd.",
		'gigantes' => "Masc. Gig.",
	];
	$parametros_label = array(
		"pagar",
		"tarjeta",
		"fechas",
		"cantidades",
		"transporte",
		"adicionales",
		"cupones",
	);
  	$info = explode("===", $info);
	$parametros = array();
	foreach ($info as $key => $value) {
		if( array_key_exists($key, $parametros_label) ){
			$parametros[ $parametros_label[ $key ] ] = json_decode( str_replace('\"', '"', $value) );
		}
	}
	extract($parametros);

	$data['ruta'] = $ruta.'/reservar/validar-pago/?p=mercadopago';

	# Variables
	$descripcion = ucfirst($pagar->tipo_servicio)." - ".$pagar->name_servicio;
	$noches = $fechas->duracion;
	$total = $pagar->total; 
	$total_mascotas = 0;
	$descuento = 0;
	$descuento = ( isset($pagar->fee) && $pagar->fee>0 ) ? $pagar->fee : 0 ;
    foreach ($cupones as $key => $value) { $descuento += $value[1]; }

    # Crear solicitud de pago Mercadopago
	$res = ['status' => 'ERROR', 'links' => '' ];

	# Servicios ( Items )
	$items = [];
	// Servicios
	foreach ($cantidades as $key => $value) {	
		if( count($value) > 1 ){
			if( $value[0] > 0 ){
				$obj = new MercadoPago\Item();
				$obj->id = $pagar->servicio ."-". ucfirst($key);
				$obj->title = $value[0] ." ". $tamanios[$key] . " x " . $value[1]; 
				$obj->description = $value[0] ." ". $tamanios[$key] . " x " . $value[1];
				$obj->currency_id = 'MXN';
				$obj->quantity = $value[0];
				$obj->unit_price = $value[1] * $noches;
				$items[] = $obj;
				$total_mascotas += (int) $value[0];
			}			
		}		
	}
	// Transporte
	if( !empty($transporte) ){	
		$obj = new MercadoPago\Item();
		$obj->id = "Transporte";
		$obj->title = $transporte[0]; 
		$obj->description = $transporte[0];
		$obj->currency_id = 'MXN';
		$obj->quantity = 1;
		$obj->unit_price = $transporte[1];
		$items[] = $obj;
	}
	// Adicionales
	$item_adicional = [
		'bano' => 'Baño',
        'corte' => 'Corte',
        'acupuntura' => 'Acupuntura',
        'limpieza_dental' => 'Limpieza dental',
        'visita_al_veterinario' => 'Visita al veterinario',
	];
	foreach ($adicionales as $key => $value) {	
		if( $value > 0 ){
			$adicional_desc = ( array_key_exists($key, $item_adicional))? $item_adicional[$key]: str_replace('_', '', $key);
			$obj = new MercadoPago\Item();
			$obj->id = "Adicional-". ucfirst($adicional_desc);
			$obj->title = $adicional_desc; 
			$obj->description = $adicional_desc;
			$obj->currency_id = 'MXN';
			$obj->quantity = $total_mascotas;
			$obj->unit_price = $value;
			$items[] = $obj;
		}		
	}

	# Create a payer object ( falta )
	$cliente = (object) $cliente;
	$payer = new MercadoPago\Payer();
	$payer->email = $cliente->email;
	$payer->name = $cliente->name;
	$payer->surname = $cliente->lastname;
	$payer->date_created = "2018-06-02T12:58:41.425-04:00";

	# Payment
	$payment = new MercadoPago\Payment();
	$payment->site_id = 'MLM';
	$payment->installments = 1;
	$payment->capture = true;
	$payment->callback_url = $data['ruta'] . "&t=callback";
	$payment->notification_url = $data['ruta'] . "&t=notification";
	$payment->order = [
		'type' => 'mercadopago',
		'id' => '234',
	];

	# Preference
	$preference = new MercadoPago\Preference();
	$preference->items = $items;
	$preference->external_reference = $id_orden;
	$preference->payer = $payer;
	$preference->binary_mode = true;
	$preference->back_urls = array(
	    "success" => $data['ruta'] . "&t=success",
	    "failure" => $data['ruta'] . "&t=failure",
	    "pending" => $data['ruta'] . "&t=pending",
	);
	$preference->payment_methods = array(
	  "excluded_payment_methods" => array( ),
	  "installments" => 1
	);
	$preference->auto_return = "approved";
	$preference->save();
	# Respuesta
	if( $preference->init_point != '' ){
		$res = ['status'=>'CREATED', 'links'=>$preference->init_point, 'response'=>$preference];
	}else{
		$res = ['status'=>'ERROR', 'links'=>'', 'response'=>''];
	}
?>