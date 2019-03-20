<?php

	function resumen_add_dashboard_widgets() {

		/* BEGIN amCharts-v4 */		
	    wp_enqueue_script("c4-core", getTema().'/lib/amcharts4/core.js', array("jquery"), '1.0.0');
	    wp_enqueue_script("c4-charts", getTema().'/lib/amcharts4/charts.js', array("jquery"), '1.0.0');
	    wp_enqueue_script("c4-animated", getTema().'/lib/amcharts4/themes/animated.js', array("jquery"), '1.0.0');
		/* END amCharts-v4 */

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
		include('views/resumen-reservas.php');
	}
	
	function ventas_dashboard_widget_function() {
		include('views/resumen-ventas.php');

		/*
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
		*/
	}

	function noches_dashboard_widget_function(){
		include('views/resumen-noches.php');
	}
?>