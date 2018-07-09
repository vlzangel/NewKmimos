<?php

require_once ("general.php");

class ventas extends general{

	public function get_datos( $desde, $hasta ){
		$sql = "SELECT * 
			FROM monitor_diario_ventas 
			WHERE fecha >= '{$desde}' AND fecha <= '{$hasta}' 
			ORDER BY fecha DESC";
		$datos = $this->select($sql);
		return $datos;
	} 

	public function get_recompras_clientes_nuevos( $desde, $hasta ){
		
		//echo date('Y-m-d H:i:s');	

		$sql = "
			SELECT 
				DATE_FORMAT(r.post_date_gmt,'%Y-%m-%d') as 'fecha_solicitud',
				u.user_registered,
				u.ID,
				r.ID as reserva,
				r.post_parent as pedido
			FROM wp_posts as r
				LEFT JOIN wp_users as u ON u.ID = r.post_author
			WHERE r.post_type = 'wc_booking' 
				AND not r.post_status like '%cart%' 
				AND r.post_status = 'confirmed'
				AND r.post_date_gmt >= '{$desde}'
				AND r.post_date_gmt <= '{$hasta}'
				AND u.user_registered >= '{$desde}' 
				AND u.user_registered <= '{$hasta}'  
			";
		$reservas = $this->select($sql);

		$_resultado = [];
		if( !empty($reservas) ){
			foreach ($reservas as $reserva) {

				// buscar metadatos reservas
					$meta_reserva = $this->select("SELECT * FROM wp_postmeta WHERE 
						post_id = {$reserva['reserva']} 
						AND meta_key in ( '_booking_order_item_id', '_booking_start', '_booking_end' )
					");
					foreach( $meta_reserva as $meta ){
						$meta_reserva[$meta['meta_key']] = $meta['meta_value'];
					}

				// buscar metadatos No. Mascotas
					$order_item_id = 0;
					if( isset($meta_reserva['_booking_order_item_id']) && $meta_reserva['_booking_order_item_id']>0 ){
						$meta_order_item = $this->select("SELECT * FROM wp_woocommerce_order_itemmeta WHERE 
							order_item_id = {$meta_reserva['_booking_order_item_id']} 
							AND meta_key in ( 
								'Mascotas Pequeños', 
								'Mascotas Pequeñas',
								'Mascotas Medianos',
								'Mascotas Medianas',
								'Mascotas Grandes',
								'Mascotas Gigantes',
								'_product_id'
							)
						");
						$meta_order_item['total_mascotas'] = 0;
						foreach( $meta_order_item as $meta ){
							switch ($meta['meta_key']) {
								case 'Mascotas Pequeños':
								case 'Mascotas Pequeñas':
									$meta_order_item['Mascotas_Pequenos'] = $meta['meta_value'];
									$meta_order_item['total_mascotas'] += $meta['meta_value'];
									break;
								case 'Mascotas Medianos':
								case 'Mascotas Medianas':
									$meta_order_item['Mascotas_Medianos'] = $meta['meta_value'];
									$meta_order_item['total_mascotas'] += $meta['meta_value'];
									break;
								case 'Mascotas Grandes':
									$meta_order_item['Mascotas_Grandes'] = $meta['meta_value'];
									$meta_order_item['total_mascotas'] += $meta['meta_value'];
									break;
								case 'Mascotas Gigantes':
									$meta_order_item['Mascotas_Gigantes'] = $meta['meta_value'];
									$meta_order_item['total_mascotas'] += $meta['meta_value'];
									break;
								default:
									$meta_order_item[$meta['meta_key']] = $meta['meta_value'];
									break;
							}
						}
					}

				// buscar producto
					$producto = [];
					if( isset($meta_order_item['_product_id']) && $meta_order_item['_product_id']>0 ){
						$producto = $this->select("SELECT post_name FROM wp_posts WHERE 
							ID = {$meta_order_item['_product_id']}
						");
						$producto = (isset($producto[0]))? $producto[0]: [];
					}		

				# ** *************************** **
				# Calcular
				# ** *************************** **
					$num_noches = $this->dias_transcurridos(
							$this->date_convert($meta_reserva['_booking_end'], 'd-m-Y'), 
							$this->date_convert($meta_reserva['_booking_start'], 'd-m-Y') 
						);					

					if( isset($producto['post_name']) && !in_array('hospedaje', explode("-", $producto['post_name']))){
						$num_noches += 1;
					}

					$num_total_noches = $num_noches * $meta_order_item['total_mascotas'];

					$_fecha = date('Y-m-d', strtotime($reserva['fecha_solicitud']));
					if( isset($_resultado[ $_fecha ]) ){
						$_resultado[ $_fecha ] += $num_total_noches;
					}else{
						$_resultado[ $_fecha ] = $num_total_noches;
					}
			}
		}
		//echo date('Y-m-d H:i:s');	

		return $_resultado;
	}

	public function get_recompras( $desde, $hasta ){
		$result = [];
		$sql = "SELECT 
				sum(recompra_cant) as recompra_cant, 
				sum(recompra_noches) as recompra_noches, 
				sum(recompra_mascotas_cantidad) as recompra_mascotas_cantidad, 
				sum(recompra_noches_total) as recompra_noches_total,
				count(cliente_email) as num_clientes_recompra, 
				fecha 
			FROM
				(SELECT 
					count(id) as recompra_cant, 
					sum(noches) as recompra_noches, 
					sum(mascotas_cantidad) as recompra_mascotas_cantidad, 
					sum(noches_total) as recompra_noches_total, 
					cliente_email, 
					fecha 
				FROM monitor_reservas 
				WHERE 
					estatus = 'Confirmado' 
					AND fecha >= '{$desde}' 
					AND fecha <= '{$hasta}' 
				GROUP BY cliente_email) as temp 
			WHERE recompra_cant > 1
			GROUP BY fecha
			order by fecha desc";

//echo $sql;

		$result = $this->select($sql);
		return $result;
	}

	public function dias_transcurridos($fecha_i,$fecha_f){
		$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
		$dias 	= abs($dias); $dias = floor($dias);		
		return $dias;
	}

	public function date_convert( $str_date, $format = 'd-m-Y H:i:s', $totime=true ){
		$fecha = $str_date;
		if(!empty($str_date)){
			if($totime){
				$time = strtotime($str_date);
			}
			$fecha = date($format,$time);
		}
		return $fecha;
	}

}