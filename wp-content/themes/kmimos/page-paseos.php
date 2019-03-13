<?php 
    /*
        Template Name: Paseos
    */

    wp_enqueue_style('home_kmimos', get_recurso("css")."paseos.css", array(), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/paseos.css", array(), '1.0.0');
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

    $dias_str = '';
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
    	$letra = substr($value, 0, 1);
    	$dias_str .= 
    	'	<label class="input_check_box" title="'.$value.'" for="'.$key.'">'.
		'		<input type="checkbox" id="'.$key.'" name="dias[]" value="'.$key.'"  />'.
		'		<span>'.$letra.'</span>'.
		'		<div class="top_check"></div>'.
		'	</label>'
    	;
    }
        
    $HTML = '
    	<div id="banner_home">
    		<div class="fondo_banner"></div>
			<div>
				<div class="solo_pc">
					<span class="banner_txt_1">La red de <em>paseadores</em> certificados de México</span>
					<span id="buscar" class="banner_txt_2">¡Tu mejor amigo regresa feliz!</span>
				</div>
				<div class="solo_movil banner_home"></div>
				<form id="buscador" method="POST" action="'.getTema().'/procesos/busqueda/buscar.php" >

					<input type="hidden" name="orderby" value="price_asc" />
					<input type="hidden" name="landing_paseos" value="yes" />
					<input type="hidden" name="redireccionar" value="1" />
					<input type="hidden" name="USER_ID" value="'.$user_id.'" />
					<input type="hidden" id="latitud" name="latitud" />
					<input type="hidden" id="longitud" name="longitud" />

					<input type="hidden" id="servicios" name="servicios[]" value="paseos" />

					<input type="hidden" id="paquete" name="paquete" value="" />

					<div class="solo_movil" style="padding: 0px 10px;">
						<div class="boton boton_border_morado">Regístrate</div>
						<span class="banner_txt_1">Disfruta de la red de más segura de <em>paseadores</em> certificados de México</span>
						<span class="banner_txt_2" id="buscar">Nuestra promesa: ¡Tu mejor amigo regresa feliz!</span>
						<span class="banner_txt_3">Cuándo y cómo te gustaría pasearlo</span>
					</div>

					<div class="controles_top_container">
						<div class="ubicacion_container">
							&nbsp;
						</div>
						<div class="dias_container">
							&nbsp;
						</div>
					</div>

					<div class="controles_mitad_container">

						<div class="ubicacion_container">
							<div class="ubicacion_msg">
								Dinos donde te encuentras
							</div>
							<img class="ubicacion_localizacion" src="'.get_recurso("img").'BUSQUEDA/SVG/Localizacion_2.svg" />
							<input type="text" class="ubicacion_txt" name="ubicacion_txt" placeholder="Ubicación estado municipio" autocomplete="off" />
							<input type="hidden" class="ubicacion" name="ubicacion" value="'.$busqueda["ubicacion"].'" />	
						    <div class="cerrar_list_box">
						    	<div class="cerrar_list">X</div>
						    	<ul class="ubicacion_list"></ul>
						    </div>
							<i class="fa icon_left ubicacion_gps _mi_ubicacion"></i>
							<img class="mi_ubicacion" src="'.get_recurso("img").'HOME/SVG/GPS_Off.svg" />
							<div class="barra_ubicacion"></div>
							<small class="hidden" data-error="ubicacion">Función disponible solo en México</small>
						</div>

						<div class="dias_container">
							<div class="dias_msg">
								Selecciona que días de la semana deseas que paseen a tu mascota
							</div>
							'.$dias_str.'
						</div>

						<div class="fechas_container">
							<div id="desde_container">
								<img class="icon_fecha" src="'.get_recurso("img").'HOME/SVG/Fecha.svg" />
								<input type="text" id="checkin" name="checkin" placeholder="Inicio de Paseos" class="date_from" readonly>
								<small class="">Requerido</small>
							</div>
							<div>
								<img class="icon_fecha" src="'.get_recurso("img").'HOME/SVG/Fecha.svg" />
								<input type="text" id="checkout" name="checkout" placeholder="Fin de Paseos" class="date_to" readonly>
								<small class="">Requerido</small>
							</div>
						</div>
					</div>

					<div class="tamanios_container">
						<label class="input_check_box" for="paqueno">
							<input type="checkbox" id="paqueno" name="tamanos[]" value="paquenos"  />
							<div class="top_check"></div>
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Pequenio.svg" />
								<div class="tam_label_pc">Pequeño</div>
								<div class="tam_label_movil">Peq.</div>
								<small>0 a 25 cm</small>
							</span>
						</label>
						<label class="input_check_box" for="mediano">
							<input type="checkbox" id="mediano" name="tamanos[]" value="medianos"  />
							<div class="top_check"></div>
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Mediano.svg" />
								<div class="tam_label_pc">Mediano</div>
								<div class="tam_label_movil">Med.</div>
								<small>25 a 58 cm</small>
							</span>
						</label>

						<label class="input_check_box" for="grande">
							<input type="checkbox" id="grande" name="tamanos[]" value="grandes"  />
							<div class="top_check"></div>
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Grande.svg" />
								<div class="tam_label_pc">Grande</div>
								<div class="tam_label_movil">Gde</div>
								<small>58 a 73 cm</small>
							</span>
						</label>

						<label class="input_check_box" for="gigante" style="margin-right: 0px;">
							<input type="checkbox" id="gigante" name="tamanos[]" value="gigantes"  />
							<div class="top_check"></div>
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'HOME/RESPONSIVE/SVG/Gigante.svg" />
								<div class="tam_label_pc">Gigante</div>
								<div class="tam_label_movil">Gte.</div>
								<small>73 a 200 cm</small>
							</span>
						</label>
					</div>

					<div class="boton_buscar_container">
						<input type="button" id="boton_buscar" class="boton_buscar boton_verde" value="Buscar paseador">
					</div>

				</form>

				<div class="boton_ver_paquetes_container">
					<input type="button" id="boton_ver_paquetes" class="boton_buscar boton_verde" value="Ver paquetes">
				</div>

			</div>	
		</div>';


		$HTML .= '
			<a 
				onclick="evento_google_kmimos(\'banner\'); evento_fbq_kmimos(\'banner\');" 
				target="_blanck" href="'.get_home_url().'/redireccion/?utm_source=homepage&utm_medium=banner&utm_campaign=nomadas_kmimos_paseos&url=https://www.nomadas.life/?publicmap=kmimos" style="display: block;">
				<img src="'.get_recurso("img").'BANNERS/banner_rotativo/pc/4.jpg" width="100%" class="solo_pc" />
				<img src="'.get_recurso("img").'BANNERS/banner_rotativo/movil/4.jpg" width="100%" class="solo_movil" />
			</a>
		';

		
		$HTML .= '
		<!-- BENEFICIOS -->

		<div class="beneficios_container">
			
			<div class="beneficios_buscar_top">
				Cientos de Paseadores Certificados a nivel nacional
			</div>
			
			<h2>¿Por qué son tan importantes los paseos para tu mascota? </h2>
			<img class="beneficios_banner_movil" src="'.get_recurso("img").'HOME/RESPONSIVE/PNG/Beneficios-de-dejar---.png" />

			<div class="importancia_detalles">

				<div class="importancia_detalles_tabla">
					<div class="importancia_detalles_col">
						<img src="'.get_recurso("img").'PASEOS/SVG/Paseos_1.svg" />
						<label>Los paseos diarios mantienen a tu mascota saludable</label>
						<p>Los perritos tienen una necesidad de actividad física importante que al no ser cubierta podría desarrollar estrés crónico que hace, por ejemplo, nos destrozen los muebles del hogar.</p>
					</div>
					<div class="importancia_detalles_col">
						<img src="'.get_recurso("img").'PASEOS/SVG/Paseos_2.svg" />
						<label>Una jornada de paseos previene</label>
						<p>Obecidad por sedentarismo, estrés crónico, ansiedad, apatía en la cadera, diábetes y cardiopatías, problemas ortopédicos y articulares. Además, mantiene sus sentidos alertas.</p>
					</div>
					<div class="importancia_detalles_col">
						<img src="'.get_recurso("img").'PASEOS/SVG/Paseos_3.svg" />
						<label>Con qué frecuencia debo pasear a mi mascota</label>
						<p>Para mantener la salud de tus mascotas se les debe sacar como mínimo 3 veces al día, realizando estas salidas por lo menos 20 minutus después de consumir sus alimentos.</p>
					</div>
				</div>
			</div>
		</div>

		<img class="importancia_banner solo_movil" src="'.get_recurso("img").'PASEOS/RESPONSIVE/Banner-paseo-gratis_2.jpg" />
		<img class="importancia_banner solo_pc" src="'.get_recurso("img").'PASEOS/PNG/Banner-paseo-gratis_2.png" />

		<div class="registrar_container">
			<div class="beneficios_registrar_container">
				<div data-target="#popup-registrarte" role="button" class="boton boton_border_morado">Regístrate</div>
				<span class="">
					Crea tu perfil, y comienza a disfrutar de los servicios que te trae Kmimos
				</span>
			</div>
		</div>
			
		<div class="beneficios_container">
			<h2 class="beneficios_title">Conoce los beneficios de dejar tu mascota con cuidadores certificados</h2>
			<img class="beneficios_banner_movil" src="'.get_recurso("img").'HOME/RESPONSIVE/PNG/Beneficios-de-dejar---.png" />

			<div class="beneficios_detalles">

				<div class="beneficios_detalles_tabla">
					<div class="beneficios_detalles_col_left">
						
						<div class="beneficios_detalles_item">
							<div class="beneficios_detalles_icon">
								<img src="'.get_recurso("img").'HOME/SVG/Km_Certificado.svg" />
							</div>
							<div class="beneficios_detalles_info">
								<h3>Paseadores Certificados</h3>
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

					</div>
					<div class="beneficios_detalles_col_right">
						
						<div class="beneficios_servicios_principales">
							<div class="beneficios_servicios_principales_titulo">
								Otros servicios ofrecidos por Kmimos
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
					Cientos de paseadores certificados a nivel nacional
				</div>
				<div class="beneficios_buscar_right">
					<div onclick="ancla_form()" class="boton boton_verde">Buscar Paseador</div>
				</div>
			</div>
			<div id="paquetes"></div>

		
		</div>';
		
		$HTML .= '
		<div class="paquetes_container">
			<h2>Selecciona el <span>paquete perfecto</span> para tu mejor amigo</h2>
			<p>Aprovecha las promociones por paseos semanales, mensuales, bimensuales y trimestrales. Ahorra tiempo y dinero</p>

			<div class="paquetes_tabla">
				<label class="paquetes_celdas" for="paq_1">
					<input type="checkbox" id="paq_1" name="paq_1" />
					<div class="paquete_item paq_1">
						<div class="paquete_top">
							<div class="paquete_title">
								1 Semana
								<i class="fa fa-angle-down" aria-hidden="true"></i>
								<i class="fa fa-angle-up" aria-hidden="true"></i>
							</div>
							<div class="paquete_list">
								<ul>
									<li>Para dueños de mascotas que desean ver el cambio en sus consentidos</li>
									<li>- Cobertura veterinaria</li>
									<li>- 5% de descuento</li>
									<li>- Hasta 5 paseos en una misma semana</li>
								</ul>
							</div>
						</div>
						<div>
							<input type="radio" id="paq_1_radio" value="1" class="input_radio" />
							<button class="btn_paq" data-id="1">Solicitar</button>
						</div>
					</div>
				</label>
				<label class="paquetes_celdas" for="paq_2">
					<input type="checkbox" id="paq_2" name="paq_2" />
					<div class="paquete_item paq_2">
						<div class="paquete_top">
							<div class="paquete_title">
								1 Mes
								<i class="fa fa-angle-down" aria-hidden="true"></i>
								<i class="fa fa-angle-up" aria-hidden="true"></i>
							</div>
							<div class="paquete_list">
								<ul>
									<li>Si ya conoces los beneficios de pasear a tu mejor amigo, te ofrecemos paquetes mensuales</li>
									<li>- Cobertura veterinaria y accidentes</li>
									<li>- 10% de descuento</li>
									<li>- Hasta 4 paseos en la semana</li>
								</ul>
							</div>
						</div>
						<div>
							<input type="radio" id="paq_2_radio" value="2" class="input_radio" />
							<button class="btn_paq" data-id="2">Solicitar</button>
						</div>
					</div>
				</label>
				<label class="paquetes_celdas" for="paq_3">
					<input type="checkbox" id="paq_3" name="paq_3" />
					<div class="paquete_item paq_3">
						<div class="paquete_top">
							<div class="paquete_title">
								2 Meses
								<i class="fa fa-angle-down" aria-hidden="true"></i>
								<i class="fa fa-angle-up" aria-hidden="true"></i>
							</div>
							<div class="paquete_list">
								<ul>
									<li>Tu consentido estará más que feliz, y tu recibirás todos estos beneficios</li>
									<li>- Cobertura veterinaria premium (daños a terceros)</li>
									<li>- 15% de descuento</li>
									<li>- Hasta 5 paseos en la semana</li>
									<li>- 5% de descuento en otros servicios de Kmimos</li>
								</ul>
							</div>
						</div>
						<div>
							<input type="radio" id="paq_3_radio" value="3" class="input_radio" />
							<button class="btn_paq paq_3_btn" data-id="3">Solicitar</button>
						</div>
					</div>
				</label>
				<label class="paquetes_celdas" for="paq_4">
					<input type="checkbox" id="paq_4" name="paq_4" />
					<div class="paquete_item paq_4">
						<div class="paquete_top">
							<div class="paquete_title">
								3 Meses
								<i class="fa fa-angle-down" aria-hidden="true"></i>
								<i class="fa fa-angle-up" aria-hidden="true"></i>
							</div>
							<div class="paquete_list">
								<ul>
									<li>Para dueños de mascotas que desean ver el cambio en sus consentidos</li>
									<li>- Cobertura veterinaria premium (daños a terceros)</li>
									<li>- 20% de descuento</li>
									<li>- Hasta 5 paseos en la semana</li>
									<li>- 10% de descuento en otros servicios de Kmimos</li>
								</ul>
							</div>
						</div>
						<div>
							<input type="radio" id="paq_4_radio" value="4" class="input_radio" />
							<button class="btn_paq" data-id="4">Solicitar</button>
						</div>
					</div>
				</label>
			</div>
		</div>';
		
		$HTML .= '
		<div class="testimonios_container">
			<div class="testimonios_item">
				<p>He estado llevando mi perrito con Gabriel, estoy muy agradecido y encantado con los paseos que le han dado estas últimas dos semanas. Durante estos paseos me envió fotos de mi perrito conociendo nuevos amigos, y cada vez que volvía, se veía más feliz que nunca.</p>
				<span>- Alejandra R.</span>
			</div>
			<div class="testimonios_img">
				<span>
					+1,500 comentarios positivos en perfiles de paseadores certificados
				</span>
			</div>
			<a href="" class="testimonios_link">Ver más comentarios como éste</a>
		</div>';
		
		$HTML .= '

		<!-- PASOS PARA RESERVAR -->

		<div class="pasos_reserva_container">
			<h2>Tu mascota vuelve feliz de sus paseos diarios, además de estar más saludable que nunca</h2>

			<h2 class="solo_movil">Tu mascota vuelve feliz de sus paseos diarios, además de estar más saludable que nunca</h2>

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
							<h3><strong>1.</strong> Haz tu búsqueda</h3>
							<p>Consigue paseadores cerca de ti, con las características que necesites</p>
						</div>
					</div>
					<div class="pasos_reserva_celda">
						<div class="pasos_celda_top">
							<img src="'.get_recurso("img").'HOME/SVG/Paso_2.svg" />
						</div>
						<div class="pasos_celda_bottom">
							<h3><strong>2.</strong> Agenda y haz el pago</h3>
							<p>Paga con tarjeta de débito, crédito o efectivo en tienda de conveniencia</p>
						</div>
					</div>
					<div class="pasos_reserva_celda">
						<div class="pasos_celda_top">
							<img src="'.get_recurso("img").'HOME/SVG/Paso_3.svg" />
						</div>
						<div class="pasos_celda_bottom">
							<h3><strong>3.</strong> Tu mascota vuelve feliz</h3>
							<p>¡Despreocúpate! Tu mejor amigo volverá feliz, esa es la garantía Kmimos</p>
						</div>
					</div>
				</div>
			</div>

		</div>';

		$HTML .= '
		<!-- SECCIÓN 4 - CLUB PATITAS FELICES -->
		<div class="club_patitas_container_superior">
			<div class="club_patitas_container">
				<div class="club_patitas_tabla">
					<div class="club_patitas_celda celda_30">
						<h2>¡Únete al Club de las patitas felices! </h2>
						<img  class="club_patitas_logo" src="'.get_recurso('img/HOME/SVG').'Club_patitas.svg" />
					</div>
					<div class="club_patitas_celda celda_70">
						<h2>¡Únete al Club de las patitas felices! </h2>
						<span>Cada amigo que complete 1 reservación</span>
						<h3>Gana $150 y tú ganas otros $150</h3>
						<form id="club_patitas">
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
		<!-- FIN SECCIÓN 4 - CLUB PATITAS FELICES -->';

		/*
		$HTML .= '

		<!-- CLUB PATITAS FELICES -->

		<div class="club_patitas_container_superior">
			<div class="club_patitas_container">
				<div class="club_patitas_tabla">
					<div class="club_patitas_celda celda_30">
						<h2>¡Únete al Club de las patitas felices! </h2>
						<img  class="club_patitas_logo" src="'.get_recurso("img").'HOME/SVG/Club_patitas.svg" />
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
		</div>';
		*/

		$HTML .= '

		<!-- QUIERO SER CUIDADOR -->

		<div class="quiero_ser_cuidador_container">
			<div class="quiero_ser_cuidador_img"></div>

			<div class="quiero_ser_cuidador_info">
				<h2>Conviértete en paseador certificado kmimos</h2>
				<div>
					<span>Kmimos necesita doglovers como tú</span>
					<a href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros" class="boton boton_verde">Quiero pasear perritos</a>
				</div>
			</div>

		</div>
		
		<div class="quiero_ser_cuidador_container_2">
			<span>Kmimos necesita doglovers como tú</span>
			<a href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros" class="boton boton_border_gris">Quiero pasear perritos</a>
		</div>';
		
		$HTML .= '
		<!-- CONECTATE -->

		<div class="conectate_container" style="display: none;" >
			<h2>Conéctate de donde quieras</h2>
			<img src="'.get_recurso("img").'HOME/PNG/Moviles.png" />
			<span>Disponible en la web, y en dispositivos iOS y Android</span>
			<div class="mensaje_movil">
				<span>Baja nuestra <strong>app</strong>, y conéctate desde donde quieras</span>
			</div>
			<div class="conectate_botones_tabla">
				<div class="conectate_botones_celda"><img src="'.get_recurso("img").'HOME/SVG/APP_STORE.svg" /></div>
				<div class="conectate_botones_celda"><img src="'.get_recurso("img").'HOME/SVG/GOOGLE_PLAY.svg" /></div>
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
    
    wp_enqueue_script('buscar_home', get_recurso("js")."paseos.js", array(), '1.0.0');

    get_footer(); 
?>


