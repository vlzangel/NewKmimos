<?php
	/*
        Template Name: Factura pdf
    */
	global $wpdb;
	
	wp_enqueue_style('ver_pdf_factura', getTema()."/css/datos-de-facturacion.css", array(), '1.0.0');
	wp_enqueue_script('ver_pdf_factura', getTema()."/js/consultar-factura.js");
	
	$reserva_id = vlz_get_page();
	
	
	$ruta = '';
	if( $reserva_id > 0 ){
		$factura = $wpdb->get_row( "select * from facturas where reserva_id = {$reserva_id}");
		$ruta = get_home_url()."/wp-content/uploads/facturas/".$reserva_id.'_'.$factura->numeroReferencia.".pdf";
	}
?>
<iframe src="<?php echo $ruta; ?>" width="100%" height="200px" marginheight="0" marginwidth="0"  frameborder="0">
</iframe>
<?php $no_display_footer = true; ?> 
<?php get_footer(); ?> 
