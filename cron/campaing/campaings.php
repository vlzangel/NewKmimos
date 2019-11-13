<?php
	
	function phpmailer_init_vlz() {
        return [
            "email" => "promociones@kmimos.la",
            "clave" => "Kmimos2019",
            "From" => "promociones@kmimos.la",
            "FromName" => "Promaciones Kmimos",
        ];
    }


	include dirname(dirname(__DIR__)).'/wp-load.php';
    date_default_timezone_set('America/Mexico_City');
	global $wpdb;

	//ini_set('display_errors', 'On');
    //error_reporting(E_ALL);

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
				$enviados = (array) json_decode($enviados);
				if( count($enviados) > 0 ){
					$padre_id_solo = $data->campaing_anterior;
					$padre_id = "padre_".$padre_id_solo;
					$otro_flujo = $wpdb->get_row("SELECT * FROM vlz_campaing WHERE data LIKE '%campaing_anterior\":\"{$padre_id_solo}%' AND id != {$campaing->id} ");
					$enviados_otro = ( $otro_flujo->enviados != '' ) ? (array) json_decode($otro_flujo->enviados) : [];
					foreach ($enviados[$padre_id] as $email => $time) {
						if( !array_key_exists($email, $enviados_otro[$padre_id]) ){ 
							$enviados_otro[$padre_id]->$email = time();
						}
					}
					$data_otros = json_encode($enviados_otro, JSON_UNESCAPED_UNICODE);
					$sql = "UPDATE vlz_campaing SET enviados = '{$data_otros}' WHERE id = ".$otro_flujo->id;
					$wpdb->query( $sql );
				}
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

	function _desuscrito($email){
		global $wpdb;
		$existe = $wpdb->get_row("SELECT * FROM vlz_desuscritos WHERE email = '{$email}' ");
		return ( empty($existe) );
	}

	function update_envios( $info ){
		global $wpdb;
		$envios = $wpdb->get_row("SELECT * FROM vlz_envios WHERE campaing = '{$info['campaing']}' ");
		if( $envios == null ){
			$emails = json_encode( $info['emails'] );
			$wpdb->query("
				INSERT INTO
					vlz_envios
				VALUES (
					NULL,
					'{$info['campaing']}',
					'{$emails}',
					'[]',
					NOW()
				)
			");
		}else{

			$por_enviar = (array) json_decode( $envios->por_enviar );
			$enviados = (array) json_decode( $envios->enviados );

			$nuevos = [];

			foreach ( $info['emails'] as $email ) {
				if( !in_array($email, $por_enviar) && !in_array($email, $enviados) ){
					$por_enviar[] = $email;
				}
			}

			$por_enviar_new = json_encode($por_enviar);

			$wpdb->query("
				UPDATE
					vlz_envios
				SET
					por_enviar = '{$por_enviar_new}'
				WHERE 
					id = {$envios->id}
			");
		}
	}

	$campaings = $wpdb->get_results("SELECT * FROM vlz_campaing");

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

						$enviar_correo = [
							"asunto" => $d->asunto, 
							"campaing" => $campaing->id,
							"emails" => []
						];

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
										
										$mensaje = add_seguimiento_($mensaje, [
											"campaing" => $campaing->id,
											"email" => trim($email),
										]);

										$info_desuscribir = base64_encode( json_encode( [
											"campaing_id" => $campaing->id,
											"email" => $email
										] ) );
										$mensaje = str_replace("#FIN_SUSCRIPCION#", get_home_url().'/campaing_2/'.$info_desuscribir.'/end', $mensaje);

										if( _desuscrito($email) ){

											// vlz_enviar_campaing( trim($email) , $d->asunto, $mensaje);

											$enviar_correo['emails'][] = $email;
										}
									}
								}
							}
							if( count($enviados) > 0 ){
								update_campaing($campaing, $data, $d, $enviados);
							}

							update_envios( $enviar_correo );
						}
					}
				}

			break;
			case 1:

				$un_dia = 86400; // Prueba en minutos 60 segundos, en producción colocar: 1 dia > 86400 segundos;
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
									$enviados[$padre_id]->$email = time();
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

									if( _desuscrito($email) ){
										vlz_enviar_campaing( trim($email) , $d->asunto, $mensaje);
									}
								}
							}
						}
						if( count($enviados) > 0 ){
							update_campaing($campaing, $data, $d, $enviados);
						}
					break;
					case 'no':
						$no_abiertos = get_email_no_abiertos($data_anterior, $esperar, json_decode($anterior->enviados));

						foreach ($no_abiertos as $key => $email) {
							if( !array_key_exists($email, $enviados[$padre_id]) ){ 
								$enviados[$padre_id]->$email = time();
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

								if( _desuscrito($email) ){
									vlz_enviar_campaing( trim($email) , $d->asunto, $mensaje);
								}
							}
						}
						if( count($enviados) > 0 ){
							update_campaing($campaing, $data, $d, $enviados);
						}
					break;
				}

			break;
		}
		
	}
	
?>