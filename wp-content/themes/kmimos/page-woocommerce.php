<?php 
    /*
        Template Name: Woocommerce
    */

    wp_enqueue_style('producto', getTema()."/css/producto.css", array(), '1.0.0');
	wp_enqueue_style('producto_responsive', getTema()."/css/responsive/producto_responsive.css", array(), '1.0.0');

	wp_enqueue_script('producto', getTema()."/js/producto.js", array("jquery"), '1.0.0');

	wp_enqueue_script('openpay-v1', getTema()."/js/openpay.v1.min.js", array("jquery"), '1.0.0');
	wp_enqueue_script('openpay-data', getTema()."/js/openpay-data.v1.min.js", array("jquery", "openpay-v1"), '1.0.0');

	get_header();
		
		global $wpdb;

		$post_id = vlz_get_page();

		$post = get_post( $post_id );

		$D = $wpdb;

		$id_user = get_current_user_id(); // [SERVER_NAME] 

		$actual = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$referencia = $_SERVER['HTTP_REFERER'];

		if( $actual == $referencia ){
			$referencia = get_home_url();
		} 

		$DS = kmimos_session();
	    if( $DS ){ ?>
			
				<?php if( $DS["saldo_temporal"] > 0 ){ ?>
					<div class="theme_button" style="padding: 10px; margin-bottom: 20px;">
						<strong><?php echo kmimos_saldo_titulo(); ?>:</strong> MXN $<?php echo $DS["saldo"]; ?>
					</div>
				<?php }else{ 
						$kmisaldo = kmimos_get_kmisaldo();
						if( $kmisaldo > 0 ){ ?>
							<div class="theme_button" style="padding: 10px; margin-bottom: 20px;">
								<strong><?php echo kmimos_saldo_titulo(); ?>:</strong> MXN $<?php echo $kmisaldo; ?>
							</div>
				<?php 	}
					  } ?>
			 <?php
			if( isset($DS["reserva"]) ){ ?>
				<div class="theme_button" style="padding: 10px 10px 10px 40px; margin-bottom: 20px; position: relative;">
					<img src="<?php echo get_template_directory_uri()."/images/advertencia.png"; ?>" style="position: absolute; top: 4px; left: 6px; width: 30px;" />
					
					<span style="font-weight: 600;">Importante:</span> Confirme previamente con el cuidador la disponibilidad del ajuste que usted desea realizar.
				</div> <?php 
			}
	    }

		$busqueda = getBusqueda();

		$servicio_id = $post_id;

		$hoy = date("Y-m-d");

		$cupos = $wpdb->get_results("SELECT * FROM cupos WHERE servicio = '{$servicio_id}' AND fecha >= NOW()");

		$sql = "
	        SELECT
	            tipo_servicio.slug AS slug
	        FROM 
	            wp_term_relationships AS relacion
	        LEFT JOIN wp_terms as tipo_servicio ON ( tipo_servicio.term_id = relacion.term_taxonomy_id )
	        WHERE 
	            relacion.object_id = '{$servicio_id}' AND
	            relacion.term_taxonomy_id != 28
	    ";
		$tipo = $wpdb->get_var($sql);

		$cuidador = $wpdb->get_row( "SELECT * FROM cuidadores WHERE user_id = ".$post->post_author );

		$cuidador_name = $wpdb->get_var( "SELECT post_title FROM wp_posts WHERE ID = ".$cuidador->id_post );
		$servicio_name = $wpdb->get_var( "SELECT post_title FROM wp_posts WHERE ID = ".$servicio_id );

	    $precios = "xx";
	    
		$adicionales = unserialize($cuidador->adicionales);

	    if( $tipo == "hospedaje" ){
	    	$precios = getPrecios( unserialize($cuidador->hospedaje) );
	    }else{
	    	$precios = getPrecios( $adicionales[$tipo] );
	    } 

		$transporte = getTransporte($adicionales);
		if( $transporte != "" ){
			$transporte = '
				<div class="km-service-title"> TRANSPORTACI&Oacute;N </div>
				<div class="km-services">
					<select id="transporte" name="transporte" class="km-input-custom"><option value="">Seleccione una opci&oacute;n</option>'.$transporte.'</select>
				</div>
			';
		}

		$adicionales = getAdicionales($adicionales);
		if( $adicionales != "" ){
			$adicionales = '
				<div class="km-service-title"> SERVICIOS ADICIONALES </div>
				<div id="adicionales" class="km-services">
					'.$adicionales.'
				</div>
			';
		}

		$productos .= '</div>';

		$email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$id_user}");

		echo "
		<script> 
			var SERVICIO_ID = '".get_the_ID()."';
			var cupos = eval('".json_encode($cupos)."'); 
			var tipo_servicio = '".$tipo."'; 
			var name_servicio = '".$servicio_name."'; 
			var cliente = '".$id_user."'; 
			var cuidador = '".$cuidador->id_post."'; 
			var email = '".$email."'; 
		</script>";

		$HTML .= '
	 		<form id="reservar" class="km-content km-content-reservation">
				<div id="step_1" class="km-col-steps">
					<div class="km-col-content">
						<ul class="steps-numbers">
							<li><span class="number active">1</span></li>
							<li class="line"></li><li><span class="number">2</span></li>
							<li class="line"></li><li><span class="number">3</span></li>
						</ul>

						<div class="km-title-step">
							RESERVACIÓN
						</div>

						<div class="km-sub-title-step">
							Reserva las fechas y los servicios con tu cuidador(a) '.$cuidador_name.'
						</div>

						<div class="km-dates-step">
							<div class="km-ficha-fechas">
								<input type="text" id="checkin" name="checkin" placeholder="DESDE" value="'.$busqueda["checkin"].'" class="date_from" readonly>
								<input type="text" id="checkout" name="checkout" placeholder="DESDE" value="'.$busqueda["checkout"].'" readonly>
							</div>
						</div>

						<div class="km-content-step">
							<div class="km-content-new-pet">
								'.$precios.'
								<div class="km-services-content">
									'.$transporte.'
									'.$adicionales.'
								</div>

								<div class="km-services-total">
									<div class="valido">
										<span class="km-text-total">TOTAL</span>
										<span class="km-price-total">$0.00</span>
									</div>
									<div class="invalido">
										
									</div>
								</div>

							</div>
						</div>

						<div class="km-text-end-form">
							* Precio final (incluye cobertura veterinaria y gastos administrativos; no incluye servicios adicionales)
						</div>

						<a href="#" id="reserva_btn_next_1" class="km-end-btn-form km-end-btn-form-disabled disabled vlz_btn_reservar">
							<span>SIGUIENTE</span>
						</a>

					</div>
				</div>


				<div id="step_2" class="km-col-steps">

					<div class="km-col-content">
						<ul class="steps-numbers">
							<li>
								<span class="number checked">1</span>
							</li>
							<li class="line"></li>
							<li>
								<span class="number active">2</span>
							</li>
							<li class="line"></li>
							<li>
								<span class="number">3</span>
							</li>
						</ul>

						<div class="km-title-step">
							RESUMEN DE TU RESERVA
						</div>

						<div class="km-sub-title-step">
							Queremos confirmar tu reservación y tu método de pago
						</div>

						<div class="km-content-step km-content-step-2">
							<div class="km-option-resume">
								<span class="label-resume">CUIDADOR SELECCIONADO</span>
								<span class="value-resume">'.$cuidador_name.'</span>
							</div>

							<div class="km-option-resume">
								<span class="label-resume">FECHA</span>
								<span class="value-resume">
									<span id="fecha_ini"></span>
									&nbsp; &gt; &nbsp;
									<span id="fecha_fin"></span>
								</span>
							</div>

							<div class="km-option-resume">

								<div class="km-option-resume-service">
									<span class="label-resume-service">'.$servicio_name.'</span>
								</div>

								<div class="items_reservados"></div>

							</div>

							<div class="km-services-total">
								<span class="km-text-total">TOTAL</span>
								<span class="km-price-total">$420.00</span>
							</div>
						</div>

						<div class="km-select-method-paid">
							<div class="km-method-paid-title">
								SELECCIONA EL MÉTODO DE PAGO
							</div>

							<div class="km-method-paid-options">
								<div class="km-method-paid-option km-option-deposit">
									<div class="km-text-one">
										DEPÓSITO DEL 17 %
									</div>
									<div class="km-text-two">
										Pague ahora el 17% y el restante
									</div>
									<div class="km-text-three">
										AL CUIDADOR EN EFECTIVO
									</div>
								</div>

								<div class="km-method-paid-option km-option-total active">
									<div class="km-text-one">
										MONTO TOTAL
									</div>
								</div>
							</div>
						</div>

						<div class="km-detail-paid-deposit">
							<div class="km-detail-paid-line-one">
								<span class="km-detail-label">TOTAL</span>
								<span id="monto_total" class="km-detail-value">$975.00</span>
							</div>

							<div class="km-detail-paid-line-two">
								<span class="km-detail-label">MONTO A PAGAR <b>EN EFECTIVO AL CUIDADOR</b></span>
								<span id="pago_cuidador" class="km-detail-value">$809.25</span>
							</div>

							<div class="km-detail-paid-line-three">
								<span class="km-detail-label">PAGUE AHORA</span>
								<span id="pago_17" class="km-detail-value">$165.75</span>
							</div>
						</div>

						<span id="reserva_btn_next_2" class="km-end-btn-form vlz_btn_reservar">
							<span>SIGUIENTE</span>
						</span>

					</div>

				</div>

				<div id="step_3" class="km-col-steps">
					<div class="km-col-content">
						<ul class="steps-numbers">
							<li>
								<span class="number checked">1</span>
							</li>
							<li class="line"></li>
							<li>
								<span class="number checked">2</span>
							</li>
							<li class="line"></li>
							<li>
								<span class="number active">3</span>
							</li>
						</ul>

						<div class="km-title-step">
							ESTOY TRABAJANDO EN ESTA PANTALLA<!-- <br>
							RESUMEN DE TU RESERVA -->
						</div>

						<div class="km-tab-content" style="display: block;">
							<div class="km-content-step km-content-step-2">
								<div class="km-option-resume">
									<span class="label-resume">CUIDADOR SELECCIONADO</span>
									<span class="value-resume">'.$cuidador_name.'</span>
								</div>

								<div class="km-option-resume">
									<span class="label-resume">FECHA</span>
									<span class="value-resume">
										24/07/2017
										&nbsp; &gt; &nbsp;
										26/07/2017
									</span>
								</div>

								<div class="km-option-resume">
									<div class="km-option-resume-service">
										<span class="label-resume-service">'.$servicio_name.'</span>
									</div>
									<div class="items_reservados"></div>
								</div>

								<div class="km-services-total">
									<span class="km-text-total">TOTAL</span>
									<span class="km-price-total"></span>
								</div>
							</div>
						</div>

						<div class="errores_box">
							Datos de la tarjeta invalidos
						</div>

						<a href="#" class="km-tab-link">MEDIO DE PAGO</a>
						<div class="km-tab-content" style="display: block;">
							<div class="km-content-method-paid-inputs">
								<select class="km-select-method-paid-inputs" id="tipo_pago">
									<option value="tarjeta">Pago con tarjeta de crédito o débito</option>
									<option value="tienda">Pago en tienda de conveniencia</option>
								</select>

								<div id="tarjeta_box" class="metodos_container">

									<div class="label-placeholder">
										<label>Nombre del tarjetahabitante*</label>
										<input type="text" id="nombre" name="nombre" value="" class="input-label-placeholder" data-openpay-card="holder_name">
									</div>

									<div class="label-placeholder">
										<label>Número de Tarjeta*</label>
										<input type="text" id="numero" name="numero" class="input-label-placeholder" maxlength="16" data-openpay-card="card_number">
									</div>

									<div class="content-placeholder">
										<div class="label-placeholder">
											<label>Expira (MM AA)</label>
											<input type="text" id="mes" name="mes" class="input-label-placeholder expiration" maxlength="2" data-openpay-card="expiration_month">
											<input type="text" id="anio" name="anio" class="input-label-placeholder expiration" maxlength="2" data-openpay-card="expiration_year">
										</div>

										<div class="label-placeholder">
											<label>Código de seguridad (XXX)</label>
											<input type="text" id="codigo" name="codigo" class="input-label-placeholder" maxlength="3" data-openpay-card="cvv2">
										</div>
									</div>
									<!--
									<div class="km-msje-minimal">
										*Recuerda que tus datos deben ser los mismos que el de tu tarjeta
									</div>
									-->
								</div>

								<div id="tienda_box" class="metodos_container">
									<img src="'.get_template_directory_uri().'/images/tiendas.png" />
									<img src="'.get_template_directory_uri().'/images/pasos.png" />
								</div>

								<div class="km-term-conditions">
									<label>
										<input type="checkbox" name="term-conditions" value="1">
										Acepto los términos y condiciones
									</label>
								</div>
							</div>
						</div>

						<a id="reserva_btn_next_3" href="#" class="km-end-btn-form vlz_btn_reservar">
							TERMINAR RESERVA
						</a>

					</div>
				</div>

				<div class="km-col-empty">
					<br><br><br><br><br><br><br><br>
					<img src="'.getTema().'/images/new/bg-cachorro.png" style="max-width: 100%;">
				</div>
			</form>

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