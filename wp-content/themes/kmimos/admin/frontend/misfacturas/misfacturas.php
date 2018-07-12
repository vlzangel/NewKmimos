<?php

	global $wpdb;

	$cuidador_id = get_current_user_id();

	$sql = "SELECT * FROM facturas WHERE cuidador_id = {$cuidador_id} ORDER BY fechaGeneracion DESC";
	$facturas = $wpdb->get_results($sql);

	if( count($facturas) > 0 ){

		$factura_array = array(
			"cuidador" => array(
				"titulo" => 'Facturas Recibidas',
				"facturas" => array()
			),
			"cliente" => array(
				"titulo" => 'Facturas Emitidas',
				"facturas" => array()
			),
		);

		foreach ($facturas as $factura) {
			
			$reserva_detalle = kmimos_desglose_reserva_data( $factura->pedido_id,  true);
			$foto = kmimos_get_foto( $factura->cliente_id ) ;
			$cliente_nombre = $reserva_detalle['cliente']['nombre'];
			$total = $reserva_detalle['servicio']['desglose']['total'];			

			switch ($factura->receptor) {
				case 'cuidador':
					$factura_array["cuidador"]["facturas"][] = array(
						'id' => $factura->id, 
						'fecha_creacion' => $factura->fechaGeneracion, 
						'cliente' => strtoupper('Kmimos'), 
						'reserva_id' => $factura->reserva_id, 
						'serie' => $factura->serie, 
						'estado' => $factura->estado, 
						'foto' => $foto,
						'numeroReferencia' => $factura->numeroReferencia, 
						'certificado' => $factura->serieCertificado, 
						'certificadoSAT' => $factura->serieCertificadoSAT, 
						'folioFiscalUUID' => $factura->folioFiscalUUID, 
						'QR' => $factura->urlQR, 
						'total' => $total,
						'acciones' => array(
							"factura_pdf" => $factura->urlPdf,
						),
					);
					break;
				case 'cliente':
					$factura_array["cliente"]["facturas"][] = array(
						'id' => $factura->id, 
						'fecha_creacion' => $factura->fechaGeneracion, 
						'cliente' => strtoupper($cliente_nombre), 
						'reserva_id' => $factura->reserva_id, 
						'serie' => $factura->serie, 
						'estado' => $factura->estado, 
						'foto' => $foto,
						'numeroReferencia' => $factura->numeroReferencia, 
						'certificado' => $factura->serieCertificado, 
						'certificadoSAT' => $factura->serieCertificadoSAT, 
						'folioFiscalUUID' => $factura->folioFiscalUUID, 
						'QR' => $factura->urlQR, 
						'total' => $total,						
						'acciones' => array(
							"factura_pdf" => $factura->urlPdf,
							"factura_xml" => $factura->urlXml,
						),
					);
					break;
			}

		}
		
		//BUILD TABLE
		$CONTENIDO .= '
			<h1 style="margin: 0px; padding: 0px;">Mis Facturas</h1><hr style="margin: 5px 0px 10px;">
		'.
		construir_listado($factura_array);
	}else{
		$CONTENIDO .= "<h1 style='line-height: normal;'>Usted aún no tiene facturas.</h1><hr>";
	}

?>