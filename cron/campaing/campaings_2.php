<?php
	
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

		/*
		echo "<pre>";
			print_r($info);
		echo "</pre>";
		*/
		
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

		if( $campaing->id == 28 ){

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

											if( _desuscrito($email) ){
												$excl = [
													'facruras.abrahm.nava.ru00edos@gmail.com',
													'Maru00eda.acosta.ingles@gmail.com',
												];
												if( !in_array($email) ){
													$enviar_correo['emails'][] = $email;
												}
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
			}

			echo "<pre>";
				print_r( $enviados );
			echo "</pre>";


		}
		
	}
	
?>