<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once ("general.php");

class cuidador extends general{

	public function get_datos( $desde, $hasta ){
		$sql = "
			SELECT fecha, cliente as usuarios 
			FROM monitor_diario
			WHERE fecha >= '{$desde}' AND fecha <= '{$hasta}'
		";

		return $this->select( $sql );
	}

	public function by_day( $resultado ){

		$data = [];
		if( !empty($resultado)){	
			foreach ($resultado as $registro) {
				if( isset($registro['fecha']) ){	
					$fecha = str_replace('-', '', $registro['fecha']);

					$items = json_decode($registro['usuarios'], true);
					if( isset( $items['CU'] ) ){

						$attr = $this->countUserAttr( $items['CU'] );

						$data[ $fecha ] = [
							'total' => count($items['CU']),
							'attr' => $attr,
							'list' => $items['CU'],
						];

					}else{
						unset($data[$fecha]);
					}
				}
			}
		}

		return $data;
	}

	public function by_month( $resultado ){

		$data = [];
		if( !empty($resultado)){	
			foreach ($resultado as $registro) {
				if( isset($registro['fecha']) ){	
					// $fecha = str_replace('-', '', $registro['fecha']);
					$month = date( 'm', strtotime($registro['fecha']) ); 
					$year = date('Y', strtotime($registro['fecha']));
					$fecha = $month.$year;

					$items = json_decode($registro['usuarios'], true);
					if( isset( $items['CU'] ) ){

						if( is_array($data[ $fecha ]['list']) && is_array($items['CU']) ){
							foreach ($items['CU'] as $email => $val) {
								if( !array_key_exists($email, $data[ $fecha ]['list']) ){
									$data[ $fecha ]['list'][$email] = $val;
								}
							}
							$count = $data[ $fecha ]['list'];
						}else{
							$count = $items['CU'];
						}

						$data[ $fecha ] = [
							'total' => count($count),
							'list' => $count,
						];

					}
				}
			}
		}

		return $data;
	}

	public function by_year( $resultado ){

		$data = [];
		if( !empty($resultado)){	
			foreach ($resultado as $registro) {
				if( isset($registro['fecha']) ){	

					$year = date('Y', strtotime($registro['fecha']));

					$fecha = $year;

					$items = json_decode($registro['usuarios'], true);
					if( isset( $items['CU'] ) ){

						if( is_array($data[ $fecha ]['list']) && is_array($items['CU']) ){
							foreach ($items['CU'] as $email => $val) {
								if( !array_key_exists($email, $data[ $fecha ]['list']) ){
									$data[ $fecha ]['list'][$email] = $val;
								}
							}
							$count = $data[ $fecha ]['list'];
						}else{
							$count = $items['CU'];
						}

						$data[ $fecha ] = [
							'total' => count($count),
							'list' => $count,
						];

					}
				}
			}
		}

		return $data;
	}

	public function merge_branch( $origen, $destino ){
		if( !empty($origen)){	
			foreach ($origen as $fecha => $registro) {
				
				if( isset( $registro ) ){

					if( !isset($destino[$fecha]) ){
						$destino[$fecha] = $registro;
					}else{
						if( is_array($destino[ $fecha ]['list']) && is_array($registro['list']) ){
							foreach ($registro['list'] as $email => $val) {
								if( !array_key_exists($email, $destino[ $fecha ]['list']) ){
									$destino[ $fecha ]['list'][$email] = $val;
								}
							}
							$count = $destino[ $fecha ]['list'];
						}else{
							$count = $registro['list'];
						}
						$data[ $fecha ] = [
							'total' => count($count),
							'list' => $count,
						];
					}

				}
			}
		}

		return $destino;
	}

	public function procesar( $datos, $desde, $hasta ){

		$resultado = [];
		$hoy = $desde ;

		for ($i=0; $hoy <= $hasta ; $i++) { 

			$anio = date('Y', strtotime($hoy));
			$mes = date('m', strtotime($hoy)).$anio;
			
			$fecha_pasado = date( 'Y-m-d', strtotime( "$hoy -1 month") );
			$anio_pasado = date('Y', strtotime($fecha_pasado));
			$mes_pasado = date('m', strtotime($fecha_pasado)).$anio_pasado;

			$total = 0;
			$nuevo = 0;
			$costo = 0;
			$costo_campana = 0;

			$sum_campanas = 0; // cargar valores de DB - monitor_marketing
			$valor = 0; // cargar valores de DB - parametros

			if( isset($datos[$mes]) ){

				if( isset( $resultado[ $mes_pasado ]['total'] ) ){
					$nuevo = $datos[ $mes ]['total']+0;
					$total = $resultado[ $mes_pasado ]['total'] + $nuevo +0;
				}else{
					$nuevo = $datos[ $mes ]['total']+0;
					$total = $datos[ $mes ]['total']+0;
				}

				$costo_campana = 0;
				if( $nuevos > 0 ){
					$costo_campana = ($sum_campana / $nuevos) + 0;
				}

				$costo = 0;
				if( $valor > 0 ){
					$costo = ($costo_campana / $valor)+0;
				}
			}

			$resultado[ $mes ] = [
				'total' => $total,
				'nuevos' => $nuevo,
				'costos_por_campana' => $costo_campana,
				'costo' => $costo,
			];

			$hoy = date( "Y-m-d", strtotime( "$hoy +1 month" ) );
		}

		return $resultado;
	}
}
