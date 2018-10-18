<?php
	error_reporting(E_ERROR);
//date_default_timezone_set('America/Mexico_City');

$pagos = new PagoCuidador();

class PagoCuidador {
	
	public $db;
	
	public function PagoCuidador(){
		$this->raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
		if( !isset($db) || is_string( $db ) ){
			include($this->raiz.'/vlz_config.php');
			if( !class_exists('db') ){
				include($this->raiz.'/wp-content/themes/kmimos/procesos/funciones/db.php');
			}
		    $db = new db( new mysqli($host, $user, $pass, $db) );
		}

		$this->db = $db;
	}

	public function balance( $user_id ){
		$hoy = date( "Y/m/d H:i:s" );
		$habilitado = true;
		$dia_habil = $hoy;

		$total_NC = $this->get_NC( $user_id );
		$total_en_progreso = $this->pagos_en_progreso( $user_id );
		$total_retenido = $this->total_retenido( $user_id );
		$total_generado = $this->get_total_generado( $user_id );
		$total_disponible = $this->detalle_disponible( $user_id );

		$ultimo_retiro = $this->ultimo_retiro( $user_id );
		if( !empty($ultimo_retiro) ){
			$dia_habil = date( "Y/m/d H:i:s", strtotime($ultimo_retiro." +24 hours" ) );
			if( $hoy < $dia_habil ){
				$habilitado = false;
			}
		}

		$proximo_pago = ( $total_disponible['total'] > 0 )? $total_disponible['total'] : 0;

		$pay = (object)[
			"total_generado"	=> $total_generado,
			"disponible" 		=> $total_disponible['total'],
			"proximo_pago"		=> $proximo_pago,
			"en_progreso"		=> $total_en_progreso,
			"retenido"			=> $total_retenido,
			"retiro"=>(object)[
				"habilitado"		=> $habilitado,
				"ultimo_retiro" 	=> $ultimo_retiro,
				"tiempo_restante"	=> $dia_habil,
			],
			"detalle"=>$total_disponible['detalle'],
		];
		return $pay;
	}

	protected function diferenciaDias($inicio, $fin)
{
    $inicio = strtotime($inicio);
    $fin = strtotime($fin);
    $dif = $fin - $inicio;
    $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
    return ceil($diasFalt);
}

	public function detalle_disponible( $user_id ){
		$reservas = $this->db->get_results( "SELECT * FROM cuidadores_reservas WHERE user_id = {$user_id} and estatus='pendiente'" );
		$total = 0;
		$hoy = date('Y-m-d');

		$list = ['total'=>0, 'detalle'=>[]];

		foreach ($reservas as $reserva) {

			$pago_por_noches = $reserva->total_reserva / $reserva->total_dias;

			$dias = $this->diferenciaDias( $reserva->checkin, $hoy );
			
			$monto = $pago_por_noches * $dias;

			$tr = $this->total_transacciones_by_reserva( $user_id, $reserva->reserva_id );
			$nc = $this->get_NC( $user_id, $reserva->reserva_id );

			$total = $monto - ($tr + $nc);

			$list['detalle'][$reserva->reserva_id] = $total;
			$list['total'] += $total;
		}

		return $list;
	}


	protected function total_disponible( $user_id ){
		$reservas = $this->db->get_results( "SELECT * FROM cuidadores_reservas WHERE user_id = {$user_id} and estatus='pendiente'" );
		$total = 0;
		$nc = $this->get_NC( $user_id );
		$hoy = date('Y-m-d');

		foreach ($reservas as $reserva) {

			$pago_por_noches = $reserva->total_reserva / $reserva->total_dias;

			$dias = $this->diferenciaDias( $reserva->checkin, $hoy );
			
			$monto = $pago_por_noches * $dias;

			$tr = $this->total_transacciones_by_reserva( $user_id, $reserva->reserva_id );

			$total += $monto - $tr;

		}

		$total -= $nc;

		return ( is_numeric($total) )? $total : 0;
	}

	protected function pagos_en_progreso( $user_id ){
		$total = $this->db->get_var( "SELECT SUM(total) as total FROM cuidadores_pagos WHERE user_id = {$user_id} and estatus = 'in_progress'" );
		return ( $total > 0 )? $total : 0;
	}

	protected function get_NC( $user_id, $reserva=0 ){
		$where_reserva = ( $reserva > 0 )? ' AND reserva_id = '.$reserva_id : '' ;
		$total = $this->db->get_var( "SELECT SUM(monto) as total FROM notas_creditos WHERE user_id = {$user_id} and tipo='cuidador' and estatus='pendiente' {$where_reserva}");
		return ( $total > 0 )? $total : 0;
	}

	protected function ultimo_retiro( $user_id ){
		$fecha = $this->db->get_var( "SELECT fecha FROM cuidadores_transacciones WHERE user_id = {$user_id} AND tipo='pago_c' ORDER BY id desc limit 1" );
		return ( !empty($fecha) )? date("Y/m/d H:i:s", strtotime($fecha)) : '' ;
	}

	protected function total_retenido( $user_id ){
		$total = $this->db->get_var( "SELECT SUM(monto) as total FROM cuidadores_transacciones WHERE user_id = {$user_id} AND tipo='retenido'" );
		return ( $total > 0 )? $total : 0 ;
	}

	protected function total_transacciones_by_reserva( $user_id, $reserva_id ){
		$total = $this->db->get_var( "SELECT SUM(monto) as total FROM cuidadores_transacciones WHERE user_id = {$user_id} AND reserva_id={$reserva_id}" );
		return ( $total > 0 )? $total : 0 ;
	}

	protected function get_total_generado( $user_id ){
		$total = $this->db->get_var( "SELECT SUM(total_reserva) as total FROM cuidadores_reservas WHERE user_id = {$user_id} " );
		//$NC = $this->get_NC( $user_id );
		//if( $NC > 0 ){
		//	$total -=  $NC;
		//}
		return ( $total > 0 )? $total : 0 ;
	}




	public function getPagoCompletados( $desde, $hasta ){
		$where = " WHERE estatus = 'completed' ";
		if( !empty($desde) && !empty($hasta) ){
			$where = " and fecha_creacion >= '{$desde} 00:00:00' and fecha_creacion <= '{$hasta} 23:59:59' ";
		}
		$sql = "SELECT * FROM cuidadores_pagos {$where} order by fecha_creacion asc";
		return $this->db->get_results($sql);
	}	

	public function getPagoGenerados( $desde, $hasta ){
		$where = " WHERE estatus <> 'completed' ";
		if( !empty($desde) && !empty($hasta) ){
			$where = " and fecha_creacion >= '{$desde} 00:00:00' and fecha_creacion <= '{$hasta} 23:59:59' ";
		}
		$sql = "SELECT * FROM cuidadores_pagos {$where} order by fecha_creacion asc";
 	
		return $this->db->get_results($sql);
	}

	public function getPagoGeneradosTotal( $desde, $hasta ){
		$where = " WHERE estatus = 'in_progress'";
		if( !empty($desde) && !empty($hasta) ){
			$where .= " and fecha_creacion >= '{$desde} 00:00:00' and fecha_creacion <= '{$hasta} 23:59:59' ";
		}
		$sql = "SELECT sum(total) as total FROM cuidadores_pagos {$where} order by fecha_creacion asc";
 	
		return $this->db->get_results($sql);
	}

	public function updatePagoCuidador($desde, $hasta, $user_id=0){
		if( empty($desde) || empty($hasta) ){
			return [];
		}

		$reservas = $this->getReservas($desde, $hasta, $user_id);

		$obj_pagos = [];
		$detalle = [];
		$pagos = [];
		$count = 1;

		$dev = [];
		if( !empty($reservas) ){
			foreach ($reservas as $row) {

				$total = 0;
				$condicion = 's:7:"reserva";s:'.strlen($row->reserva_id).':"'.$row->reserva_id.'";';
				$reserva_procesada = $this->db->get_row("SELECT * FROM cuidadores_pagos WHERE detalle like '%{$condicion}%' limit 1" );
 
				if( !isset($reserva_procesada->id) ){

					$cuidador = $this->db->get_row('SELECT * FROM cuidadores WHERE user_id = '.$row->cuidador_id);
					if( isset($cuidador->nombre) || isset($cuidador->apellido) ){
							
					// Datos del cuidador
						$pagos[ $row->cuidador_id ]['fecha_creacion'] = date('Y-m-d', strtotime("now"));
						$pagos[ $row->cuidador_id ]['user_id'] = $row->cuidador_id; 
						$pagos[ $row->cuidador_id ]['nombre'] = $cuidador->nombre ; 
						$pagos[ $row->cuidador_id ]['apellido'] = $cuidador->apellido ; 
						$pagos[ $row->cuidador_id ]['estatus'] = '';

					// Meta de padido
						$meta_pedido = $this->getMetaPedido( $row->pedido_id );

					// Metodos de pago
						$method_payment = '';
						if( !empty($meta_pedido['_payment_method_title']) ){
							$method_payment = $meta_pedido['_payment_method_title']; 
						}else{
							if( !empty($meta_reserva['modificacion_de']) ){
								$method_payment = 'Saldo a favor' ; 
							}else{
								$method_payment = 'Manual'; 
							}
						}

					// Calculo por reserva
						$monto = $this->calculo_pago_cuidador( 
							$row->reserva_id,
							$row->total
						);
 
						if( $count == 4 ){
							$separador = '<br><br>';
							$count=1;
						}else{
							$separador = '';
							$count++;
						}
  
						if( !isset($pagos[ $row->cuidador_id ]['detalle']) ){
							$pagos[ $row->cuidador_id ]['detalle']=[]; 
						}
						if( $monto > 0 ){
							$pagos[ $row->cuidador_id ]['detalle'][$row->reserva_id] = [
								'reserva'=>$row->reserva_id,
								'pedido'=>$row->pedido_id,
								'monto'=>$monto,
							];

							// 
					    }

						if( array_key_exists('total', $pagos[ $row->cuidador_id ]) ){
							$monto = $pagos[ $row->cuidador_id ]['total'] + $monto;
						}

						if( array_key_exists('total_row', $pagos[ $row->cuidador_id ]) ){
							$total = $pagos[ $row->cuidador_id ]['total_row'] + 1;
						}

					// Total a pagar
						$pagos[ $row->cuidador_id ]['total'] = $monto;
						$pagos[ $row->cuidador_id ]['cantidad'] = count($pagos[ $row->cuidador_id ]['detalle']);

					// Object
						if( $monto > 0 ){
							$obj_pagos[ $row->cuidador_id ] = (object) $pagos[ $row->cuidador_id ];
						}
					}
				}
			}
		}
		
		return $obj_pagos;
	}

	public function getRangoFechas(){
    	$d = getdate();
    	$strFecha = strtotime( date("Y-m-d", $d[0]) );
		$fecha = $this->inicio_fin_semana( $strFecha, 'tue' );
		return $fecha;
	}

	protected function inicio_fin_semana( $date, $str_to_date  ){

	    $diaInicio=$str_to_date;

	    $fecha['ini'] = date('Y-m-d',strtotime('last '.$diaInicio, $date));
	    $fecha['fin'] = date('Y-m-d',$date);
	    
	    $fecha['min'] = $fecha['ini'];
	    $fecha['max'] = date('Y-m-d',strtotime($diaInicio." +30"));

	    if( date("l",$date) == 'Tuesday' ){
	        $fecha['fin'] = date('Y-m-d',strtotime('last mon', $date));
	    }

	    return $fecha;
	}

	public function calculo_pago_cuidador( $reserva_id, $total ){
 
		$pago_cuidador = $total / 1.25;
		$pago_kmimos = $total - $pago_cuidador;

		// Cupones de la reserva
			$cupones = $this->db->get_results("SELECT items.order_item_name as name, meta.meta_value as monto  
            FROM `wp_woocommerce_order_items` as items 
                INNER JOIN wp_woocommerce_order_itemmeta as meta ON meta.order_item_id = items.order_item_id
                INNER JOIN wp_posts as p ON p.ID = ".$reserva_id." and p.post_type = 'wc_booking' 
                WHERE meta.meta_key = 'discount_amount'
                    and items.`order_id` = p.post_parent
                    and not items.order_item_name like ('saldo-%')
            ;");

		// Datos de los cupones
			$meta_cupon = [];
			if( !empty($cupones) ){
				foreach ($cupones as $cupon) {


					$cupon_id = $this->db->get_var("SELECT ID FROM wp_posts WHERE post_title = '".$cupon->name."' ");
					$metas =  $this->db->get_results("SELECT meta_key, meta_value FROM wp_postmeta WHERE meta_key like 'descuento%' and post_id = ".$cupon_id );

					$meta_cupon[ $cupon->name ][ 'total' ] = $cupon->monto; 
					if( $cupon->monto > 0 ){
						if( !empty($metas) ){
							foreach ($metas as $meta) {
								$meta_cupon[ $cupon->name ][ $meta->meta_key ] = $meta->meta_value;
							}
						}
 
						// tipo de descuento
						$_cupon = $meta_cupon[ $cupon->name ];
 						
						$_cupon['descuento_tipo'] = ( isset($_cupon['descuento_tipo']) )? $_cupon['descuento_tipo'] : ''; 
						switch ( strtolower($_cupon['descuento_tipo']) ) {
							case 'kmimos':
								if( $pago_kmimos < $_cupon['total'] ){
									$diferencia = $_cupon['total'] - $pago_kmimos;
									$pago_cuidador -= $diferencia;
								}else{
									$pago_kmimos -= $_cupon['total'];
								}
								break;
							case 'cuidador':
								if( $pago_cuidador < $_cupon['total'] ){
									$pago_cuidador = 0;
								}else{
									$pago_cuidador -= $_cupon['total'];
								}
								break;						
							case 'compartido':
								// Calculo de descuentos
								$descuento_kmimos = ( $_cupon['descuento_kmimos'] * $_cupon['total'] ) / 100;
								$descuento_cuidador = ( $_cupon['descuento_cuidador'] * $_cupon['total'] ) / 100;
								if( $pago_cuidador <= $descuento_cuidador ){
									$pago_cuidador = 0;
								}else{
									// validar si el monto de kmimos es superior a la comision
									$diferencia = 0;
									if( $pago_kmimos < $descuento_kmimos ){
										$diferencia = $descuento_kmimos - $pago_kmimos;
										$descuento_cuidador += $diferencia;
										$pago_kmimos = 0;
									}

									if( $descuento_cuidador >= $pago_cuidador ){
										$pago_cuidador = 0;
									}else{
										$pago_cuidador -= $descuento_cuidador;
									}
								}
								break;
							default:
								$pago_cuidador -= $_cupon['total'];
								break;
						}
					}
				}
			}

		return $pago_cuidador ; 
	}

	protected function getReservas($desde="", $hasta="", $user_id=0){

		$filtro_adicional = "";

		if( $user_id > 0 ){
			$filtro_adicional = " AND pr.post_author = {$user_id}";
		}

		if( !empty($desde) && !empty($hasta) ){
			$desde = str_replace('-', '', $desde);
			$hasta = str_replace('-', '', $hasta);
			$filtro_adicional .= " 
				AND ( rm_start.meta_value >= '{$desde}000000' and  rm_start.meta_value <= '{$hasta}235959' )
			";
		}else{
			return [];
		}

		// SQL Nuevo
		$sql = "
			SELECT 
				pr.post_author as cuidador_id,
				r.ID as reserva_id,
				r.post_parent as pedido_id,
				( IFNULL(rm_cost.meta_value,0) ) as total,
				rm_start.meta_value as booking_start
			FROM wp_posts as r
				LEFT JOIN wp_postmeta as rm ON rm.post_id = r.ID and rm.meta_key = '_booking_order_item_id' 
				LEFT JOIN wp_postmeta as rm_cost ON rm_cost.post_id = r.ID and rm_cost.meta_key = '_booking_cost'
				LEFT JOIN wp_postmeta as rm_start ON rm_start.post_id = r.ID and rm_start.meta_key = '_booking_start'
				LEFT JOIN wp_woocommerce_order_itemmeta as pri ON (pri.order_item_id = rm.meta_value and pri.meta_key = '_product_id')
				LEFT JOIN wp_posts as pr ON pr.ID = pri.meta_value
			WHERE r.post_type = 'wc_booking' 
				and not r.post_status like '%cart%' 
				and r.post_status = 'confirmed'
				{$filtro_adicional}
		;";

		$reservas = $this->db->get_results($sql);
		return $reservas;
	}

	protected function getMetaPedido( $post_id ){
		$metas = [
			'_payment_method' => '',
			'_payment_method_title' => '',
			'_order_total' => '',
			'_wc_deposits_remaining' => '',
			'_cart_discount' => '',
		];
		$sql = "SELECT u.meta_key, u.meta_value, u.post_id FROM wp_postmeta as u WHERE u.post_id = {$post_id} AND meta_key IN ( '".implode("','", array_keys($metas))."' )";
		$result = $this->db->get_results( $sql );
		
		if( !empty($result) ){
			foreach ($result as $row) { 
				$metas[ $row->meta_key ] = utf8_encode( $row->meta_value );
			}
		}
		return $metas;	
	}
}
