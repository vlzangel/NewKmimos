<?php 
    /*
        Template Name: Conocer Pagos
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
						'PayerID' => $_GET['PayerID'],
						'token' => $_GET['token'],
						'info' => $_SESSION['paypal'],
					);
					$request = Requests::post( "http://mx.kmimos.la/wp-content/themes/kmimos/procesos/conocer/pagar.php", array(), $options );
					$body = json_decode($request->body);				
					if( $body->order_id > 0 ){
						unset($_SESSION['paypal']);
						header( 'location:'.get_home_url()."/busqueda/" );
					}
				}
			}
			break;
	}
	//header( 'location:'.get_home_url() );
