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

		wp_add_dashboard_widget( 'leads_dashboard_widget', 'Resumen de Leads', 'leads_dashboard_widget_function' );
		wp_add_dashboard_widget( 'registro_dashboard_widget', 'Resumen de Registros', 'registro_dashboard_widget_function' );

	}
	add_action( 'wp_dashboard_setup', 'resumen_add_dashboard_widgets' );

	function resumen_dashboard_widget_function() {
		include('views/resumen-reservas.php');
	}
	
	function ventas_dashboard_widget_function() {
		include('views/resumen-ventas.php');
	}

	function noches_dashboard_widget_function(){
		include('views/resumen-noches.php');
	}

	function leads_dashboard_widget_function(){
		include('views/resumen-leads.php');
	}

	function registro_dashboard_widget_function(){
		include('views/resumen-registro.php');
	}
?>