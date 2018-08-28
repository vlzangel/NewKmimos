<?php
date_default_timezone_set('America/Mexico_City');

$pagos = new Pagos();

class Pagos {
	
	public $db;
	
	public function Pagos(){
		$this->raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));

		if( !isset($db) || is_string( $db ) ){
			include($this->raiz.'/vlz_config.php');
			if( !class_exists('db') ){
				include($this->raiz.'/wp-content/themes/kmimos/procesos/funciones/db.php');
			}
		    $db = new db( new mysqli($host, $user, $pass, $db) );
		}

		$this->db = $db;
	}

	public function getPagoGenerados( $desde, $hasta, $estatus =''){

		$where = '';
		if( !empty($desde) && !empty($hasta) ){
			$where .= " WHERE fecha_creacion >= '{$desde} 00:00:00' and fecha_creacion <= '{$hasta} 23:59:59' ";
		}
		$sql = "SELECT * FROM cuidadores_pagos {$where} order by fecha_creacion asc";
 	
		return $this->db->get_results($sql);
	}

	public function getPagoCuidador($desde, $hasta){
		$reservas = $this->getReservas($desde, $hasta);

		$obj_pagos = [];
		$pagos = [];
		$detalle = [];
		$count = 1;

		$dev = [];
		if( !empty($reservas) ){
			foreach ($reservas as $row) {
				$total = 0;
				$condicion = 's:7:"reserva";s:'.strlen($row->reserva_id).':"'.$row->reserva_id.'";';
				$reserva_procesada = $this->db->get_row("SELECT * FROM cuidadores_pagos WHERE detalle like '%{$condicion}%' limit 1" );
 
				if( !isset($reserva_procesada->id) ){

					// Datos del cuidador
						$pagos[ $row->cuidador_id ]['fecha_creacion'] = date('Y-m-d', strtotime("now"));
						$pagos[ $row->cuidador_id ]['user_id'] = $row->cuidador_id;
						$pagos[ $row->cuidador_id ]['nombre'] = $row->nombre;
						$pagos[ $row->cuidador_id ]['apellido'] = $row->apellido;
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
							$row->total, 
							$row->total_pago, 
							$row->remanente,

							$meta_pedido['_cart_discount'],
							$meta_pedido['_wc_deposits_remaining'],
							$method_payment

						);

						$dev2[] = [
							$row->reserva_id,
							$row->total, 
							$row->total_pago, 
							$row->remanente,

							$meta_pedido['_cart_discount'],
							$meta_pedido['_wc_deposits_remaining'],
							$method_payment
						];

						$dev[] = [
							'row'  => $row,
							'pago' => $row->total_pago, 
							'monto'=> $monto,
							'total'=> $row->total, 
							'remanente'=> $row->remanente
						];

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
							$pagos[ $row->cuidador_id ]['detalle'][] = [
								'reserva'=>$row->reserva_id,
								'monto'=>$monto
							];
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
							$obj_pagos[$row->cuidador_id ] = (object) $pagos[$row->cuidador_id ];
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

	    if( date("l",$date) == 'Tuesday' ){
	        $fecha['fin'] = date('Y-m-d',strtotime('last mon', $date));
	    }

	    return $fecha;
	}

	protected function calculo_pago_cuidador( $total, $pago, $remanente, $deposits=0, $discount=0, $method='' ){
		$saldo_cuidador = 0;

		//	$pago_kmimos = ceil (( 16.666666666 * $total )/100 );
		//	$pago_kmimos = $total - ($total / 1.25);
		//	$pago_cuidador_real = $total - $pago_kmimos;
		//	$saldo_cuidador = $pago_cuidador_real - $remanente;

		$pago_cuidador_real = 0;
		$saldo_cuidador = 0;
		$pago_kmimos = 0;
		$dif = $remanente + $pago;
		$pago_cuidador_real = ($total / 1.25);

		if( $deposits > 0 ){
			if( $dif != $total || ($remanente == 0 && $dif == $total) || $method == "Saldo y/o Descuentos" ){
		        $saldo_cuidador = $pago_cuidador_real - $remanente;
			}else{
				$saldo_cuidador = $deposits;
			}
		}else{
			if( $dif != $total || ($remanente == 0 && $dif == $total) || $method == "Saldo y/o Descuentos" ){
		        $pago_kmimos = $total - $pago_cuidador_real;
		        $saldo_cuidador = $pago_cuidador_real;  
		    }
		}

		return $saldo_cuidador; 
	}

	protected function getReservas($desde="", $hasta=""){

		$filtro_adicional = "";

		if( !empty($desde) && !empty($hasta) ){
			$filtro_adicional = " 
				AND ( r.post_date_gmt >= '{$desde} 00:00:00' and  r.post_date_gmt <= '{$hasta} 23:59:59' )
			";
		}

		$sql = "
			SELECT 
				us.user_id as cuidador_id,
	 			us.nombre,
				us.apellido,
				r.ID as reserva_id,
				r.post_parent as pedido_id,

				( IFNULL(rm_cost.meta_value,0) ) as total,
				( IFNULL(pm_total.meta_value,0) ) as total_pago,
				( IFNULL(pm_remain.meta_value,0) ) as remanente

			FROM wp_posts as r
				LEFT JOIN wp_postmeta as rm ON rm.post_id = r.ID and rm.meta_key = '_booking_order_item_id' 
				LEFT JOIN wp_postmeta as rm_cost ON rm_cost.post_id = r.ID and rm_cost.meta_key = '_booking_cost'

				LEFT JOIN wp_posts as p ON p.ID = r.post_parent
				LEFT JOIN wp_postmeta as pm_remain ON pm_remain.post_id = p.ID and pm_remain.meta_key = '_wc_deposits_remaining'
				LEFT JOIN wp_postmeta as pm_total  ON pm_total.post_id = p.ID and pm_total.meta_key = '_order_total'
				LEFT JOIN wp_postmeta as pm_disco  ON pm_disco.post_id = p.ID and pm_disco.meta_key = '_cart_discount'

				LEFT JOIN wp_woocommerce_order_itemmeta as pri ON (pri.order_item_id = rm.meta_value and pri.meta_key = '_product_id')
				LEFT JOIN wp_posts as pr ON pr.ID = pri.meta_value
				LEFT JOIN cuidadores as us ON us.user_id = pr.post_author
				LEFT JOIN wp_users as cl ON cl.ID = r.post_author
			WHERE r.post_type = 'wc_booking' 
				and not r.post_status like '%cart%' 
				and cl.ID > 0 
				and p.ID > 0
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
