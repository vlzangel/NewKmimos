<?php 
    /*
        Template Name: Validar Pagos
    */

	date_default_timezone_set('America/Mexico_City');

	if( !isset($_SESSION)){ session_start(); }

	switch ( $_GET['p'] ) {
		case 'paypal':
			if( isset($_SESSION['paypal']) ){
				$_POST['info'] = $_SESSION['paypal'];
				include('lib/Requests/Requests.php');
				if( $_GET['t'] == 'return' && isset($_GET['PayerID']) ){
					Requests::register_autoloader();
					$options = array(
						'info' => $_SESSION['paypal'],
						'id_invalido' => false,
						'PayerID' => $_GET['PayerID'],
						'token' => $_GET['token'],
					);
					$request = Requests::post( get_home_url()."/wp-content/themes/kmimos/procesos/reservar/pagar.php", array(), $options );
					$body = json_decode($request->body);
					print_r($body);
					if( $body->order_id > 0 ){
						unset($_SESSION['paypal']);
						header( 'location:'.get_home_url().'/finalizar/'.$body->order_id );
					}
				}
			}
			break;
	}
	// echo 'paso prueba';
	// header( 'location:'.get_home_url() );
