<?php

	include dirname(dirname(__DIR__)).'/wp-load.php';
    date_default_timezone_set('America/Mexico_City');
	global $wpdb;

	function phpmailer_init_vlz() {
		global $wpdb;

		$mail_actual = $wpdb->get_var("SELECT valor FROM vlz_campaing_config WHERE clave = 'cuenta_actual' ");

		$cuenta = [];

		switch ( $mail_actual+0 ) {
			case 0:
				$cuenta = [
					"email" => "promociones@kmimos.la",
					"clave" => "Kmimos2019",
					"From" => "promociones@kmimos.la",
					"FromName" => "Promociones Kmimos",
				];
			break;
			case 1:
				$cuenta = [
					"email" => "Promocioneskmimos1@gmail.com",
					"clave" => "Abc1234#",
					"From" => "Promocioneskmimos1@gmail.com",
					"FromName" => "Promociones Kmimos",
				];
			break;
			case 2:
				$cuenta = [
					"email" => "Promocioneskmimos2@gmail.com",
					"clave" => "Abc1234#",
					"From" => "Promocioneskmimos2@gmail.com",
					"FromName" => "Promociones Kmimos",
				];
			break;
			case 3:
				$cuenta = [
					"email" => "Promocioneskmimos3@gmail.com",
					"clave" => "Abc1234#",
					"From" => "Promocioneskmimos3@gmail.com",
					"FromName" => "Promociones Kmimos",
				];
			break;
			case 4:
				$cuenta = [
					"email" => "Promocioneskmimos4@gmail.com",
					"clave" => "Abc1234#",
					"From" => "Promocioneskmimos4@gmail.com",
					"FromName" => "Promociones Kmimos",
				];
			break;
			case 5:
				$cuenta = [
					"email" => "Promocioneskmimos5@gmail.com",
					"clave" => "Abc1234#",
					"From" => "Promocioneskmimos5@gmail.com",
					"FromName" => "Promociones Kmimos",
				];
			break;
			default:
				$cuenta = [
					"email" => "promociones@kmimos.la",
					"clave" => "Kmimos2019",
					"From" => "promociones@kmimos.la",
					"FromName" => "Promociones Kmimos",
				];
			break;
		}

		if( $mail_actual < 5 ){
			$mail_actual += 1;
		}else{
			$mail_actual = 0;
		}
		
		$wpdb->query("UPDATE vlz_campaing_config SET valor = '{$mail_actual}' WHERE clave = 'cuenta_actual' ");

		return $cuenta;
	}

	vlz_enviar_campaing( "vlzangel91@gmail.com", "Test", "Test");
?>