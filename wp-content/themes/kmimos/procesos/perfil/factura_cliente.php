<?php

//	Codigo esta en admin/frontend/historial/factura

	include( dirname(dirname(__DIR__))."/lib/enlaceFiscal/CFDI.php" );

	global $wpdb;

	extract($_POST);

	$data_reserva = kmimos_desglose_reserva_data($id_orden, true);
	$user_id = $data_reserva['cliente']['id'];	

	$data_reserva['receptor']['rfc'] = get_user_meta( $user_id, 'billing_rfc', true );
	$data_reserva['receptor']['nombre'] = get_user_meta( $user_id, 'billing_first_name', true );

	$AckEnlaceFiscal = $CFDI->generar_Cfdi_Cliente($data_reserva);

	$respuesta = [];
	if( !empty($AckEnlaceFiscal['ack']) ){
		$ack = json_decode($AckEnlaceFiscal['ack']);

        // Datos complementarios
        $datos['comentario'] = '';
        $datos['subtotal'] = $AckEnlaceFiscal['data']['CFDi']['subTotal'];
        $datos['impuesto'] = $AckEnlaceFiscal['data']['CFDi']['Impuestos']['Totales']['traslados'];
        $datos['total'] = $AckEnlaceFiscal['data']['CFDi']['total'];

		$CFDI->guardarCfdi( 'cliente', $data_reserva, $ack );

		$respuesta['estatus'] = $ack->AckEnlaceFiscal->estatusDocumento;
		$respuesta['pdf'] = $ack->AckEnlaceFiscal->descargaArchivoPDF;
	}
	$respuesta['ack'] = $AckEnlaceFiscal;

