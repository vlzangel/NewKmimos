<?php



include_once( dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))) ."/vlz_config.php");
include_once( dirname(dirname(dirname(__DIR__))) . "/procesos/funciones/db.php");
include_once( dirname(dirname(dirname(__DIR__))) . "/lib/enlaceFiscal/CFDI.php" );

global $wpdb;

$factura_generada = 'none';
$factura_datos = 'block';
$pdf = 'javascript:;';

$informacion = 'Ocurrio un problema al tratar de procesar la solitiud';

// Orden
	$orden = vlz_get_page();

// Reserva
	$reserva_id = $CFDI->db->get_var( "select ID from wp_posts where post_parent = {$orden} and post_type = 'wc_booking'");
	if( $reserva_id > 0 ){
		$factura = $CFDI->db->get_row( "select * from facturas where reserva_id = {$reserva_id}");
		if( !isset($factura->id) && $factura->id > 0 ){
			$factura_generada = 'block';
			$factura_datos = 'none';
			$pdf = $factura->urlPdf;
		}else{

			// Desglose de reserva
			$data_reserva = kmimos_desglose_reserva_data($orden, true);

			if( validar_datos_facturacion( $data_reserva['cliente']['id'] ) ){
	
				if( validar_datos_facturacion( $data_reserva['cuidador']['id'] ) ){

					// Datos complementarios CFDI
					$data_reserva['receptor']['rfc'] = get_user_meta( $user_id, 'billing_rfc', true );
					$data_reserva['receptor']['nombre'] = get_user_meta( $user_id, 'billing_first_name', true );


					// Usuario ID
					$user_id = $data_reserva['cliente']['id'];	

					// Generar CFDI
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

						if( $ack->AckEnlaceFiscal->estatusDocumento == 'aceptado' ){
							$factura_generada = 'block';
							$factura_datos = 'none';
							$pdf = $ack->AckEnlaceFiscal->descargaArchivoPDF;
						}
					}
				}else{
					$informacion = '
						En estos momentos <strong>'. $data_reserva['cuidador']['nombre'] .'</strong>, no tiene sus datos de facturación cargados, nos encargaremos de contactar al cuidador y te avisaremos cuando esté lista. En caso de dudas, puedes contactarte con nuestro equipo de atención al cliente al teléfono <strong>(01) 55 3137 4829</strong>, Whatsapp <strong>+52 (33) 1261 4186</strong>, o al correo <a href="mailto:contactomex@kmimos.la" target="_blank" style="text-decoration: none; ">contactomex@kmimos.la</a>
					';


					$__email = file_get_contents( dirname(dirname(dirname(__DIR__))) . "/template/mail/factura/cuidador_sin_datos.php"  );
					$__email = str_replace('[id_reserva]', $data_reserva['servicio']['id_reserva'], $__email);
					$__email = str_replace('[avatar_cuidador]', kmimos_get_foto($data_reserva['cuidador']['id']), $__email);
					$__email = str_replace('[cuidador_nombre]', $data_reserva['cuidador']['nombre'], $__email);
					$__email = str_replace('[telefonos_cuidador]', $data_reserva['cuidador']['telefono'], $__email);
					$__email = str_replace('[correo_cuidador]', $data_reserva['cuidador']['email'], $__email);

					$__email = str_replace('[cliente_nombre]', $data_reserva['cliente']['nombre'], $__email);
					$__email = str_replace('[avatar_cliente]', kmimos_get_foto($data_reserva['cliente']['id']), $__email);
					$__email = str_replace('[telefonos_cliente]', $data_reserva['cliente']['telefono'], $__email);
					$__email = str_replace('[correo_cliente]', $data_reserva['cliente']['email'], $__email);

					$html_email = get_email_html($__email, false, false);
					wp_mail( 'italococchini@gmail.com', $data_reserva['cuidador']['nombre']." No posee datos de facturacion!", $html_email ); 

				}

			}else{
				$informacion = 'Debe completar los <a href="'.get_home_url().'/perfil-usuario/datos-de-facturacion/">Datos de facturaci&oacute;n</a>';
			}

		}
	}

// Vista
	$CONTENIDO .= '	
	<div class="text-left">
	    <h1 style="margin: 10px 0px 5px 0px; padding: 0px;">Comprobante Fiscal Digital - Reserva #'.$reserva_id.' </h1>

		<section id="descargar-factura" style="display: '.$factura_datos.';">
			<label class="lbl-text" style="font-style:italic;">El Comprobante Fiscal Digital no fue emitido</label>
	        <hr style="margin: 5px 0px 15px;">
			<aside class="alert alert-info">'.$informacion.'</aside>
		</section>

		<section id="descargar-factura" style="display: '.$factura_generada.';">
			<label class="lbl-text" style="font-style:italic;">El Comprobante Fiscal Digital fue emitido satisfactoriamente</label>
	        <hr style="margin: 5px 0px 15px;">
			<div class="col-sm-6 col-md-3 col-md-offset-3">
				<a href="'.$pdf.'" id="btn_factura_pdf" class="km-btn-primary" style="margin-top: 5px;margin-bottom: 5px; border: 0px solid transparent;">Descargar PDF</a>
			</div>
			<div class="col-sm-6 col-md-3">
				<a href="javascript:;" id="btn_facturar_sendmail" class="km-btn-primary" style="margin-top: 5px;margin-bottom: 5px;border: 0px solid transparent;">Enviar por Email</a>
			</div>
		</section>

		<div class="clear"></div>
		<section class="col-sm-12 col-md-12" style="margin-top: 20px;">
			<div class="perfil_cargando" style="width: 100%; background-image: url('.getTema().'/images/cargando.gif);" ></div>
			<br>
			<!-- a href="/perfil-usuario/historial"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Volver </a -->
		</section>

	</div>
	';


