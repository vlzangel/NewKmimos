<?php
	
	extract($_GET);
	if( isset($_GET["id_orden"]) ){
		include((dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))))."/wp-load.php");
	}

	global $URL_LOCAL;

	$PATH_TEMPLATE = (dirname(dirname(dirname(__DIR__))));

	$info = kmimos_get_info_syte();
	add_filter( 'wp_mail_from_name', function( $name ) { global $info; return $info["titulo"]; });
    add_filter( 'wp_mail_from', function( $email ) { global $info; return $info["email"]; });

    global $wpdb;
	$id = $id_orden;

	$orden = $wpdb->get_row("SELECT * FROM conocer_pedidos WHERE id = {$id}");
	$metas = (array) json_decode( $orden->metadata );

	$email_cliente = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = ".$orden->user_id);
	$nombre_cliente = get_user_meta($orden->user_id, 'first_name', true).' '.get_user_meta($orden->user_id, 'last_name', true);

	$time = strtotime($orden->fecha);

	if( $orden->tipo_pago == 'Tienda' ){

	    $INFORMACION = [
	        // GENERALES

	            'id'                	=> $id,
	            'HEADER'                => "pago_solicitud",

	            'CODIGO'				=> end( explode("/", $metas["pdf"]) ),
	            'MONTO'					=> $orden->total,
	            'FECHA'					=> date("d/m/Y", $time),
	            'HORA'					=> date("H:s", $time),
	            'PDF'					=> $metas["pdf"],

	        // CLIENTE
	            'DATOS_CLIENTE'         => $_datos_cliente,
	            'NAME_CLIENTE'          => $nombre_cliente,
	            'AVATAR_CLIENTE'        => kmimos_get_foto($orden->user_id),
	    ];

	}else{

	    $INFORMACION = [
	        // GENERALES

	            'id'                	=> $id,
	            'HEADER'                => "pago_solicitud",

	        // CLIENTE
	            'NAME_CLIENTE'          => $nombre_cliente,
	            'AVATAR_CLIENTE'        => kmimos_get_foto($orden->user_id),
	    ];
	}

	if( $orden->status == "Pagado" ){
    	include(__DIR__."/pagado.php");
	}else{
		if( $orden->tipo_pago == 'Tienda' ){
    		include(__DIR__."/pendiente_tienda.php");
		}else{
			// LOG: echo "El pago por tarjeta fallo";
		}
	}

?>