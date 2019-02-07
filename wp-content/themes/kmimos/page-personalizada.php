<?php 
    /*
        Template Name: Personalizada
    */

	date_default_timezone_set('America/Mexico_City');

    wp_enqueue_style('home_kmimos', get_recurso("css")."personalizada.css", array(), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/personalizada.css", array(), '1.0.0');

	wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );


    wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

    wp_enqueue_script('select_localidad', getTema()."/js/select_localidad.js", array(), '1.0.0');
    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');
            
    get_header();

    $user_id = get_current_user_id();
    
    $HTML = '';
    $cuidadores_destacados = '';

	/* DESTACADOS */
	$destacados = get_destacados_home();
	if( is_array($destacados) && count($destacados) > 0 ){
		$items = count($destacados);
		$final_pc = $items-3;
		$final_movil = $items-1;
		$desta_str = '';
		foreach ($destacados as $key => $cuidador) {
			$desta_str .= 
				'<div class="destacados_item">'.
					'<div class="img_destacado" style="background-image: url('.$cuidador->img.');"></div>'.
					'<div class="datos_destacado_containder">'.
						'<div class="datos_top_destacado_containder">'.
							'<div class="avatar_destacado" style="background-image: url('.$cuidador->cliente.');"></div>'.
							'<div class="nombre_destacado">'.
								'<a href="'.$cuidador->link.'">'.$cuidador->nombre.'</a>'.
								'<span>'.$cuidador->experiencia.'</span>'.
							'</div>'.
							'<div class="ranking_destacado">'.$cuidador->ranking.'</div>'.
						'</div>'.
						'<div class="msg_destacado_containder">'.
							'"'.$cuidador->msg.'"'.
						'</div>'.
					'</div>'.
					'<a href="'.$cuidador->link.'" class="boton">Ver perfil</a>'.
				'</div>';
		}

    	$cuidadores_destacados = '
    	<div class="seccion_destacados">
    		<h2>Bienvenido a los <span>filtros personalizados</span> <img src="'.get_recurso('img').'PERSONALIZADA/PNG/logo-verde.png" style="width: 130px;" /></h2>
    		<div class="seccion_destacados_subtitulo">
    			Para facilitar tu búsqueda hemos seleccionado estos tres cuidadores para ti. Ajusta los <span>filtros de personalización</span> para encontrar al Cuidador deal para tu mascota. También puedes <a class="">omitir este paso y ver la lista completa de Cuidadores</a> 
    		</div>
    		<div class="destacados_container">
    			<div class="destacados_box" data-paso="0" data-final_pc="'.($final_pc).'" data-final_movil="'.($final_movil).'">
	    			<div><div>'.$desta_str.'</div></div>
    				<img class="seccion_destacados_flechas seccion_destacados_izq" src="'.get_recurso('img').'HOME/SVG/WLABEL/boton_anterior.svg" />
    				<img class="seccion_destacados_flechas seccion_destacados_der" src="'.get_recurso('img').'HOME/SVG/WLABEL/boton_siguiente.svg" />
    			</div>
    		</div>
    	</div>
    	';
	}

    $HTML .= $cuidadores_destacados.'
    	<div id="banner_home">
			<div>
				<form id="buscador" method="POST" action="'.getTema().'/procesos/busqueda/buscar.php" >

					<div class="cada_vez">
						Cada vez que modifiques un filtro, tu búsqueda será mucho más personalizada.
					</div>

					<input type="hidden" name="redireccionar" value="1" />
					<input type="hidden" name="USER_ID" value="'.$user_id.'" />

					<input type="hidden" id="latitud" name="latitud" />
					<input type="hidden" id="longitud" name="longitud" />

					<table>
						<tr>
							<td class="celda_1">
								<label class="titulo_form">Ubicación<br>&nbsp;</label>
								<div class="ubicacion_container">
									<img class="ubicacion_localizacion" src="'.get_recurso("img").'BUSQUEDA/SVG/Localizacion_2.svg" />
									<input type="text" class="ubicacion_txt" name="ubicacion_txt" placeholder="Ubicación estado municipio" autocomplete="off" />
									<input type="hidden" class="ubicacion" name="ubicacion" />	
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
								<div class="tipo_mascota_container">
									<label class="titulo_form">Características de tu mascota</label>
									<div style="clear: both;"></div>
									<label class="input_check_box" for="perro">
										<input type="checkbox" id="perro" name="mascotas[]" value="perros"  />
										<img src="'.get_recurso("img").'HOME/SVG/Perro.svg" />
										<span>Perro</span>
										<div class="top_check"></div>
									</label>
									<label class="input_check_box" for="gato">
										<input type="checkbox" id="gato" name="mascotas[]" value="gatos"  />
										<img src="'.get_recurso("img").'HOME/SVG/Gato.svg" />
										<span>Gato</span>
										<div class="top_check"></div>
									</label>
								</div>

								<div class="tamanios_container">
									<label class="titulo_form">&nbsp;<br>&nbsp;</label>
									<div style="clear: bold;"></div>
									<label class="input_check_box" for="pequenos">
										<input type="checkbox" id="pequenos" name="tamanos[]" value="pequenos"  />
										<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Pequenio.svg" />
										<span>
											<div class="tam_label_movil">Pequeño</div>
											<small>0 a 25 cm</small>
										</span>
										<div class="top_check"></div>
									</label>
									<label class="input_check_box" for="mediano">
										<input type="checkbox" id="mediano" name="tamanos[]" value="medianos"  />
										<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Mediano.svg" />
										<span>
											<div class="tam_label_movil">Mediano</div>
											<small>25 a 58 cm</small>
										</span>
										<div class="top_check"></div>
									</label>

									<label class="input_check_box" for="grande">
										<input type="checkbox" id="grande" name="tamanos[]" value="grandes"  />
										<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Grande.svg" />
										<span>
											<div class="tam_label_movil">Grande</div>
											<small>58 a 73 cm</small>
										</span>
										<div class="top_check"></div>
									</label>

									<label class="input_check_box" for="gigante" style="margin-right: 0px;">
										<input type="checkbox" id="gigante" name="tamanos[]" value="gigantes"  />
										<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Gigante.svg" />
										<span>
											<div class="tam_label_movil">Gigante.</div>
											<small>73 a 200 cm</small>
										</span>
										<div class="top_check"></div>
									</label>
								</div>

							</td>
							<td class="celda_3">
								<label class="titulo_form">&nbsp;<br>Servicio requerido</label>
								<div id="servicios_principales_container">
									<div class="servicios_principales_container">
										<div class="servicios_principales_box"  style="position: relative;">
											<label class="input_check_box" for="hospedaje">
												<input type="checkbox" id="hospedaje" name="servicios[]" value="hospedaje"  />
												<img class="solo_pc" src="'.get_recurso("img").'HOME/SVG/Hospedaje.svg" />
												<img class="solo_movil" src="'.get_recurso("img").'HOME/RESPONSIVE/PNG/Hospedaje.png" />
												<span>Hospedaje</span>
												<div class="top_check"></div>
											</label>

											<label class="input_check_box" for="guarderia" onclick="evento_google(\'guarderia\'); evento_fbq("track", "traking_code_boton_guarderia");">
												<input type="checkbox" id="guarderia" name="servicios[]" value="guarderia"  />
												<img class="solo_pc" src="'.get_recurso("img").'HOME/SVG/Guarderia.svg" />
												<img class="solo_movil" src="'.get_recurso("img").'HOME/RESPONSIVE/PNG/Guarderia.png" />
												<span>Guardería</span>
												<div class="top_check"></div>
											</label>

											<label class="input_check_box" for="paseos" onclick="evento_google(\'paseos\'); evento_fbq("track", "traking_code_boton_paseos"); evento_google_2(\'paseos\'); evento_fbq_2("track", "traking_code_boton_paseos_kmimos"); ">
												<input type="checkbox" id="paseos" name="servicios[]" value="paseos"  />
												<img class="solo_pc" src="'.get_recurso("img").'HOME/SVG/Paseos.svg" />
												<img class="solo_movil" src="'.get_recurso("img").'HOME/RESPONSIVE/PNG/Paseos.png" />
												<span>Paseos</span>
												<div class="top_check"></div>
											</label>

											<label class="input_check_box" for="adiestramiento" onclick="evento_google(\'entrenamiento\'); evento_fbq("track", "traking_code_boton_entrenamiento"); ">
												<input type="checkbox" id="adiestramiento" name="servicios[]" value="adiestramiento"  />
												<img class="solo_pc" src="'.get_recurso("img").'HOME/SVG/Entrenamiento.svg" />
												<img class="solo_movil" src="'.get_recurso("img").'HOME/RESPONSIVE/PNG/Entrenamiento.png" />
												<span>Adiestramiento</span>
												<div class="top_check"></div>
											</label>
											<small class="error_principales" style="position: absolute; bottom: -13px; left: 6px; color: red; display: none;">
												Debe seleccionar al menos un servicio principal
											</small>
										</div>
									</div>

									<img onclick="serviciosAnterior( jQuery(this) );" class="Flechas Flecha_Izquierda Ocultar_Flecha" src="'.get_recurso("img").'PERFIL_CUIDADOR/Flecha_2.svg" />
									<img onclick="serviciosSiguiente( jQuery(this) );" class="Flechas Flecha_Derecha '.$ocultar_siguiente_img.'" src="'.get_recurso("img").'PERFIL_CUIDADOR/Flecha_1.svg" />
								</div>

							</td>
						</tr>

						<tr>
							<td class="personaliza_title">
								Personaliza tu búsqueda
							</td>
							<td class="celda_2">
								<div class="checkes_bottom">
									<div class="check_container">
										<label for="check_1">
											Cuidador con<br>
											mascotas propias
										</label>
										<span>
											<input type="checkbox" id="check_1" />
											<div class="check_icon"></div>
										</span>
									</div>
									<div class="check_container">
										<label for="check_2">
											Cuidador con<br>
											transporte
										</label>
										<span>
											<input type="checkbox" id="check_2" />
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
											<input type="checkbox" id="check_3" />
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
											<input type="checkbox" id="check_4" />
											<div class="check_icon_2 check_no"></div>
											<div class="check_icon_2 check_si"></div>
										</span>
									</div>
								</div>
							</td>
							<td class="omitir_btn">
								<a href="'.get_home_url().'/busqueda" class="boton boton_verde">Omitir filtros personalizados</a>
								<span>
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
    
    wp_enqueue_script('buscar_home', get_recurso("js")."home.js", array(), '1.0.0');
    wp_enqueue_script('club_patitas', get_recurso("js")."club_patitas.js", array(), '1.0.0');

    get_footer(); 
?>


