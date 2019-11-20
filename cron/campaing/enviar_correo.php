<?php
	
	$time_start = microtime(true);

	function phpmailer_init_vlz() {
		return [
			"email" => "promociones@kmimos.la",
			"clave" => "Kmimos2019",
			"From" => "promociones@kmimos.la",
			"FromName" => "Promociones Kmimos",
		];
	}

	include dirname(dirname(__DIR__)).'/wp-load.php';
        date_default_timezone_set('America/Mexico_City');
	global $wpdb;



	function _desuscrito($email){
		global $wpdb;
		$existe = $wpdb->get_row("SELECT * FROM vlz_desuscritos WHERE email = '{$email}' ");
		return ( empty($existe) );
	}

	$para_enviar = $wpdb->get_results("SELECT * FROM vlz_envios WHERE por_enviar != '[]' ORDER BY rand() LIMIT 1C");

	foreach ($para_enviar as $key => $envios) {
		$por_enviar = (array) json_decode( $envios->por_enviar );
		$enviados = (array) json_decode( $envios->enviados );

		if( count($por_enviar) > 0 ){

			$campaing = $wpdb->get_row("SELECT * FROM vlz_campaing WHERE id = '{$envios->campaing}' ");
			$data = json_decode($campaing->data);
			$d = $data->data;

			$contador = 0;
			$por_enviar_new = [];
			foreach ($por_enviar as $key => $email) {
				$contador++;
				if( $contador > 2 ){
					$por_enviar_new[] = $email;
				}else{
					if( !in_array($email, haystack) ){
						$enviados[] = $email;
						$info_validacion = base64_encode( json_encode( [
							"id" => $campaing->id,
							"type" => "img",
							"format" => "png",
							"email" => $email
						] ) );
						$mensaje = $campaing->plantilla.'<img src="'.get_home_url().'/campaing_2/'.$info_validacion.'/'.md5($info_validacion).'.png" />';
						$mensaje = add_seguimiento_($mensaje, [
							"campaing" => $campaing->id,
							"email" => trim($email),
						]);
						$info_desuscribir = base64_encode( json_encode( [
							"campaing_id" => $campaing->id,
							"email" => $email
						] ) );
						$mensaje = str_replace("#FIN_SUSCRIPCION#", get_home_url().'/campaing_2/'.$info_desuscribir.'/end', $mensaje);
					}
					vlz_enviar_campaing( trim($email) , $d->asunto, $mensaje);
				}

			}

			/*
			echo "<pre>";
				print_r( $por_enviar_new );
				echo "<br><br>";
				print_r( $enviados );
			echo "</pre>";
			*/

			$por_enviar_new = json_encode( $por_enviar_new );
			$enviados = json_encode( $enviados );

			$wpdb->query("
				UPDATE
					vlz_envios
				SET
					por_enviar = '{$por_enviar_new}',
					enviados = '{$enviados}'
				WHERE 
					id = {$envios->id}
			"); 
			

		}
	}

	$time_end = microtime(true);
	$time = $time_end - $time_start;

	//echo "No se hizo nada en $time segundos\n";
?>