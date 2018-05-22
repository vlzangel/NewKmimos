<?php

	require_once('funciones.php');

	$hoy = date('Y-m-d');
	if( isset($_GET['d']) && !empty($_GET['d']) ){
		$hoy = $_GET['d'];
	}

	$ordenes = getOrdenes( $hoy, $hoy );

	/* ************************************* */
	// Estructura de los datos
	/* ************************************* */
	$data =[
		"mascotas_total" => 0,
		"noches" => [
			"total" => 0,	// multiplicar por numero de mascotas
			"numero" => 0,  // sin incluir numero de mascotas
		],
		"ventas" => [
				"cant" => 0,
			"tipo" => [],
				"estatus" => [],
				"tipo_pago" => ['Pago_Total'=>0],
				"forma_pago" => [],
				"costo" => [],
		]
	];

	foreach ($ordenes['rows'] as $key => $orden) {

  		/* ******************************************* */
  		// Buscar datos 
  		/* ******************************************* */
  			# Metadatos de Orden
  				$meta_orden = unserialize($orden['metadata']);

			# Metadatos de la ventas
				$items = getItems($orden['id']);

			# cargar forma de pago 
				$forma_pago = ( isset($meta_orden['tipo_pago']) )? $meta_orden['tipo_pago'] : 'otro';

			# cargar estatus de la orden
				$estatus = $orden['status']; 
					
  		/* ******************************************* */
  		// Agregar datos 
  		/* ******************************************* */
  			// agregar tipo de pago
				$data["ventas"]['tipo_pago']['Pago_Total'] += 1;

  			// agregar cantidad de ventas
				$data["ventas"]['cant'] += 1;

  			// agregar forma de pago
				if( isset($data["ventas"]['forma_pago'][$forma_pago]) ){
					$data["ventas"]['forma_pago'][$forma_pago] += 1;
				}else{
					$data["ventas"]['forma_pago'][$forma_pago] = 1;
				}

  			// agregar estatus 
				if( isset($data["ventas"]['estatus'][$estatus]) ){
					$data["ventas"]['estatus'][$estatus] += 1;
				}else{
					$data["ventas"]['estatus'][$estatus] = 1;
				}

			// agregar costos de la orden por estatus
				if( isset($data["ventas"]['costo'][$estatus]) ){
					$data["ventas"]['costo'][$estatus] += $orden['total'];
				}else{
					$data["ventas"]['costo'][$estatus] = $orden['total'];
				}

			// agregar tipo de compra
				foreach ($items['rows'] as $key => $item) {
					$meta = unserialize(preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $item['data']));
					if( isset($meta['plan']) ){
						$plan = $meta['plan'];
						if( isset($data['ventas']['tipo'][$plan]) ){
							$data['ventas']['tipo'][$plan] += 1;
						}else{
							$data['ventas']['tipo'][$plan] = 1;
						}						
					}
				}

	}

	/* ******************************************* */
	// Guardar Datos 
	/* ******************************************* */
			save( 'ventas', $hoy, $data );
	echo '<pre>'; 
		if( $data['ventas']['cant'] > 0 ){
		}
	echo '</pre>';

// Recompra es un servicio por separado
