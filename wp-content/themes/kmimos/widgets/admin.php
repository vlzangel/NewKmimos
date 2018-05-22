<?php

	function resumen_add_dashboard_widgets() {
		wp_add_dashboard_widget( 'resumen_dashboard_widget', 'Resumen del Mes', 'resumen_dashboard_widget_function' );	

        $permitidos = array(
            367, // Kmimos
            8604, // Rob
            12795, // Rodriguez
            8574, // Elvira
        );

        if( !in_array($current_user->ID, $permitidos)){
            wp_add_dashboard_widget( 'ventas_dashboard_widget', 'Resumen de Ventas', 'ventas_dashboard_widget_function' );	
        }

	}
	add_action( 'wp_dashboard_setup', 'resumen_add_dashboard_widgets' );

	function resumen_dashboard_widget_function() {
		global $wpdb;

		$inicio = date("Y-m")."-01 00:00:00";
		$fin = date("Y-m", strtotime ( '+1 month' , time() ) )."-01 23:59:59";

		$sql = "
			SELECT 
				orden.ID AS ordenID,
				reserva.ID AS reservaID,
				orden.post_status AS ordenStatus,
				reserva.post_status AS reservaStatus
			FROM 
				wp_posts AS orden
			INNER JOIN wp_posts AS reserva ON ( orden.ID = reserva.post_parent )
			WHERE 
				orden.post_date >= '{$inicio}' AND 
				orden.post_date <= '{$fin}' AND 
				orden.post_type = 'shop_order' ";

		$pedidos = $wpdb->get_results( $sql );
		
		$pendientes = 0;
		$confirmadas = 0;
		$completadas = 0;
		$modificadas = 0;
		$canceladas = 0;

		foreach ($pedidos as $pedido) {

			switch ( $pedido->ordenStatus ) {
				case 'wc-confirmed':
					$fin = strtotime( get_post_meta($pedido->reservaID, "_booking_end", true) );
					if( time() <= $fin){
						$completadas++;
					}
					$confirmadas++;
				break;
				case 'wc-completed':
					$pendientes++;
				break;
				case 'wc-partially-paid':
					$pendientes++;
				break;
				case 'wc-cancelled':
					$canceladas++;
				break;
				case 'modified':
					$modificadas++;
				break;
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
			</style>
			<div id="woocommerce_dashboard_status" class="resumen_mes" >
				<ul class="wc_status_list">
					<li class="sales-this-month">
						<a><strong><span class="amount">'.$confirmadas.'</span> Reservas</strong> Confirmadas + Pendientes</a>
					</li>
					<li class="processing-orders">
						<a><strong>'.$pendientes.' Reservas</strong> Pendientes</a>
					</li>
					<li class="completed">
						<a><strong>'.$completadas.' Reservas</strong> Completadas</a>
					</li>
					<li class="on-hold-orders">
						<a><strong>'.$canceladas.' Reservas</strong> Canceladas</a>
					</li>
					<li class="on-hold-orders modified">
						<a><strong>'.$modificadas.' Reservas</strong> Modificadas</a>
					</li>
				</ul>
			</div>
		';
	}

	function ventas_dashboard_widget_function() {
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
		
		$ventas_mes = 0;
		$ventas_90 = 0;
		$ventas_12 = 0;
		$ventas_anio_curso = 0;

		$mes_en_curso = strtotime ( date("Y-m").'-1' );
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

			if( $mes_en_curso <= $fecha ){
				$ventas_mes += $monto;
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
			<div id="woocommerce_dashboard_status" class="resumen_ventas">
				<ul class="wc_status_list">
					<li class="sales-this-month">
						<a><strong><span class="amount">$ '.number_format( $ventas_mes, 2, ',', '.').' MXN</strong> Mes en curso</a>
					</li>
					<li class="processing-orders">
						<a><strong>$ '.number_format( $ventas_90, 2, ',', '.').' MXN</strong> &Uacute;ltimos 90 d&iacute;as</a>
					</li>
					<li class="completed">
						<a><strong>$ '.number_format( $ventas_12, 2, ',', '.').' MXN</strong> &Uacute;ltimos 12 meses </a>
					</li>
					<li class="on-hold-orders">
						<a><strong>$ '.number_format( $ventas_anio_curso, 2, ',', '.').' MXN</strong> A&ntilde;o en curso</a>
					</li>
					<li class="on-hold-orders">
						
					</li>
				</ul>
			</div>
		';
	}
?>