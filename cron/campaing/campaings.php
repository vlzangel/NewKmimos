<?php
	
	include dirname(dirname(__DIR__)).'/wp-load.php';
    date_default_timezone_set('America/Mexico_City');
	global $wpdb;

	function update_campaing($campaing, $data, $d) {
		global $wpdb;
		$d->plantilla = preg_replace("/[\r\n|\n|\r]+/", " ", $d->plantilla);
		$d->plantilla = str_replace('"', '\"', $d->plantilla);
		$d->plantilla = str_replace("'", '', $d->plantilla);
		$d->plantilla = str_replace('<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>', '', $d->plantilla);
		$data->data = $d;
		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		$sql = "UPDATE vlz_campaing SET data = '{$data}' WHERE id = ".$campaing->id;
		$wpdb->query( $sql );
	}

	function get_email_no_abiertos($data, $espera){
		$vistos = [];
		$_vistos = ( isset($data_anterior->vistos) ) ? $data_anterior->vistos : [];
		foreach ($_vistos as $key => $cliente) {
			$vistos[] = $cliente[1];
		}
		$no_abiertos = [];
		$enviados = ( isset($data->enviados) ) ? $data->enviados : [];
		foreach ($enviados as $enviado_date => $email) {
			if( !in_array($email, $vistos) ){
				if( (time()-$enviado_date) >= $espera ){
					$no_abiertos[] = $email;
				}
			}
		}
		return $no_abiertos;
	}

	$campaings = $wpdb->get_results("SELECT * FROM vlz_campaing WHERE data NOT LIKE '%\"ENVIADO\":\"SI\"%' ");
	foreach ($campaings as $key => $campaing) {
		$data = json_decode($campaing->data);
		$d = $data->data;
		
		switch ( $d->hacer_despues+0 ) {
			case 0:

				$fecha = strtotime( $d->fecha." ".$d->hora );
				if( $fecha <= time() ){
					$fecha_fin = strtotime( $d->fecha_fin." ".$d->hora_fin );
					if( $fecha_fin >= time() ){

						$_listas = $data->data_listas;
						// $d->ENVIADO = "SI";
						$enviados = ( isset($data->enviados) ) ? $data->enviados : [];
						$_listas = $wpdb->get_results("SELECT * FROM vlz_listas WHERE id IN ( ".implode(",", $_listas)." ) ");
						if( !empty($_listas) ){
									echo "<pre>";
										print_r( $enviados );
									echo "</pre>";

							foreach ($_listas as $lista) {
								$_d = json_decode($lista->data);
								$temp = explode(",", $_d->suscriptores);
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

										$mensaje = $d->plantilla.'<img src="'.get_home_url().'/campaing_2/'.$info_validacion.'/'.md5($info_validacion).'.png" />';
										wp_mail( trim($email) , $d->asunto, $mensaje);
									}
								}
							}
									echo "<pre>";
										print_r( $enviados );
									echo "</pre>";
						}
						$data->enviados = $enviados;
						// update_campaing($campaing, $data, $d);
					}
				}

			break;
			case 1:
				$un_dia = 60; // Prueba en minutos 60 segundos, en producción colocar: 1 dia > 86400 segundos;
				$esperar = $data->campaing_despues_delay*$un_dia;
				$anterior = $wpdb->get_row("SELECT * FROM vlz_campaing WHERE id = ".$data->campaing_anterior);
				$data_anterior = json_decode($anterior->data);

				$enviados = ( isset($data_anterior->enviados) ) ? $data_anterior->enviados : [];

				switch ( $data->campaing_despues_no_abre ) {
					case 'si':
						$vistos = ( isset($data_anterior->vistos) ) ? $data_anterior->vistos : [];
						foreach ($vistos as $key => $cliente) {
							$enviado_date = $cliente[0];
							$email = $cliente[1];
							if( (time()-$enviado_date) >= $esperar ){
								if( !array_key_exists($email, $enviados) ){ 
									$enviados[ $email ] = time();

									$info_validacion = base64_encode( json_encode( [
										"id" => $campaing->id,
										"type" => "img",
										"format" => "png",
										"email" => $email
									] ) );

									$mensaje = $d->plantilla.'<img src="'.get_home_url().'/campaing_2/'.$info_validacion.'/'.md5($info_validacion).'.png" />';
									wp_mail( trim($email) , $d->asunto, $mensaje);
								}
							}
						}
					break;
					case 'no':
						$no_abiertos = get_email_no_abiertos($data_anterior, $esperar);
						foreach ($no_abiertos as $key => $cliente) {
							$email = $cliente[1];
							if( !array_key_exists($email, $enviados) ){ 
								$enviados[ $email ] = time();

								$info_validacion = base64_encode( json_encode( [
									"id" => $campaing->id,
									"type" => "img",
									"format" => "png",
									"email" => $email
								] ) );

								$mensaje = $d->plantilla.'<img src="'.get_home_url().'/campaing_2/'.$info_validacion.'/'.md5($info_validacion).'.png" />';
								wp_mail( trim($email) , $d->asunto, $mensaje);
							}
						}
					break;
				}

			break;
		}
		
	}
	
?>