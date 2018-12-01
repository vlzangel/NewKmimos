<?php
    date_default_timezone_set('America/Mexico_City');

    if(!session_id()){ session_start() ;}

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");
	include_once($raiz."/wp-load.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");
    include_once($tema."/admin/backend/agregar_cupon/lib/cupon.php");


    $_db = new db( new mysqli($host, $user, $pass, $db) );

    extract($_POST);


    $cupon = strtolower($cupon);
    
    $reserva_id = $idReserva;


    // *************************************
    // Desglose reserva
    // *************************************
    	$reserva = $_db->get_row("SELECT * FROM wp_posts WHERE ID = {$reserva_id}");
    	$pedido = $_db->get_row("SELECT * FROM wp_posts WHERE ID = ".$reserva->post_parent);
    	$desglose = kmimos_desglose_reserva_data( $pedido->ID, true );

    	$servicio = $_db->get_row( "SELECT * FROM wp_postmeta WHERE meta_key = '_booking_product_id' and ID = ".$reserva->ID );
 
 
  		include dirname(dirname(dirname(dirname(__DIR__)))).'/NEW/data_finalizar.php';
		$antes = $CONTENIDO;

    // *************************************
    // Aplicar Cupon
    // *************************************

	    // cargar cupones
	    	$xcupones = [];
	    	$existe_cupon = false;

	    	$_cupones = $_db->get_var( "
	    		SELECT i.order_item_name as name, im.meta_value as monto
	    		FROM wp_woocommerce_order_items as i
	    			inner join wp_woocommerce_order_itemmeta as im ON im.order_item_id = i.order_item_id and im.meta_key = 'discount_amount'
	    		WHERE order_item_type = 'coupon' 
	    			AND order_id = ".$reserva->ID 
	    	);

	    	if( !empty($_cupones) ){
		    	foreach ($_cupones as $val) {
		    		$xcupones[] = [
		    			$val->name,
		    			$val->monto,
		    			0
		    		];
		    		if( strtolower($val->name) == $cupon ){
				    	$existe_cupon = true;
				    	print_r(json_encode([ 'error'=>'1', 'contenido'=>'El cupon ya fue aplicado a la reserva' ]));
				    	exit();
		    		}
		    	}
	    	}

	    // Validar desglose
	    	$duracion = explode(' ', $desglose['servicio']['duracion']);
	    	if( isset($duracion[0]) && $duracion[0] > 0 ){
	    		$duracion = $duracion[0];
	    	}else{
	    		$duracion = 0;
	    	}

	    	$total = ($desglose['servicio']['desglose']['total']>0)? $desglose['servicio']['desglose']['total'] : 0;
	    	$inicio = ($desglose['servicio']['inicio']>0)? date('Y-m-d H:i:s', strtotime($desglose['servicio']['desglose']['total']) ) : 0;


    // *************************************
    // Cargar datos de reserva
    // *************************************
 		// cargar mascotas
 			$mascotas = [
				"cantidad" => 0,
				"pequenos" => [],
				"medianos" => [],
				"grandes"  => [],
				"gigantes" => [],
				"gatos"    => []
			];
 			$_items = [
				"pequenos" => "pequenos",
				"pequeno" => "pequenos",

				"medianos" => 'medianos',
				"mediano" => 'medianos',

				"grandes"  => 'grandes',
				"grande"  => 'grandes',

				"gigantes" => 'gigantes',
				"gigante" => 'gigantes',

				"gatos"    => 'gatos',
				"gato"    => 'gatos'
			];

			if( isset($desglose['servicio']['variaciones']) && count($desglose['servicio']['variaciones']) > 0 ){			
		    	foreach ($desglose['servicio']['variaciones'] as $var) {
		    		$cantidad_mascotas++;
		    		$mascotas['cantidad'] += (int) $var[0];
		    		$mascotas[ $_items[ strtolower($var[1]) ] ][] = [
		    			$var[0], 
		    			$var[3]
		    		];
		    	}
			}

    	// Calcular monto del cupon
	    	$_param = [
	    		"db" => $_db,
				"cupon" => $cupon, 
				"cupones" => $xcupones, 
				"validar" => true, 
				"cliente" => $reserva->post_author,
				"duracion" => $duracion,
				"total" => $total,
				"inicio" => $inicio,
				"servicio" => $servicio->ID,
				"tipo_servicio" => strtolower($desglose['servicio']['tipo']),
				"mascotas" => $mascotas
			];
    		$_cupon_monto = aplicarCupon( $_param );
    		$cupon_monto = isset($_cupon_monto[1]) ? $_cupon_monto[1] : 0 ;

    	if( $cupon_monto > 0 ){


    		// Agregar Order Item
    			$sql0 = "INSERT INTO wp_woocommerce_order_items (
		    			order_item_name,
		    			order_item_type,
		    			order_id
		    		) VALUES (
		    			'".$cupon."',
		    			'coupon',
		    			".$reserva->post_parent."
		    		) ";
		    	$_db->query( $sql0 ); 
		    	$order_item_id = $_db->insert_id();


	    	// Agregar meta Order item
		    	if( $order_item_id > 0 ){		
					$sql1 = "INSERT INTO wp_woocommerce_order_itemmeta ( 
			    			order_item_id,
			    			meta_key,
			    			meta_value
			    		) VALUES ( 
			    		 	{$order_item_id},
			    		 	'discount_amount',
			    		 	{$cupon_monto}
			    		) 
			    	";
			    	$_db->query( $sql1 );
			    	$sql2 = "INSERT INTO wp_woocommerce_order_itemmeta ( 
			    			order_item_id,
			    			meta_key,
			    			meta_value
			    		) VALUES ( 
			    		 	{$order_item_id},
			    		 	'discount_amount_tax',
			    		 	0
			    		) 
			    	";
			    	$_db->query( $sql2 );

					// Actualizar monto de descuento
					
				    	$_monto = $cupon_monto;
				    	$sql10 = "SELECT SUM(meta.meta_value) as monto
				            FROM `wp_woocommerce_order_items` as items 
				                INNER JOIN wp_woocommerce_order_itemmeta as meta ON meta.order_item_id = items.order_item_id
				                WHERE meta.meta_key = 'discount_amount'
				                    AND items.`order_id` = ".$reserva->post_parent;
				    	$total_descuento = $_db->get_var($sql10);

				    	if( $total_descuento > $desglose['servicio']['desglose']['total'] ){
				    		$_monto = $desglose['servicio']['desglose']['total'];
				    	}
				    	$sql3 = "UPDATE wp_postmeta 
				    		SET meta_value = {$_monto} 
				    		WHERE meta_key = '_cart_discount' 
				    			AND post_id=".$reserva->post_parent;
				    	$_db->query( $sql3 );
				    	print_r($sql3);


				    	// asignar saldo a favor
				    	$_saldoF = 'No se devolvio el saldo al cliente';
				    	if( $saldo == 'on' ){
				    		$_db->query( "UPDATE wp_usermeta SET meta_value = meta_value + {$cupon_monto} WHERE meta_key = 'kmisaldo' and user_id = ".$desglose['cliente']['id'] );
				    		$_saldoF = $cupon_monto;
				    	}				    	
					    $descripcion = "
					    	<br> Esta reserva fue modificada por que se le agrego un cupon.
					    	<br><hr>
					    	<br>Reserva: ".$reserva->ID."
					    	<br>Descuento:". $cupon_monto."
					    	<br>Cupon: {$cupon}
					    	<br>Saldo a favor: {$_saldoF}
					    ";
					    wp_mail( 'italococchini@gmail.com', 'Se agrego un cupon a la reserva #'.$reserva->ID, $descripcion );
				    	
		    	}

	    }
 
    // *************************************
    // Mostrar desglose de reserva
    // *************************************
	    include dirname(dirname(dirname(dirname(__DIR__)))).'/NEW/data_finalizar.php';
	    $despues = $CONTENIDO;


	    print_r(json_encode(['antes'=>$antes, 'despues'=>$despues]));

