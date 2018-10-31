<?php
	include '../wp-load.php';

	global $wpdb;

	$hoy = time();

	$fecha = date('Y-m-d H:i:s');
	$nuevafecha = strtotime ( '-1 year' , strtotime ( $fecha ) ) ;
	$date = date ( 'Y-m-d H:i:s' , $nuevafecha );

	$mes_1 = strtotime ( '-1 month' , $hoy );
	$mes_3 = strtotime ( '-3 month' , $hoy );
	$mes_6 = strtotime ( '-6 month' , $hoy );
	$mes_12 = strtotime ( '-12 month' , $hoy );

	$SQL = "
		SELECT 
			r.ID AS reserva,
			r.post_author AS user,
			u.user_email AS email,
			r.post_date AS fecha,
			t.slug AS tipo
		FROM 
			wp_posts AS r
		INNER JOIN wp_users AS u ON ( r.post_author = u.ID )
		INNER JOIN wp_postmeta AS m ON ( r.ID = m.post_id AND m.meta_key = '_booking_product_id' )
		INNER JOIN wp_term_relationships AS re ON ( m.meta_value = re.object_id )
		INNER JOIN wp_terms AS t ON ( t.term_id = re.term_taxonomy_id AND re.term_taxonomy_id != 28 )
		WHERE
			post_type = 'wc_booking' AND 
			post_status = 'confirmed'
	";

	$reservas = $wpdb->get_results($SQL);

	$grupos = [];

	foreach ($reservas as $reserva) {
		$entro = false;
		if( $mes_1 <= strtotime($reserva->fecha) ){
			$grupos[ $reserva->tipo ][ $reserva->email ][ "mes_1" ][] = $reserva;
			$entro = true;
		}
		if( $mes_3 <= strtotime($reserva->fecha) ){
			$grupos[ $reserva->tipo ][ $reserva->email ][ "mes_3" ][] = $reserva;
			$entro = true;
		}
		if( $mes_6 <= strtotime($reserva->fecha) ){
			$grupos[ $reserva->tipo ][ $reserva->email ][ "mes_6" ][] = $reserva;
			$entro = true;
		}
		if( $mes_12 <= strtotime($reserva->fecha) ){
			$grupos[ $reserva->tipo ][ $reserva->email ][ "mes_12" ][] = $reserva;
			$entro = true;
		}
		if( $mes_12 > strtotime($reserva->fecha) ){
			$grupos[ $reserva->tipo ][ $reserva->email ][ "mas_12" ][] = $reserva;
		}
	}

	$recompras = [];
	$_plantilla = [
		"mes_1" => "false",
		"mes_3" => "false",
		"mes_6" => "false",
		"mes_12" => "false",
		"mas_12" => "false"
	];
	foreach ($grupos as $servicio_id => $servicio) {
		$servicio_id = ( strpos($servicio_id, "adiestramiento") !== false ) ? "entrenamiento": $servicio_id;
		foreach ($servicio as $user_email => $value) {
			$recompras[$servicio_id][$user_email] = $_plantilla;
			foreach ($value as $rango_mes => $_value) {
				if( count($_value) > 0 ){
					$recompras[$servicio_id][$user_email][$rango_mes] = "true";
				}
			}
		}
	}

	$listas = [];
	foreach ($recompras as $servicio_id => $_servicios) {


		foreach ($_servicios as $user_email => $_listas) {
			$lista = "";
			foreach ($_listas as $_lista => $value) {
				if( $value == "false" ){
					$lista = $_lista;
				}
			}
			if( $lista != "" ){
				$listas[ $servicio_id."_".$lista ][] = $user_email;
			}
		}


	}

	$_listas = [
		"hospedaje_mes_1" => "f09276222f3c3241df9e9ab228225809",
		"hospedaje_mes_3" => "bcb0620e001312c244e3754ff004f92e",
		"hospedaje_mes_6" => "0b46c3df3f182a33c399c623edb9d41b",
		"hospedaje_mes_12" => "b63b547925808415e77bd76a09537063",

		"guarderia_mes_1" => "c6e3cc748f97d28c4f1c1b81f1b9da44",
		"guarderia_mes_3" => "c4e25467fdb365fdad242c6bb7b8cb15",
		"guarderia_mes_6" => "9d597bd0c915159a0895bc0b072d725d",
		"guarderia_mes_12" => "ca1d5570acd764dc52f6468621dfe23f",

		"paseos_mes_1" => "ba4c983a7b7984a9554ab11861e535c8",
		"paseos_mes_3" => "9bf63e7fc06474c49985d14dae6a1c0b",
		"paseos_mes_6" => "4029faa8003e58bd066e4175966c992a",
		"paseos_mes_12" => "f6473dcec05919d2754ba1a65c663244",

		"entrenamiento_mes_1" => "263f460aa7b711a42fb21567d4b350d7",
		"entrenamiento_mes_3" => "f2fc85de36b72c1bcea1f8332a7c3c4c",
		"entrenamiento_mes_6" => "ba219654b881b8ea580e16c6ccea5449",
		"entrenamiento_mes_12" => "47f2c68820a612324a5b3b1636ffb671"
	];

	foreach ($listas as $key => $value) {
		echo $key.": ".count($value)."<br>";
	}


	$hoy = time();
	
	$mes_1 = strtotime ( '-1 month' , $hoy );
	$mes_3 = strtotime ( '-3 month' , $hoy );
	$mes_6 = strtotime ( '-6 month' , $hoy );
	$mes_12 = strtotime ( '-12 month' , $hoy );

	echo date("m-d-Y", time())." - ".date("m-d-Y", $mes_1)."<br>";
	echo date("m-d-Y", $mes_1)." - ".date("m-d-Y", $mes_3)."<br>";
	echo date("m-d-Y", $mes_3)." - ".date("m-d-Y", $mes_6)."<br>";
	echo date("m-d-Y", $mes_6)." - ".date("m-d-Y", $mes_12);

	echo "<pre>";
		// print_r($grupos);
		// print_r($listas);
	echo "</pre>";

/*	$credenciales = $wpdb->get_var("SELECT data FROM campaing WHERE id = 1");
	require_once __DIR__.'/campaing/csrest_campaigns.php';
	$credenciales = json_decode( $credenciales );

	$auth = (array) $credenciales->auth;
	$lists = (array) $credenciales->lists;

	require_once __DIR__.'/campaing/csrest_subscribers.php';

	foreach ($listas as $key => $correos) {
		$list = new CS_REST_Subscribers($_listas[ $key ], $auth);
		foreach ($correos as $key => $email) {
			$r = $list->add([
				"EmailAddress" => $email,
			    "Resubscribe" => true,
			    "RestartSubscriptionBasedAutoresponders" => true,
			    "ConsentToTrack" => "Yes"
			]);
		}
	}*/

?>