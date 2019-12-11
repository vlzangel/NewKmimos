<?php 
    /*
        Template Name: Kmivet
    */

    $HOME = 2;
    $HEADER = 'kmivet';

	$_wlabel = ( !empty($_SESSION["wlabel"]) ) ? '_'.$_SESSION["wlabel"] : '';

	date_default_timezone_set('America/Mexico_City');

    wp_enqueue_style('home_club_responsive', getTema()."/css/responsive/club_patitas_home.css", array(), '1.0.0');
    wp_enqueue_style('home_kmimos', get_recurso("css")."home_2.css", array(), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/home_2.css", array(), '1.0.0');

	wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );


    wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');


    wp_enqueue_style('kmivet', get_recurso("css")."kmivet.css?v=".time(), array(), '1.0.0');


    wp_enqueue_script('select_localidad', getTema()."/js/select_localidad.js", array(), '1.0.0');
    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');


            
    get_header();

    $user_id = get_current_user_id();

    $items = '';
    $info_banner = [
    	['1.png', false, get_home_url().'/mediqo/', 'Veterinarios'],
    ];
    foreach ($info_banner as $key => $url) {
    	$link = $url[2];
    	$target = ( $url[1] ) ? ' target="_blank" ' : '';
    	$items .= '<div class="banner_rotativo_item solo_pc_banner" style="background-image: url('.get_recurso('img').'KMIVET/BANNER/'.$url[0].');"><a href="'.get_home_url().'/seg/?banner='.$url[3].$_wlabel.'&url='.base64_encode($link).'" '.$target.'></a></div>';
    	// $items .= '<div class="banner_rotativo_item solo_movil_banner"> <img src="'.get_recurso('img').'HOME_2/RESPONSIVE/Muestra'.$url[0].'" /><a href="'.get_home_url().'/seg/?banner='.$url[3].$_wlabel.'&url='.base64_encode($link).'" '.$target.'></a></div>';
    }

    $items_count = count($info_banner);
	$final_pc = $items_count-1;
	$final_movil = $items_count-1;
    
	$HTML = '
	<div id="banner_home">
		<div>
			<div class="banner_rotativo">
				<div class="banner_rotativo_container">
					<div class="banner_rotativo_box banner_box" data-paso="0" data-final_pc="'.($final_pc).'" data-final_movil="'.($final_movil).'" data-h_pc="100" data-h_movil="100" data-t="1500">
						'.$items.'
					</div>
				</div>
				<img class="seccion_destacados_flechas seccion_destacados_izq" data-dir="izq" src="'.get_recurso('img').'HOME_2/SVG/boton_anterior.svg" />
				<img class="seccion_destacados_flechas seccion_destacados_der" data-dir="der" src="'.get_recurso('img').'HOME_2/SVG/boton_siguiente.svg" />
			</div>

			<form id="buscador" method="POST" action="'.get_home_url().'/mediqo" >

				<div class="titulo_banner_top">Selecciona los filtros para búsqueda avanzada</div>
				<input type="hidden" name="USER_ID" value="'.$user_id.'" />
				<input type="hidden" id="latitud" name="latitud" />
				<input type="hidden" id="longitud" name="longitud" />
				
				<input type="hidden" id="log" name="log" />

				<div class="vlz_top">

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
						<label class="input_check_box gato" for="gato">
							<input type="checkbox" id="gato" name="mascotas[]" value="gatos"  />
							<img src="'.get_recurso("img").'HOME/SVG/Gato.svg" />
							<span>Gato</span>
							<div class="top_check"></div>
						</label>
						<label id="label_otro" class="input_check_box_" for="otro">
							<input type="checkbox" id="otro" name="mascotas[]" value="otro"  />
							Otro
						</label>
						<label id="label_input">
							<input type="text" id="input_otro" name="otro" placeholder="Escribe el tipo de mascota" disabled />
						</label>
					</div>
				</div>

				<div class="tamanios_container">
					<textarea id="motivo" name="motivo" type="text" placeholder="Motivo de la consulta" ></textarea>
				</div>

				<div class="boton_buscar_container">
					<input type="submit" id="boton_buscar" class="boton_buscar boton_verde" value="Buscar cuidador">
				</div>

				<div style="clear: both;"></div>

			</form>

		</div>	
	</div>';

	/*
	$SERVICIOS_PRINCIPALES = [
		[
			'Hospedaje.jpg',
			'Hospedaje',
			'¿Te vas de viaje? Tu mejor amigo será huesped en el propio hogar de uno de nuestros cuidadores',
			'hospedaje'
		],
		[
			'Guarderia.jpg',
			'Guardería',
			'Uno de nuestros cuidadores lo apapachará y jugará con el durante el día',
			'guarderia'
		],
		[
			'Paseos.jpg',
			'Paseos',
			'¿Sabías que un paseo de al menos dos horas para tu peludo baja sus niveles de estrés?',
			'paseos'
		],
		[
			'Entrenamiento.jpg',
			'Entrenamiento',
			'Encuentra especialistas para cualquier tipo de comportamiento',
			'adiestramiento'
		],
	];

	$items = '';
	foreach ($SERVICIOS_PRINCIPALES as $key => $servicio) {
		$items .= 
		'<label class="carrusel_servicios_principales_item" for="'.$servicio[3].'_2">'.
			'<div class="carrusel_servicios_principales_img" style="background-image: url('.get_recurso('img').'HOME_2/'.$servicio[0].');"></div>'.
			'<div class="carrusel_servicios_principales_data">'.
				'<label>'.strtoupper($servicio[1]).'</label>'.
				'<p>'.$servicio[2].'</p>'.
			'</div>'.
		'</label>';
	}

    $items_count = count($SERVICIOS_PRINCIPALES);
	$final_pc = $items_count-3;
	$final_movil = ($items_count)-1;

	$HTML .= '
		<div class="carrusel_servicios carrusel_servicios_1">
			<h2 class="solo_pc">¿Qué estás buscando para tu mascota? <span>></span> </h2>
			<h2 class="solo_movil">O busca cuidadores por servicio > </h2>

			<div class="carrusel_servicios_principales_container">
				<div class="carrusel_servicios_principales_box banner_box" data-paso="0" data-paso-movil="7" data-final_pc="'.($final_pc).'" data-final_movil="'.($final_movil).'" data-h_pc="33.333334" data-h_movil="70" data-t="1000">
					'.$items.''.$items.''.$items.''.$items.''.$items.'
				</div>
			</div>
			<img class="seccion_destacados_flechas seccion_destacados_izq" data-dir="izq" src="'.get_recurso('img').'HOME_2/SVG/boton_anterior.svg" />
			<img class="seccion_destacados_flechas seccion_destacados_der" data-dir="der" src="'.get_recurso('img').'HOME_2/SVG/boton_siguiente.svg" />
		</div>
		<form id="buscador_2" method="POST" action="'.getTema().'/procesos/busqueda/buscar.php" >
			<input type="hidden" name="personalizada" value="1" />  
			<input type="hidden" name="redireccionar" value="1" />
			<input type="hidden" name="USER_ID" value="'.$user_id.'" />
			<input type="radio" id="hospedaje_2" name="servicios[]" value="hospedaje"  />
			<input type="radio" id="guarderia_2" name="servicios[]" value="guarderia"  />
			<input type="radio" id="paseos_2" name="servicios[]" value="paseos"  />
			<input type="radio" id="adiestramiento_2" name="servicios[]" value="adiestramiento"  />
		</form>
	';
	*/

	$cuidadores = get_recomendaciones_homa_2();

	$items = '';
	foreach ($cuidadores as $key => $c) {
		$items .= 
		'<div class="carrusel_recomendados_item">'.
			'<div class="carrusel_recomendados_img" style="background-image: url('.$c->img.');"></div>'.
			'<div class="carrusel_recomendados_data">'.
				'<span>'.$c->nombre.'</span>'.
				'<div class="carrusel_recomendados_ubicacion">'.$c->ubicacion.'</div>'.
				'<div class="carrusel_recomendados_experiencia">'.$c->experiencia.'</div>'.
				'<div class="carrusel_recomendados_precio">Desde MXN $ '.$c->precio.'</div>'.
				'<div class="carrusel_recomendados_ranking">'.$c->ranking.'</div>'.
				'<div class="carrusel_recomendados_experiencia">'.$c->valoraciones.'</div>'.
			'</div>'.
			'<a href="'.$c->link.'?ldg=d"></a>'.
		'</div>';
	}

    $items_count = count($cuidadores);
	$final_pc = $items_count-5;
	$final_movil = $items_count-1;

	$HTML .= '
		<div class="carrusel_recomendados">
			<h2>Te recomendamos estos cuidadores mejor evaluados <span>></span> </h2>

			<div class="carrusel_recomendados_container">
				<div class="carrusel_recomendados_box banner_box" data-paso="0" data-final_pc="'.($final_pc).'" data-final_movil="'.($final_movil).'" data-h_pc="20" data-h_movil="50" data-t="800">
					'.$items.'
				</div>
			</div>
			<img class="seccion_destacados_flechas seccion_destacados_izq" data-dir="izq" src="'.get_recurso('img').'HOME_2/SVG/boton_anterior.svg" />
			<img class="seccion_destacados_flechas seccion_destacados_der" data-dir="der" src="'.get_recurso('img').'HOME_2/SVG/boton_siguiente.svg" />
		</div>
	';

	$seccion = ( $_SESSION["wlabel"] != "" && strtolower($_SESSION["wlabel"]) != "quitar" ) ? $_SESSION["wlabel"] : "home";
	$HTML .= '
		<div class="suscribir_blog">
			<h2>Entérate de los últimos cuidados para tu mascota <span>¡Inscribete a nuestro blog y conócelas!</span></h2>

			<form id="suscribir" onsubmit="form_subscribe(this); return false;" class="subscribe" data-subscribe="'.get_home_url().'/wp-content/plugins/kmimos">
				<input type="hidden" name="section" value="'.$seccion.'" class="form-control" placeholder="Ingresa tu correo">
				<input type="hidden" id="wlabelSubscribeFooter" name="wlabelSubscribeFooter" value="'.$_SESSION["wlabel"].'" class="form-control" placeholder="Ingresa tu correo">
				<input type="text" id="mail" name="mail" placeholder="Ingresa tu correo" />
				<input type="submit" value="Inscribirme al blog" />
				<div class="message message-especial"></div>
			</form>

		</div>
	';

	$HTML .= '
	<!-- BENEFICIOS -->

	<div class="beneficios_container">
		
		<div class="beneficios_buscar_top">
			Más de <strong>1,000 Cuidadores Certificados y 60,000 noches reservadas.</strong> Tu consentido se queda en el hogar de una <strong>VERDADERA FAMILIA,</strong> con cobertura veterinaría
		</div>
		
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
				Más de <strong>1,000 Cuidadores Certificados y 60,000 noches reservadas.</strong> Tu consentido se queda en el hogar de una <strong>VERDADERA FAMILIA,</strong> con cobertura veterinaria
			</div>
			<div class="beneficios_buscar_right">
				<div onclick="ancla_form()" class="boton boton_verde">Buscar cuidador</div>
			</div>
		</div>

	
	</div>';














	$SERVICIOS_PRINCIPALES = [
		[
			'Banner-CPF.jpg',
			'Club de las patitas felices',
			'Consigue recompensas',
			'¡Cada amigo que complete una reservación gana $150 y tú $150 más!',
			'cpf',
			get_home_url().'/club-patitas-felices',
			'lo_nuevo_CPF'
		],
		[
			'Banner-GPS.jpg',
			'GPS',
			'Seguridad total durante su estadía',
			'Monitoreo en tiempo real durante el paseo o estadía.<br>¡Busca a los cuidadores con localización GPS!',
			'gps',
			 get_home_url().'/redireccion/?utm_source=homepage&utm_medium=banner&utm_campaign=nomadas_kmimos&url=https://www.nomadas.life/?publicmap=kmimos'
		],
		[
			'Banner-Conviertete.jpg',
			'Conviértete en cuidador',
			'¿Gana dinero con tu hobbie favorito?',
			'¡Kmimos necesita Doglovers como tú! Gana hasta $30.000 mensuales cuidando mascotas en tu hogar',
			'conviertete',
			get_home_url().'/quiero-ser-cuidador-certificado-de-perros',
			'lo_nuevo_Conviertete'
		]
	];

	$items = '';
	foreach ($SERVICIOS_PRINCIPALES as $key => $servicio) {
		$seguimiento = ( isset($servicio[6]) ) ? get_home_url().'seg/?banner='.$servicio[6].$_wlabel.'&url='.base64_encode($servicio[5]) : $servicio[5];
		$items .= 
		'<label class="carrusel_servicios_principales_item" for="'.$servicio[3].'_2">'.
			'<a href="'.$seguimiento.'" target="_blank"></a>'.
			'<div class="carrusel_servicios_principales_img" style="background-image: url('.get_recurso('img').'HOME_2/NEW/'.$servicio[0].');"></div>'.
			'<div class="carrusel_servicios_principales_data">'.
				'<label>'.($servicio[1]).'</label>'.
				'<label class="label_2">'.($servicio[2]).'</label>'.
				'<p>'.$servicio[3].'</p>'.
			'</div>'.
		'</label>';
	}


    $items_count = count($SERVICIOS_PRINCIPALES);
	$final_pc = $items_count-3;
	$final_movil = ($items_count)-1;

	$HTML .= '
		<div class="carrusel_servicios carrusel_servicios_2">

			<a id="ancla_ciudades" style="position: absolute; top: 150px;"></a>
			<h2 class="solo_pc">Lo nuevo de Kmimos <span>></span> </h2>
			<h2 class="solo_movil">Lo nuevo de Kmimos > </h2>

			<div class="carrusel_servicios_principales_container">
				<div class="carrusel_servicios_principales_box banner_box" data-paso="0" data-final_pc="'.($final_pc).'" data-final_movil="'.($final_movil).'" data-h_pc="0" data-h_movil="100" data-t="800">
					'.$items.'
				</div>
			</div>

			<img class="seccion_destacados_flechas seccion_destacados_izq" data-dir="izq" src="'.get_recurso('img').'HOME_2/SVG/boton_anterior.svg" />
			<img class="seccion_destacados_flechas seccion_destacados_der" data-dir="der" src="'.get_recurso('img').'HOME_2/SVG/boton_siguiente.svg" />
		</div>
	';















	$SERVICIOS_PRINCIPALES = [
		[
			'Guadalajara.png',
			'3',
			'Jalisco, Guadalajara',
		],
		[
			'CDMX.png',
			'1',
			'Ciudad de México',
		],
		[
			'Monterrey.png',
			'4',
			'Nuevo León, Monterrey',
		],
		[
			'Puebla.png',
			'23',
			'Puebla',
		],
		[
			'Queretaro.png',
			'24',
			'Queretaro',
		]
	];

	$items = '';
	foreach ($SERVICIOS_PRINCIPALES as $key => $servicio) {
		$evento = explode(".", $servicio[0]);
		$evento = "ciudad_".$evento[0].$_wlabel;
		$items .= 
		'<label class="carrusel_servicios_principales_item" data-id="'.$servicio[1].'" data-nombre="'.$servicio[2].'" data-evento="'.$evento.'">'.
			'<div class="carrusel_servicios_principales_img" style="background-image: url('.get_recurso('img').'HOME_2/NEW/'.$servicio[0].');"></div>'.
			'<div class="carrusel_servicios_principales_data">'.
				'<div class="carrusel_capa"></div>'.
			'</div>'.
		'</label>';
	}


    $items_count = count($SERVICIOS_PRINCIPALES);
	$final_pc = $items_count-3;
	$final_movil = ($items_count)-2;

	$HTML .= '
		<div class="carrusel_servicios carrusel_servicios_3">
			<h2 class="solo_pc">Buscar Cuidadores por ciudad <span>></span> </h2>
			<h2 class="solo_movil">Buscar Cuidadores por ciudad > </h2>

			<div class="carrusel_servicios_principales_container">
				<div class="carrusel_servicios_principales_box banner_box" data-paso="0" data-final_pc="'.($final_pc).'" data-final_movil="'.($final_movil).'" data-h_pc="0" data-h_movil="50" data-t="800">
					'.$items.'
				</div>
			</div>

			<img class="seccion_destacados_flechas seccion_destacados_izq" data-dir="izq" src="'.get_recurso('img').'HOME_2/SVG/boton_anterior.svg" />
			<img class="seccion_destacados_flechas seccion_destacados_der" data-dir="der" src="'.get_recurso('img').'HOME_2/SVG/boton_siguiente.svg" />
		</div>
	';







	



	
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
	</div>
	<div class="testimonio_separador"></div>';
	
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

	$link_APP_STORE = "https://apps.apple.com/mx/app/kmimos/id1247272074";
    $link_GOOGLE_PLAY = "https://play.google.com/store/apps/details?id=com.it.kmimos";

	$HTML .= '	
	<!-- CONECTATE -->
	<div class="conectate_container">
		<h2>Conéctate de donde quieras</h2>
		<img src="'.get_recurso("img").'HOME/PNG/Moviles.png" />
		<span>Disponible en la web, y en dispositivos iOS y Android</span>
		<div class="mensaje_movil">
			<span>Baja nuestra <strong>app</strong>, y conéctate desde donde quieras</span>
		</div>
		<div class="conectate_botones_tabla">
			<div class="conectate_botones_celda"> <a style="display: inline-block; width: 135px; height: 40px;" href="'.get_home_url().'/seg/?banner=APP_STORE'.$_wlabel.'&url='.base64_encode($link_APP_STORE).'" target="_blank"> <img src="'.get_recurso("img").'HOME/SVG/APP_STORE.svg" /> </a> </div>
			<div class="conectate_botones_celda"> <a style="display: inline-block; width: 135px; height: 40px;" href="'.get_home_url().'/seg/?banner=GOOGLE_PLAY'.$_wlabel.'&url='.base64_encode($link_GOOGLE_PLAY).'" target="_blank"> <img src="'.get_recurso("img").'HOME/SVG/GOOGLE_PLAY.svg" /> </a> </div>
		</div>
	</div>';
	
	$HTML .= '
	<!-- ALIADOS -->
	<div class="aliados_container">
		<img src="'.get_recurso("img").'HOME/PNG/Reforma.png" />
		<img src="'.get_recurso("img").'HOME/PNG/Mural.png" />
		<img src="'.get_recurso("img").'HOME/PNG/El-norte.png" />
		<img src="'.get_recurso("img").'HOME/PNG/Financiero.png" />
		<img src="'.get_recurso("img").'HOME/PNG/Universal.png" />
		<img src="'.get_recurso("img").'HOME/PNG/Petco.png" style="display: none;" />
	</div>';

    echo comprimir($HTML);
    
    wp_enqueue_script('isMobile', get_recurso("js")."isMobile.js", array(), '1.0.0');
    wp_enqueue_script('buscar_home', get_recurso("js")."kmivet.js", array('isMobile'), '1.0.0');
    wp_enqueue_script('club_patitas', get_recurso("js")."club_patitas.js", array(), '1.0.0');

    get_footer(); 
?>


