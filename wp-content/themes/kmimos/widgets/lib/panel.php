<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
date_default_timezone_set('America/Mexico_City');

$panel = new PANEL();

class PANEL {
	
	public $db;
	protected $raiz;

	function __construct(){
		date_default_timezone_set('America/Mexico_City');
		$this->raiz = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
		if( !isset($db) || is_string( $db ) ){
			include($this->raiz.'/vlz_config.php');
			if( !class_exists('db') ){
				include($this->raiz.'/wp-content/themes/kmimos/procesos/funciones/db.php');
			}
		    $db = new db( new mysqli($host, $user, $pass, $db) );
		}
		$this->db = $db;
		$this->db->query("SET NAMES 'utf8'");
	}

	public function resumen(){
 
		$inicio = date("Y-m")."-01 00:00:00";
		$fin = date("Y-m", strtotime ( '+1 month' , time() ) )."-01 23:59:59";
		
		$_hoy = 0;
		$_mes = 0;
		$_dia = [];

		$pendientes = 0;
		$canceladas = 0;
		$confirmadas = 0;
		$completadas = 0;
		$modificadas = 0;
		
		$dia_en_curso = strtotime ( date("Y-m-d") );
		$mes_en_curso = strtotime ( date("Y-m").'-1' );
		$mes_anterior = strtotime ( date("Y-m", strtotime ( '-1 month' , time() ) ).'-1' );

		$sql = "
			SELECT 
				orden.ID AS ordenID,
				reserva.ID AS reservaID,
				orden.post_status AS ordenStatus,
				reserva.post_status AS reservaStatus,
				orden.post_date AS fecha
			FROM 
				wp_posts AS orden
			INNER JOIN wp_posts AS reserva ON ( orden.ID = reserva.post_parent )
			WHERE 
				orden.post_date >= '{$inicio}' AND 
				orden.post_date <= '{$fin}' AND 
				orden.post_type = 'shop_order' ";
		$pedidos = $this->db->get_results( $sql );
		foreach ($pedidos as $pedido) {

			$monitorear = false;
			$fecha = strtotime( $pedido->fecha );

			$_fecha = date( 'Y-m-d', strtotime($pedido->fecha) );

			if( !isset($_dia[ $_fecha ]) ){
				$_dia[ $_fecha ] = [
					'date' => date( 'd', strtotime($pedido->fecha) ),
					'completadas' => 0,
					'confirmadas' => 0,
					'pendientes' => 0,
					'canceladas' => 0,
					'modificadas' => 0,
				];
			}

			switch ( $pedido->ordenStatus ) {
				case 'wc-confirmed':
					$fin = strtotime( get_post_meta($pedido->reservaID, "_booking_end", true) );
					if( time() <= $fin){
						$completadas++;
						$_dia[ $_fecha ]['completadas']++;
					}
					$confirmadas++;
					$monitorear = true;
					$_dia[ $_fecha ]['confirmadas']++;
				break;
				case 'wc-completed':
					$pendientes++;
					$confirmadas++;
					$monitorear = true;
					$_dia[ $_fecha ]['pendientes']++;
					$_dia[ $_fecha ]['confirmadas']++;
				break;
				case 'wc-partially-paid':
					$pendientes++;
					$confirmadas++;
					$monitorear = true;
					$_dia[ $_fecha ]['pendientes']++;
					$_dia[ $_fecha ]['confirmadas']++;
				break;
				case 'wc-cancelled':
					$canceladas++;
					$_dia[ $_fecha ]['canceladas']++;
				break;
				case 'modified':
					$modificadas++;
					$_dia[ $_fecha ]['modificadas']++;
				break;
			}

			if( $monitorear ){

				if( $dia_en_curso <= $fecha ){
					$_hoy++;
				}
			}
		}

		// Ordenar Items y agregar dias faltantes
		// *****************************************
		$next_day = date("Y-m").'-01';
		$next_month = date("Y-m", strtotime ( $next_day . ' +1 month' ) );
		for( $i = 1;  $i <= 31; $i++){
			$next_day = date("Y-m-d", strtotime ( $next_day . ' +1 day' ) );
			$current_month = date("Y-m", strtotime ( $next_day ) );
			if( $next_month == $current_month ){				 
				$i = 32;
			}else{
				if( !isset($_dia[ $next_day ]) ){
					$_dia[ $next_day ] = [
						'date' => date("d", strtotime ( $next_day ) ),
						'completadas' => 0,
						'confirmadas' => 0,
						'modificadas' => 0,
						'pendientes' => 0,
						'canceladas' => 0,
					];
				}
			}
		}

		$temp = [];
		sort($_dia);
		foreach ($_dia as $val){$temp[] = $val;}
		$_dia = $temp;
		// Ordenar Items
		// *****************************************

		$inicio = date("Y-m", strtotime ( '-2 month' , time() ) );
		$fin = date("Y-m")."-01 00:00:00";
		$_mes = 0;
		$sql = "
			SELECT 
				orden.ID AS ordenID,
				reserva.ID AS reservaID,
				orden.post_status AS ordenStatus,
				reserva.post_status AS reservaStatus,
				orden.post_date AS fecha
			FROM 
				wp_posts AS orden
			INNER JOIN wp_posts AS reserva ON ( orden.ID = reserva.post_parent )
			WHERE 
				orden.post_date >= '{$inicio}' AND 
				orden.post_date <= '{$fin}' AND 
				orden.post_type = 'shop_order' ";
		$pedidos = $this->db->get_results( $sql );
		foreach ($pedidos as $pedido) {

			$monitorear = false;
			$fecha = strtotime( $pedido->fecha );

			switch ( $pedido->ordenStatus ) {
				case 'wc-confirmed':
					$monitorear = true;
				break;
				case 'wc-completed':
					$monitorear = true;
				break;
				case 'wc-partially-paid':
					$monitorear = true;
				break;
			}

			if( $monitorear ){

				if( $mes_anterior <= $fecha && $fecha < $mes_en_curso ){
					$_mes++;
				}

			}
		}

		$data = [
			"hoy" => [
				"total" => $_hoy,
			],
			"mes" => [
				"total" => $_mes,
				"por_dia"=> $_dia,
				"canceladas" => $canceladas,
				"pendientes" => $pendientes,
				"confirmadas" => $confirmadas,
				"completadas" => $completadas,
				"modificadas" => $modificadas,

			]
		];
		return $data;
	} 

	public function noches(){
		date_default_timezone_set('America/Mexico_City');

		$fin = date("Y-m", strtotime ( '-12 month' , time() ) )."-01 00:00:00";
		
		$sql = "
			SELECT 
				orden.ID AS ordenID,
				reserva.ID AS reservaID,
				metas.meta_value AS monto,
				orden.post_status AS ordenStatus,
				reserva.post_status AS reservaStatus,
				orden.post_date AS fecha,
				item.meta_value AS item_id,
				duracion.meta_value AS duracion,
				mascotas.meta_value AS mascotas
			FROM 
				wp_posts AS orden
			INNER JOIN wp_posts AS reserva ON ( orden.ID = reserva.post_parent )
			INNER JOIN wp_postmeta AS metas ON ( reserva.ID = metas.post_id )
			INNER JOIN wp_postmeta AS mascotas ON ( reserva.ID = mascotas.post_id )
			INNER JOIN wp_postmeta AS item ON ( reserva.ID = item.post_id AND item.meta_key = '_booking_order_item_id' )
			INNER JOIN wp_woocommerce_order_itemmeta AS duracion ON ( duracion.order_item_id = item.meta_value )

			WHERE 
				orden.post_type = 'shop_order' AND 
				metas.meta_key = '_booking_cost' AND
				mascotas.meta_key = '_booking_persons' AND
				duracion.meta_key = 'Duración'
		";
		$pedidos = $this->db->get_results( $sql );

		$ventas_hoy = 0;
		$ventas_mes = 0;
		$ventas_mes_anterior = 0;
		$ventas_90 = 0;
		$ventas_12 = 0;
		$ventas_anio_curso = 0;

		$dia_en_curso = strtotime ( date("Y-m-d") );
		$mes_en_curso = strtotime ( date("Y-m").'-1' );
		$mes_anterior = strtotime ( date("Y-m", strtotime ( '-1 month' , time() ) ).'-1' );
		$hace_90_dias = strtotime ( '-90 day' , time() );
		$hace_12_meses = strtotime ( '-12 month' , time() );
		$anio_en_curso = strtotime ( date("Y").'-01-01' );

		foreach ($pedidos as $pedido) {

			$_mascotas = unserialize( $pedido->mascotas ); $mascotas = 0;
			foreach ($_mascotas as $key => $value) {
				$mascotas += $value;
			}

			$fecha = strtotime( $pedido->fecha );

			$inicio = strtotime( $pedido->inicio );
			$fin = strtotime( $pedido->fin );

			$diferencia = $fin-$inicio;
			$dias = $diferencia/(60*60*24);

			$duracion = explode(" ", $pedido->duracion);

			$yes = false;
			switch ( $pedido->ordenStatus ) {
				case 'wc-confirmed':
					$yes = true;
				break;
				case 'wc-completed':
					$yes = true;
				break;
				case 'wc-partially-paid':
					$yes = true;
				break;
			}

			if( $yes ){
				$duracion[0] *= $mascotas;
			}else{
				$duracion[0] *= 0;
			}

			if( $dia_en_curso <= $fecha ){
				$ventas_hoy += $duracion[0]+0;
			}

			if( $mes_en_curso <= $fecha ){
				$ventas_mes += $duracion[0]+0;
			}

			if( $mes_anterior <= $fecha && $fecha < $mes_en_curso ){
				$ventas_mes_anterior += $duracion[0]+0;
			}

			if( $anio_en_curso <= $fecha ){
				$ventas_anio_curso += $duracion[0]+0;
			}

			if( $hace_90_dias <= $fecha ){
				$ventas_90 += $duracion[0]+0;
			}

			if( $hace_12_meses <= $fecha ){
				$ventas_12 += $duracion[0]+0;
			}
		}

		return [
			"dia_curso" => $ventas_hoy,
			"mes_curso" => $ventas_mes,
			"mes_anterior" => $ventas_mes_anterior,
			"ventas_90" => $ventas_90,
			"ventas_12" => $ventas_12,
			"anio_curso" => $ventas_anio_curso
		];
	}

	public function ventas(){
			
		$fin = date("Y-m", strtotime ( '-12 month' , time() ) )."-01 00:00:00";

		$sql = "
			SELECT 
				orden.ID AS ordenID,
				reserva.ID AS reservaID,
				metas.meta_value AS monto,
				orden.post_status AS ordenStatus,
				reserva.post_status AS reservaStatus,
				orden.post_date AS fecha
			FROM 
				wp_posts AS orden
			INNER JOIN wp_posts AS reserva ON ( orden.ID = reserva.post_parent )
			INNER JOIN wp_postmeta AS metas ON ( reserva.ID = metas.post_id )
			WHERE 
				orden.post_date >= '{$fin}' AND 
				orden.post_type = 'shop_order' AND 
				metas.meta_key = '_booking_cost' 
		";

		$pedidos = $this->db->get_results( $sql );
		
		$ventas_hoy = 0;
		$ventas_mes = 0;
		$ventas_mes_anterior = 0;
		$ventas_90 = 0;
		$ventas_12 = 0;
		$ventas_anio_curso = 0;

		$dia_en_curso = strtotime ( date("Y-m-d") );
		$mes_en_curso = strtotime ( date("Y-m").'-1' );

		$mes_anterior = strtotime ( date("Y-m", strtotime ( '-1 month' , time() ) ).'-1' );

		$anio_en_curso = strtotime ( date("Y").'-01-01' );
		$hace_90_dias = strtotime ( '-90 day' , time() );
		$hace_12_meses = strtotime ( '-12 month' , time() );

		$por_dia=[];

		foreach ($pedidos as $pedido) {
			// $fin_reserva = strtotime( get_post_meta($pedido->reservaID, "_booking_end", true) );

			$fecha = strtotime( $pedido->fecha );
			$monto = 0;

			switch ( $pedido->ordenStatus ) {
				case 'wc-confirmed':
					$monto = $pedido->monto;
				break;
				case 'wc-completed':
					$monto = $pedido->monto;
				break;
				case 'wc-partially-paid':
					$monto = $pedido->monto;
				break;
			}

			if( $dia_en_curso <= $fecha ){
				$ventas_hoy += $monto;
			}

			if( $mes_en_curso <= $fecha ){
				$ventas_mes += $monto;
			}

			if( $mes_anterior <= $fecha && $fecha < $mes_en_curso ){
				$ventas_mes_anterior += $monto;
			}

			if( $anio_en_curso <= $fecha ){
				$ventas_anio_curso += $monto;
			}

			if( $hace_90_dias <= $fecha ){
				$ventas_90 += $monto;
			}

			if( $hace_12_meses <= $fecha ){
				$ventas_12 += $monto;
			}


			if( $monto > 0 ){
				$date = date('Y-m-d', strtotime( $pedido->fecha ));
				if( !isset( $por_dia[ $date ] ) ){
					$por_dia[ $date ] = [
						'date' => $date,
						'monto' => $monto,
					];
				}else{
					$por_dia[ $date ]['monto'] += $monto;
				}
			}
		}

		$temp = [];
		sort($por_dia);
		foreach ($por_dia as $value) {
			$temp[] = $value;
		}

		return [
			"por_dia" => $temp,
			"ventas_hoy" => "$ ".number_format( $ventas_hoy, 2, ",", "." )." MXN",
			"ventas_mes" => "$ ".number_format( $ventas_mes, 2, ",", "." )." MXN",
			"ventas_mes_anterior" => "$ ".number_format( $ventas_mes_anterior, 2, ",", "." )." MXN",
			"ventas_90" => "$ ".number_format( $ventas_90, 2, ",", "." )." MXN",
			"ventas_12" => "$ ".number_format( $ventas_12, 2, ",", "." )." MXN",
			"ventas_anio_curso" => "$ ".number_format( $ventas_anio_curso, 2, ",", "." )." MXN",
		];
	}
}