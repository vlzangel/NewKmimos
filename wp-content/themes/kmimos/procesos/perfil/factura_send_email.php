<?php
	
	include( dirname(dirname(__DIR__))."/lib/enlaceFiscal/CFDI.php" );

	global $wpdb;

	extract($_POST);

	$reserva_id = $db->get_var( "select ID from wp_posts where post_parent = {$id_orden} and post_type = 'wc_booking'", "ID");

	if( $reserva_id > 0 ){

		$factura = $db->get_row( "select * from facturas where reserva_id = {$reserva_id}");

		$email = $db->get_var( "select user_email from wp_users where ID = ".$factura->cliente_id , "user_email" );

		if( isset($factura->id) && $factura->id > 0 ){

			if(wp_mail( 
				'italococchini@gmail.com', 
				'Comprobante Fiscal Digital - Reserva #'.$factura->reserva_id, 
				'adjunto comprobante fiscal',
				'',
				[ dirname(dirname(dirname(dirname(__DIR__)))).'/uploads/facturas/'.$reserva_id.'-'.$factura->numeroReferencia.'.pdf' ]
			)){
				$respuesta['estatus'] = 'enviado';
				$respuesta['email'] = $email;
			}
		}

	}
