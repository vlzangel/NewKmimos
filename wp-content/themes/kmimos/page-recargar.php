<?php 
    /* Template Name: Recargar */

    error_reporting(0);

    include __DIR__.'/funciones.php';

	date_default_timezone_set('America/Mexico_City');

    if( !isset($_SESSION) ){ session_start(); }

	global $wpdb;

	/* Cliente */
		$USER_ID = get_current_user_id();
		$CUIDADOR = $wpdb->get_var( "SELECT url FROM cuidadores WHERE id_post = ".vlz_get_page() );

		COMPROBAR_ERRORES_CONOCER();

		$email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$USER_ID}");
		$saldo = getSaldo();

	/* Recursos */

		get_header();

	    wp_enqueue_style('producto', getTema()."/css/producto.css", array(), '1.0.0');
		wp_enqueue_style('producto_responsive', getTema()."/css/responsive/producto_responsive.css", array(), '1.0.0');

		wp_enqueue_script('recargar', getTema()."/js/recargar.js", array("jquery"), '1.0.0');

		wp_enqueue_script('openpay-v1', getTema()."/js/openpay.v1.min.js", array("jquery"), '1.0.0');
		wp_enqueue_script('openpay-data', getTema()."/js/openpay-data.v1.min.js", array("jquery", "openpay-v1"), '1.0.0');

		include( dirname(__FILE__)."/procesos/funciones/config.php" );

		$super_admin = (  $_SESSION['admin_sub_login'] != 'YES' ) ? 'No': 'Si';

		$HTML .= "
		<script> 
			var cliente = '".$USER_ID."'; 
			var cuidador = '".$CUIDADOR."'; 
			var email = '".$email."'; 
			var OPENPAY_TOKEN = '".$MERCHANT_ID."';
			var OPENPAY_PK = '".$OPENPAY_KEY_PUBLIC."';
			var OPENPAY_PRUEBAS = ".$OPENPAY_PRUEBAS.";
			var HOY = '".$hoy."';
			var MANANA = '".$manana."';
			var HORA = '".(date("G", $NOW )+0)."';
			var SUPERU = '".$super_admin."';
		</script>";


		$precios = '
			<div id="bloque_info_servicio" class="km-content-step '.$bloquear.' '.$bloquear_madrugada.'">
				<div class="km-content-new-pet">

					<div class="km-services-total km-total-calculo">
						<div class="invalido"></div>
						<div class="valido">
							<div class="barra_inferior_container">
								<div style="padding-right: 50px;">
									<span class="km-text-total">TOTAL</span>
									<span class="km-price-total">$30.00</span>
								</div>
								<div class="btn_container">
									<a href="#" id="reserva_btn_next_1" class="km-end-btn-form km-end-btn-form-disabled disabled vlz_btn_reservar">
										<span>Siguiente</span>
									</a>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		';

		$HTML .= '
		<div class="page-reservation">
	 		<form id="reservar" class="km-content km-content-reservation">
				<div id="step_1" class="km-col-steps">
					<div class="km-col-content">
					
						<div id="atras_0" class="atras" style="display: none;"> &nbsp; </div>
					
						<div class="barra_titulo">
							<ul class="steps-numbers">
								<li><span class="number active">1</span></li>
								<li class="line"></li><li><span class="number">2</span></li>
								<li class="line"></li><li><span class="number">3</span></li>
							</ul>
							<div class="km-title-step">
								ADQUIERE TUS CRÉDITOS
							</div>
						</div>

						<div class="km-sub-title-step">
							Adquiere tus créditos para poder conocer a cualquier cuidador
						</div>

						'.$precios.'

					</div>
				</div>';

		$HTML .= '
				<div id="step_2" class="km-col-steps">
					<div class="km-col-content">
						<div id="atras_1" class="atras"> Volver </div>
						<div class="barra_titulo">
							<ul class="steps-numbers">
								<li><span class="number checked">1</span></li>
								<li class="line"></li><li><span class="number active">2</span></li>
								<li class="line"></li><li><span class="number">3</span></li>
							</ul>
							<div class="km-title-step">
								RESUMEN DE TU COMPRA
								<div>Queremos confirmar tu método de pago</div>
							</div>
						</div>

						<div class="km-content-step km-content-step-2">
							<div class="km-option-resume">
								<div class="km-option-resume-service">
									<span class="label-resume-service">'.$post->post_title.'</span>
								</div>
								<div class="items_reservados"></div>
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
									<span class="km-price-total2"></span>
								</div>
							</div>
						</div>

						<div class="barra_inferior_2_container">
							<div class="km-cupones" style="visibility: hidden;">
								<div>
									<input type="text" id="cupon" placeholder="Ingresa tu cupón">
								</div>
								<div class="">
									<span id="cupon_btn">Cup&oacute;n</span>
								</div>
							</div>
							<span id="reserva_btn_next_2" class="km-end-btn-form vlz_btn_reservar">
								<span>Siguiente</span>
							</span>
						</div>

					</div>

				</div>';

		$HTML .= '
				<div id="step_3" class="km-col-steps">
					<div class="km-col-content">
						<div id="atras_2" class="atras"> Volver </div>
						<div class="barra_titulo">
							<ul class="steps-numbers">
								<li><span class="number checked">1</span></li>
								<li class="line"></li><li><span class="number active">2</span></li>
								<li class="line"></li><li><span class="number">3</span></li>
							</ul>
							<div class="km-title-step">
								RESUMEN DE TU COMPRA
							</div>
						</div>

						<div class="km-content-step km-content-step-2">
							<div class="km-option-resume">
								<div class="km-option-resume-service">
									<span class="label-resume-service">'.$post->post_title.'</span>
								</div>
								<div class="items_reservados"></div>
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
									<span class="km-price-total2"></span>
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
									<div id="mercadopago_box" class="metodos_container" style="display:none;">
										<img class="img-responsive" src="'.get_recurso("img").'RESERVA/pago_mercadopago.png" /><br>
									</div>
									<div id="paypal_box" class="metodos_container" style="display:none;">
										<img class="img-responsive" src="'.get_recurso("img").'RESERVA/pago_paypal.png" /><br>
									</div>
									<div id="tienda_box" class="metodos_container" style="display:block;">
										<img src="'.get_recurso("img").'RESERVA/pago_tienda.png" />
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

				