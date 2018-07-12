<?php

	include( dirname(dirname(__DIR__))."/lib/enlaceFiscal/CFDI.php" );

	global $wpdb;

	extract($_POST);

	$data_reserva = kmimos_desglose_reserva_data($id_orden, true);

	$user_id = $data_reserva['cliente']['id'];

	// Guardar datos de facturacion del usuario
	update_user_meta( $user_id, 'billing_rfc', $rfc );
	update_user_meta( $user_id, 'billing_fullname', $nombre );

	$data_reserva['receptor']['rfc'] = $rfc;
	$data_reserva['receptor']['nombre'] = $nombre;

	$AckEnlaceFiscal = $CFDI->generar_Cfdi_Cliente($data_reserva);

	$respuesta = [];
	if( !empty($AckEnlaceFiscal) ){
		$ack = json_decode($AckEnlaceFiscal);

		$CFDI->guardarCfdi( 'cliente', $data_reserva, $ack, $db );

		$respuesta['estatus'] = $ack->AckEnlaceFiscal->estatusDocumento;
		$respuesta['pdf'] = $ack->AckEnlaceFiscal->descargaArchivoPDF;
		
	}
