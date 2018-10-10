<?php 
    /*
        Template Name: Home
    */

    wp_enqueue_style('home_kmimos', get_recurso("css")."home.css", array(), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/home.css", array(), '1.0.0');

	wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );


    wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

    wp_enqueue_script('select_localidad', getTema()."/js/select_localidad.js", array(), '1.0.0');
    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');
            
    get_header();

    $user_id = get_current_user_id();
        
    $HTML = '
    	<script>
    		var HOME = "'.getTema().'";
    	</script>
    	<div id="banner_home">
			<div>
				<div class="solo_pc">
					<span class="banner_txt_1">la red más segura de cuidadores certificados de México</span>
					<span class="banner_txt_2">¡Tu mejor amigo regresa feliz!</span>
				</div>
				<div class="solo_movil banner_home"></div>

				<form id="buscador" >

					<input type="hidden" name="redireccionar" value="1" />
					<input type="hidden" name="USER_ID" value="'.$user_id.'" />

					<input type="hidden" id="latitud" name="latitud" />
					<input type="hidden" id="longitud" name="longitud" />

					<div class="solo_movil">
						<div class="boton boton_border_morado">Regístrate</div>
						<span class="banner_txt_1">Kmimos es la red más segura de cuidadores certificados de México</span>
						<span class="banner_txt_2">Nuestra promesa: ¡Tu mejor amigo regresa feliz!</span>
						<span class="banner_txt_3">¿Qué estas buscando para tu mascota?</span>
					</div>
				
					<div class="servicios_principales_container">

						<div class="servicios_principales_box">

							<label class="input_check_box" for="hospedaje">
								<input type="checkbox" id="hospedaje" name="hospedaje"  />
								<img class="solo_pc" src="'.get_recurso("img").'SVG/Hospedaje.svg" />
								<img class="solo_movil" src="'.get_recurso("img").'RESPONSIVE/PNG/Hospedaje.png" />
								<span>Hospedaje</span>
								<div class="top_check"></div>
							</label>

							<label class="input_check_box" for="guarderia">
								<input type="checkbox" id="guarderia" name="guarderia"  />
								<img class="solo_pc" src="'.get_recurso("img").'SVG/Guarderia.svg" />
								<img class="solo_movil" src="'.get_recurso("img").'RESPONSIVE/PNG/Guarderia.png" />
								<span>Guardería</span>
								<div class="top_check"></div>
							</label>

							<label class="input_check_box" for="paseos">
								<input type="checkbox" id="paseos" name="paseos"  />
								<img class="solo_pc" src="'.get_recurso("img").'SVG/Paseos.svg" />
								<img class="solo_movil" src="'.get_recurso("img").'RESPONSIVE/PNG/Paseos.png" />
								<span>Paseos</span>
								<div class="top_check"></div>
							</label>

							<label class="input_check_box" for="entrenamiento">
								<input type="checkbox" id="entrenamiento" name="entrenamiento"  />
								<img class="solo_pc" src="'.get_recurso("img").'SVG/Entrenamiento.svg" />
								<img class="solo_movil" src="'.get_recurso("img").'RESPONSIVE/PNG/Entrenamiento.png" />
								<span>Entrenamiento</span>
								<div class="top_check"></div>
							</label>

						</div>
					</div>

					<div class="controles_mitad_container">

						<div class="ubicacion_container">
							<img class="ubicacion_localizacion" src="'.get_recurso("img").'SVG/Localizacion.svg" />
							<input type="text" id="ubicacion_txt" name="ubicacion_txt" placeholder="Ubicación estado municipio" autocomplete="off" />

							<input type="hidden" id="ubicacion" name="ubicacion" value="'.$busqueda["ubicacion"].'" />	
						    <div class="cerrar_list_box">
						    	<div class="cerrar_list">X</div>
						    	<ul id="ubicacion_list" class=""></ul>
						    </div>

							<img id="mi_ubicacion" class="ubicacion_gps" src="'.get_recurso("img").'SVG/GPS_Off.svg" />
							<small class="hidden" data-error="ubicacion">Función disponible solo en México</small>
						</div>

						<div class="tipo_mascota_container">
							<label class="input_check_box" for="perro">
								<input type="checkbox" id="perro" name="perro"  />
								<img src="'.get_recurso("img").'SVG/Perro.svg" />
								<span>Perro</span>
								<div class="top_check"></div>
							</label>
							<label class="input_check_box" for="gato">
								<input type="checkbox" id="gato" name="gato"  />
								<img src="'.get_recurso("img").'SVG/Gato.svg" />
								<span>Gato</span>
								<div class="top_check"></div>
							</label>
						</div>
						<div class="fechas_container">
							<div id="desde_container">
								<img class="icon_fecha" src="'.get_recurso("img").'SVG/Fecha.svg" />
								<input type="text" id="checkin" name="desde" placeholder="Desde" class="date_from" readonly>
							</div>
							<div>
								<img class="icon_fecha" src="'.get_recurso("img").'SVG/Fecha.svg" />
								<input type="text" id="checkout" name="hasta" placeholder="Hasta" class="date_to" readonly>
							</div>
						</div>
					</div>

					<div class="tamanios_container">
						<label class="input_check_box" for="paqueno">
							<input type="checkbox" id="paqueno" name="paqueno"  />
							<img class="icon_fecha" src="'.get_recurso("img").'RESPONSIVE/SVG/Pequenio.svg" />
							<span>
								<div class="tam_label_pc">Pequeño</div>
								<div class="tam_label_movil">Peq.</div>
								<small>0 a 25 cm</small>
							</span>
							<div class="top_check"></div>
						</label>
						<label class="input_check_box" for="mediano">
							<input type="checkbox" id="mediano" name="mediano"  />
							<img class="icon_fecha" src="'.get_recurso("img").'RESPONSIVE/SVG/Mediano.svg" />
							<span>
								<div class="tam_label_pc">Mediano</div>
								<div class="tam_label_movil">Med.</div>
								<small>25 a 58 cm</small>
							</span>
							<div class="top_check"></div>
						</label>

						<label class="input_check_box" for="grande">
							<input type="checkbox" id="grande" name="grande"  />
							<img class="icon_fecha" src="'.get_recurso("img").'RESPONSIVE/SVG/Grande.svg" />
							<span>
								<div class="tam_label_pc">Grande</div>
								<div class="tam_label_movil">Gde</div>
								<small>58 a 73 cm</small>
							</span>
							<div class="top_check"></div>
						</label>

						<label class="input_check_box" for="gigante">
							<input type="checkbox" id="gigante" name="gigante"  />
							<img class="icon_fecha" src="'.get_recurso("img").'RESPONSIVE/SVG/Gigante.svg" />
							<span>
								<div class="tam_label_pc">Gigante</div>
								<div class="tam_label_movil">Gte.</div>
								<small>73 a 200 cm</small>
							</span>
							<div class="top_check"></div>
						</label>
					</div>

					<div class="boton_buscar_container">
						<input type="submit" class="boton_buscar boton_verde" value="Buscar cuidador">
					</div>

				</form>

			</div>	
		</div>

		<!-- BENEFICIOS -->

		<div class="beneficios_container">
			
			<div class="beneficios_buscar_top">
				Más de <strong>1,000 Cuidadores Certificados y 60,000 noches reservadas.</strong> Tu consentido se queda en el hogar de una <strong>VERDADERA FAMILIA,</strong> con cobertura veterinaría
			</div>

			<div class="beneficios_registrar_container">
				<div class="boton boton_border_morado">Regístrate</div>
				<span class="">
					Crea tu perfil, y comienza a disfrutar de los servicios que te trae Kmimos
				</span>
			</div>
			
			<h2>Conoce los beneficios de dejar tu mascota con cuidadores certificados</h2>
			<img class="beneficios_banner_movil" src="'.get_recurso("img").'RESPONSIVE/PNG/Beneficios-de-dejar---.png" />

			<div class="beneficios_detalles">

				<div class="beneficios_detalles_tabla">
					<div class="beneficios_detalles_col_left">
						
						<div class="beneficios_detalles_item">
							<div class="beneficios_detalles_icon">
								<img src="'.get_recurso("img").'SVG/Km_Certificado.svg" />
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
								<img src="'.get_recurso("img").'SVG/Km_Veterinario.svg" />
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
								<img src="'.get_recurso("img").'SVG/Km_Fotografia.svg" />
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
										<img src="'.get_recurso("img").'SVG/Check.svg" align="left" />
									</div>
									<div>
										<strong>Hospedaje</strong>
										<p>Para cuando sales de viaje</p>
									</div>
								</li>
								<li>
									<div>
										<img src="'.get_recurso("img").'SVG/Check.svg" align="left" />
									</div>
									<div>
										<strong>Guardería</strong>
										<p>Cuando vas a tu oficina, gimnasio, etc.</p>
									</div>
								</li>
								<li>
									<div>
										<img src="'.get_recurso("img").'SVG/Check.svg" align="left" />
									</div>
									<div>
										<strong>Paseos</strong>
										<p>Mejora su salud, socializa y elimina su ansiedad</p>
									</div>
								</li>
								<li>
									<div>
										<img src="'.get_recurso("img").'SVG/Check.svg" align="left" />
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
					<div class="boton boton_verde">Buscar cuidador</div>
				</div>
			</div>

		</div>

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

			<a href="" class="testimonios_link">Ver más comentarios como éste</a>

		</div>

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
							<img src="'.get_recurso("img").'SVG/Paso_1.svg" />
						</div>
						<div class="pasos_celda_bottom">
							<h3>Haz tu búsqueda</h3>
							<p>Consigue cuidadores cerca de ti, con las características que necesites</p>
						</div>
					</div>
					<div class="pasos_reserva_celda">
						<div class="pasos_celda_top">
							<img src="'.get_recurso("img").'SVG/Paso_2.svg" />
						</div>
						<div class="pasos_celda_bottom">
							<h3>Agenda y haz el pago</h3>
							<p>Paga con tarjeta de débito, crédito o efectivo en tienda de conveniencia</p>
						</div>
					</div>
					<div class="pasos_reserva_celda">
						<div class="pasos_celda_top">
							<img src="'.get_recurso("img").'SVG/Paso_3.svg" />
						</div>
						<div class="pasos_celda_bottom">
							<h3>Tu mascota vuelve feliz</h3>
							<p>¡Despreocúpate! Tu mejor amigo volverá feliz, esa es la garantía Kmimos</p>
						</div>
					</div>
				</div>
			</div>

		</div>

		<!-- CLUB PATITAS FELICES -->

		<div class="club_patitas_container_superior">
			<div class="club_patitas_container">
				<div class="club_patitas_tabla">
					<div class="club_patitas_celda celda_30">
						<h2>¡Únete al Club de las patitas felices! </h2>
						<img  class="club_patitas_logo" src="'.get_recurso("img").'SVG/Club_patitas.svg" />
					</div>
					<div class="club_patitas_celda celda_70">
						<h2>¡Únete al Club de las patitas felices! </h2>
						<span>Cada amigo que complete 1 reservación</span>
						<h3>Gana $150 y tú ganas otros $150</h3>
						<form>
							<div class="club_patitas_tabla">
								<div class="club_patitas_celda celda_70">
									<input type="text" name="club_nombre" placeholder="Nombres y Apellidos" />
									<input type="text" name="club_nombre" placeholder="Correo Electrónico"  />
								</div>
								<div class="club_patitas_celda celda_30">
									<input type="submit" value="Inscribete y gana" class="boton boton_morado">
									<small>Ingresa los datos y haz click aquí</small>
								</div>
							</div>
						</form>
					</div>
				</div>

			</div>
		</div>

		<!-- QUIERO SER CUIDADOR -->

		<div class="quiero_ser_cuidador_container">
			<div class="quiero_ser_cuidador_img"></div>

			<div class="quiero_ser_cuidador_info">
				<h2>Conviértete en cuidador certificado kmimos</h2>
				<div>
					<span>Kmimos necesita doglovers como tú</span>
					<a href="" class="boton boton_verde">Empezar a cuidar</a>
				</div>
			</div>

		</div>
		
		<div class="quiero_ser_cuidador_container_2">
			<span>Kmimos necesita doglovers como tú</span>
			<a href="" class="boton boton_border_gris">Empezar a cuidar</a>
		</div>


		<!-- CONECTATE -->

		<div class="conectate_container">
			<h2>Conéctate de donde quieras</h2>
			<img src="'.get_recurso("img").'PNG/Moviles.png" />
			<span>Disponible en la web, y en dispositivos iOS y Android</span>
			<div class="mensaje_movil">
				<span>Baja nuestra <strong>app</strong>, y conéctate desde donde quieras</span>
			</div>
			<div class="conectate_botones_tabla">
				<div class="conectate_botones_celda"><img src="'.get_recurso("img").'SVG/APP_STORE.svg" /></div>
				<div class="conectate_botones_celda"><img src="'.get_recurso("img").'SVG/GOOGLE_PLAY.svg" /></div>
			</div>
		</div>

		<!-- ALIADOS -->

		<div class="aliados_container">
			<img src="'.get_recurso("img").'PNG/Reforma.png" />
			<img src="'.get_recurso("img").'PNG/Mural.png" />
			<img src="'.get_recurso("img").'PNG/El-norte.png" />
			<img src="'.get_recurso("img").'PNG/Financiero.png" />
			<img src="'.get_recurso("img").'PNG/Universal.png" />
			<img src="'.get_recurso("img").'PNG/Petco.png" />
		</div>
    ';

    echo comprimir($HTML);
    
    wp_enqueue_script('buscar_home', get_recurso("js")."home.js", array(), '1.0.0');

    get_footer(); 
?>


