<?php
	
	include dirname(dirname(__DIR__)).'/wp-load.php';
    date_default_timezone_set('America/Mexico_City');

    include dirname(dirname(__DIR__)).'/test/list_campaing.php';

    global $emails_validos;

	global $wpdb;

	$campaings = $wpdb->get_results("SELECT * FROM vlz_listas");
	foreach ($campaings as $key => $campaing) {
		
		$data = json_decode($campaing->data);
		$titulo = $data->titulo;

		$config = (array) json_decode($campaing->config);
		extract($config);

		$desde = ( $desde != "" ) ? date("Y-m-d", strtotime( str_replace("/", "-", $desde) ) ) : '';
		$hasta = ( $hasta != "" ) ? date("Y-m-d", strtotime( str_replace("/", "-", $hasta) ) ) : '';

		$suscriptores = json_decode($campaing->manuales);

		if( $newsletter != "" ){
			$fechas = ( $desde != "" ) ? " AND time >= '{$desde}' " : '';
			$fechas .= ( $hasta != "" ) ? " AND time <= '{$hasta}' " : '';
			$suscritos = $wpdb->get_results("SELECT * FROM wp_kmimos_subscribe WHERE source = '{$newsletter}' {$fechas} ");
			foreach ($suscritos as $key => $suscrito) {
				if( in_array($suscrito->email, $emails_validos) ){
					$suscriptores[] = [
						$suscrito->email,
						$suscrito->email
					];
				}
			}
		}

		if( $wlabel != "" ){
			if( $wlabel == 'kmimos' ){
				$fechas = ""; 
				if( $desde != "" ) { $fechas = " AND u.user_registered >= '{$desde}' "; }
				if( $hasta != "" ) { $fechas = " AND u.user_registered <= '{$hasta}' "; }

				$sql =  "
					SELECT DISTINCT (u.user_email), u.ID
					FROM wp_usermeta AS m 
					INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
					INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
					WHERE NOT EXISTS
				    (
				        SELECT  null 
				        FROM wp_usermeta AS w
				        WHERE w.user_id = u.ID AND w.meta_key = '_wlabel'
				    ) 
				    AND c.meta_value LIKE '%subscriber%'
				    {$fechas}";

				$suscritos = $wpdb->get_results($sql);
				$cont = 0;
				foreach ($suscritos as $key => $suscrito) {
					$cont++;
					$first = get_user_meta($suscrito->ID, 'first_name', true);
					$first = str_replace('"', '', $first);

					if( in_array($suscrito->user_email, $emails_validos) ){
						$suscriptores[] = [
							$first,
							$suscrito->user_email
						];
					}
				}

			}else{
				$fechas = ""; 
				if( $desde != "" ) { $fechas = " AND u.user_registered >= '{$desde}' "; }
				if( $hasta != "" ) { $fechas = " AND u.user_registered <= '{$hasta}' "; }

				$sql =  "
				SELECT u.user_email AS email, n.meta_value AS name 
				FROM wp_usermeta AS m 
				INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
				INNER JOIN wp_usermeta AS n ON ( u.ID = n.user_id AND n.meta_key = 'first_name' )
				INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
				WHERE  (  m.meta_key = '_wlabel' OR  m.meta_key = 'user_referred' ) AND m.meta_value LIKE '%{$wlabel}%' AND c.meta_value LIKE '%subscriber%' {$fechas}";

				$suscritos = $wpdb->get_results($sql);

				foreach ($suscritos as $key => $suscrito) {
					if( in_array($suscrito->email, $emails_validos) ){
						$suscriptores[] = [
							$suscrito->name,
							$suscrito->email
						];
					}
				}

			}

		}

		if( $cuidadores != "" ){

			if( $cuidadores == 'kmimos' ){
				$fechas = ""; 
				if( $desde != "" ) { $fechas = " AND u.user_registered >= '{$desde}' "; }
				if( $hasta != "" ) { $fechas = " AND u.user_registered <= '{$hasta}' "; }

				$sql =  "
					SELECT DISTINCT (u.user_email), u.ID
					FROM wp_usermeta AS m 
					INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
					INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
					WHERE NOT EXISTS
				    (
				        SELECT  null 
				        FROM wp_usermeta AS w
				        WHERE w.user_id = u.ID AND w.meta_key = '_wlabel'
				    ) 
				    AND c.meta_value LIKE '%vendor%'
				    {$fechas}
				";

				$suscritos = $wpdb->get_results($sql);
				$cont = 0;
				foreach ($suscritos as $key => $suscrito) {
					$cont++;
					$first = get_user_meta($suscrito->ID, 'first_name', true);
					$first = str_replace('"', '', $first);

					if( in_array($suscrito->email, $emails_validos) ){
						$suscriptores[] = [
							$first,
							$suscrito->user_email
						];
					}
				}

			}else{
				$fechas = ""; 
				if( $desde != "" ) { $fechas = " AND u.user_registered >= '{$desde}' "; }
				if( $hasta != "" ) { $fechas = " AND u.user_registered <= '{$hasta}' "; }

				$sql =  "
				SELECT u.user_email AS email, n.meta_value AS name 
				FROM wp_usermeta AS m 
				INNER JOIN wp_users AS u ON ( u.ID = m.user_id ) 
				INNER JOIN wp_usermeta AS n ON ( u.ID = n.user_id AND n.meta_key = 'first_name' )
				INNER JOIN wp_usermeta AS c ON ( u.ID = c.user_id AND c.meta_key = 'wp_capabilities' )
				WHERE  (  m.meta_key = '_wlabel' OR  m.meta_key = 'user_referred' ) AND m.meta_value LIKE '%{$cuidadores}%' AND c.meta_value LIKE '%vendor%' {$fechas}";

				$suscritos = $wpdb->get_results($sql);

				foreach ($suscritos as $key => $suscrito) {
					if( in_array($suscrito->email, $emails_validos) ){
						$suscriptores[] = [
							$suscrito->name,
							$suscrito->email
						];
					}
				}
			}
		}

		$info = [
			"titulo" => $titulo,
			"suscriptores" => $suscriptores,
		];
		$data = json_encode($info, JSON_UNESCAPED_UNICODE);

		$config = [
			"newsletter" => $newsletter,
			"wlabel" => $wlabel,
			"cuidadores" => $cuidadores,
			"desde" => $desde,
			"hasta" => $hasta
		];
		$config = json_encode($config, JSON_UNESCAPED_UNICODE);

		$sql = "UPDATE vlz_listas SET data = '{$data}' WHERE id = ".$campaing->id;
		$wpdb->query( $sql );

	}
?>