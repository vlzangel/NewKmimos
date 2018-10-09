<?php
	session_start();

    date_default_timezone_set('America/Mexico_City');

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/wp-load.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/admin/backend/notas_creditos/lib/notas_creditos.php");
    global $wpdb;

	$total = 0;
	$detalle = [];
    extract($_POST);

	$tiene_nota_credito = $wpdb->get_var("
		SELECT id 
		FROM notas_creditos 
		WHERE reserva_id = ".$reserva_id 
	); 

	if( $tiene_nota_credito > 0 ){
		echo json_encode("['error'=>'SI', 'mensaje'=>'Posee una nota de credito']");
		exit();
	}

	$reserva = kmimos_desglose_reserva_data( $pedido_id, true );
	$inicio = date('Y-m-d',$reserva['servicio']['inicio']) ;


	// *************************************
	// Detalle de la Nota de Credito
	// *************************************

		// Servicio principal
		if( !empty($reserva['servicio']['variaciones']) && !empty($s_principal) ){	
			foreach( $reserva['servicio']['variaciones'] as $item ){ 
				$code = md5($item[1]);

				if( in_array($code, $s_principal) ){
					$noches = $_POST[ 'noches_'.$code ];	
					$prorrateo = $item[3] * $noches;
					if( $prorrateo > 0 ){				
						$detalle[] = [  
							'fecha' => $_POST[ 'hasta_'.$code ],
							'titulo'=> "{$item[0]} {$item[1]} x {$item[2]} x {$item[3]}",
							'cantidad'=> $item[0],
							'tamano'=> $item[1],
							'noches'=> $item[2],
							'costo' => $prorrateo,
							'precio_base' => $item[3],
							
						];
						$total += $prorrateo;
					}
				}
			}
		}

		//$servicios
		if( !empty($reserva['servicio']['adicionales']) && !empty($servicios) ){
			foreach( $reserva['servicio']['adicionales'] as $key => $item ){ 
				$code = md5($item[0]);

				if( in_array($code, $servicios) ){
					$monto = str_replace(',','.', str_replace('.', '', $item[2]));
					$monto *= $item[1];
					$detalle[] = [
						'titulo'=> "{$item[0]} - {$item[1]} x {$item[2]}",
						'costo' => $monto,
					];
					$total += $monto;
				}

			}
		}

		//transporte
		if( !empty($reserva['servicio']['transporte']) && !empty($transporte) ){
			foreach( $reserva['servicio']['transporte'] as $key => $item ){ 
				$code = md5($item[0]);

				if( in_array($code, $transporte) ){
					$monto = str_replace(',','.', str_replace('.', '', $item[3]));
					$detalle[] = [
						'titulo'=> "{$item[0]}",
						'costo' => $monto,
					];
					$total += $monto;
				}
			}
		}

		$_detalle = serialize($detalle);

	// *************************************
	// Nota de Credito - Cuidador
	// *************************************

		$sql_cuidador = "INSERT INTO notas_creditos ( 
				`tipo`,
				`user_id`,
				`reserva_id`,
				`monto`,
				`detalle`,
				`observaciones`,
				`estatus`
			) VALUES (
				'cuidador', 
				".$reserva['cuidador']['id'].", 
				$reserva_id, 
				$total, 
				'{$_detalle}',
				'{$observaciones}',
				'pendiente'
			);";

		$wpdb->query( $sql_cuidador );

	// *************************************
	// Nota de Credito - Cliente
	// *************************************

		$factura_id = $wpdb->get_var( "SELECT id FROM facturas WHERE receptor = 'cliente' and reserva_id = {$reserva_id}" );

		if( $factura_id > 0 ){
			$sql_cliente = "INSERT INTO notas_creditos ( 
					`tipo`,
					`user_id`,
					`reserva_id`,
					`monto`,
					`detalle`,
					`observaciones`,
					`estatus`,
					factura
				) VALUES (
					'cliente', 
					".$reserva['cliente']['id'].", 
					$reserva_id, 
					$total,
					'{$_detalle}',
					'{$observaciones}',
					'procesada',
					{$factura_id}
				);";

			$wpdb->query( $sql_cliente );
		}

	// *************************************
	// Act. reserva
	// *************************************		
