<?php
	/*
        Template Name: Validar Pagos
    */
    global $wpdb;
	date_default_timezone_set('America/Mexico_City');
	
	include(__DIR__.'/lib/Requests/Requests.php');
	Requests::register_autoloader();
	

	if( !isset($_SESSION)){ session_start(); }

	switch ( strtolower($_GET['p']) ) {
		case 'paypal':
			require_once( 'procesos/reservar/pasarelas/paypal/validar.php' );
			$paypal_order = new Order();
			if( $paypal_order->validar( $_GET['token'] ) ){
				$sql = "SELECT post_id FROM wp_postmeta WHERE meta_value ='".$_GET['token']."' AND meta_key='_paypal_order_id'";	
				$orden = $wpdb->get_row( $sql );
				if( isset($orden->post_id) && $orden->post_id > 0 ){
					$id_orden = $orden->post_id;
					$pedido = $wpdb->get_row("SELECT * FROM wp_posts WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
					if( isset( $pedido->post_status ) && $pedido->post_status == 'unpaid' ){
						$wpdb->query( "UPDATE wp_postmeta SET meta_value = '".json_encode($_GET)."' WHERE meta_key = '_paypal_data' AND post_id = {$id_orden}  )");
						$wpdb->query("UPDATE wp_posts SET post_status = 'paid' WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
						$wpdb->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = {$id_orden};");
						$path=get_home_url().'/wp-content/themes/kmimos/procesos/reservar/emails/index.php';
						$reserva_data = Requests::post($path,array(),['id_orden' => $id_orden]);
					}
					header( 'location:'.get_home_url().'/finalizar/'.$id_orden );
				}
			}

			/*
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
	
					if( get_home_url() == 'https://mx.kmimos.la/' ){
						$request = Requests::post( "http://mx.kmimos.la/wp-content/themes/kmimos/procesos/reservar/pagar.php", array(), $options );
					}else{
						$request = Requests::post( get_home_url()."/wp-content/themes/kmimos/procesos/reservar/pagar.php", array(), $options );
					}
	
					$body = json_decode($request->body);
					print_r($body);
					if( $body->order_id > 0 ){
						//unset($_SESSION['paypal']);
						header( 'location:'.get_home_url().'/finalizar/'.$body->order_id );
					}
				}
			}
			*/
		break;

		case 'mercadopago':

			if( strtolower($_GET['collection_status']) == 'approved' ){
				$id_orden = $_GET['external_reference'];
				$wpdb->query( "UPDATE wp_postmeta SET meta_value = '".json_encode($_GET)."' 
					WHERE meta_key = '_mercadopago_data' AND post_id = {$id_orden}  )");
				$wpdb->query("UPDATE wp_posts SET post_status = 'paid' WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
				$wpdb->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = {$id_orden};");

				$path=get_home_url().'/wp-content/themes/kmimos/procesos/reservar/emails/index.php';
				$reserva_data = Requests::post($path,array(),['id_orden'=>$id_orden]);
				header( 'location:'.get_home_url().'/finalizar/'.$_GET['external_reference'] );
			}else if( strtolower($_GET['collection_status']) == 'pending' ){
				header( 'location:'.get_home_url().'/finalizar/'.$_GET['external_reference'] );
			}else{
				$wpdb->query( "UPDATE wp_postmeta SET meta_value = '".json_encode($_GET)."' 
					WHERE meta_key = '_mercadopago_data' AND post_id = {$id_orden}  )");
				$wpdb->query("UPDATE wp_posts SET post_status = 'canceled' WHERE post_parent = {$id_orden} AND post_type = 'wc_booking';");
				$wpdb->query("UPDATE wp_posts SET post_status = 'wc-completed' WHERE ID = {$id_orden};");
				header( 'location:'.get_home_url() .'/busqueda' );
			}

			# Transferencia
				// p=mercadopago
				// t=pending
				// collection_id=17930836
				// collection_status=pending
				// preference_id=405963188-75248673-1a4b-4286-b76f-6158e7d5b2e0
				// external_reference=203294
				// payment_type=bank_transfer
				// merchant_order_id=976304989

			# Tienda
				// p=mercadopago
				// t=pending
				// collection_id=17930826
				// collection_status=pending
				// preference_id=405963188-446c29b7-38c9-45c2-8870-6f255c2bd2f7
				// external_reference=203292
				// payment_type=k
				// merchant_order_id=976304935

			# Tarjeta
				// p=mercadopago
				// t=success
				// collection_id=17930720
				// collection_status=approved
				// preference_id=405963188-62fb8632-a33e-47ec-818e-748b067f8e0a
				// external_reference=203290
				// payment_type=credit_card
				// merchant_order_id=976312353

		break;

		default:
			echo "metodo de pago no existe";
		break;
	}
	// echo 'paso prueba';
	//header( 'location:'.get_home_url() );
