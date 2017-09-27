<?php 
    /*
        Template Name: Busqueda
    */

    wp_enqueue_style('beneficios_kmimos', getTema()."/css/busqueda.css", array(), '1.0.0');
	wp_enqueue_style('beneficios_responsive', getTema()."/css/responsive/busqueda_responsive.css", array(), '1.0.0');
	wp_enqueue_script('buscar_home', getTema()."/js/busqueda.js", array(), '1.0.0');

    get_header();
    if( !isset($_SESSION)){ session_start(); }
	if( isset($_SESSION['busqueda'])){ $_POST = unserialize($_SESSION['busqueda']); }

	$home = get_home_url();

	$pagina = vlz_get_page();
	$destacados = get_destacados();
	$total  = vlz_num_resultados();
	$paginacion = vlz_get_paginacion($total, $pagina);
	$resultados = $_SESSION['resultado_busqueda'];
	$favoritos = get_favoritos();
	//var_dump($favoritos);
	
	$pines = unserialize($_SESSION['pines_array']);
	$pines_v = array();
 	$t = count($pines);
	for($i = 0; $i < $t; $i++){
		$pines[$i]["ser"] = vlz_servicios($pines[$i]["adi"], true);
		$pines[$i]["rating"] = kmimos_petsitter_rating( $pines[$i]["post_id"], true );
		unset($pines[$i]["adi"]);
	}
 	
 	$TIPO_DISEÑO = "list";
	if( $total > 6 ){
		$TIPO_DISEÑO = "grid";
	}

	$CUIDADORES = "";
	if( $total > 0 ){
		for ($i=$paginacion["inicio"]; $i < $paginacion["fin"]; $i++) {
			$cuidador = $resultados[$i];
			$CUIDADORES .= get_ficha_cuidador($cuidador, $i, $favoritos, $TIPO_DISEÑO);
		}
	}else{
		$CUIDADORES .= "<h2 style='padding-right: 20px!important; font-size: 21px; text-align: justify; margin: 10px 0px;'>No tenemos resultados para esta búsqueda, si quieres intentarlo de nuevo pícale <a  style='color: #00b69d; font-weight: 600;' href='".get_home_url()."/'>aquí,</a> o aplica otro filtro de búsqueda.</h2>";
	}

	$xPINES = json_encode($pines);

	$busqueda = getBusqueda();

	if( $destacados != "" ){
		$destacados_str = '
		<div class="km-premium km-search-slider">
			<div style="height: 220px; overflow: hidden;">
				<div class="km-premium-slider">
					'.$destacados.'
				</div>
			</div>
		</div>';
	}

	if( $total > 6 ){
		$CUIDADORES_STR = '
		<div class="km-resultados-grid">
			'.$CUIDADORES.'
		</div>';
	}else{
		$CUIDADORES_STR = '
		<div class="km-resultados-lista">
			'.$CUIDADORES.'
		</div>';
	}

    $option_servicios_adicionales = '';
    $servicios_adicionales = servicios_adicionales();
    foreach ($servicios_adicionales as $opt_key => $opt_value) {
    	$check = (servicios_en_session($opt_key, $busqueda, 'servicios'))? 'checked' : '' ;
	    $option_servicios_adicionales .= '
		<li>
			<label>
				<input type="checkbox" name="servicios[]" value="'.$opt_key.'" '.$check.' >'.
					$opt_value['label']
			.'</label>
		</li>
	    ';
    }

    $check = '';
    $option_tipo_servicio = '';
    $tipo_servicio = get_tipo_servicios();
    foreach ($tipo_servicio as $opt_key => $opt_value) {
    	$check = (servicios_en_session($opt_key, $busqueda, 'servicios'))? 'checked' : '' ;
	    $option_tipo_servicio .= '
		<li> 
			<label>
				<input type="checkbox" name="servicios[]" value="'.$opt_key.'" '.$check.' >'.
				$opt_value['name'].
			'</label>
		</li>
	    ';
    }	

    $check = '';
    $option_tamanos_mascotas = '';
    $tamanos_mascotas = kmimos_get_sizes_of_pets();
    foreach ($tamanos_mascotas as $opt_key => $opt_value) {
    	$check = (servicios_en_session($opt_value['ID'], $busqueda, 'tamanos'))? 'checked' : '' ;
	    $option_tamanos_mascotas .= '
		<li> 
			<label>
				<input type="checkbox" name="tamanos[]" value="'.$opt_value['ID'].'" '.$check.' >'.
				$opt_value['name'].
			'</label>
		</li>
	    ';
    }


    $HTML = '
		<div class="header-search" style="background-image:url('.getTema().'/images/new/km-fondo-buscador.gif);">
			<div class="overlay"></div>
		</div>

		<div class="container contentenedor-buscador-todos">
			<div class="km-contentido-formulario-buscador">
				<form class="km-formulario-buscador" action="'.get_home_url().'/wp-content/themes/kmimos/procesos/busqueda/buscar.php" method="post">
					<div class="km-bloque-cajas">
						<div class="km-div-ubicacion">
							<div class="km-select-custom km-select-ubicacion" style="border-right: 0px; height: 47px;">
								<img src="'.getTema().'/images/new/icon/icon-gps.svg" class="icon_left" />
								<input type="text" id="ubicacion_txt" class="km-fechas" style="width: 100%;" name="ubicacion_txt" placeholder="UBICACI&Oacute;N, ESTADO, MUNICIPIO" value="'.$busqueda["ubicacion_txt"].'" autocomplete="off" readonly />
								<input type="hidden" id="ubicacion" name="ubicacion" value="'.$busqueda["ubicacion"].'" />
								<div id="ubicacion_list"></div>
							</div>
						</div>
						<div class="km-div-fechas">
							<input type="text" id="checkin" name="checkin" placeholder="DESDE" value="'.$busqueda["checkin"].'" class="km-input-custom km-input-date date_from" readonly>
							<input type="text" id="checkout" name="checkout" placeholder="HASTA" value="'.$busqueda["checkout"].'" class="km-input-custom km-input-date date_to" readonly>
						</div>
						<div class="km-div-enviar">
							<button type="submit" class="km-submit-custom" name="button">
								BUSCAR
							</button>
						</div>
					</div>

					<div class="km-div-filtro">
						<div class="km-titulo-filtro">
							FILTRAR BÚSQUEDA
						</div>
						<div class="km-cajas-filtro">
							
							<div class="form-group">
						    	<button class="btn km-select-custom-button" type="button">
						    		TIPO DE SERVICIO
						    	</button>
						    	<ul class="list-unstyled km-select-custom-list">
							    	'.$option_tipo_servicio.'
								</li>
							</div>

							<div class="form-group">
						    	<button class="btn km-select-custom-button" type="button">
						    		TAMAÑO DE MASCOTA
						    	</button>
						    	<ul class="list-unstyled km-select-custom-list">
								    '.$option_tamanos_mascotas.'
								</li>
							</div>

							<div class="form-group">
						    	<button class="btn km-select-custom-button" type="button">
						    		SERVICIOS ADICIONALES
						    	</button>
						    	<ul class="list-unstyled km-select-custom-list">
								    '.$option_servicios_adicionales.'
								</li>
							</div>

							<div class="km-caja-filtro">
								<input type="text" name="nombre" value="'.$busqueda["nombre"].'" placeholder="BUSCAR POR NOMBRE" class="km-input-custom">
							</div>
						</div>
					</div>
				</form>
			</div>
	    	<script>
	    		pines = eval(\''.$xPINES.'\'); 
	    	</script>
	    	<div class="km-caja-resultados">
					<div class="km-columna-izq">
						
						'.$destacados_str.'

						<div class="km-superior-resultados">
							<span class="km-texto-resultados">
								<b>Resultado de búsqueda</b> '.$total.' cuidadores disponibles
							</span>

							<div class="km-opciones-resultados">
								<!-- 
								<div class="km-vista-resultados">
									<a href="./km-resultado.html" class="view-list active">
										List
									</a>
									<a href="./km-resultado-grid.html" class="view-grid">
										Gris
									</a>
								</div> -->
								<div class="km-orden-resultados">
									
									<div class="btn-group">
									  <button type="button" class="km-select-custom dropdown-order km-cajas-filtro-dropdown dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									    ORDENAR POR</span>
									  </button>
									  <ul class="dropdown-menu">
										<li><a href="'.get_home_url().'/wp-content/themes/kmimos/procesos/busqueda/buscar.php?o=rating_desc">Valoración de mayor a menor</a></li>
										<li><a href="'.get_home_url().'/wp-content/themes/kmimos/procesos/busqueda/buscar.php?o=rating_asc">Valoración de menor a mayor</a></li>
										<li><a href="'.get_home_url().'/wp-content/themes/kmimos/procesos/busqueda/buscar.php?o=distance_asc">Distancia al cuidador de cerca a lejos</a></li>
										<li><a href="'.get_home_url().'/wp-content/themes/kmimos/procesos/busqueda/buscar.php?o=distance_desc">Distancia al cuidador de lejos a cerca</a></li>
										<li><a href="'.get_home_url().'/wp-content/themes/kmimos/procesos/busqueda/buscar.php?o=price_asc">Precio del Servicio de menor a mayor</a></li>
										<li><a href="'.get_home_url().'/wp-content/themes/kmimos/procesos/busqueda/buscar.php?o=price_desc">Precio del Servicio de mayor a menor</a></li>
										<li><a href="'.get_home_url().'/wp-content/themes/kmimos/procesos/busqueda/buscar.php?o=experience_asc">Experiencia de menos a más años</a></li>
										<li><a href="'.get_home_url().'/wp-content/themes/kmimos/procesos/busqueda/buscar.php?o=experience_desc">Experiencia de más a menos años</a></li>
									  </ul>
									</div>

								</div>
							</div>
						</div>

						'.$CUIDADORES_STR.'

						<div class="navigation">
							<ul>
								'.$paginacion["html"].'
							</ul>
							<div class="message-nav">
								'.($paginacion["inicio"]+1).' - '.$paginacion["fin"].' de '.$total.' Cuidadores Certificados
							</div>
						</div>
					</div>

					<div class="km-columna-der">
						<div class="km-titulo-mapa">
							UBICACIÓN DE RESULTADOS EN MAPA
						</div>
						<div id="mapa" class="km-mapa"></div>
					</div>
				</div>
			</div>
			<script type="text/javascript" src="'.getTema().'/js/markerclusterer.js"></script>
			<script type="text/javascript" src="'.getTema().'/js/oms.min.js"></script>

    ';
    //include ('partes/cuidador/conocelo.php');
	echo comprimir_styles($HTML);



    get_footer(); 
?>