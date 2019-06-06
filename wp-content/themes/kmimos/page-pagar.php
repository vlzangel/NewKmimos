<?php 
    /* Template Name: Pagar */

    // error_reporting(0);

    include __DIR__.'/funciones.php';

	date_default_timezone_set('America/Mexico_City');

    if( !isset($_SESSION) ){ session_start(); }

	$order_id = vlz_get_page();

	global $wpdb;

	/* Cuidador */
		$reserva = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_parent = ".$order_id);
		$servicio_id = get_post_meta($reserva, '_booking_product_id', true);
		$post = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = ".$servicio_id);
		$author = $post->post_author;
		$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = ".$author);
		$cuidador_name = $cuidador->titulo;

	/* Servicio */
		$servicio_name_corto = explode(" - ", $post->post_title);
		$servicio_name = $servicio_name_corto[1];
		$servicio_name_corto = $servicio_name_corto[0];
		$atributos = unserialize($cuidador->atributos);


	/* Recursos */

		get_header();
	    wp_enqueue_style('producto', getTema()."/css/producto.css", array(), '1.0.0');
		wp_enqueue_style('producto_responsive', getTema()."/css/responsive/producto_responsive.css", array(), '1.0.0');
		wp_enqueue_script('pagar', get_recurso("js")."pagar.js", array("jquery"), '2.0.0');
		wp_enqueue_script('openpay-v1', getTema()."/js/openpay.v1.min.js", array("jquery"), '1.0.0');
		wp_enqueue_script('openpay-data', getTema()."/js/openpay-data.v1.min.js", array("jquery", "openpay-v1"), '1.0.0');

		include( dirname(__FILE__)."/procesos/funciones/config.php" );

		$HTML .= "
		<script> 
			var OPENPAY_TOKEN = '".$MERCHANT_ID."';
			var OPENPAY_PK = '".$OPENPAY_KEY_PUBLIC."';
			var OPENPAY_PRUEBAS = ".$OPENPAY_PRUEBAS.";
		</script>";

		$descripcion = $wpdb->get_var("SELECT post_excerpt FROM wp_posts WHERE ID = {$servicio_id}");
		preg_match_all("#-(.*?)\n#i", "-".$descripcion, $matches_1);
		preg_match_all("#<small>(.*?)</small>#", $descripcion, $matches_2);
		$descripcion_1 = $matches_1[1][0];
		$descripcion_2 = 'Precio final (incluye cobertura veterinaria y gastos administrativos; no incluye servicios adicionales)';
		if( $descripcion_1 != "" ){
			$descripcion_1 = "* ".$descripcion_1;
		}

		$HTML .= '
		<div class="page-reservation">
	 		<form id="reservar" class="km-content km-content-reservation">

				<div id="step_3" class="km-col-steps" style="display: block !important;">
					<div class="km-col-content">
						<div class="barra_titulo">
							<div class="km-title-step">
								REALIZA TU PAGO
							</div>
						</div>

						<div class="cuidador_select">
							<strong>Cuidador seleccionado:</strong> <span>'.$cuidador_name.'</span>
						</div>

						<div class="fechas_select">
							<span class="value-resume">
								<img class="" src="'.get_recurso("img").'/HOME/SVG/Fecha.svg" align="center">
								<span class="fecha_ini">17/06/2019</span>
								<img class="" src="'.get_recurso("img").'/HOME/SVG/Flecha.svg" align="center" style="margin: 0px 0px 4px 10px;">
							
								<img class="" src="'.get_recurso("img").'/HOME/SVG/Fecha.svg" align="center">
								<span class="fecha_fin">19/06/2019</span>
							</span>
						</div>

						<div class="km-content-step km-content-step-2">
							<div class="km-option-resume">
								<div class="km-option-resume-service">
									<span class="label-resume-service">'.$post->post_title.'</span>
								</div>
								<div class="items_reservados reservados">
									<div class="km-option-resume-service">	
										<span class="label-resume-service">1 Mascota Pequeña x 1 Noche x $162.5 </span>	
										<span class="label-resume-service_movil">1 Masc. Peq. x 1 Noche x $162.5 </span>	
										<span class="value-resume-service">$162.50</span>
									</div>
									<div class="km-option-resume-service">	
										<span class="label-resume-service">1 Mascota Mediana x 1 Noche x $250 </span>	
										<span class="label-resume-service_movil">1 Masc. Med. x 1 Noche x $250 </span>	
										<span class="value-resume-service">$250.00</span>
									</div>
									<div class="km-option-resume-service">	
										<span class="label-resume-service">Corte de pelo y uñas - 2 Mascotas x $7.5</span>	
										<span class="value-resume-service">$15.00</span>
									</div>
									<div class="km-option-resume-service">	
										<span class="label-resume-service">Transp. Sencillo - Rutas Cortas - Precio por Grupo </span>	
										<span class="value-resume-service">$1.25</span>
									</div>
								</div>
							</div>
							<div class="cupones_desglose km-option-resume">
								<div>
									<span class="label-resume">Descuentos</span>
									<div></div>
								</div>
							</div>
							<div class="km-services-total">
								<div style="max-width: 70%;">
									<span class="km-text-total">TOTAL</span>
									<span class="km-price-total2">$428.75</span>
								</div>
							</div>
						</div>

						<div id="metodos_pagos">
							<div class="km-tab-content" style="display: block;">
								<div class="km-content-method-paid-inputs">

									<div class="km-select-method-paid">
										<div class="km-method-paid-title">
											Medio de pago
										</div>

										<div id="pasarela-container">
											<div class="km-method-paid-option km-option-3-lineas km-tienda active"
												onclick="evento_google_kmimos(\'tienda\'); evento_fbq_kmimos(\'tienda\');" >
												<div class="km-text-one">PAGO EN TIENDA DE CONVENIENCIA</div>
											</div>
											<div class="km-method-paid-option km-option-3-lineas km-tarjeta"
												onclick="evento_google_kmimos(\'tarjeta\'); evento_fbq_kmimos(\'tarjeta\');" >
												<div class="km-text-one"><span>PAGO CON </span>TARJETA DE CRÉDITO O DÉBITO</div>
											</div>
											<div class="km-method-paid-option km-option-3-lineas km-paypal"
												onclick="evento_google_kmimos(\'paypal\'); evento_fbq_kmimos(\'paypal\');" >
												<div class="km-text-one">PAGO CON PAYPAL</div>
											</div>
											<div class="km-method-paid-option km-option-3-lineas km-mercadopago"
												onclick="evento_google_kmimos(\'mercadopago\'); evento_fbq_kmimos(\'mercadopago\');">
												<div class="km-text-one">PAGO CON MERCADOPAGO</div>					
											</div>
										</div>
									</div>

									<select id="tipo_pago" style="display: none;">
										<option value="tienda">PAGO EN TIENDA DE CONVENIENCIA</option>
										<option value="tarjeta">PAGO CON TARJETA DE CRÉDITO O DÉBITO</option>
										<option value="paypal">PAGO CON PAYPAL</option>
										<option value="mercadopago">PAGO CON MERCADOPAGO</option>
									</select>

									<div class="errores_box">
										Datos de la tarjeta invalidos
									</div>

									<div id="tienda_box" class="metodos_container" style="display:block;">
										<img src="'.get_recurso("img").'RESERVA/pago_tienda.png" />
									</div>
									<div id="mercadopago_box" class="metodos_container" style="display:none;">
										<img class="img-responsive" src="'.get_recurso("img").'RESERVA/pago_mercadopago.png" /><br>
									</div>
									<div id="paypal_box" class="metodos_container" style="display:none;">
										<img class="img-responsive" src="'.get_recurso("img").'RESERVA/pago_paypal.png" /><br>
									</div>
									<div id="tarjeta_box" class="metodos_container" style="display:none;">

										<div class="datos_tarjeta_container">

											<div style="padding-right: 10px;">
												<div class="label-placeholder">
													<label>Nombre del tarjetahabiente*</label>
													<input type="text" id="nombre" name="nombre" value="" class="input-label-placeholder solo_letras" data-openpay-card="holder_name">
												</div>

												<div class="label-placeholder">
													<label>Número de Tarjeta*</label>
													<input type="text" id="numero" name="numero" class="input-label-placeholder next solo_numeros maxlength" data-max="19" data-next="mes">
													<input type="hidden" id="numero_oculto" data-openpay-card="card_number">
												</div>
											</div>

											<div style="padding-left: 10px;">
											
												<div class="label-placeholder">
													<label>Expira (MM AA)</label>
													<input type="text" id="mes" name="mes" class="input-label-placeholder next expiration solo_numeros maxlength" data-max="2" data-next="anio" maxlength="2" data-openpay-card="expiration_month">
													<input type="text" id="anio" name="anio" class="input-label-placeholder next expiration solo_numeros maxlength" data-max="2" data-next="codigo" maxlength="2" data-openpay-card="expiration_year">
												</div>

												<div class="label-placeholder">
													<label>Código de seguridad(CVV)</label>
													<input type="text" id="codigo" name="codigo" class="input-label-placeholder next solo_numeros maxlength" data-max="4" maxlength="4" data-next="null" data-openpay-card="cvv2">
													<small>Número de tres dígitos en el reverso de la tarjeta</small>
												</div>
											</div>

										</div>

									</div>

								</div>
							</div>
						</div>

						<div class="km-term-conditions">
							<label>
								<input type="checkbox" id="term-conditions" name="term-conditions" value="1">
								Acepto los <a href="'.get_home_url().'/terminos-y-condiciones/" target="_blank">términos y condiciones</a>
							</label>
						</div>

						<span id="reserva_btn_next_3" class="km-end-btn-form vlz_btn_reservar disabled">
							<div class="perfil_cargando" style="background-image: url('.getTema().'/images/cargando.gif);" ></div> <span>Terminar reserva</span>
						</span>

					</div>
				</div>

				<div class="km-col-empty">
					<img src="'.getTema().'/images/new/bg-cachorro.png" style="max-width: 100%;">
				</div>
			</form>
		</div>
		';

		$HTML .= '
			<div class="modal fade" role="dialog" data-backdrop="false" id="card-points-dialog">
			  <div class="modal-dialog">
			    <div class="modal-content">
                  <div class="modal-footer" style="border:0px solid transparent!important">
				      <div id="mensaje-puntos-bancomer">
				      	<div class="col-md-6 col-sm-12">
					      	<img src="'.getTema().'/recursos/img/RESERVA/pago-bancomer.png" class="img-responsive solo-pc">
					      	<div class="button-container col-md-12 text-center">
					        	<button type="button" class="btn btn-default" data-dismiss="modal" id="points-no-button">No</button>
					        	<button type="button" class="btn btn-primary" data-dismiss="modal" id="points-yes-button">Si</button>
					        </div>
					        <div class="clear"></div>
					    </div>
				      	<div class="col-md-6 col-sm-12 hidden-sm hidden-xs">
					      	<img src="'.getTema().'/recursos/img/RESERVA/pago-bancomer-tdc.png" class="img-responsive">
					        <div class="clear"></div>
					    </div>
				      </div>
                  </div>

			    </div>
			  </div>
			</div>
		';

		echo comprimir($HTML);

		echo "<pre>";
			print_r($_SESSION["sql"]);
		echo "</pre>";

		unset($_SESSION["pagando"]);

    get_footer(); 
?>
