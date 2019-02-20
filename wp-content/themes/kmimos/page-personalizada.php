<?php 
    /*
        Template Name: Personalizada
    */

	date_default_timezone_set('America/Mexico_City');

    wp_enqueue_style('home_kmimos', get_recurso("css")."personalizada.css", array(), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/personalizada_2.css", array(), '1.0.0');

	wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );


    wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

    wp_enqueue_script('select_localidad', getTema()."/js/select_localidad.js", array(), '1.0.0');

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');
            
    get_header();

    $user_id = get_current_user_id();
    
    $HTML = '';
    $cuidadores_destacados = '';

	/* DESTACADOS */

	$cuidadores_destacados = '
	<div class="seccion_destacados">
		<h2>Bienvenido a los <span>filtros personalizados <img src="'.get_recurso('img').'PERSONALIZADA/PNG/logo-verde.png" /></span> </h2>
		<div class="seccion_destacados_subtitulo">
			Para facilitar tu búsqueda hemos seleccionado estos tres cuidadores para ti. Ajusta los <span>filtros mostrados abajo</span> para encontrar al Cuidador ideal para tu mascota. También puedes <a class="'.get_home_url().'/busqueda">omitir este paso y ver la lista completa de Cuidadores.</a>
		</div>
		<div class="destacados_container">
			<div class="destacados_box" data-paso="0" data-final_pc="0" data-final_movil="0">
    			<div><div></div></div>
			</div>
		</div>
		<div class="botones_movil">
			<a id="aplicar_btn" href="#" class="boton boton_verde">Ajustar filtros</a>
			<a href="'.get_home_url().'/busqueda" class="boton boton_verde">Omitir</a>
			<span>
				Al omitir verás a los más de 1,000 Cuidadores Certificados
			</span>
		</div>
	</div>
	';

	$tamanos = [];
	if( is_array($_SESSION['busqueda']["tamanos"]) ){
		foreach ($_SESSION['busqueda']["tamanos"] as $key => $value) {
			$tamanos[ $value] = 'checked';
		}
	}

	$servicios = [];
	if( is_array($_SESSION['busqueda']["servicios"]) ){
		foreach ($_SESSION['busqueda']["servicios"] as $key => $value) {
			$servicios[ $value ] = 'checked';
		}
	}

	$mascotas = [];
	if( is_array($_SESSION['busqueda']["mascotas"]) ){
		foreach ($_SESSION['busqueda']["mascotas"] as $key => $value) {
			$mascotas[ $value ] = 'checked';
		}
	}

	$mascotas_propias = ( $_SESSION["busqueda"]["mascotas_propias"] == 1 ) ? 'checked' : '';
	$con_transporte = ( $_SESSION["busqueda"]["con_transporte"] == 1 ) ? 'checked' : '';
	$areas_verdes = ( $_SESSION["busqueda"]["areas_verdes"] == 1 ) ? 'checked' : '';
	$es_agresiva = ( $_SESSION["busqueda"]["es_agresiva"] == 1 ) ? 'checked' : '';

    $HTML .= $cuidadores_destacados.'
    	<div id="banner_home">
			<div>
				<form id="buscador" method="POST" >

					<div class="cada_vez">
						<div>
							Modifica los <span>filtros personalizados</span> de acuerdo a tus preferencias.
						</div>
						IMPORTANTE: Cada vez que modifiques un filtro, los Cuidadores mostrados se actualizarán.
					</div>

					<input type="hidden" name="USER_ID" value="'.$user_id.'" />
					<input type="hidden" id="latitud" name="latitud" value="'.$_SESSION['busqueda']['latitud'].'" />
					<input type="hidden" id="longitud" name="longitud" value="'.$_SESSION['busqueda']['longitud'].'" />

					<table>
						<tr>
							<td class="celda_1">
								<label class="titulo_form">Ubicación<br>&nbsp;</label>
								<div class="ubicacion_container">
									<img class="ubicacion_localizacion" src="'.get_recurso("img").'BUSQUEDA/SVG/Localizacion_2.svg" />
									<input type="text" class="ubicacion_txt" name="ubicacion_txt" placeholder="Ubicación estado municipio" autocomplete="off" value="'.$_SESSION['busqueda']['ubicacion_txt'].'" />
									<input type="hidden" class="ubicacion" name="ubicacion" value="'.$_SESSION['busqueda']['ubicacion'].'" />	
								    <div class="cerrar_list_box">
								    	<div class="cerrar_list">X</div>
								    	<ul class="ubicacion_list"></ul>
								    </div>
									<i id="_mi_ubicacion" class="fa icon_left ubicacion_gps"></i>
									<img class="mi_ubicacion" src="'.get_recurso("img").'HOME/SVG/GPS_Off.svg" />
									<div class="barra_ubicacion"></div>
									<small class="hidden" data-error="ubicacion">Función disponible solo en México</small>
								</div>
							</td>
							<td class="celda_2">
								<label class="titulo_form">Características de tu<br>mascota</label>
								<div class="tipo_mascota_container">
									<div style="clear: both;"></div>
									<label class="input_check_box" for="perro">
										<input type="checkbox" id="perro" name="mascotas[]" value="perros" '.$mascotas[ 'perros' ].' />
										<img src="'.get_recurso("img").'HOME/SVG/Perro.svg" />
										<span>Perro</span>
										<div class="top_check"></div>
									</label>
									<label class="input_check_box" for="gato">
										<input type="checkbox" id="gato" name="mascotas[]" value="gatos" '.$mascotas[ 'gatos' ].' />
										<img src="'.get_recurso("img").'HOME/SVG/Gato.svg" />
										<span>Gato</span>
										<div class="top_check"></div>
									</label>

									<div class="barra_right"></div>
								</div>

								<div class="tamanios_container">
									<div style="clear: bold;"></div>
									<label class="input_check_box" for="pequenos">
										<input type="checkbox" id="pequenos" name="tamanos[]" value="pequenos" '.$tamanos[ 'pequenos' ].' />
										<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Pequenio.svg" />
										<span>
											<div class="tam_label_movil">Pequeño</div>
											<small>0 a 25 cm</small>
										</span>
										<div class="top_check"></div>
									</label>
									<label class="input_check_box" for="mediano">
										<input type="checkbox" id="mediano" name="tamanos[]" value="medianos" '.$tamanos[ 'medianos' ].' />
										<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Mediano.svg" />
										<span>
											<div class="tam_label_movil">Mediano</div>
											<small>25 a 58 cm</small>
										</span>
										<div class="top_check"></div>
									</label>

									<label class="input_check_box" for="grande">
										<input type="checkbox" id="grande" name="tamanos[]" value="grandes" '.$tamanos[ 'grandes' ].' />
										<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Grande.svg" />
										<span>
											<div class="tam_label_movil">Grande</div>
											<small>58 a 73 cm</small>
										</span>
										<div class="top_check"></div>
									</label>

									<label class="input_check_box" for="gigante" style="margin-right: 0px;">
										<input type="checkbox" id="gigante" name="tamanos[]" value="gigantes" '.$tamanos[ 'gigantes' ].' />
										<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Gigante.svg" />
										<span>
											<div class="tam_label_movil">Gigante</div>
											<small>73 a 200 cm</small>
										</span>
										<div class="top_check"></div>
									</label>

									<div class="barra_right"></div>
								</div>

							</td>
							<td class="celda_3">
								<label class="titulo_form">&nbsp;<br>Servicio requerido</label>
								<div id="servicios_principales_container">
									<div class="servicios_principales_container">
										<div class="servicios_principales_box"  style="position: relative;">
											<label class="input_check_box" for="hospedaje">
												<input type="checkbox" id="hospedaje" name="servicios[]" value="hospedaje" '.$servicios[ 'hospedaje' ].' />
												<img class="" src="'.get_recurso("img").'HOME/SVG/Hospedaje.svg" />
												<span>Hospedaje</span>
												<div class="top_check"></div>
											</label>

											<label class="input_check_box" for="guarderia" onclick="evento_google(\'guarderia\'); evento_fbq("track", "traking_code_boton_guarderia");">
												<input type="checkbox" id="guarderia" name="servicios[]" value="guarderia" '.$servicios[ 'guarderia' ].' />
												<img class="" src="'.get_recurso("img").'HOME/SVG/Guarderia.svg" />
												<span>Guardería</span>
												<div class="top_check"></div>
											</label>

											<label class="input_check_box" for="paseos" onclick="evento_google(\'paseos\'); evento_fbq("track", "traking_code_boton_paseos"); evento_google_2(\'paseos\'); evento_fbq_2("track", "traking_code_boton_paseos_kmimos"); ">
												<input type="checkbox" id="paseos" name="servicios[]" value="paseos" '.$servicios[ 'paseos' ].' />
												<img class="" src="'.get_recurso("img").'HOME/SVG/Paseos.svg" />
												<span>Paseos</span>
												<div class="top_check"></div>
											</label>

											<label class="input_check_box" for="adiestramiento" onclick="evento_google(\'entrenamiento\'); evento_fbq("track", "traking_code_boton_entrenamiento"); ">
												<input type="checkbox" id="adiestramiento" name="servicios[]" value="adiestramiento" '.$servicios[ 'adiestramiento' ].' />
												<img class="" src="'.get_recurso("img").'HOME/SVG/Entrenamiento.svg" />
												<span>Adiestramiento</span>
												<div class="top_check"></div>
											</label>
											<small class="error_principales" style="position: absolute; bottom: -13px; left: 6px; color: red; display: none;">
												Debe seleccionar al menos un servicio principal
											</small>
										</div>
									</div>
								</div>

							</td>
						</tr>

						<tr>
							<td class="personaliza_title">
								Personaliza tu búsqueda

								<div class="barra_right"></div>
							</td>
							<td class="celda_2">
								<div class="checkes_bottom">
									<div class="check_container">
										<label for="check_1">
											Cuidador con<br>
											mascotas propias
										</label>
										<span>
											<input type="checkbox" id="check_1" name="mascotas_propias" value="1" '.$mascotas_propias.' />
											<div class="check_icon"></div>
										</span>
									</div>
									<div class="check_container">
										<label for="check_2">
											Cuidador con<br>
											transporte
										</label>
										<span>
											<input type="checkbox" id="check_2" name="con_transporte" value="1" '.$con_transporte.' />
											<div class="check_icon"></div>
										</span>
									</div>
								</div>
								<div class="checkes_bottom">
									<div class="check_container">
										<label for="check_3">
											Propiedad con<br>
											áreas verdes
										</label>
										<span>
											<input type="checkbox" id="check_3" name="areas_verdes" value="1" '.$areas_verdes.' />
											<div class="check_icon"></div>
										</span>
									</div>
									<div class="check_container">
										<label for="check_4" style="padding-top: 10px; color: #e53340;">
											¿Tu mascota es Agresiva con<br>
											otras mascotas, personas, o<br>
											no es muy sociable?
										</label>
										<span>
											<input type="checkbox" id="check_4" name="es_agresiva" value="1" '.$es_agresiva.' />
											<div class="check_icon_2 check_no"></div>
											<div class="check_icon_2 check_si"></div>
										</span>
									</div>
								</div>
							</td>
							<td class="omitir_btn">
								<a id="btn_aplicar_filtros" href="#" class="solo_movil boton boton_verde">Personalizar mi búsqueda</a>
								<a href="'.get_home_url().'/busqueda" class="solo_pc boton boton_verde">Omitir filtros personalizados</a>
								<span class="solo_pc">
									Al darle click verás a los más de 1,000<br>
									Cuidadores Certificados
								</span>
							</td>
						</tr>

					</table>

				</form>

			</div>	
		</div>';

    echo comprimir($HTML);
    
    wp_enqueue_script('buscar_home', get_recurso("js")."personalizada.js", array(), '1.0.0');

    get_footer(); 
?>


