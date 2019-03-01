<?php 
    /*
        Template Name: Conocer Pagos
    */

	date_default_timezone_set('America/Mexico_City');

	if( !isset($_SESSION)){ session_start(); }

	switch ( $_GET['km'] ) {
		case 'paypal':
			if( isset($_SESSION['conocer']['paypal']) ){
				$_POST['info'] = $_SESSION['conocer']['paypal'];
				include('lib/Requests/Requests.php');
				if( $_GET['t'] == 'return' && isset($_GET['PayerID']) ){
					Requests::register_autoloader();
					$options = array(
						'PayerID' => $_GET['PayerID'],
						'token' => $_GET['token'],
						'info' => $_SESSION['conocer']['paypal'],
					);
					$request = Requests::post( get_home_url()."/wp-content/themes/kmimos/procesos/conocer/pagar.php", array(), $options );
					$body = json_decode($request->body);				
					if( $body->order_id > 0 ){
						unset($_SESSION['conocer']['paypal']);
						header( 'location:'.get_home_url()."/busqueda/" );
					}
				}
			}
			break;

		case 'mercadopago';


			if( strtolower($_GET['collection_status']) == 'approved' ){
				$id_orden = $_GET['external_reference'];
					$wpdb->query("
						UPDATE 
							conocer_pedidos 
						SET										
							transaccion_id = '".$_GET['merchant_order_id']."',
							tipo_pago = 'Mercadopago',
							status = 'Pagado',
							metadata = '{$metas}'
						WHERE 
							id = {$id_orden}
					");

			}

			break;
	}
	$cuidador = $wpdb->get_var( "SELECT url FROM cuidadores order by id desc" );
	header( 'location:'.get_home_url() . "/petsitters/"+$cuidador+"/1/" );
