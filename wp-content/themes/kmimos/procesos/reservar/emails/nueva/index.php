<?php
	
	extract($_GET);
	if( isset($_GET["id_orden"]) ){
		include(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))))."/wp-load.php");
	}

	$info = kmimos_get_info_syte();
	add_filter( 'wp_mail_from_name', function( $name ) { global $info; return $info["titulo"]; });
    add_filter( 'wp_mail_from', function( $email ) { global $info; return $info["email"]; });

    
    global $wpdb;
	$id = $id_orden;
	$data = kmimos_desglose_reserva_data($id, true);

	extract($data);

/*	echo "<pre>";
		print_r($data);
	echo "</pre>";*/
	
 	$modificacion_de = get_post_meta($reserva_id, "modificacion_de", true);
    if( $modificacion_de != "" ){ $modificacion = 'Esta es una modificación de la reserva #: '.$modificacion_de;
 	}else{ $modificacion = ""; }

	$email_admin = $info["email"];

	$mascotas_plantilla = dirname(dirname(dirname(dirname(__DIR__)))).'/template/mail/reservar/partes/mascotas.php';
    $mascotas_plantilla = file_get_contents($mascotas_plantilla);
    $mascotas = "";
	foreach ($cliente["mascotas"] as $mascota) {
		$temp = str_replace('[NOMBRE]', $mascota["nombre"], $mascotas_plantilla);
		$temp = str_replace('[RAZA]', $mascota["raza"], $temp);
		$temp = str_replace('[EDAD]', $mascota["edad"], $temp);
		$temp = str_replace('[TAMANO]', $mascota["tamano"], $temp);
		$temp = str_replace('[CONDUCTA]', $mascota["conducta"], $temp);
		$mascotas .= $temp;
	}
	
	$desglose_plantilla = dirname(dirname(dirname(dirname(__DIR__)))).'/template/mail/reservar/partes/desglose.php';
    $desglose_plantilla = file_get_contents($desglose_plantilla);

    $desglose = "";
	foreach ($servicio["variaciones"] as $variacion) {
		$plural = ""; if($variacion[0]>1){$plural="s";}
		$temp = str_replace('[TAMANO]', strtoupper($variacion[1]), $desglose_plantilla);
		$temp = str_replace('[CANTIDAD]', $variacion[0]." mascota".$plural, $temp);
		$temp = str_replace('[TIEMPO]', $variacion[2], $temp);
		$temp = str_replace('[PRECIO_C_U]', "$ ".$variacion[3], $temp);
		$temp = str_replace('[SUBTOTAL]', "$ ".$variacion[4], $temp);
		$desglose .= $temp;
	}


	
	$adicionales_desglose_plantilla = dirname(dirname(dirname(dirname(__DIR__)))).'/template/mail/reservar/partes/adicionales_desglose.php';
    $adicionales_desglose_plantilla = file_get_contents($adicionales_desglose_plantilla);

    $adicionales = "";
    foreach ($servicio["adicionales"] as $adicional) {
		$temp = str_replace('[SERVICIO]', $adicional[0], $adicionales_desglose_plantilla);
		$temp = str_replace('[CANTIDAD]', $adicional[1], $temp);
		$temp = str_replace('[PRECIO_C_U]', "$ ".$adicional[2], $temp);
		$temp = str_replace('[SUBTOTAL]', "$ ".$adicional[3], $temp);
		$adicionales .= $temp;
	}

	if( $adicionales != "" ){
		$adicionales_plantilla = dirname(dirname(dirname(dirname(__DIR__)))).'/template/mail/reservar/partes/adicionales.php';
    	$adicionales_plantilla = file_get_contents($adicionales_plantilla);

    	$adicionales = $adicionales_plantilla.$adicionales;
	}
	
	$transporte_desglose_plantilla = dirname(dirname(dirname(dirname(__DIR__)))).'/template/mail/reservar/partes/transporte_desglose.php';
    $transporte_desglose_plantilla = file_get_contents($transporte_desglose_plantilla);

    $transporte = "";
    foreach ($servicio["transporte"] as $valor) {
		$temp = str_replace('[SERVICIO]', $valor[0], $transporte_desglose_plantilla);
		$temp = str_replace('[SUBTOTAL]', $valor[2], $temp);
		$transporte .= $temp;
	}

	if( $transporte != "" ){
		$transporte_plantilla = dirname(dirname(dirname(dirname(__DIR__)))).'/template/mail/reservar/partes/transporte.php';
    	$transporte_plantilla = file_get_contents($transporte_plantilla);

    	$transporte = $transporte_plantilla.$transporte;
	}

	$totales_plantilla = dirname(dirname(dirname(dirname(__DIR__)))).'/template/mail/reservar/partes/totales.php';
    $totales_plantilla = file_get_contents($totales_plantilla);
    $totales_plantilla = str_replace('[TIPO_PAGO]', $servicio["tipo_pago"], $totales_plantilla);

    if( $servicio["desglose"]["enable"] == "yes" ){
    	$deposito_plantilla = dirname(dirname(dirname(dirname(__DIR__)))).'/template/mail/reservar/partes/deposito.php';
    	$deposito_plantilla = file_get_contents($deposito_plantilla);
    	$deposito_plantilla = str_replace('[REMANENTE]', number_format( number_format( $servicio["desglose"]["remaining"], 2, ',', '.'), 2, ',', '.'), $deposito_plantilla);
        $totales_plantilla = str_replace('[TOTAL]', number_format( $servicio["desglose"]["total"], 2, ',', '.'), $totales_plantilla);
    	$totales_plantilla = str_replace('[PAGO]', number_format( $servicio["desglose"]["deposit"], 2, ',', '.'), $totales_plantilla);
    	$totales_plantilla = str_replace('[DETALLES]', $deposito_plantilla, $totales_plantilla);

    }else{
        $totales_plantilla = str_replace('[TOTAL]', number_format( $servicio["desglose"]["deposit"], 2, ',', '.'), $totales_plantilla);
    	$totales_plantilla = str_replace('[PAGO]', number_format( $servicio["desglose"]["deposit"]-$servicio["desglose"]["descuento"], 2, ',', '.'), $totales_plantilla);
    	$totales_plantilla = str_replace('[DETALLES]', "", $totales_plantilla);
    }
	
	if( $servicio["desglose"]["descuento"]+0 > 0 ){
		$descuento_plantilla = dirname(dirname(dirname(dirname(__DIR__)))).'/template/mail/reservar/partes/descuento.php';
	    $descuento_plantilla = file_get_contents($descuento_plantilla);
	    $descuento_plantilla = str_replace('[DESCUENTO]', number_format( $servicio["desglose"]["descuento"], 2, ',', '.'), $descuento_plantilla);
	    $totales_plantilla = str_replace('[DESCUENTO]', $descuento_plantilla, $totales_plantilla);
	}else{
		$totales_plantilla = str_replace('[DESCUENTO]', "", $totales_plantilla);
	}

	if( strtolower($servicio["metodo_pago"]) == "tienda" ){
		include("tienda.php");
	}else{
		include("otro.php");
	}

?>