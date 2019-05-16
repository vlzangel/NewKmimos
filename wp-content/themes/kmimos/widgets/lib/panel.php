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
 
	 	include($this->raiz.'/wp-load.php');

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
				
				orden.post_type = 'shop_order' AND 
				metas.meta_key = '_booking_cost' 
			ORDER BY fecha ASC
		";

		// orden.post_date >= '{$fin}' AND 

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


		$data= [
			'dia' => [],
			'mes' => [],
			'acumulado' => [],
		];
		$acumulado = 0;
		foreach ($pedidos as $pedido) {
			// $fin_reserva = strtotime( get_post_meta($pedido->reservaID, "_booking_end", true) );

			// -------
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

				// Por dia
					$date = date('Y-m-d', strtotime( $pedido->fecha ));
					if( !isset( $data['dia'][ $date ] ) ){
						$data['dia'][ $date ] = [
							'date' => $date,
							'monto' => $monto,
						];
					}else{
						$data['dia'][ $date ]['monto'] += $monto;
					}

				// Por mes
					$month = date('Y-m', strtotime( $pedido->fecha ));
					if( !isset( $data['mes'][ $month ] ) ){
						$data['mes'][ $month ] = [
							'date' => $month,
							'monto' => $monto,
						];
					}else{
						$data['mes'][ $month ]['monto'] += $monto;
					}

				// Acumulado
					$acumulado += $monto; 
					if( !isset( $data['acumulado'][ $month ] ) ){
						$item += 1;
						$data['acumulado'][ $month ] = [
							'date' => $month,
							'monto' => $acumulado,
							'item' => $item,
						];
					}else{
						$data['acumulado'][ $month ]['monto'] = $acumulado;
					}
			}
		}


		ksort($data['dia']);
		$r_day = array_values($data['dia']);

		ksort($data['mes']);
		$r_month = array_values($data['mes']);

		ksort($data['acumulado']);
		$r_total = array_values($data['acumulado']);

		return [
			
			'byDay' => $r_day,
			'byMonth' => $r_month,
			'total' => $r_total,

			"ventas_hoy" => "$ ".number_format( $ventas_hoy, 2, ",", "." )." MXN",
			"ventas_mes" => "$ ".number_format( $ventas_mes, 2, ",", "." )." MXN",
			"ventas_mes_anterior" => "$ ".number_format( $ventas_mes_anterior, 2, ",", "." )." MXN",
			"ventas_90" => "$ ".number_format( $ventas_90, 2, ",", "." )." MXN",
			"ventas_12" => "$ ".number_format( $ventas_12, 2, ",", "." )." MXN",
			"ventas_anio_curso" => "$ ".number_format( $ventas_anio_curso, 2, ",", "." )." MXN",
		];
	}

	public function leads(){

		$SQL = "SELECT count(id) as total, DATE_FORMAT(time, '%Y-%m-%d') as fecha FROM `wp_kmimos_subscribe` group by DATE_FORMAT(time, '%Y-%m-%d') order by fecha ASC;";
		$usuarios = $this->db->get_results($SQL);

	    $_data['byMonth']=[];
	    $_data['byDay']=[];
	    $lead_total =0;
		foreach ($usuarios as $lead) {
			// By Day
			$fecha =  $lead->fecha;
			$_data['byDay'][$fecha]['value'] = $lead->total;
			$_data['byDay'][$fecha]['date'] = $fecha;

			// By Month
			$month = date( 'Y-m', strtotime($lead->fecha) );
			if( isset($_data['byMonth'][$month]) ){
				$_data['byMonth'][$month]['value'] += $lead->total;
			}else{
				$_data['byMonth'][$month]['value'] = $lead->total;
				$_data['byMonth'][$month]['date'] = $month;
			}

			// Acumulado
			$lead_total += $lead->total;
			if( isset($_data['total'][$month]) ){
				$_data['total'][$month]['value'] = $lead_total;
			}else{
				$_data['total'][$month]['value'] = $lead_total;
				$_data['total'][$month]['date'] = $month;
			}
		}

		ksort($_data['byDay']);
		$data['byDay'] = array_values($_data['byDay']);
		$data['date'] = array_keys($_data['byDay']);

		ksort($_data['byMonth']);
		$data['byMonth'] = array_values($_data['byMonth']);

		ksort($_data['total']);
		$data['total'] = array_values($_data['total']);

		return $data;
	}

	public function noches_reservadas(){

		$SQL = "
			SELECT 
				r.ID,
				m_m.meta_value AS mascotas,
				m_i.meta_value AS inicio,
				m_f.meta_value AS fin,
				p.post_name AS servicio
			FROM
				wp_posts AS r
			INNER JOIN wp_postmeta AS m_i ON (r.ID = m_i.post_id)
			INNER JOIN wp_postmeta AS m_f ON (r.ID = m_f.post_id)
			INNER JOIN wp_postmeta AS m_m ON (r.ID = m_m.post_id)
			INNER JOIN wp_postmeta AS m_p ON (r.ID = m_p.post_id)
			INNER JOIN wp_posts    AS p   ON (p.ID = m_p.meta_value  )
			WHERE
				r.post_type = 'wc_booking' AND r.post_status = 'confirmed' AND
				m_m.meta_key = '_booking_persons' AND 
				m_i.meta_key = '_booking_start' AND 
				m_f.meta_key = '_booking_end'
			ORDER BY r.post_date ASC
		";
		$reservas = $this->db->get_results($SQL);
		$min_date = '';
		$max_date = '';
		$reservas_por_dia = [];
		$reservas_por_mes = [];
		$reservas_acumulado = [];
		$total = 0;

	    foreach ($reservas as $key => $reserva) {
	    	$reserva->inicio = ( strlen($reserva->inicio) > 8 ) ? substr($reserva->inicio, 0, 8) : $reserva->inicio;
	    	$reserva->fin = ( strlen($reserva->fin) > 8 ) ? substr($reserva->fin, 0, 8) : $reserva->fin;
	    	$ini = strtotime($reserva->inicio);
	    	$fin = strtotime($reserva->fin);
	    	$_mas = unserialize($reserva->mascotas);
	    	$mas = 0;
	    	foreach ($_mas as $key => $value) {
	    		$mas += $value;
	    	}
	    	$tipo = explode("-", $reserva->servicio);
	    	$tipo = $tipo[0];
	    	$fin = ( $tipo == 'hospedaje' ) ? $fin-86400 : $fin;
	    	for ($i=$ini; $i <= $fin; $i+=86400) { 
	    		$anio = date("Y", $i);
	    		if( $anio > 2015){
		    		$min_date = ($min_date == '' || $min_date > $i) ? $i : $min_date;
		    		$max_date = ($max_date == '' || $max_date < $i) ? $i : $max_date;
	    		}
	    		$mes_id = $anio = date("Y-m", $i);
	    		if( $mes_id == "2018-10" ){
	    			$mes_oct += $mas;
	    		}
	    		$reservas_por_mes[ $mes_id ] += $mas;
				$reservas_por_dia[ date("Y-m-d", $i) ] += $mas;
	    	}
	    }

	    ksort($reservas_por_dia);
	    ksort($reservas_por_mes);
	    ksort($reservas_acumulado);

	    foreach ($reservas_por_mes as $key => $value) {
	    	$total += $value;
	    	$reservas_acumulado[ $key ] = $total;
	    }

		$data = [];
	    $data['byMonth']=[];
	    $data['byDay']=[];
	    $data['total']=[];

	    foreach ($reservas_por_dia as $key => $value) {
	    	$data['byDay'][] = [
	    		"date" => $key,
	    		"value" => $value
	    	];
	    }

	    foreach ($reservas_por_mes as $key => $value) {
	    	$data['byMonth'][] = [
	    		"date" => $key,
	    		"value" => $value
	    	];
	    }
	    
	    foreach ($reservas_acumulado as $key => $value) {
	    	$data['total'][] = [
	    		"date" => $key,
	    		"value" => $value
	    	];
	    }

	    $data['date'] = array_keys($reservas_por_dia);

		return $data;
	}

	public function registro(){

		$SQL = "SELECT 
					count(u.ID) as total,
					DATE_FORMAT(u.user_registered,'%Y-%m-%d') as fecha,
					CASE WHEN c.id is NULL THEN 'cuidador' ELSE 'cliente' END as tipo
			FROM wp_users as u
					LEFT JOIN cuidadores as c ON c.user_id = u.ID
			GROUP BY DATE_FORMAT(u.user_registered,'%Y-%m-%d'), CASE WHEN c.id is NULL THEN 'cuidador' ELSE 'cliente' END
			ORDER BY fecha ASC;
		";
		$usuarios = $this->db->get_results($SQL);

	    $_data['byMonth']=[];
	    $_data['byDay']=[];

	    $total = 0;
	    $cliente = 0;
	    $cuidador = 0;
		foreach ($usuarios as $item) {
			// By Day
				$fecha =  $item->fecha;
				if( isset($_data['byDay'][$month]) ){
					$_data['byDay'][$fecha]['value'] += $item->total;
				}else{
					$_data['byDay'][$fecha]['value'] = $item->total;
					$_data['byDay'][$fecha]['date'] = $fecha;
					$_data['byDay'][$fecha]['cliente'] = 0;
					$_data['byDay'][$fecha]['cuidador'] = 0;
				}
				if( $item->tipo == 'cliente' ){
					$_data['byDay'][$fecha]['cliente'] += $item->total;
				}else{
					$_data['byDay'][$fecha]['cuidador'] += $item->total;
				}

			// By Month
				$month = date( 'Y-m', strtotime($item->fecha) );
				if( isset($_data['byMonth'][$month]) ){
					$_data['byMonth'][$month]['value'] += $item->total;
				}else{
					$_data['byMonth'][$month]['value'] = $item->total;
					$_data['byMonth'][$month]['date'] = $month;
					$_data['byMonth'][$month]['cliente'] = 0;
					$_data['byMonth'][$month]['cuidador'] = 0;
				}
				if( $item->tipo == 'cliente' ){
					$_data['byMonth'][$month]['cliente'] += $item->total;
				}else{
					$_data['byMonth'][$month]['cuidador'] += $item->total;
				}

			// Acumulado
				$total += $item->total;
				if( isset($_data['total'][$month]) ){
					$_data['total'][$month]['value'] = $total;
				}else{
					$_data['total'][$month]['value'] = $total;
					$_data['total'][$month]['date'] = $month;
					$_data['total'][$month]['cliente'] = 0;
					$_data['total'][$month]['cuidador'] = 0;
				}
				if( $item->tipo == 'cliente' ){
					$cliente += $item->total;
					$_data['total'][$month]['cliente'] = $cliente;
				}else{
					$cuidador += $item->total;
					$_data['total'][$month]['cuidador'] = $cuidador;
				}

		}

		ksort($_data['byDay']);
		$data['byDay'] = array_values($_data['byDay']);

		ksort($_data['byMonth']);
		$data['byMonth'] = array_values($_data['byMonth']);

		ksort($_data['total']);
		$data['total'] = array_values($_data['total']);

		return $data;
	}
}