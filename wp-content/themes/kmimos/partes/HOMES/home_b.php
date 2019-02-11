<?php
	
    $HTML .= '
	<div id="banner_home">
		<div>
			<div class="solo_pc">
				<span class="banner_txt_1">la red más segura de cuidadores certificados de México</span>
				<span id="buscar" class="banner_txt_2">¡Tu mejor amigo regresa feliz!</span>
			</div>
			<div class="solo_movil banner_home"></div>
			<form id="buscador" method="POST" action="'.getTema().'/procesos/busqueda/buscar.php" >

				<input type="hidden" name="personalizada" value="1" />

				<input type="hidden" name="redireccionar" value="1" />
				<input type="hidden" name="USER_ID" value="'.$user_id.'" />
				'.$personalizada.'

				<input type="hidden" id="latitud" name="latitud" />
				<input type="hidden" id="longitud" name="longitud" />

				<div class="solo_movil" style="padding: 0px 10px;">
					'.$btn_registro.'
					<span class="banner_txt_1">Kmimos es la red más segura de cuidadores certificados de México</span>
					<span class="banner_txt_2" id="buscar">Nuestra promesa: ¡Tu mejor amigo regresa feliz!</span>
					<span class="banner_txt_3">¿Qué estas buscando para tu mascota?</span>
				</div>

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

				<div class="controles_mitad_container">

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

					<div class="tipo_mascota_container">
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
					<div class="fechas_container">
						<div id="desde_container">
							<img class="icon_fecha" src="'.get_recurso("img").'HOME/SVG/Fecha.svg" />
							<input type="text" id="checkin" name="checkin" placeholder="Desde" class="date_from" readonly>
							<img class="icon_flecha_fecha" src="'.get_recurso("img").'HOME/SVG/Flecha.svg" />
							<small class="">Requerido</small>
						</div>
						<div>
							<img class="icon_fecha" src="'.get_recurso("img").'HOME/SVG/Fecha.svg" />
							<input type="text" id="checkout" name="checkout" placeholder="Hasta" class="date_to" readonly>
							<small class="">Requerido</small>
						</div>
					</div>
				</div>

				<div class="tamanios_container">
					<label class="input_check_box" for="pequenos">
						<input type="checkbox" id="pequenos" name="tamanos[]" value="pequenos"  />
						<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Pequenio.svg" />
						<span>
							<div class="tam_label_pc">Pequeño</div>
							<div class="tam_label_movil">Peq.</div>
							<small>0 a 25 cm</small>
						</span>
						<div class="top_check"></div>
					</label>
					<label class="input_check_box" for="mediano">
						<input type="checkbox" id="mediano" name="tamanos[]" value="medianos"  />
						<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Mediano.svg" />
						<span>
							<div class="tam_label_pc">Mediano</div>
							<div class="tam_label_movil">Med.</div>
							<small>25 a 58 cm</small>
						</span>
						<div class="top_check"></div>
					</label>

					<label class="input_check_box" for="grande">
						<input type="checkbox" id="grande" name="tamanos[]" value="grandes"  />
						<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Grande.svg" />
						<span>
							<div class="tam_label_pc">Grande</div>
							<div class="tam_label_movil">Gde</div>
							<small>58 a 73 cm</small>
						</span>
						<div class="top_check"></div>
					</label>

					<label class="input_check_box" for="gigante" style="margin-right: 0px;">
						<input type="checkbox" id="gigante" name="tamanos[]" value="gigantes"  />
						<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Gigante.svg" />
						<span>
							<div class="tam_label_pc">Gigante</div>
							<div class="tam_label_movil">Gte.</div>
							<small>73 a 200 cm</small>
						</span>
						<div class="top_check"></div>
					</label>
				</div>

				<!-- BEGIN MODAL SERVICIOS ADICIONALES -->
				<div id="popup-servicios-new" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4><b>Tu consentido merece lo mejor, mira todo lo que le ofrecemos</b></h4>
							<div class="km-servicios-adicionales">
								<div class="row">
									<div class="col-xs-12 col-sm-3">
										<label for="corte" class="km-opcion">
											<input type="checkbox" name="servicios[]" value="corte" id="corte" >
											<span></span>
											<img src="'.get_recurso("img").'HOME/SVG/Adicionales/corte.svg">
											<div class="km-opcion-text">
												CORTE DE PELO<br> Y UÑAS
											</div>
										</label>
									</div>
									<div class="col-xs-12 col-sm-3">
										<label for="bano" class="km-opcion">
											<input type="checkbox" name="servicios[]" value="bano" id="bano" >
											<span></span>
											<img src="'.get_recurso("img").'HOME/SVG/Adicionales/bano.svg">
											<div class="km-opcion-text">
												BAÑO Y SECADO
											</div>
										</label>
									</div>
									<div class="col-xs-12 col-sm-3">
										<label for="limpieza_dental" class="km-opcion">
											<input type="checkbox" name="servicios[]" value="limpieza_dental" id="limpieza_dental" >
											<span></span>
											<img src="'.get_recurso("img").'HOME/SVG/Adicionales/limpieza_dental.svg">
											<div class="km-opcion-text">
												LIMPIEZA DENTAL
											</div>
										</label>
									</div>
									<div class="col-xs-12 col-sm-3">
										<label for="visita_al_veterinario" class="km-opcion">
											<input type="checkbox" name="servicios[]" value="visita_al_veterinario" id="visita_al_veterinario" >
											<span></span>
											<img src="'.get_recurso("img").'HOME/SVG/Adicionales/visita_al_veterinario.svg">
											<div class="km-opcion-text">
												VISITA AL<br> VETERINARIO
											</div>
										</label>
									</div>
								</div>
								<div class="row mtb-10">
									<div class="col-xs-12 col-sm-3">
										<label for="acupuntura" class="km-opcion">
											<input type="checkbox" name="servicios[]" value="acupuntura" id="acupuntura" >
											<span></span>
											<img src="'.get_recurso("img").'HOME/SVG/Adicionales/acupuntura.svg">
											<div class="km-opcion-text">
												ACUPUNTURA
											</div>
										</label>
									</div>
									<div class="col-xs-12 col-sm-3">
										<label for="transportacion_sencilla" class="km-opcion">
											<input type="checkbox" name="servicios[]" value="transportacion_sencilla" id="transportacion_sencilla" >
											<span></span>
											<img src="'.get_recurso("img").'HOME/SVG/Adicionales/transportacion_sencilla.svg">
											<div class="km-opcion-text">
												TRANSPORTE<br> SENCILLO
											</div>
										</label>
									</div>
									<div class="col-xs-12 col-sm-3">
										<label for="transportacion_redonda" class="km-opcion">
											<input type="checkbox" name="servicios[]" value="transportacion_redonda" id="transportacion_redonda" >
											<span></span>
											<img src="'.get_recurso("img").'HOME/SVG/Adicionales/transportacion_redonda.svg">
											<div class="km-opcion-text">
												TRANSPORTE<br> REDONDO
											</div>
										</label>
									</div>
									<div class="col-xs-12 col-sm-3">
										<a id="agregar_servicios" href="javascript:;" class="boton_buscar boton_verde">AGREGAR SERVICIO</a>
									</div>
								</div>
							</div>
							<a href="javascript:;" id="buscar_no" class="km-link" style="color: black; display:block; margin-top: 15px;">NO DESEO POR AHORA, GRACIAS</a>
						</div>
					</div>
				</div>
				<!-- END MODAL SERVICIOS ADICIONALES -->

				<div class="boton_buscar_container">
					<input type="button" id="boton_buscar" class="boton_buscar boton_verde" value="Buscar cuidador">
				</div>

			</form>

		</div>	
	</div>';

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
    		<h2>Conoce a los mejores <span>cuidadores kmimos</span></h2>
    		<div class="destacados_container">
    			<div class="destacados_box" data-paso="0" data-final_pc="'.($final_pc).'" data-final_movil="'.($final_movil).'" data-h_pc="33.33333334" data-h_movil="100">
	    			<div><div>'.$desta_str.'</div></div>
    				<img class="seccion_destacados_flechas seccion_destacados_izq" src="'.get_recurso('img').'HOME/SVG/WLABEL/boton_anterior.svg" />
    				<img class="seccion_destacados_flechas seccion_destacados_der" src="'.get_recurso('img').'HOME/SVG/WLABEL/boton_siguiente.svg" />
    			</div>
    		</div>
    	</div>';
	}

	if( time() > strtotime("2018-11-16 00:00:00") && $cuidadores_destacados == '' ){
		$HTML .= '
			<a 
				onclick="evento_google_kmimos(\'banner\'); evento_fbq_kmimos(\'banner\');" 
				target="_blanck" href="'.get_home_url().'/redireccion/?utm_source=homepage&utm_medium=banner&utm_campaign=nomadas_kmimos&url=https://www.nomadas.life/?publicmap=kmimos" style="display: block;">
				<img src="'.get_recurso("img").'BANNERS/banner_rotativo/pc/4.jpg" width="100%" class="solo_pc" />
				<img src="'.get_recurso("img").'BANNERS/banner_rotativo/movil/4.jpg" width="100%" class="solo_movil" />
			</a>
		';
	}

	$HTML .= $cuidadores_destacados;

	$HTML .= '
	<!-- PASOS PARA RESERVAR -->
	<div class="pasos_reserva_container">
		<h2>Tu mascota será parte de una verdadera familia mientras se queda</h2>

		<h2 class="solo_movil">Reserva en 3 simples pasos</h2>

		<div class="pasos_reserva_tabla">
			<div class="pasos_reserva_row pasos_reserva_numeros">
				<div class="pasos_reserva_celda">
					<span id="paso_1">1</span>
				</div>
				<div class="pasos_reserva_celda">
					<span id="paso_2">2</span>
				</div>
				<div class="pasos_reserva_celda">
					<span id="paso_3">3</span>
				</div>
			</div>
			<div class="pasos_reserva_row">
				<div class="pasos_reserva_celda">
					<div class="pasos_celda_top">
						<img src="'.get_recurso("img").'HOME/SVG/Paso_1.svg" />
					</div>
					<div class="pasos_celda_bottom">
						<h3>Haz tu búsqueda</h3>
						<p>Consigue cuidadores cerca de ti, con las características que necesites</p>
					</div>
				</div>
				<div class="pasos_reserva_celda">
					<div class="pasos_celda_top">
						<img src="'.get_recurso("img").'HOME/SVG/Paso_2.svg" />
					</div>
					<div class="pasos_celda_bottom">
						<h3>Agenda y haz el pago</h3>
						<p>Paga con tarjeta de débito, crédito o efectivo en tienda de conveniencia</p>
					</div>
				</div>
				<div class="pasos_reserva_celda">
					<div class="pasos_celda_top">
						<img src="'.get_recurso("img").'HOME/SVG/Paso_3.svg" />
					</div>
					<div class="pasos_celda_bottom">
						<h3>Tu mascota vuelve feliz</h3>
						<p>¡Despreocúpate! Tu mejor amigo volverá feliz, esa es la garantía Kmimos</p>
					</div>
				</div>
			</div>
		</div>

	</div>';

	$HTML .= '
	<!-- BENEFICIOS -->

	<div class="beneficios_container">
		
		<div class="beneficios_buscar_top">
			Más de <strong>1,000 Cuidadores Certificados y 60,000 noches reservadas.</strong> Tu consentido se queda en el hogar de una <strong>VERDADERA FAMILIA,</strong> con cobertura veterinaría
		</div>

		'.$info_registro.'
		
		<h2>Conoce los beneficios de dejar tu mascota con cuidadores certificados</h2>
		<img class="beneficios_banner_movil" src="'.get_recurso("img").'HOME/RESPONSIVE/PNG/Beneficios-de-dejar---.png" />

		<div class="beneficios_detalles">

			<div class="beneficios_detalles_tabla">
				<div class="beneficios_detalles_col_left">
					
					<div class="beneficios_detalles_item">
						<div class="beneficios_detalles_icon">
							<img src="'.get_recurso("img").'HOME/SVG/Km_Certificado.svg" />
						</div>
						<div class="beneficios_detalles_info">
							<h3>Cuidadores Certificados</h3>
							<p>
								Solo cuidadores que aprueban pruebas psicométricas, veterinarias y auditoría en casa
							</p>
						</div>
					</div>
					
					<div class="beneficios_detalles_item">
						<div class="beneficios_detalles_icon">
							<img src="'.get_recurso("img").'HOME/SVG/Km_Veterinario.svg" />
						</div>
						<div class="beneficios_detalles_info">
							<h3>Cobertura Veterinaría</h3>
							<p>
								Los protegemos con una cobertura en caso de malestares o incidentes
							</p>
						</div>
					</div>
					
					<div class="beneficios_detalles_item">
						<div class="beneficios_detalles_icon">
							<img src="'.get_recurso("img").'HOME/SVG/Km_Fotografia.svg" />
						</div>
						<div class="beneficios_detalles_info">
							<h3>Fotos y videos diarios</h3>
							<p>
								Para que siempre veas lo feliz que está tu peludo mientras no estás
							</p>
						</div>
					</div>

				</div>
				<div class="beneficios_detalles_col_right">
					
					<div class="beneficios_servicios_principales">
						<div class="beneficios_servicios_principales_titulo">
							Servicios ofrecidos por Kmimos
						</div>
						<ul class="beneficios_servicios_principales_lista">
							<li>
								<div>
									<img src="'.get_recurso("img").'HOME/SVG/Check.svg" align="left" />
								</div>
								<div>
									<strong>Hospedaje</strong>
									<p>Para cuando sales de viaje</p>
								</div>
							</li>
							<li>
								<div>
									<img src="'.get_recurso("img").'HOME/SVG/Check.svg" align="left" />
								</div>
								<div>
									<strong>Guardería</strong>
									<p>Cuando vas a tu oficina, gimnasio, etc.</p>
								</div>
							</li>
							<li>
								<div>
									<img src="'.get_recurso("img").'HOME/SVG/Check.svg" align="left" />
								</div>
								<div>
									<strong>Paseos</strong>
									<p>Mejora su salud, socializa y elimina su ansiedad</p>
								</div>
							</li>
							<li>
								<div>
									<img src="'.get_recurso("img").'HOME/SVG/Check.svg" align="left" />
								</div>
								<div>
									<strong>Entrenamiento</strong>
									<p>Ayúdalo a corregir su comportamiento</p>
								</div>
							</li>
						</ul>
					</div>

				</div>
			</div>
		</div>

		<div class="beneficios_buscar_container">
			<div class="beneficios_buscar_left">
				Más de <strong>1,000 Cuidadores Certificados y 60,000 noches reservadas.</strong> Tu consentido se queda en el hogar de una <strong>VERDADERA FAMILIA,</strong> con cobertura veterinaría
			</div>
			<div class="beneficios_buscar_right">
				<div onclick="ancla_form()" class="boton boton_verde">Buscar cuidador</div>
			</div>
		</div>

	
	</div>';
	
	$HTML .= '
	<div class="testimonios_container">
		<div class="testimonios_item">
			<p>Por segunda vez dejé a mi perro con Gabriel y su familia, estoy muy agradecido y encantado con el cuidado que le ha dado a mi mascota. Durante toda la estadía me envió fotos de mi perrito feliz mientras yo viajaba.</p>
			<span>- Alejandra R.</span>
		</div>
		<div class="testimonios_img">
			<span>
				+1,500 comentarios positivos en perfiles de cuidadores
			</span>
		</div>
		<a href="'.get_home_url().'/testimonios" class="testimonios_link">Ver más comentarios como éste</a>
	</div>';
	
	$HTML .= '
	<!-- SECCIÓN 4 - CLUB PATITAS FELICES -->
	<div class="km-club-patitas" style="background-image: url('.getTema().'/images/club-patitas/Kmimos-Club-de-las-patitas-felices-3.png);">
		<div class="row">
			<header class="col-sm-12 col-xs-8 col-md-5 pull-right text-center">
				<img src="'.getTema().'/images/club-patitas/Kmimos-Club-de-las-patitas-felices-5.png">
				<h2>
					Club de las patitas felices
				</h2>
				<span class="gana_150">Gana $150</span>
				<p> cada véz que un amigo tuyo reserve con Kmimos </p>
			</header>
			<div class="saber_mas"> Saber más >> </div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-xs-12 col-md-5 pull-right text-center">
				<a class="btn btn-club-patitas" href="'.get_home_url().'/club-patitas-felices">Ingresa aquí</a>
			</div>
		</div>
	</div>
	<!-- FIN SECCIÓN 4 - CLUB PATITAS FELICES -->';

	$HTML .= '
	<!-- QUIERO SER CUIDADOR -->
	<div class="quiero_ser_cuidador_container">
		<div class="quiero_ser_cuidador_img"></div>
		<div class="quiero_ser_cuidador_info">
			<h2>Conviértete en cuidador certificado kmimos</h2>
			<div>
				<span>Kmimos necesita doglovers como tú</span>
				<a href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros" class="boton boton_verde">Empieza a cuidar</a>
			</div>
		</div>
	</div>
	<div class="quiero_ser_cuidador_container_2">
		<span>Kmimos necesita doglovers como tú</span>
		<a href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros" class="boton boton_border_gris">Empieza a cuidar</a>
	</div>';
	
?>