<?php



include_once( dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))) ."/vlz_config.php");
include_once( dirname(dirname(dirname(__DIR__))) . "/procesos/funciones/db.php");

global $wpdb;

$db = new db( new mysqli($host, $user, $pass, $db) );
include_once( dirname(dirname(dirname(__DIR__)))."/lib/enlaceFiscal/CFDI.php" );


$orden = vlz_get_page();

$factura_generada = 'none';
$factura_datos = 'block';
$pdf = 'javascript:;';

$reserva_id = $db->get_var( "select ID from wp_posts where post_parent = {$orden} and post_type = 'wc_booking'");
if( $reserva_id > 0 ){
	$factura = $db->get_row( "select * from facturas where reserva_id = {$reserva_id}");
	if( isset($factura->id) && $factura->id > 0 ){
		$factura_generada = 'block';
		$factura_datos = 'none';
		$pdf = $factura->urlPdf;
	}
}


$CONTENIDO = '	
<div class="text-left">
    <h1 style="margin: 10px 0px 5px 0px; padding: 0px;">Comprobante Fiscal Digital - Reserva #'.$reserva_id.' </h1>
	
	<section id="solicitar-factura" style="display: '.$factura_datos.';">
		<label class="lbl-text" style="font-style:italic;">Complete los siguientes datos para emitir su CFDI</label>
        <hr style="margin: 5px 0px 15px;">

        <input type="hidden" id="id_orden" name="id_orden" value="'.$orden.'" />
        <input type="hidden" name="user_id" value="'.$user_id.'" />
        <input type="hidden" name="accion" value="factura_cliente" />
        <input type="hidden" name="core" value="SI" />

		<div class="col-sm-12 col-md-6 col-md-offset-3 text-left" style="margin: 0 auto; float: none;">
	 		
	 		<div>
				<label for="rfc" class="lbl-text">* RFC:</label>
				<label class="lbl-ui">
					<input type="text" id="rfc" name="rfc" value="AAA010101AAA" data-valid="requerid" autocomplete="off" min-lenght="12" max-lenght="13">
					<div class="no_error" id="error_rfc" data-id="rfc">Completa este campo.</div>
				</label>
	 		</div>

	 		<div>
				<label for="nombre" class="lbl-text">* Nombre:</label>
				<label class="lbl-ui">
					<input type="text" id="nombre" name="nombre" value="Pedro Jose" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
					<div class="no_error" id="error_nombre" data-id="nombre">Completa este campo.</div>
				</label>
	 		</div>

	 		<div>
				<div class="checkbox">
				    <label>
						<input type="checkbox" id="check" data-valid="isChecked">
						<small>Doy fe que los datos suministrados en el presente formulario son correctos y serán utilizados para la facturación del servicio.</small>
						<div class="no_error" id="error_check" data-id="check">Completa este campo.</div>
					</label>
				</div>
	 		</div>

		</div>
		<div class="col-sm-12 col-md-6 col-md-offset-3 text-left" style="margin: 0 auto; float: none;">
			<input type="button" id="btn_facturar" class="col-md-3 pull-right km-btn-primary" value="Generar Factura" style="border: 0px solid transparent;"/>
 		</div>
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

