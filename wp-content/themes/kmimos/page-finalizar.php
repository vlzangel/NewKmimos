<?php 
    /*
        Template Name: Finalizar
    */

    wp_enqueue_style('finalizar', getTema()."/css/finalizar.css", array(), '1.0.0');
	wp_enqueue_style('finalizar_responsive', getTema()."/css/responsive/finalizar_responsive.css", array(), '1.0.0');

	wp_enqueue_script('finalizar', getTema()."/js/finalizar.js", array("jquery"), '1.0.0');

	get_header();

		global $wpdb;
		
		$id_user = get_current_user_id();

		$orden_id = vlz_get_page();

		$reserva_id = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = ".$orden_id);

		$items_id = get_post_meta($reserva_id, "_booking_order_item_id", true);

		$xitems = $wpdb->get_results("SELECT * FROM wp_woocommerce_order_itemmeta WHERE order_item_id = ".$items_id);
		$items = array();
		foreach ($xitems as $key => $value) {
			$items[ $value->meta_key ] = $value->meta_value;
		}

		$desglose = ''; 

		$deposito = unserialize($items["_wc_deposit_meta"]);
		$descuento = get_post_meta($orden_id, "_cart_discount", true);

		if( $deposito["enable"] == "yes" ){
			$desglose .= '
				<div>
					<div class="remanente">
						Monto Restante a Pagar<br>
						al cuidador en EFECTIVO
					</diV>
					<span>&nbsp;<br>$'.number_format( $deposito["remaining"]-$descuento, 2, ',', '.').'</span>
				</div>
				<div class="border_desglose">
					<div>Pag&oacute; </diV>
					<span>$'.number_format( $deposito["deposit"], 2, ',', '.').'</span>
				</div>
			';
		}else{
			$desglose .= '
				<div>
					<div>Pag&oacute; </diV>
					<span>$'.number_format( $items["_line_subtotal"]-$descuento, 2, ',', '.').'</span>
				</div>
			';
		}

		if( $descuento+0 > 0){
			$desglose = '
				<div>
					<div>
						Descuento
					</diV>
					<span>$'.number_format( $descuento, 2, ',', '.').'</span>
				</div>
			'.$desglose;
		}

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

					<div class="desglose_reserva" >
						<div class="border_desglose">
							<div>Tu numero de reserva </diV>
							<span>'.$reserva_id.'</span>
						</div>
						<div class="border_desglose">
							<div>Fecha de tu reserva </diV>
							<span>'.$items["Fecha de Reserva"].'</span>
						</div>
						'.$desglose.'
						<div class="border_total">
							<div>Total </diV>
							<span>$'.number_format( $items["_line_subtotal"], 2, ',', '.').'</span>
						</div>
					</div>
					
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