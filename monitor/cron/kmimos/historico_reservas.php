<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
 
// ************************************* **
// Estructurar datos
// ************************************* **
	require_once('funciones.php');


	$hoy = date('Y-m-d');
	if( isset($_GET['d']) && !empty($_GET['d']) ){
		$hoy = $_GET['d'];
	}

	$f = new funciones();

	// total de clientes
	$clientes_nuevos = $f->getTotalClientes( $hoy, $hoy );
	$total_clientes = $f->getTotalClientes( '2000-01-01', $hoy );

	$reservas = $f->getReservas( $hoy, $hoy );

	// Resultado final
	$data =[
		'numero_reserva' => 0,
		'flash' => 0,
		'estatus' => 0,
		'fecha' => '',
		'reserva_inicio' => '',
		'reserva_fin' => '',
		'noches' => 0,
		'mascotas_cantidad' => 0,
		'noches_total' => 0,
		'cliente_email' => '',
		'cuidador_email' => '',
		'servicio_principal' => '',
		'servicio_adicional' => '',
		'forma_pago' => '',
		'total_pago' => 0,
		'monto_pagado' => 0,
		'monto_remanente' => 0,
		'descuento' => 0,
		'descuento_porcentaje' => 0,
		'observaciones' => '',
	];

echo '<pre>';

	if( !empty($reservas) ){
		$razas = $f->getRazas();
		foreach ($reservas as $key => $reserva) {
	  		// ******************************************* **
	  		// Buscar datos 
	  		// ******************************************* **
		
		  		# MetaDatos del Cuidador
			  		$meta_cuidador = $f->getMetaCuidador($reserva['cuidador_id']);

		  		# MetaDatos del Cliente
			  		$cliente = $f->getMetaCliente($reserva['cliente_id']);

			  	# Datos Cliente
			  		$cliente_data = $f->getUserBy( $reserva['cliente_id'], 'id' );
		  			$cliente_email = '';
			  		if(isset($cliente_data[0]['user_email'])){
			  			$cliente_email = $cliente_data[0]['user_email'];
			  		}
				# Datos Cuidador
			  		$cuidador_email = $f->getUserBy( $reserva['cuidador_id'], 'id' );
			  		if(isset($cuidador_email[0]['user_email'])){
			  			$cuidador_email = $cuidador_email[0]['user_email'];
			  		}
		  		# MetaDatos del Reserva
			  		$meta_reserva = $f->getMetaReserva($reserva['nro_reserva']);

		  		# MetaDatos del Pedido
			  		$meta_Pedido = $f->getMetaPedido($reserva['nro_pedido']);

		  		# Mascotas del Cliente
			  		$mypets = $f->getMascotas($reserva['cliente_id']); 

		  		# Estado y Municipio del cuidador
			  		$ubicacion = $f->get_ubicacion_cuidador($reserva['cuidador_id']);

		  		# Servicios de la Reserva
			  		$services = $f->getServices($reserva['nro_reserva']);

		  		# Status
			  		$estatus = $f->get_status(
			  			$reserva['estatus_reserva'], 
			  			$reserva['estatus_pago'], 
			  			$meta_Pedido['_payment_method'],
			  			$reserva['nro_reserva'] // Modificacion Ángel Veloz
			  		);

			  	# mascotas
			  		$pets_nombre = array();
			  		$pets_razas  = array();
			  		$pets_edad	 = array();

					foreach( $mypets as $pet_id => $pet) { 
						$pets_nombre[] = $pet['nombre'];
						$pets_razas[] = $razas[ $pet['raza'] ];
						$pets_edad[] = $pet['edad'];
					} 

				# numero de noches
					$nro_noches = $f->dias_transcurridos(
							$f->date_convert($meta_reserva['_booking_end'], 'd-m-Y'), 
							$f->date_convert($meta_reserva['_booking_start'], 'd-m-Y') 
						);					
					if(!in_array('hospedaje', explode("-", $reserva['post_name']))){
						$nro_noches += 1;
						
					}

 				# servicio Flash
					$flash = 0;
					if( $meta_reserva['_booking_flash'] == "SI" ){
						$flash = 1;
					}

				# estatus de la reserva
					if( isset($meta_reserva["modificacion_de"]) || isset($meta_reserva["reserva_modificada"]) ){
						switch ( $estatus ) {
							case 'Modificado':
								if( $meta_reserva["modificacion_de"] != "" && $meta_reserva["reserva_modificada"] != "" ){
									$estatus = 'Modificada-I';
								}else{
									if( $meta_reserva["reserva_modificada"] != "" ){
										$estatus = 'Modificada-O';
									}
									if( $meta_reserva["modificacion_de"] != "" ){
										$estatus = 'Modificada-F';
									}
								}
							break;
							case 'Confirmado':
								if( $meta_reserva["modificacion_de"] != "" ){
									// $estatus = 'Modificada-F';
								}
							break;
						}
					}

				# forma de pago
					$forma_pago = '';
					if( !empty($meta_Pedido['_payment_method_title']) ){
						$forma_pago = $meta_Pedido['_payment_method_title']; 
					}else{
						if( !empty($meta_reserva['modificacion_de']) ){
							$forma_pago = 'Saldo a favor' ; 
						}else{
							$forma_pago = 'Saldo a favor y/o cupones'; 
						}
					} 					

				# servicios
					$servicios_adicionales = '';
					if( !empty($services) ){					
						$servicios = [];
						foreach( $services as $service ){
							$servicios[] = str_replace("(precio por mascota)", "", $service);
							//$servicios[] = str_replace("Servicios Adicionales", "", $service['servicio']); 
						}
						if( !empty($servicios) ){
							$servicios_adicionales = serialize($servicios);
						}
					}

				# Tipo de pago
					$deposito = $f->select("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = {$meta_reserva['_booking_order_item_id']} AND meta_key = '_wc_deposit_meta' ");
					if( isset($deposito[0]['meta_value']) ){					
						$deposito = unserialize($deposito[0]['meta_value']);
					}
					if( $deposito["enable"] == "yes" ){
						$deposito = "Pago 20%";
					}else{
						$deposito = "Pago Total";
					}

	  		// ******************************************* **
	  		// Agregar datos 
	  		// ******************************************* **

				$data['numero_pedido'] = $reserva['nro_pedido'];
				$data['numero_reserva'] = $reserva['nro_reserva'];
				$data['flash'] = $flash;
				$data['estatus'] = $estatus;
				$data['fecha'] = $reserva['fecha_solicitud'];
				$data['reserva_inicio'] = $f->date_convert($meta_reserva['_booking_start'], 'Y-m-d', true);
				$data['reserva_fin'] = $f->date_convert($meta_reserva['_booking_end'], 'Y-m-d', true);
				$data['noches'] = $nro_noches;
				$data['mascotas_cantidad'] = $reserva['nro_mascotas'];
				
				$data['noches_total'] = $nro_noches * $reserva['nro_mascotas'];
				$data['cliente_email'] = $cliente_email;
				$data['cuidador_email'] = $cuidador_email;
				$data['user_referred'] = $cliente['user_referred'];
				
				$data['producto_id'] = $reserva['producto_id'];
				$data['servicio_principal'] = $reserva['producto_title'];

				$data['servicio_adicional'] = $servicios_adicionales;

				$data['pago_estatus'] = $reserva['estatus_pago'];
				$data['tipo_pago'] = $deposito;
				$data['forma_pago'] = $forma_pago;
				$data['total_pago'] = $meta_reserva['_booking_cost'];
				$data['monto_pagado'] = $meta_Pedido['_order_total'];
				$data['monto_remanente'] = $meta_Pedido['_wc_deposits_remaining'];

				$data['descuento'] = 0;
				$data['descuento_porcentaje'] = 0;
				$data['observaciones'] = "";
		

	  		// ******************************************* **
	  		// Guardar datos
	  		// ******************************************* **
			if( $data['numero_reserva'] > 0 ){
				
				if( $f->save_reservas( $data ) ){
					echo '<br>Datos guardados';
				}else{
					echo '<br>Datos no guardados';
				}

			}else{
				echo '<br>sin registro';
			}
		}
	}

// ******************************************* **
// Guardar Datos Base
// ******************************************* **
 


