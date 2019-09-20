<?php
	
	include dirname(dirname(__DIR__)).'/wp-load.php';
    date_default_timezone_set('America/Mexico_City');
	global $wpdb;

	function update_campaing($campaing, $data, $d, $enviados) {
		global $wpdb;
		$d->plantilla = preg_replace("/[\r\n|\n|\r]+/", " ", $d->plantilla);
		$d->plantilla = str_replace('"', '\"', $d->plantilla);
		$d->plantilla = str_replace("'", '', $d->plantilla);
		$d->plantilla = str_replace('<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>', '', $d->plantilla);
		$data->data = $d;
		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		$enviados = json_encode($enviados, JSON_UNESCAPED_UNICODE);
		$sql = "UPDATE vlz_campaing SET data = '{$data}', enviados = '{$enviados}' WHERE id = ".$campaing->id;
		$wpdb->query( $sql );

		$data = json_decode($campaing->data);
		$d = $data->data;

		switch ( $data->hacer_despues+0 ) {
			case 1:

			break;
		}
	}

	function get_email_no_abiertos($data, $espera, $enviados){
		$vistos = [];
		$_vistos = ( isset($data->vistos) ) ? $data->vistos : [];
		foreach ($_vistos as $key => $cliente) {
			$vistos[] = $cliente->email;
		}
		$no_abiertos = [];
		foreach ($enviados as $email => $enviado_date) {
			if( !in_array($email, $vistos) ){
				if( (time()-$enviado_date) >= $espera ){
					$no_abiertos[] = $email;
				}
			}
		}
		return $no_abiertos;
	}

	function add_seguimiento($mensaje, $info){
		$mensaje = preg_replace("/[\r\n|\n|\r]+/", " ", $mensaje);
		preg_match_all("#href=\"http(.*?)\"#i", $mensaje, $matches);
		$url_base = get_home_url().'/campaing_2';
		foreach ($matches[1] as $key => $url) {
			$old_url = "http".$url;
			$data = base64_encode( json_encode( [
				"id" => $info["campaing"],
				"email" => $info["email"],
				"url" => $old_url,
			] ) );
			$new_url = $url_base.'/'.$data.'/redi';
			$mensaje = str_replace($old_url, $new_url, $mensaje);
		}
		return $mensaje;
	}

	$campaings = $wpdb->get_results("SELECT * FROM vlz_campaing"); // WHERE data NOT LIKE '%\"ENVIADO\":\"SI\"%'

	/*
		echo "<pre>";
			print_r($campaings);
		echo "</pre>";
	*/

	foreach ($campaings as $key => $campaing) {

		$data = json_decode($campaing->data);
		$d = $data->data;

		switch ( $data->hacer_despues+0 ) {
			case 0:
				$fecha = strtotime( $d->fecha." ".$d->hora );
				if( $fecha <= time() ){
					$fecha_fin = strtotime( $d->fecha_fin." ".$d->hora_fin );
					if( $fecha_fin >= time() ){

						$_listas = $data->data_listas;
						// $d->ENVIADO = "SI";
						$enviados = ( $campaing->enviados != '' ) ? (array) json_decode($campaing->enviados) : [];
						$_listas = $wpdb->get_results("SELECT * FROM vlz_listas WHERE id IN ( ".implode(",", $_listas)." ) ");
						if( !empty($_listas) ){
							foreach ($_listas as $lista) {
								$_d = json_decode($lista->data);
								foreach ($_d->suscriptores as $cliente) {
									$email = $cliente[1];

									if( !array_key_exists($email, $enviados) ){ 
										$enviados[ $email ] = time();

										$info_validacion = base64_encode( json_encode( [
											"id" => $campaing->id,
											"type" => "img",
											"format" => "png",
											"email" => $email
										] ) );

										$mensaje = $campaing->plantilla.'<img src="'.get_home_url().'/campaing_2/'.$info_validacion.'/'.md5($info_validacion).'.png" />';
										
										$mensaje = add_seguimiento($mensaje, [
											"campaing" => $campaing->id,
											"email" => trim($email),
										]);

										// wp_mail( trim($email) , $d->asunto, $mensaje);
									}
								}
							}
						}
					}
				}

			break;
			case 1:

				$un_dia = 60; // Prueba en minutos 60 segundos, en producción colocar: 1 dia > 86400 segundos;
				$esperar = $data->campaing_despues_delay*$un_dia;
				$anterior = $wpdb->get_row("SELECT * FROM vlz_campaing WHERE id = ".$data->campaing_anterior);
				$data_anterior = json_decode($anterior->data);

				$padre_id = "padre_".$data->campaing_anterior;
				$enviados = ( $campaing->enviados != '' ) ? (array) json_decode($campaing->enviados) : [];

				switch ( $data->campaing_despues_no_abre ) {
					case 'si':
						$vistos = ( isset($data_anterior->vistos) ) ? $data_anterior->vistos : [];
						foreach ($vistos as $key => $cliente) {
							$enviado_date = $cliente->fecha;
							$email = $cliente->email;
							if( (time()-$enviado_date) >= $esperar ){
								if( !array_key_exists($email, $enviados[$padre_id]) ){ 
									$enviados[$padre_id][ $email ] = time();
									$info_validacion = base64_encode( json_encode( [
										"id" => $campaing->id,
										"type" => "img",
										"format" => "png",
										"email" => $email
									] ) );
									$mensaje = $campaing->plantilla.'<img src="'.get_home_url().'/campaing_2/'.$info_validacion.'/'.md5($info_validacion).'.png" />';
									
									$mensaje = add_seguimiento($mensaje, [
										"campaing" => $campaing->id,
										"email" => trim($email),
									]);

									// wp_mail( trim($email) , $d->asunto, $mensaje);
								}
							}
						}
					break;
					case 'no':
						$no_abiertos = get_email_no_abiertos($data_anterior, $esperar, json_decode($campaing->enviados));
						foreach ($no_abiertos as $key => $email) {
							if( !array_key_exists($email, $enviados[$padre_id]) ){ 
								$enviados[$padre_id][ $email ] = time();
								$info_validacion = base64_encode( json_encode( [
									"id" => $campaing->id,
									"type" => "img",
									"format" => "png",
									"email" => $email
								] ) );
								$mensaje = $campaing->plantilla.'<img src="'.get_home_url().'/campaing_2/'.$info_validacion.'/'.md5($info_validacion).'.png" />';
								
								$mensaje = add_seguimiento($mensaje, [
									"campaing" => $campaing->id,
									"email" => trim($email),
								]);

								// wp_mail( trim($email) , $d->asunto, $mensaje);
							}
						}
					break;
				}

				$padre_id_solo = $data->campaing_anterior;
				$otro_flujo = $wpdb->get_row("SELECT * FROM vlz_campaing WHERE data LIKE '%campaing_anterior\":\"{$padre_id_solo}%' AND id != {$campaing->id} ");
				$enviados_otro = ( $otro_flujo->enviados != '' ) ? (array) json_decode($otro_flujo->enviados) : [];
				foreach ($enviados[$padre_id] as $key => $email) {
					if( !array_key_exists($email, $enviados_otro[$padre_id]) ){ 
						$enviados_otro[$padre_id][ $email ] = time();
					}
				}
				$data_otros = json_encode($enviados_otro, JSON_UNESCAPED_UNICODE);
				$sql = "UPDATE vlz_campaing SET enviados = '{$data_otros}' WHERE id = ".$otro_flujo->id;
				// $wpdb->query( $sql );
				// wp_mail('vlzangel91@gmail.com', 'SQL', $sql );
			break;
		}
		
		// $data->enviados = $enviados;
		update_campaing($campaing, $data, $d, $enviados);
		
	}
	
?>