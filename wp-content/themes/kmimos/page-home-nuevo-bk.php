<?php 
    /*
        Template Name: Home
    */

    wp_enqueue_style('home_kmimos', get_recurso("css")."home.css", array(), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/home.css", array(), '1.0.0');
            
    get_header();
        
	    $HTML = '
	    	<div id="banner_home">
				<div>
					<span class="banner_txt_1">la red más segura de cuidadores certificados de méxico</span>
					<span class="banner_txt_2">¡Tu mejor amigo regresa feliz!</span>

					<form id="busqueda">
					
						<div class="servicios_principales_container">
							<label class="input_check_box" for="hospedaje">
								<input type="checkbox" id="hospedaje" name="hospedaje"  />
								<img src="'.get_recurso("img").'SVG/Hospedaje.svg" />
								<span>Hospedaje</span>
								<div class="top_check"></div>
							</label>

							<label class="input_check_box" for="guarderia">
								<input type="checkbox" id="guarderia" name="guarderia"  />
								<img src="'.get_recurso("img").'SVG/Guarderia.svg" />
								<span>Guardería</span>
								<div class="top_check"></div>
							</label>

							<label class="input_check_box" for="paseos">
								<input type="checkbox" id="paseos" name="paseos"  />
								<img src="'.get_recurso("img").'SVG/Paseos.svg" />
								<span>Paseos</span>
								<div class="top_check"></div>
							</label>

							<label class="input_check_box" for="entrenamiento">
								<input type="checkbox" id="entrenamiento" name="entrenamiento"  />
								<img src="'.get_recurso("img").'SVG/Entrenamiento.svg" />
								<span>Entrenamiento</span>
								<div class="top_check"></div>
							</label>
						</div>

						<div class="controles_mitad_container">

							<div class="ubicacion_container">
								<img class="ubicacion_localizacion" src="'.get_recurso("img").'SVG/Localizacion.svg" />
								<input type="text" name="ubicacion" placeholder="Ubicación estado municipio" />
								<img class="ubicacion_gps" src="'.get_recurso("img").'SVG/GPS_Off.svg" />
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
									<input type="text" id="desde" name="desde" placeholder="Desde">
								</div>
								<div>
									<img class="icon_fecha" src="'.get_recurso("img").'SVG/Fecha.svg" />
									<input type="text" id="hasta" name="hasta" placeholder="Hasta">
								</div>

							</div>
						
						</div>

						<div class="tamanios_container">
							<label class="input_check_box" for="paqueno">
								<input type="checkbox" id="paqueno" name="paqueno"  />
								<span>
									Pequeño
									<small>0 a 25 cm</small>
								</span>
								<div class="top_check"></div>
							</label>

							<label class="input_check_box" for="mediano">
								<input type="checkbox" id="mediano" name="mediano"  />
								<span>
									Mediano
									<small>25 a 58 cm</small>
								</span>
								<div class="top_check"></div>
							</label>

							<label class="input_check_box" for="grande">
								<input type="checkbox" id="grande" name="grande"  />
								<span>
									Grande
									<small>58 a 73 cm</small>
								</span>
								<div class="top_check"></div>
							</label>

							<label class="input_check_box" for="gigante">
								<input type="checkbox" id="gigante" name="gigante"  />
								<span>
									Gigante
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
				
				<div class="beneficios_registrar_container">
					<div class="boton boton_border_morado">Regístrate</div>
					<span class="">
						Crea tu perfil, y comienza a disfrutar de los servicios que te trae Kmimos
					</span>
				</div>
				
				<div class="beneficios_detalles">
					<h2>Conoce los beneficios de dejar tu mascota con cuidadores certificados</h2>
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
				<div class="testimonios_img">
					<span>
						+1,500 comentarios positivos en perfiles de cuidadores
					</span>
				</div>

				<div class="testimonios_item">
					<p>Por segunda vez dejé a mi perro con Gabriel y su familia, estoy muy agradecido y encantado con el cuidado que le ha dado a mi mascota. Durante toda la estadía me envió fotos de mi perrito feliz mientras yo viajaba.</p>
					<span>- Alejandra R.</span>
				</div>

				<a href="" class="testimonios_link">Ver más comentarios como éste</a>

			</div>

			<!-- PASOS PARA RESERVAR -->

			<div class="pasos_reserva_container">
				<h2>Tu mascota será parte de una verdadera familia mientras se queda</h2>

				<div class="pasos_reserva_tabla">
					<div class="pasos_reserva_row">
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
							<img src="'.get_recurso("img").'SVG/Paso_1.svg" />
							<h3>Haz tu búsqueda</h3>
							<p>Consigue cuidadores cerca de ti, con las características que necesites</p>
						</div>
						<div class="pasos_reserva_celda">
							<img src="'.get_recurso("img").'SVG/Paso_2.svg" />
							<h3>Agenda y haz el pago</h3>
							<p>Paga con tarjeta de débito, crédito o efectivo en tienda de conveniencia</p>
						</div>
						<div class="pasos_reserva_celda">
							<img src="'.get_recurso("img").'SVG/Paso_3.svg" />
							<h3>Tu mascota vuelve feliz</h3>
							<p>¡Despreocúpate! Tu mejor amigo volverá feliz, esa es la garantía Kmimos</p>
						</div>
					</div>
				</div>

			</div>

			<!-- CLUB PATITAS FELICES -->

			<div class="club_patitas_container_superior">
				<div class="club_patitas_container">

					<div class="club_patitas_tabla">
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
						<div class="club_patitas_celda celda_30">
							<img  class="club_patitas_logo" src="'.get_recurso("img").'SVG/Club_patitas.svg" />
						</div>
					</div>

				</div>
			</div>

			<!-- QUIERO SER CUIDADOR -->

			<div class="quiero_ser_cuidador_container">
				<div class="quiero_ser_cuidador_img"></div>

				<div class="quiero_ser_cuidador_info">
					<h2>Conviértete en cuidador certificado kmimos</h2>
					<span>Kmimos necesita doglovers como tú</span>
					<a href="" class="boton boton_verde">Empezar a cuidar</a>
				</div>

			</div>

			<!-- CONECTATE -->

			<div class="conectate_container">
				<h2>Conéctate de donde quieras</h2>
				<img src="'.get_recurso("img").'PNG/Moviles.png" />
				<span>Disponible en la web, y en dispositivos iOS y Android</span>
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


