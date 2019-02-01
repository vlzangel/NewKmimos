<?php

	/* DESTACADOS */
	$destacados = get_destacados_home();
	if( is_array($destacados) && count($destacados) > 0 ){
		$desta_str = '';
		foreach ($destacados as $key => $cuidador) {
			$desta_str .= 
				'<div class="destacados_item">'.
					'<div class="img_destacado" style="background-image: url('.$cuidador->img.');"></div>'.
					'<div class="datos_destacado_containder">'.
						'<div class="datos_top_destacado_containder">'.
							'<div class="avatar_destacado" style="background-image: url('.$cuidador->img.');"></div>'.
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
    			<div class="destacados_box">
	    			<div>'.$desta_str.'</div>
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