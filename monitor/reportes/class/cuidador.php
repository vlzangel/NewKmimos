<?php
 
require_once ("general.php");

class cuidador extends general{


	public function get_cuidadores( $desde, $hasta ){
		return $this->get_usuarios( $desde, $hasta, ' c.id > 0 ');
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

			$sum_campana = (isset($datos[ $mes ]['total_campana']))? $datos[ $mes ]['total_campana'] : 0 ;
			$valor = 19; // cargar valores de DB - parametros

			if( isset($datos[$mes]) ){

				if( isset( $resultado[ $mes_pasado ]['total'] ) ){
					$nuevo = $datos[ $mes ]['total']+0;
					$total = $resultado[ $mes_pasado ]['total'] + $nuevo +0;
				}else{
					$nuevo = $datos[ $mes ]['total']+0;
					$total = $datos[ $mes ]['total']+0;
				}

				$costo_campana = 0;
				if( $nuevo > 0 ){
					$costo_campana = ($sum_campana / $nuevo) + 0;
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

	public function sumar_campanas( $desde, $hasta, $tipo='cuidador' ){
		$sql = "SELECT 
				fecha,
				plataforma,
				sum(costo) as costo
			FROM monitor_marketing 
			WHERE tipo like '%{$tipo}%' 
				AND fecha >= '{$desde}'
				AND fecha <= '{$hasta}'
			group by fecha, plataforma
		";
		$datos = $this->select($sql);

		return $datos;
	}
}
