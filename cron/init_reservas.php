<?php
    session_start();

    require('../wp-load.php');
	require_once('../wp-content/plugins/kmimos/dashboard/core/ControllerReservas.php');

    date_default_timezone_set('America/Mexico_City');
    
    $_desde = date("Y-m-d", strtotime("-30 day") );
	$_hasta = date("Y-m-d");

    $reservas = getReservas($_desde, $_hasta);

    $all_reservas = [];

    foreach( $reservas as $reserva ){ 

  		# MetaDatos del Cuidador
  		$meta_cuidador = getMetaCuidador($reserva->cuidador_id);
  		# MetaDatos del Cliente
  		$cliente = getMetaCliente($reserva->cliente_id);

  		# Recompra 12 Meses
  		$cliente_n_reserva = getCountReservas($reserva->cliente_id, "12");
  		if(array_key_exists('rows', $cliente_n_reserva)){
	  		foreach ($cliente_n_reserva["rows"] as $value) {
  				$recompra_12M = ($value['cant']>1)? "SI" : "NO" ;
	  		}
	  	}
  		# Recompra 1 Meses
  		$cliente_n_reserva = getCountReservas($reserva->cliente_id, "1");
  		if(array_key_exists('rows', $cliente_n_reserva)){
	  		foreach ($cliente_n_reserva["rows"] as $value) {
  				$recompra_1M = ($value['cant']>1)? "SI" : "NO" ;
	  		}
	  	}
  		# Recompra 3 Meses
  		$cliente_n_reserva = getCountReservas($reserva->cliente_id, "3");
  		if(array_key_exists('rows', $cliente_n_reserva)){
	  		foreach ($cliente_n_reserva["rows"] as $value) {
  				$recompra_3M = ($value['cant']>1)? "SI" : "NO" ;
	  		}
	  	}
  		# Recompra 6 Meses
  		$cliente_n_reserva = getCountReservas($reserva->cliente_id, "6");
  		if(array_key_exists('rows', $cliente_n_reserva)){
	  		foreach ($cliente_n_reserva["rows"] as $value) {
  				$recompra_6M = ($value['cant']>1)? "SI" : "NO" ;
	  		}
	  	}

  		# MetaDatos del Reserva
  		$meta_reserva = getMetaReserva($reserva->nro_reserva);
  		# MetaDatos del Pedido
  		$meta_Pedido = getMetaPedido($reserva->nro_pedido);
  		# Mascotas del Cliente
  		$mypets = getMascotas($reserva->cliente_id); 
  		# Estado y Municipio del cuidador
  		$ubicacion = get_ubicacion_cuidador($reserva->cuidador_id);
  		# Servicios de la Reserva
  		$services = getServices($reserva->nro_reserva);
  		# Status
  		$estatus = get_status(
  			$reserva->estatus_reserva, 
  			$reserva->estatus_pago, 
  			$meta_Pedido['_payment_method'],
  			$reserva->nro_reserva // Modificacion Ángel Veloz
  		);

  		if($estatus['addTotal'] == 1){
			$total_a_pagar += currency_format($meta_reserva['_booking_cost'], "");
	  		$total_pagado += currency_format($meta_Pedido['_order_total'], "", "", ".");
	  		$total_remanente += currency_format($meta_Pedido['_wc_deposits_remaining'], "", "", ".");
  		}

  		$pets_nombre = array();
  		$pets_razas  = array();
  		$pets_edad	 = array();

		foreach( $mypets as $pet_id => $pet) { 
			$pets_nombre[] = $pet['nombre'];
			$pets_razas[] = $razas[ $pet['raza'] ];
			$pets_edad[] = $pet['edad'];
		} 

  		$pets_nombre = implode("<br>", $pets_nombre);
  		$pets_razas  = implode("<br>", $pets_razas);
  		$pets_edad	 = implode("<br>", $pets_edad);

		$nro_noches = dias_transcurridos(
				date_convert($meta_reserva['_booking_end'], 'd-m-Y'), 
				date_convert($meta_reserva['_booking_start'], 'd-m-Y') 
			);					
		if(!in_array('hospedaje', explode("-", $reserva->post_name))){
			$nro_noches += 1;
			
		}


		$Day = "";
		$list_service = [ 'hospedaje' ]; // Excluir los servicios del Signo "D"
		$temp_option = explode("-", $reserva->producto_name);
		if( count($temp_option) > 0 ){
			$key = strtolower($temp_option[0]);
			if( !in_array($key, $list_service) ){
				$Day = "-D";



			}
		}

		$flash = "";
		if( $meta_reserva['_booking_flash'] == "SI" ){
			$flash = '
				<i 
					class="fa fa-bolt" 
					aria-hidden="true"
					style="
						padding: 2px 4px;
					    border-radius: 50%;
					    background: #00c500;
					    color: #FFF;
					    margin-right: 2px;
					"
				></i> Flash
			';
		}

		if( isset($meta_reserva["modificacion_de"]) || isset($meta_reserva["reserva_modificada"]) ){
			switch ( $estatus['sts_corto'] ) {
				case 'Modificado':
					if( $meta_reserva["modificacion_de"] != "" && $meta_reserva["reserva_modificada"] != "" ){
						$estatus['sts_corto'] = 'Modificada-I';
					}else{
						if( $meta_reserva["reserva_modificada"] != "" ){
							$estatus['sts_corto'] = 'Modificada-O';
						}
						if( $meta_reserva["modificacion_de"] != "" ){
							$estatus['sts_corto'] = 'Modificada-F';
						}
					}
				break;
				case 'Confirmado':
					if( $meta_reserva["modificacion_de"] != "" ){
						// $estatus['sts_corto'] = 'Modificada-F';
					}
				break;
			}
		}

		$telf_cliente = array();
		if( $cliente["user_mobile"] != "" ){ $telf_cliente[] = $cliente["user_mobile"]; }
		if( $cliente["user_phone"] != "" ){ $telf_cliente[] = $cliente["user_phone"]; }

		$telf_cuidador = array();
		if( $meta_cuidador["user_mobile"] != "" ){ $telf_cuidador[] = $meta_cuidador["user_mobile"]; }
		if( $meta_cuidador["user_phone"] != "" ){ $telf_cuidador[] = $meta_cuidador["user_phone"]; }

		$servicios_adicionales = "";

		foreach( $services as $service ){ 

			$__servicio = $service->descripcion . $service->servicio;
			$__servicio = str_replace("(precio por mascota)", "", $__servicio); 
			$__servicio = str_replace("(precio por grupo)", "", $__servicio); 
			$__servicio = str_replace("Servicios Adicionales", "", $__servicio); 
			$__servicio = str_replace("Servicios de Transportación", "", $__servicio); 

			if( strlen(trim($__servicio)) != 7 ){
				$servicios_adicionales .= $__servicio."<br>";
			}
		}

		$metodo_pago = "";
		if( !empty($meta_Pedido['_payment_method_title']) ){
			$metodo_pago = $meta_Pedido['_payment_method_title']; 
		}else{
			if( !empty($meta_reserva['modificacion_de']) ){
				$metodo_pago = 'Saldo a favor' ; 
			}else{
				$metodo_pago = 'Saldo a favor y/o cupones'; 
			}
		}

		$deposito = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = {$meta_reserva['_booking_order_item_id']} AND meta_key = '_wc_deposit_meta' ");
		$deposito = unserialize($deposito);
		$tipo_pago = "";
		if( $deposito["enable"] == "yes" ){
			$tipo_pago = "Pago 20%";
		}else{
			$tipo_pago = "Pago Total";
		}

		$nos_conocio = ( empty($cliente['user_referred']) ) ? 'Otros' : $cliente['user_referred'];

		$cupones = get_cupones_reserva( $reserva->nro_reserva );

		$data = [
			$reserva->nro_reserva,
			$flash,
			$estatus['sts_corto'],
			$reserva->fecha_solicitud,

			$meta_reserva['_booking_start'],
			$meta_reserva['_booking_end'],

			$nro_noches . $Day,
			$reserva->nro_mascotas,
			$nro_noches * $reserva->nro_mascotas,

			"<a href='".get_home_url()."/?i=".md5($reserva->cliente_id)."'>".$cliente['first_name']." ".$cliente['last_name']."</a>",

			$wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$reserva->cliente_id),
			implode(", ", $telf_cliente),

			$recompra_1M,
			$recompra_3M,
			$recompra_6M,
			$recompra_12M,

			$nos_conocio,
			$pets_nombre,
			$pets_razas,
			$pets_edad,
			$meta_cuidador['first_name']." ".$meta_cuidador['last_name'],
			$wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$reserva->cuidador_id),
			implode(", ", $telf_cuidador),
			$reserva->producto_title,
			$servicios_adicionales,
			utf8_decode( $ubicacion['estado'] ),
			utf8_decode( $ubicacion['municipio'] ),
			$metodo_pago,
			$tipo_pago,
			$meta_reserva['_booking_cost'],
			$meta_Pedido['_order_total'],
			$meta_Pedido['_wc_deposits_remaining'],
			$cupones['kmimos'],
			$cupones['cuidador'],
			$cupones['total'],
			$reserva->nro_pedido,
			$estatus['sts_largo'],

		];

		$all_reservas[] = [
			$data,
			$data_sql
		];

		foreach ($data as $key => $value) {
			$data[ $key ] = str_replace("'", "\'", $value);
		}
		$campos = implode("','", $data);
		$SQL = "INSERT INTO reporte_reserva_new VALUES (NULL,'".$campos."', '', NULL, 0);";

		$wpdb->query( $SQL );

	} 

	/*
    echo "<pre>";
    	print_r( $all_reservas );
    echo "</pre>";
    */
?>