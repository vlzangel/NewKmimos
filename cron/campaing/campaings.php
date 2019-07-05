<?php
	include dirname(dirname(__DIR__)).'/wp-load.php';
    date_default_timezone_set('America/Mexico_City');
	global $wpdb;

	$campaings = $wpdb->get_results("SELECT * FROM vlz_campaing WHERE data NOT LIKE '%\"ENVIADO\":\"SI\"%' ");
	foreach ($campaings as $key => $campaing) {
		$data = json_decode($campaing->data);
		$d = $data->data;
		$fecha = strtotime( $d->fecha." ".$d->hora );
		if( $fecha >= time() ){
			$_listas = $data->data_listas;
			$d->ENVIADO = "SI";
			$destinatarios = [];
			$_listas = $wpdb->get_results("SELECT * FROM vlz_listas WHERE id IN ( ".implode(",", $_listas)." ) ");
			if( !empty($_listas) ){
				foreach ($_listas as $lista) {
					$_d = json_decode($campaing->data);
					$temp = explode(",", $_d->data->suscriptores);
					foreach ($temp as $email) {
						if( !in_array('BCC: '.$email, $destinatarios) ){
							$destinatarios[] = 'BCC: '.$email;
						}
					}
				}
			}
			wp_mail("a.veloz@kmimos.la", $d->asunto, $d->plantilla, $destinatarios);
			$data->data = $d;
			$data = json_encode($data);
			$sql = "UPDATE vlz_campaing SET data = '{$data}' WHERE id = ".$campaing->id;
			$wpdb->query( $sql );
		}
	}
?>