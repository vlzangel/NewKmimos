<?php
	
	include( dirname(dirname(__DIR__))."/lib/enlaceFiscal/CFDI.php" );

	global $wpdb;

	extract($_POST);

	$reserva_id = $db->get_var( "select ID from wp_posts where post_parent = {$id_orden} and post_type = 'wc_booking'", "ID");

	if( $reserva_id > 0 ){

		$factura = $db->get_row( "select * from facturas where reserva_id = {$reserva_id}");

		$email = $db->get_var( "select user_email from wp_users where ID = ".$factura->cliente_id , "user_email" );
		$nombre = $db->get_var( "select meta_value from wp_usermeta where meta_key = 'first_name' and user_id = ".$factura->cliente_id , "meta_value" );
		$apellido = $db->get_var( "select meta_value from wp_usermeta where meta_key = 'last_name' and user_id = ".$factura->cliente_id , "meeta_value" );

		if( isset($factura->id) && $factura->id > 0 ){

			$template = file_get_contents( dirname(dirname(__DIR__))."/template/mail/factura.php" );
			$template = str_replace('[URL_IMGS]', get_home_url()."/wp-content/themes/kmimos/images/emails", $template);
			$template = str_replace('[name]', "{$nombre} {$apellido}", $template);
			$template = str_replace('[serie_folio]', strtoupper($factura->serie).'-'.$factura->reserva_id, $template);
			$template = str_replace('[PDF]', $factura->urlPdf, $template);
			$html = get_email_html($template, true);

			$sts_mail = wp_mail( 
				$email, 
				'Comprobante Fiscal Digital - Reserva #'.$factura->reserva_id, 
				$html,
				'',
				[ dirname(dirname(dirname(dirname(__DIR__)))).'/uploads/facturas/'.$reserva_id.'-'.$factura->numeroReferencia.'.pdf' ]
			);
			if($sts_mail){
				$respuesta['estatus'] = 'enviado';
				$respuesta['email'] = $email;
			}
		}

	}
