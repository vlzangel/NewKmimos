<?php

	function resumen_add_dashboard_widgets() {
		wp_add_dashboard_widget( 'resumen_dashboard_widget', 'Resumen de Reservas', 'resumen_dashboard_widget_function' );	

        if( !in_array($current_user->ID, array(
            367, // Kmimos
            8604, // Rob
            12795, // Rodriguez
            8574, // Elvira
        ))){
            wp_add_dashboard_widget( 'ventas_dashboard_widget', 'Resumen de Ventas', 'ventas_dashboard_widget_function' );	
        }

        if( !in_array($current_user->ID, array(
            367, // Kmimos
            8604, // Rob
            12795, // Rodriguez
            8574, // Elvira
        ))){
            wp_add_dashboard_widget( 'noches_dashboard_widget', 'Resumen de Noches Reservadas', 'noches_dashboard_widget_function' );	
        }

	}
	add_action( 'wp_dashboard_setup', 'resumen_add_dashboard_widgets' );

	function resumen_dashboard_widget_function() {

		date_default_timezone_set('America/Mexico_City');
		
		global $wpdb;

		include('graficos/resumen-reservas.php');

		$inicio = date("Y-m")."-01 00:00:00";
		$fin = date("Y-m", strtotime ( '+1 month' , time() ) )."-01 23:59:59";

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

		$pedidos = $wpdb->get_results( $sql );
		
		$_hoy = 0;
		$_mes = 0;

		$pendientes = 0;
		$confirmadas = 0;
		$completadas = 0;
		$modificadas = 0;
		$canceladas = 0;

		$dia_en_curso = strtotime ( date("Y-m-d") );
		$mes_en_curso = strtotime ( date("Y-m").'-1' );
		$mes_anterior = strtotime ( date("Y-m", strtotime ( '-1 month' , time() ) ).'-1' );

		foreach ($pedidos as $pedido) {

			$monitorear = false;
			$fecha = strtotime( $pedido->fecha );

			switch ( $pedido->ordenStatus ) {
				case 'wc-confirmed':
					$fin = strtotime( get_post_meta($pedido->reservaID, "_booking_end", true) );
					if( time() <= $fin){
						$completadas++;
					}
					$confirmadas++;
					$monitorear = true;
				break;
				case 'wc-completed':
					$pendientes++;
					$confirmadas++;
					$monitorear = true;
				break;
				case 'wc-partially-paid':
					$pendientes++;
					$confirmadas++;
					$monitorear = true;
				break;
				case 'wc-cancelled':
					$canceladas++;
				break;
				case 'modified':
					$modificadas++;
				break;
			}

			if( $monitorear ){

				if( $dia_en_curso <= $fecha ){
					$_hoy++;
				}
			}

		}


		$inicio = date("Y-m", strtotime ( '-2 month' , time() ) );
		$fin = date("Y-m")."-01 00:00:00";

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

		$pedidos = $wpdb->get_results( $sql );
		
		$_mes = 0;

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

		echo '
			<style>
				.resumen_mes .wc_status_list li.processing-orders a:before {
					color: #828282 !important;
				}
				.resumen_mes .wc_status_list li.completed a:before {
					content: "\e015" !important;
					color: #7ad03a !important;
				}
				.resumen_mes .wc_status_list li.on-hold-orders a:before {
					color: #a00 !important;
				}

				.resumen_mes .wc_status_list li.modified a:before {
					color: #ffba00 !important;
				}

				#woocommerce_dashboard_status .wc_status_list li a:before {
				    margin: 0 8px 12px 0 !important;
				}
				#woocommerce_dashboard_status .wc_status_list li.low-in-stock, #woocommerce_dashboard_status .wc_status_list li.processing-orders {
				    border-right: 0px;
				}

				.resumen_mes .wc_status_list li a {
				    padding: 9px 0px !important;
				}
			</style>
			<div id="woocommerce_dashboard_status" class="resumen_mes" >
				<ul class="wc_status_list">
					<li class="sales-this-month">
						<a><strong><span class="amount">'.$confirmadas.'</span> Reservas</strong> Confirmadas + Pendientes <span style="font-weight: bold;">( Este Mes )</span> </a>
					</li>

					<li class="completed">
						<a><strong>'.$_hoy.' Reservas</strong> Confirmadas <span style="font-weight: bold;">( Hoy )</span></a>
					</li>

					<li class="processing-orders">
						<a><strong>'.$pendientes.' Reservas</strong> Pendientes <span style="font-weight: bold;">( Este Mes )</span> </a>
					</li>
					<li class="completed">
						<a><strong>'.$completadas.' Reservas</strong> Completadas <span style="font-weight: bold;">( Este Mes )</span> </a>
					</li>
					<li class="on-hold-orders">
						<a><strong>'.$canceladas.' Reservas</strong> Canceladas <span style="font-weight: bold;">( Este Mes )</span> </a>
					</li>
					<li class="on-hold-orders modified">
						<a><strong>'.$modificadas.' Reservas</strong> Modificadas <span style="font-weight: bold;">( Este Mes )</span> </a>
					</li>

					<li class="completed">
						<a style="visibility: hidden;"><strong>'.$_mes.' Reservas</strong> Confirmadas <span style="font-weight: bold;">( Mes pasado )</span> </a>
					</li>

					<li class="completed">
						<a><strong>'.$_mes.' Reservas</strong> Confirmadas <span style="font-weight: bold;">( Mes pasado )</span> </a>
					</li>

					<li class="completed">
						
					</li>
				</ul>
			</div>
		';

	}
	

	function ventas_dashboard_widget_function() {

		date_default_timezone_set('America/Mexico_City');
		
		global $wpdb;

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

		$pedidos = $wpdb->get_results( $sql );
		
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

		}

		echo '
			<style>

				.resumen_ventas .wc_status_list {
				    overflow: hidden;
				}

				.resumen_ventas .wc_status_list li {
				    width: 50%;
				    float: left;
				    padding: 0;
				    box-sizing: border-box;
				    margin: 0;
				    color: #aaa;
				}

				.resumen_ventas .wc_status_list li a {
				    display: block;
				    color: #aaa;
				    padding: 9px 12px;
				    -webkit-transition: all ease .5s;
				    position: relative;
				    font-size: 12px;
				}

				.resumen_ventas .wc_status_list li a strong {
				    font-size: 18px;
				    line-height: 1.2em;
				    font-weight: 400;
				    display: block;
				    color: #21759b;
				}

				.resumen_ventas .wc_status_list li.borderTop {
				    border-top: 1px solid #ececec;
				}

				.resumen_ventas .wc_status_list li.processing-orders a:before {
					content: "" !important;
				    margin: 0px !important;
				    padding: 0px !important;
				    width: 0px !important;
				}
				.resumen_ventas .wc_status_list li.completed a:before {
					content: "" !important;
				    margin: 0px !important;
				    padding: 0px !important;
				    width: 0px !important;
				}
				.resumen_ventas .wc_status_list li.on-hold-orders a:before {
					content: "" !important;
				    margin: 0px !important;
				    padding: 0px !important;
				    width: 0px !important;
				}

				.resumen_ventas .wc_status_list li.modified a:before {
					content: "" !important;
				    margin: 0px !important;
				    padding: 0px !important;
				    width: 0px !important;
				}
			</style>
			<div id="xwoocommerce_dashboard_status" class="resumen_ventas">
				<ul class="wc_status_list">

					<!--
					<li class="sales-this-month">
						<a><strong><span class="amount">$ '.number_format( $ventas_hoy, 2, ',', '.').' MXN</strong> D&iacute;a en curso</a>
					</li>
					<li class="sales-this-month">
						<a><strong><span class="amount">$ '.number_format( $ventas_mes, 2, ',', '.').' MXN</strong> Mes en curso</a>
					</li>
					-->

					<li class="on-hold-orders">
						<a><strong>$ '.number_format( $ventas_hoy, 2, ',', '.').' MXN</strong> D&iacute;a en curso </a>
					</li>
					<li class="on-hold-orders">
						<a><strong>$ '.number_format( $ventas_mes, 2, ',', '.').' MXN</strong> Mes en curso </a>
					</li>


					<li class="on-hold-orders borderTop">
						<a><strong>$ '.number_format( $ventas_mes_anterior, 2, ',', '.').' MXN</strong> Mes Anterior</a>
					</li>
					<li class="on-hold-orders borderTop">
						<a><strong>$ '.number_format( $ventas_90, 2, ',', '.').' MXN</strong> &Uacute;ltimos 90 d&iacute;as</a>
					</li>

					<li class="on-hold-orders borderTop">
						<a><strong>$ '.number_format( $ventas_12, 2, ',', '.').' MXN</strong> &Uacute;ltimos 12 meses </a>
					</li>
					<li class="on-hold-orders borderTop">
						<a><strong>$ '.number_format( $ventas_anio_curso, 2, ',', '.').' MXN</strong> A&ntilde;o en curso</a>
					</li>
				</ul>
			</div>
		';
	}

	function noches_dashboard_widget_function(){

		date_default_timezone_set('America/Mexico_City');
		
		global $wpdb;

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

		$pedidos = $wpdb->get_results( $sql );

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

		echo '
			<style>

				.resumen_ventas .wc_status_list {
				    overflow: hidden;
				}

				.resumen_ventas .wc_status_list li {
				    width: 50%;
				    float: left;
				    padding: 0;
				    box-sizing: border-box;
				    margin: 0;
				    color: #aaa;
				}

				.resumen_ventas .wc_status_list li a {
				    display: block;
				    color: #aaa;
				    padding: 9px 12px;
				    -webkit-transition: all ease .5s;
				    position: relative;
				    font-size: 12px;
				}

				.resumen_ventas .wc_status_list li a strong {
				    font-size: 18px;
				    line-height: 1.2em;
				    font-weight: 400;
				    display: block;
				    color: #21759b;
				}

				.resumen_ventas .wc_status_list li.borderTop {
				    border-top: 1px solid #ececec;
				}

				.resumen_ventas .wc_status_list li.processing-orders a:before {
					content: "" !important;
				    margin: 0px !important;
				    padding: 0px !important;
				    width: 0px !important;
				}
				.resumen_ventas .wc_status_list li.completed a:before {
					content: "" !important;
				    margin: 0px !important;
				    padding: 0px !important;
				    width: 0px !important;
				}
				.resumen_ventas .wc_status_list li.on-hold-orders a:before {
					content: "" !important;
				    margin: 0px !important;
				    padding: 0px !important;
				    width: 0px !important;
				}

				.resumen_ventas .wc_status_list li.modified a:before {
					content: "" !important;
				    margin: 0px !important;
				    padding: 0px !important;
				    width: 0px !important;
				}
			</style>
			<div id="xwoocommerce_dashboard_status" class="resumen_ventas">
				<ul class="wc_status_list">
					<!--
					<li class="sales-this-month">
						<a><strong><span class="amount">'.$ventas_mes.' Noches</strong> Mes en curso</a>
					</li>
					<li class="processing-orders">
						<a><strong>'.$ventas_90.' Noches</strong> &Uacute;ltimos 90 d&iacute;as</a>
					</li>
					<li class="completed">
						<a><strong>'.$ventas_12.' Noches</strong> &Uacute;ltimos 12 meses </a>
					</li>
					<li class="on-hold-orders">
						<a><strong>'.$ventas_anio_curso.' Noches</strong> A&ntilde;o en curso</a>
					</li>
					<li class="on-hold-orders">
						
					</li>
					-->



					<li class="on-hold-orders">
						<a><strong>'.( $ventas_hoy).' Noches</strong> D&iacute;a en curso</a>
					</li>
					<li class="on-hold-orders">
						<a><strong>'.( $ventas_mes).' Noches</strong> Mes en curso </a>
					</li>


					<li class="on-hold-orders borderTop">
						<a><strong>'.( $ventas_mes_anterior).' Noches</strong> Mes Anterior</a>
					</li>
					<li class="on-hold-orders borderTop">
						<a><strong>'.( $ventas_90).' Noches</strong> &Uacute;ltimos 90 d&iacute;as</a>
					</li>

					<li class="on-hold-orders borderTop">
						<a><strong>'.( $ventas_12).' Noches</strong> &Uacute;ltimos 12 meses </a>
					</li>
					<li class="on-hold-orders borderTop">
						<a><strong>'.( $ventas_anio_curso).' Noches</strong> A&ntilde;o en curso</a>
					</li>
				</ul>
			</div>
		';
	}
?>