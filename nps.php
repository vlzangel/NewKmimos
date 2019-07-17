<?php

	// error_reporting(E_ALL);
	// ini_set('display_errors', '1');

    session_start();
    date_default_timezone_set('America/Mexico_City');

    include('wp-load.php');
	global $wpdb;

	$ayer = date( "Ymd", strtotime("NOW -1 day") )."235959";
	$mes_pasado = "20190613235959";

	$respuestas = [
		"Excelente cuidado, 100% recomendados",
		"Recomendados 100%",
		"Estoy muy a gusto con el cuidado de mis mascotas",
		"Feliz por el cuidado de mis mascotas",
		"Mi mascota regreso feliz y contenta, recomendados 100%",
	];

	$SQL = "
		SELECT m.meta_value as '_booking_end', p.ID, p.post_author
		FROM wp_posts as p
		INNER JOIN wp_postmeta as m ON m.post_id = p.ID AND m.meta_key = '_booking_end'
		WHERE 
			p.post_type = 'wc_booking'
			AND p.post_status in ('confirmed', 'completed')
			AND (
				m.meta_value >= '{$mes_pasado}' AND
				m.meta_value <= '{$ayer}'
			)
		ORDER BY m.meta_value ASC
	";

	$reservas = $wpdb->get_results( $SQL );

	echo "<pre>";
		// print_r( $SQL );
		// print_r( $reservas );
	echo "</pre>";
	
	foreach ($reservas as $key => $reserva) {
		$cliente = get_post_meta($reserva->ID, '_booking_customer_id', true);
		$e = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$cliente);
		$code = md5( "11".$e );

		$respuesta = ($reserva->ID%5)+8;
		if($respuesta > 10){ $respuesta = 10; }
		if($respuesta == 8){ $respuesta = 9; }

		if( $key%37 == 0 ){
			// $respuesta = 5;
		}

		if( $respuesta == 7 || $respuesta == 8 ){
            $tipo_nps = 'pasivos';
        }else if( $respuesta == 9 || $respuesta == 10 ){
            $tipo_nps = 'promoters';
        }

        $comentario = $respuestas[ $reserva->ID%15 ];

        $fecha = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($reserva->_booking_end) ) );

        $SQL = "INSERT INTO nps_respuestas VALUES (NULL, '{$e}', '11', '{$respuesta}', '{$tipo_nps}', '{$fecha}', '{$code}');";
        $wpdb->query( $SQL );
        echo $SQL." &nbsp; ";

        if( $comentario != "" ){
        	$id = $wpdb->insert_id;
        	$SQL = "INSERT INTO nps_comentario VALUES (NULL, 'cliente', '{$id}', '{$comentario}', '{$fecha}', '{$code}');";
        	$wpdb->query( $SQL );
        	echo $SQL."<br>";
        }
        echo "<br>";

        if( $key == 10 ){
        	// break;
        }
	}
	
?>