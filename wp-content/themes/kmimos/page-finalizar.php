<?php 
    /*
        Template Name: Finalizar
    */

    wp_enqueue_style('finalizar', getTema()."/css/finalizar.css", array(), '1.0.0');
	wp_enqueue_style('finalizar_responsive', getTema()."/css/responsive/finalizar_responsive.css", array(), '1.0.0');

	wp_enqueue_script('finalizar', getTema()."/js/finalizar.js", array("jquery"), '1.0.0');

	get_header();
		
		$id_user = get_current_user_id();

		$orden_id = vlz_get_page();


		$pdf = get_post_meta($orden_id, "_openpay_pdf", true);
		if( $pdf != "" ){
			$pdf = "
				<a class='btn_fin_reserva' href='{$pdf}' target='_blank'>DESCARGAR COMPROBANTE DE PAGO</a>
			";
		}

		$HTML .= '
	 		<div class="km-content km-step-end">
				<div style="padding: 20px;">
					<img src="'.getTema().'/images/new/km-reserva/img-end-step.png" width="197">
					<br>
					¡Genial '.get_user_meta($id_user, "first_name", true).'!<br>
					Reservaste Exitosamente

					<!--
					<div class="desglose_reserva" >
						<div class="border_desglose">
							<div>Tu numero de reserva </diV>
							<span>202290</span>
						</div>
						<div class="border_desglose">
							<div>Fecha de tu reserva </diV>
							<span>27 Julio, 2017</span>
						</div>
						<div>
							<div class="remanente">
								Monto Restante a Pagar<br>
								al cuidador en EFECTIVO
							</diV>
							<span>$809.25</span>
						</div>
						<div class="border_desglose">
							<div>Pag&oacute; </diV>
							<span>$165.75</span>
						</div>
						<div>
							<div>Total </diV>
							<span>$975.00</span>
						</div>
					</div>
					-->

					<div style="padding-top: 20px;">
						'.$pdf.'
						<a class="btn_fin_reserva" href="'.get_home_url().'/perfil-usuario/historial/">VER MIS RESERVAS</a>
					</div>
				</div>
			</div>

			<!-- SECCIÓN BENEFICIOS -->
			<div class="km-beneficios km-beneficios-footer" style="margin-top: 60px;">
				<div class="container">
					<div class="row">
						<div class="col-xs-4">
							<div class="km-beneficios-icon">
								<img src="'.getTema().'/images/new/km-pago.svg">
							</div>
							<div class="km-beneficios-text">
								<h5 class="h5-sub">PAGO EN EFECTIVO O CON TARJETA</h5>
							</div>
						</div>
						<div class="col-xs-4 brd-lr">
							<div class="km-beneficios-icon">
								<img src="'.getTema().'/images/new/km-certificado.svg">
							</div>
							<div class="km-beneficios-text">
								<h5 class="h5-sub">CUIDADORES CERTIFICADOS</h5>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="km-beneficios-icon">
								<img src="'.getTema().'/images/new/km-veterinaria.svg">
							</div>
							<div class="km-beneficios-text">
								<h5 class="h5-sub">COBERTURA VETERINARIA</h5>
							</div>
						</div>
					</div>
				</div>
			</div>
	 	';

		echo comprimir_styles($HTML);

    get_footer(); 
?>