<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once (dirname(dirname(__DIR__))."/conf/database.php");

class procesar extends db{

	public function get_plataforma( $where='' ){
		$where = ( !empty($where) )? ' AND '.$where : '' ;
		return $this->select("select * from monitor_plataforma where estatus = 1 {$where}");
	}

	public function getMeses(){
		$meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
		return $meses;
	}

	//getData Old 
	public function getData( $desde, $hasta ){

		$sql = "
			SELECT fecha, cliente, reserva 
			FROM monitor_diario
			WHERE fecha >= '{$desde}' AND fecha <= '{$hasta}'
		";

		$resultado = $this->select( $sql );

		$data = [];
		if( !empty($resultado)){	
			foreach ($resultado as $registro) {
				if( isset($registro['fecha']) ){	
					$fecha = str_replace('-', '', $registro['fecha']);
					$data[ $fecha ] = $registro;
				}
			}
		}

		require_once(dirname(dirname(__DIR__)).'/cron/kmimos/funciones.php');
		$recompras = getRecompras( $desde, $hasta );
		//$num_noches_recompra = getReservasRecompra( $desde, $hasta );
		$num_noches_recompra = [];

		return [
			'diario'=>$data, 
			'recompras'=>$recompras['rows'], 
			'noches_nuevos_clientes' => $num_noches_recompra 
		];
	} 	

	/*
	public function getData( $plataformas = [], $desde, $hasta ){
	}
	*/

	// -- Enviar solicitud
	public function request( $url, $data ){

		if( !class_exists('Requests') ){
			require_once (dirname(dirname(__DIR__))."/recursos/Requests/Requests.php");
			Requests::register_autoloader();
		}
		$headers = Array(
			'Content-Type'=> 'application/json; charset=UTF-8',	
			'Accept'=>'application/json'
		);
		$request = Requests::post($url, $headers,  $data );

		return (isset($request->body))? $request->body : '' ;
	}

	public function merge_data_sucursales( $result, $data ){

		$item_result = array_keys($result);
		$item_data = array_keys($data);
		$temp = array_merge( $item_result, $item_data );

		$items = array_unique( $temp );
		foreach( $items as $fecha ){
			if( !isset($result[ $fecha ]) && isset( $data[$fecha] )){
				$result[ $fecha ] = $data[$fecha];
			}else{
				$result[$fecha]['noches_reservadas'] += $data[$fecha]['noches_reservadas'];
				$result[$fecha]['eventos_de_compra'] += $data[$fecha]['eventos_de_compra'];
				$result[$fecha]['total_ventas'] += $data[$fecha]['total_ventas'];

				$result[$fecha]['noches_recompradas'] = ($result[$fecha]['noches_recompradas'] + $data[$fecha]['noches_recompradas'])/2; 
				$result[$fecha]['total_perros_hospedados'] += $data[$fecha]['total_perros_hospedados'];
				$result[$fecha]['clientes_nuevos'] += $data[$fecha]['clientes_nuevos'];
				$result[$fecha]['clientes_wom'] = ( $result[$fecha]['clientes_wom'] + $data[$fecha]['clientes_wom'] ) / 2;
				$result[$fecha]['numero_clientes_que_recompraron'] += $data[$fecha]['numero_clientes_que_recompraron']; 
				$result[$fecha]['porcentaje_clientes_que_recompraron'] = ( $result[$fecha]['porcentaje_clientes_que_recompraron'] + $data[$fecha]['porcentaje_clientes_que_recompraron'] ) / 2; 
				$result[$fecha]['clientes'] += $data[$fecha]['clientes']; 
				$result[$fecha]['numero_clientes_vs_mes_anterior'] = ($result[$fecha]['numero_clientes_vs_mes_anterior'] + $data[$fecha]['numero_clientes_vs_mes_anterior'])/2; 
				$result[$fecha]['clientes_nuevos_vs_mes_anterior'] = ($result[$fecha]['clientes_nuevos_vs_mes_anterior'] + $data[$fecha]['clientes_nuevos_vs_mes_anterior']) / 2; 

			}

			$result[$fecha]['precio_por_noche_pagada_promedio'] = 0;
			if($result[$fecha]['noches_reservadas']>0){
				$result[$fecha]['precio_por_noche_pagada_promedio'] = $result[$fecha]['total_ventas']/$result[$fecha]['noches_reservadas']; 
			}

			$result[$fecha]['noches_promedio'] = 0;
			if( $result[$fecha]['eventos_de_compra'] > 0 ){
				$result[$fecha]['noches_promedio'] = $result[$fecha]['noches_reservadas']/$result[$fecha]['eventos_de_compra'];
			}
			

		}

		return $result;
	}

	public function merge_datos( $items ){
		$ventas = [];
		$usuarios = [];
		$meses = $this->getMeses();
		foreach ($items as $fecha => $row) {

			$mes = date('m', strtotime($fecha));
			$anio = date('Y', strtotime($fecha));

			// Ventas
				$item = json_decode($row['reserva'], true);
				$ventas[ $mes.$anio ]['mascotas_total'] += $item['mascotas_total'];
				$ventas[ $mes.$anio ]['ventas']['cant'] += $item['ventas']['cant'];
				$ventas[ $mes.$anio ]['clientes']['total'] = $item['clientes']['total'];

				// noches
				foreach ($item['noches'] as $key => $val) {
					if( isset($ventas[ $mes.$anio ]['noches'][$key]) ){
						$ventas[ $mes.$anio ]['noches'][$key] += $val;
					}else{
						$ventas[ $mes.$anio ]['noches'][$key] = $val;
					}
				}

				// ['ventas']['tipo']
				foreach ($item['ventas']['tipo'] as $key => $val) {
					if( isset($ventas[ $mes.$anio ]['ventas']['tipo'][$key]) ){
						$ventas[ $mes.$anio ]['ventas']['tipo'][$key] += $val;
					}else{
						$ventas[ $mes.$anio ]['ventas']['tipo'][$key] = $val;
					}
				}

				// ['ventas']['estatus']
				foreach ($item['ventas']['estatus'] as $key => $val) {
					if( isset($ventas[ $mes.$anio ]['ventas']['estatus'][$key]) ){
						$ventas[ $mes.$anio ]['ventas']['estatus'][$key] += $val;
					}else{
						$ventas[ $mes.$anio ]['ventas']['estatus'][$key] = $val;
					}
				}

				// ['ventas']['tipo_pago']
				foreach ($item['ventas']['tipo_pago'] as $key => $val) {
					if( isset($ventas[ $mes.$anio ]['ventas']['tipo_pago'][$key]) ){
						$ventas[ $mes.$anio ]['ventas']['tipo_pago'][$key] += $val;
					}else{
						$ventas[ $mes.$anio ]['ventas']['tipo_pago'][$key] = $val;
					}
				}

				// ['ventas']['forma_pago']
				foreach ($item['ventas']['forma_pago'] as $key => $val) {
					if( isset($ventas[ $mes.$anio ]['ventas']['forma_pago'][$key]) ){
						$ventas[ $mes.$anio ]['ventas']['forma_pago'][$key] += $val;
					}else{
						$ventas[ $mes.$anio ]['ventas']['forma_pago'][$key] = $val;
					}
				}

				// ['ventas']['costo']
				foreach ($item['ventas']['costo'] as $key => $val) {
					if( isset($ventas[ $mes.$anio ]['ventas']['costo'][$key]) ){
						$ventas[ $mes.$anio ]['ventas']['costo'][$key] += $val;
					}else{
						$ventas[ $mes.$anio ]['ventas']['costo'][$key] = $val;
					}
				}

				// ['ventas']['costo']['total'] # Solo reservas confirmadas
				$ventas[ $mes.$anio ]['ventas']['costo']['total'] += $item['ventas']['costo']['total'];

			// Usuarios
				$item2 = json_decode($row['cliente'], true);
				foreach($item2 as $key => $val){
					if( isset( $usuarios[ $mes.$anio ][$key] ) ){
						$usuarios[ $mes.$anio ][$key] = array_merge($usuarios[ $mes.$anio ][$key], $val); 
					}else{
						$usuarios[ $mes.$anio ][$key] = $val; 	
					}
				}		
				
		}

		return ['ventas'=>$ventas, 'usuarios'=>$usuarios];
	}

	public function procesarRecompras( $data ){
		$recompra = (isset($data['recompras']))? $data['recompras'] : [];
		$result = [];
		foreach ($recompra as $item ){
			$fecha  = date('mY', strtotime($item['mes']));
			$result[ $fecha ] = $item['cant'];
		}
		return $result;
	}

	public function porSucursal( $all_data, $desde, $hasta ){

		$fecha_hoy = $desde;

		$datos = (isset($all_data['diario']))? $all_data['diario'] : [];
		$recompras = $this->procesarRecompras( $all_data );
		$num_noches_total = (isset($all_data['noches_nuevos_clientes']))? $all_data['noches_nuevos_clientes']:[];

		$datos_por_mes = $this->merge_datos( $datos );

		// crear body nivel 3
			$data = [];
			for ($i=0; $fecha_hoy <= $hasta; $i++) { 
			
				// calcular mes anterior
				$fecha_anterior = date("m", strtotime("-1 month", strtotime($fecha_hoy)));
				$fecha_anterior .= date("Y", strtotime("-1 month", strtotime($fecha_hoy)));

				// calcular mes actual
				$mes = date("m", strtotime($fecha_hoy));
				$anio = date("Y", strtotime($fecha_hoy));

				// Datos de ventas y usuarios
				$_ventas = (isset($datos_por_mes['ventas'][ $mes.$anio ]))? $datos_por_mes['ventas'][ $mes.$anio ] : [] ;
				$_usuarios = (isset($datos_por_mes['usuarios'][ $mes.$anio ]))? $datos_por_mes['usuarios'][ $mes.$anio ] : [] ;

				// calcular total de clientes
				if( isset($data[$fecha_anterior]['clientes']) ){
					$clientes_total = $data[$fecha_anterior]['clientes'] + count($_usuarios['CL']);
				}

				#calculo con el mes anterior
				$numero_clientes_vs_mes_anterior = 0;
				$clientes_nuevos_vs_mes_anterior = 0;
				if( !empty($data[$fecha_anterior]) ){
					if( $_ventas['clientes']['total'] > 0 && $data[$fecha_anterior]['clientes'] > 0 ){
						$numero_clientes_vs_mes_anterior = ($clientes_total / $data[$fecha_anterior]['clientes']);
					}
					if( $data[$fecha_anterior]['clientes_nuevos'] > 0 ){
						$clientes_nuevos_vs_mes_anterior = (count($_usuarios['CL']) * 100 / $data[$fecha_anterior]['clientes_nuevos']);
					}
				}

				# calcular clientes WOM
				$clientes_attr = [];
				$clientes_wom = 0;
				if( !empty($_usuarios) ){
					$clientes_attr = $this->countUserAttr( $_usuarios['CL'] );
					if( isset($clientes_attr['referred']['amigofamiliar']) ){
						$clientes_cant = $clientes_attr['referred']['amigofamiliar'];
						$clientes_wom = ($clientes_cant * 100 / count($_usuarios['CL'])); 				
					}
				}

				$num_clientes_recompras = 0;
				if( isset($recompras[ $mes.$anio ]) ){
					$num_clientes_recompras = $recompras[ $mes.$anio ];
				}

				// total noches clientes nuevos
				$noches_total_nuevos_clientes = 0;
				if( isset( $num_noches_total[$mes.$anio] ) ){
					$noches_total_nuevos_clientes = $num_noches_total[ $mes.$anio ];
				}

				// Data por defecto
				$data[ $mes.$anio ] = [
					'noches_reservadas' => 0,
					'noches_promedio' => 0,
					'noches_recompradas' => "0",
					'total_perros_hospedados' => 0,
					'eventos_de_compra' => 0,
					'clientes_nuevos' => 0,
					'clientes_wom' => "0",
					'numero_clientes_que_recompraron' => 0,
					'porcentaje_clientes_que_recompraron' => "0",
					'precio_por_noche_pagada_promedio' => 0,
					'clientes' => 0,
					'numero_clientes_vs_mes_anterior' => 0 ,
					'clientes_nuevos_vs_mes_anterior' => 0 ,
					'total_ventas' => 0,
				];

				// Cargar datos de registros
				if( !empty($_ventas) || !empty($_usuarios) ){

					$num_noches_recompradas = $_ventas['noches']['total'] - $noches_total_nuevos_clientes;
					$porcentaje_noches_recompradas = 0;
					if( $_ventas['noches']['total'] > 0 ){
						$porcentaje_noches_recompradas = $num_noches_recompradas / $_ventas['noches']['total'];
					}

					$data[ $mes.$anio ] = [
						'date' => $mes.substr($anio, 1, 2),
						'noches_reservadas' => $_ventas['noches']['total'],
						'noches_promedio' => ($_ventas['noches']['total'] / $_ventas['ventas']['cant']),
						'noches_recompradas' => $porcentaje_noches_recompradas,
						'total_perros_hospedados' => $_ventas['mascotas_total'],
						'eventos_de_compra' => $_ventas['ventas']['cant'],
						'clientes_nuevos' => count($_usuarios['CL']),
						'clientes_wom' => $clientes_wom,
						'numero_clientes_que_recompraron' => $num_clientes_recompras,
						'porcentaje_clientes_que_recompraron' => $num_clientes_recompras/$_ventas['ventas']['cant'],
						'precio_por_noche_pagada_promedio' =>  ($_ventas['ventas']['costo']['total']/$_ventas['noches']['total']),
						'clientes' => $clientes_total, //$clientes_total,
						'numero_clientes_vs_mes_anterior' => $numero_clientes_vs_mes_anterior ,
						'clientes_nuevos_vs_mes_anterior' => $clientes_nuevos_vs_mes_anterior ,
						'total_ventas' => $_ventas['ventas']['costo']['total'],
					];

				}

				// Calcular mes siguiente
				$fecha_hoy = date("Y-m", strtotime("+1 month", strtotime($fecha_hoy)));
			}

			return $data;
	}

	public function countUserAttr( $clientes ){

		$count = [];
		foreach ($clientes as $val) {
			foreach ($val as $key => $item) {
				$key = strtolower($key);
				$item = strtolower(str_replace('/', '', $item));

				if( !isset($count[$key]) ){
					$count[$key] = [];
				}
				if( isset($count[$key][$item]) ){
					$count[$key][$item] += 1; 
				}else{
					$count[$key][$item] = 1; 
				}
			}
		}
		return $count;
	}

}