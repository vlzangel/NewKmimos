<?php 
    /* Template Name: Woocommerce */

    // error_reporting(0);

    include __DIR__.'/funciones.php';

	date_default_timezone_set('America/Mexico_City');

    if( !isset($_SESSION) ){ session_start(); }

	$servicio_id = vlz_get_page();

	global $wpdb;

	/* Cuidador */
		$author = $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = ".$servicio_id);
		$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = ".$author);
		$cuidador_name = $cuidador->titulo;

		// if( $cuidador->activo == 0 ){ header("location: ".get_home_url()); }

	/* Servicio */
		$post = get_post( $servicio_id );
		$tipo = get_tipo($servicio_id);
		$cupos = get_cupos($servicio_id);

		$servicio_name_corto = explode(" - ", $post->post_title);
		$servicio_name = $servicio_name_corto[1];
		$servicio_name_corto = $servicio_name_corto[0];
		$atributos = unserialize($cuidador->atributos);

	/* Cliente */
		$USER_ID = get_current_user_id();

		$tieneGatos = tieneGatos();
		$tienePerros = tienePerros();

		$data_cliente = get_filtros_user($USER_ID);
		$filtros = $data_cliente[0];
		$mascotas = $data_cliente[1];

		COMPROBAR_ERRORES();

		$email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$USER_ID}");
		$saldo = getSaldo();
		$saldoTXT = $saldo["cupon"];

		$fee_conocer = get_cupos_conocer($USER_ID);
		if( $fee_conocer == 3 ){
			$fee_conocer = 0;
		}else{
			$fee_conocer = 30;
		}

	/* Generales */
		$busqueda = getBusqueda();

		$hoy = date("d/m/Y");
		$manana = date("d/m/Y", strtotime("+1 day") );

		$NOW = (strtotime("now"));
		if( isset($_GET["hora"]) ){
			$NOW = ( strtotime( date("Y-m-d")." ".$_GET["hora"].":00:00") );
		}
		$hora = date("G", $NOW);

		if( $busqueda["checkin"] == "" ){
			$busqueda["checkin"] = $hoy;
			$busqueda["checkout"] = $manana;
		}


	/* Recursos */

		get_header();

	    wp_enqueue_style('producto', getTema()."/css/producto.css", array(), '1.0.0');
		wp_enqueue_style('producto_responsive', getTema()."/css/responsive/producto_responsive.css", array(), '1.0.0');

		wp_enqueue_script('producto', getTema()."/js/producto.js", array("jquery"), '1.0.0');

		wp_enqueue_script('openpay-v1', getTema()."/js/openpay.v1.min.js", array("jquery"), '1.0.0');
		wp_enqueue_script('openpay-data', getTema()."/js/openpay-data.v1.min.js", array("jquery", "openpay-v1"), '1.0.0');

		wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );

        wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
        wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

	    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');

	    $precios = "";
	    
		$adicionales = unserialize($cuidador->adicionales);
		$precargas = array();
		$id_seccion = 'MR_'.get_the_ID()."_".md5($USER_ID);
        if( isset($_SESSION[$id_seccion] ) ){
        	$cupos_menos = $_SESSION[$id_seccion]["variaciones"]["cupos"];
        	$ini = strtotime( $_SESSION[$id_seccion]["fechas"]["inicio"] );
        	$fin = strtotime( $_SESSION[$id_seccion]["fechas"]["fin"] );
        	foreach ($cupos as $value) {
        		$xfecha = strtotime( $value->fecha );
        		if( $ini >= $xfecha && $xfecha <= $fin ){
        			$value->cupos -= $cupos_menos;
        			$value->full = 0;
        			$value->no_disponible = 0;
        		}
        	}
            $HTML .= "
                <a href='".getTema()."/procesos/perfil/update_reserva.php?b=".get_the_ID()."_".md5($USER_ID)."' class='theme_button btn_modificar'>
                    Salir de modificar reserva
                </a>
            ";
            $busqueda["checkin"] = date("d/m/Y", strtotime($_SESSION[$id_seccion]["fechas"]["inicio"]) );
            $busqueda["checkout"] = date("d/m/Y", strtotime($_SESSION[$id_seccion]["fechas"]["fin"]) );

            $precargas["tamanos"] = $_SESSION[$id_seccion]["variaciones"];
            if( isset($_SESSION[$id_seccion]["transporte"][0])){
            	$precargas["transp"] = $_SESSION[$id_seccion]["transporte"][0];
            }
            $precargas["adicionales"] = $_SESSION[$id_seccion]["adicionales"];
        }
	    if( $tipo == "hospedaje" ){
	    	$precios = getPrecios( unserialize($cuidador->hospedaje), $precargas["tamanos"], unserialize($cuidador->tamanos_aceptados) );
	    }else{
	    	$precios = getPrecios( $adicionales[ $tipo ], $precargas["tamanos"], unserialize($cuidador->tamanos_aceptados) );
	    } 
		$transporte = getTransporte($adicionales, $precargas["transp"]);
		if( $transporte != "" ){
			$transporte = '
				<div class="contenedor_info">
					<label>Transportación</label>
					<div>
						<div class="km-services">
							<select id="transporte" name="transporte" class="km-input-custom"><option value="">SELECCIONE UNA OPCI&Oacute;N</option>'.$transporte.'</select>
						</div>
					</div>
				</div>
			';
		}
		$adicionales = getAdicionales($adicionales, $precargas["adicionales"]);
		if( $adicionales != "" ){
			$adicionales = '
				<div class="contenedor_info" style="width: 100%;">
					<label>Servicios adicionales</label>
					<div>
						<div id="adicionales" class="km-services">
							'.$adicionales.'
						</div>
					</div>
				</div>
			';
		}

		$servicios_extras = '';
		if( $transporte != "" || $adicionales != "" ){
			$servicios_extras = '
				<div class="contenedor-adicionales">
					'.$transporte.'
					'.$adicionales.'
				</div>
			';
		}

		$bloquear_adicionales = false;
		$infoGatos = '';
		include __DIR__.'/NEW/mensajes_reserva.php';

		$paquetes = [
			"1 semena",
			"1 mes",
			"2 meses",
			"3 meses"
		];

		$bloq_checkout = '';
		if( $tipo == "paseos" && $busqueda["paquete"] != "" ){
			$PAQUETE = "var PAQUETE = '".$busqueda["paquete"]."';";
			$bloq_checkout = 'disabled';
		}else{
			$PAQUETE = "var PAQUETE = '';";
		}

		include( dirname(__FILE__)."/procesos/funciones/config.php" );

		$super_admin = (  $_SESSION['admin_sub_login'] != 'YES' ) ? 'No': 'Si';

		$_SESSION["flash_".$cuidador->id_post] = $ES_FLASH;

		$HTML .= "
		<script> 
			var fee_conocer = '".$fee_conocer."';
			
			var SERVICIO_ID = '".get_the_ID()."';
			var cupos = eval('".json_encode($cupos)."');
			var tipo_servicio = '".$tipo."'; 
			var name_servicio = '".$servicio_name."'; 
			var cliente = '".$USER_ID."'; 
			var cuidador = '".$cuidador->id_post."'; 
			var email = '".$email."'; 
			var saldo = '".$saldoTXT."';
			var acepta = '".$cuidador->mascotas_permitidas."';
			var OPENPAY_TOKEN = '".$MERCHANT_ID."';
			var OPENPAY_PK = '".$OPENPAY_KEY_PUBLIC."';
			var OPENPAY_PRUEBAS = ".$OPENPAY_PRUEBAS.";
			var FLASH = '".$ES_FLASH."';
			var HOY = '".$hoy."';
			var MANANA = '".$manana."';
			var HORA = '".(date("G", $NOW )+0)."';
			var SUPERU = '".$super_admin."';
			var BLOQUEAR_ADICIONALES = ".( ($bloquear_adicionales) ? 1 : 0 ).";
			".$PAQUETE."
		</script>";

		$descripcion = $wpdb->get_var("SELECT post_excerpt FROM wp_posts WHERE ID = {$servicio_id}");

		preg_match_all("#-(.*?)\n#i", "-".$descripcion, $matches_1);
		preg_match_all("#<small>(.*?)</small>#", $descripcion, $matches_2);
		$descripcion_1 = $matches_1[1][0];
		$descripcion_2 = 'Precio final (incluye cobertura veterinaria y gastos administrativos; no incluye servicios adicionales)';
		if( $descripcion_1 != "" ){
			$descripcion_1 = "* ".$descripcion_1;
		}

		$_adicionales = '<div id="contenedor-adicionales" class="contenedor-adicionales">'.$adicionales.'</div>';
		if( $bloquear_adicionales ){
			$_adicionales = '<div style="display: none;" id="contenedor-adicionales" class="contenedor-adicionales">'.$adicionales.'</div>';
		}

		$dias_str = '';
		if( $tipo == "paseos" && is_array($_SESSION['busqueda']['dias']) ){
		    $dias = [
		    	"lunes" => "Lunes",
		    	"martes" => "Martes",
		    	"miercoles" => "Miercoles",
		    	"jueves" => "Jueves",
		    	"viernes" => "Viernes",
		    	"sabado" => "Sábado",
		    	"domingo" => "Domingo"
		    ];
		    foreach ($dias as $key => $value) {
		    	$letra = substr( $value, 0, 1);
		    	$checked = ( in_array($key, $_SESSION['busqueda']['dias']) ) ? "checked": "";
		    	$dias_str .= 
		    	'	<label class="input_check_box" title="'.$value.'" for="'.$key.'">'.
				'		<input type="checkbox" id="'.$key.'" name="dias[]" value="'.$key.'" '.$checked.' />'.
				'		<span>'.$letra.'</span>'.
				'		<div class="top_check"></div>'.
				'	</label>'
		    	;
		    }
		    $dias_str = '<div class="dias_container">'.$dias_str.'</div>';
		}	

		$bloq_checkout_str = '';
		if( $bloq_checkout != "" && $busqueda["paquete"] != "" ){
			$bloq_checkout_str = '
				<div class="contenedor_info_extra">
					<div style="margin-bottom: 15px; font-size: 15px;" class="msg_bloqueador_no_valido">
						Estimado usuario la <strong>fecha final</strong> se estableció de manera automática para coincidir con
						el tiempo del paquete seleccinado de <strong>'.$paquetes[ $busqueda["paquete"]-1 ].'</strong>.
					</div>
				</div>
			';
		}

		$precios = $bloq_checkout_str.'
			<div class="km-dates-step" style="margin-bottom: 5px;">
				<div class="km-ficha-fechas">
					<input type="text" id="checkin" name="checkin" placeholder="DESDE" value="'.$busqueda["checkin"].'" class="date_from" readonly />
					<img class="flecha_fecha" src="'.get_recurso("img").'/HOME/SVG/Flecha.svg">
					<input style="margin-right: 0px;" type="text" id="checkout" name="checkout" placeholder="HASTA" value="'.$busqueda["checkout"].'" readonly '.$bloq_checkout.' />
				</div>
			</div>

			<div class="contenedor_info_extra">
				'.$dias_str.'
				'.$msg_mismo_dia.'
				'.$msg_bloqueador.'
				'.$msg_bloqueador_madrugada.'
				'.$infoGatos.'
			</div>

			<div id="bloque_info_servicio" class="km-content-step '.$bloquear.' '.$bloquear_madrugada.'">
				<div class="km-content-new-pet">
					<div class="contenedor_info">
						<label>Selecciona la cantidad de mascotas</label>
						<div>'.$precios.'</div>
					</div>

					'.$servicios_extras.'

					<div class="items_reservados_paso_1_container">
						<label class="items_reservados_paso_1_titulo">DETALLE</label>
						<div class="items_reservados_paso_1 items_reservados"></div>
					</div>

					<div class="km-services-total km-total-calculo">
						<div class="invalido"></div>
						<div class="valido">
							<div class="barra_inferior_container">
								<div style="padding-right: 50px;">
									<span class="km-text-total">TOTAL</span>
									<span class="km-price-total">$0.00</span>
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
		if( $msg_bloqueador_no_valido != "" ){
			$precios = $msg_bloqueador_no_valido;
		}

/*		if( $_SESSION["wlabel"] == "petco" ){
			$HTML .= "
				<script type='text/javascript'>
				    window._adftrack.push({
				        pm: 1453019,
				        divider: encodeURIComponent('|'),
				        pagename: encodeURIComponent('MX_Kmimos_Reservar_180907')
				    });
				</script>
				<noscript>
				    <p style='margin:0;padding:0;border:0;'>
				        <img src='https://a2.adform.net/Serving/TrackPoint/?pm=1453019&ADFPageName=MX_Kmimos_Reservar_180907&ADFdivider=|' width='1' height='1' alt='' />
				    </p>
				</noscript>
			";
		}*/

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
								RESERVACIÓN <span>'.$servicio_name_corto.'</span>
								<div>'.$descripcion_1.'</div>
							</div>
							<label for="mostrar_info" class="km-info-box">
								<input type="checkbox" id="mostrar_info" />
								<i class="fa fa-info km-info"></i>
								<div>'.$descripcion_2.'</div>
								<a href="'.get_home_url().'/petsitters/'.$post->post_author.'">Cambiar</a>
							</label>
						</div>

						<div class="km-sub-title-step">
							Reserva las fechas y los servicios con tu cuidador(a) '.$cuidador_name.'
						</div>
						'.$precios.'
					</div>
				</div>';

		$HTML .= '
				<div id="step_2" class="km-col-steps">
					<div class="km-col-content">
						<div id="atras_1" class="atras"> <span class="vlv_1">Volver</span> <span class="vlv_2"> < </span> </div>
						<div class="barra_titulo">
							<ul class="steps-numbers">
								<li><span class="number checked">1</span></li>
								<li class="line"></li><li><span class="number active">2</span></li>
								<li class="line"></li><li><span class="number">3</span></li>
							</ul>
							<div class="km-title-step">
								RESUMEN DE TU RESERVA
								<div>Queremos confirmar tu reservación y tu método de pago</div>
							</div>
						</div>

						<div class="cuidador_select">
							<strong>Cuidador seleccionado:</strong> <span>'.$cuidador_name.'</span>
						</div>

						<div class="fechas_select">
							<span class="value-resume">
								<img class="" src="'.get_recurso("img").'/HOME/SVG/Fecha.svg" align="center">
								<span class="fecha_ini"></span>
								<img class="" src="'.get_recurso("img").'/HOME/SVG/Flecha.svg" align="center">
								&nbsp;  &nbsp;
								<span class="fecha_fin"></span>
							</span>
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
							<div class="km-cupones">
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
						<div id="atras_2" class="atras"> <span class="vlv_1">Volver</span> <span class="vlv_2"> < </span> </div>
						<div class="barra_titulo">
							<ul class="steps-numbers">
								<li><span class="number checked">1</span></li>
								<li class="line"></li><li><span class="number checked">2</span></li>
								<li class="line"></li><li><span class="number active">3</span></li>
							</ul>
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
								<span class="fecha_ini"></span>
								<img class="" src="'.get_recurso("img").'/HOME/SVG/Flecha.svg" align="center">
								&nbsp;  &nbsp;
								<span class="fecha_fin"></span>
							</span>
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

										<div class="km-method-paid-options km-medio-paid-options">

											<div onclick="evento_google_kmimos(\'tienda\'); evento_fbq_kmimos(\'tienda\');" class="km-method-paid-option km-tienda km-option-3-lineas active">
												<div class="km-text-one">
													PAGO EN TIENDA DE CONVENIENCIA
												</div>
											</div>

											<div onclick="evento_google_kmimos(\'tarjeta\'); evento_fbq_kmimos(\'tarjeta\');" class="km-method-paid-option km-tarjeta km-option-3-lineas ">
												<div class="km-text-one">
													<div class="km-text-one">								
														<span>PAGO CON </span>TARJETA DE CRÉDITO O DÉBITO
													</div>
												</div>

											</div>

										</div>
									</div>

									<select id="tipo_pago" style="display: none;">
										<option value="tienda">PAGO EN TIENDA DE CONVENIENCIA</option>
										<option value="tarjeta">PAGO CON TARJETA DE CRÉDITO O DÉBITO</option>
									</select>

									<div class="errores_box">
										Datos de la tarjeta invalidos
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

		echo comprimir($HTML);

		echo "<pre>";
			print_r($_SESSION["sql"]);
		echo "</pre>";

		unset($_SESSION["pagando"]);

    get_footer(); 
?>

				