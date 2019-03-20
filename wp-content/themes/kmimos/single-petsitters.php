<?php

	wp_enqueue_style( 'perfil_cuidador', get_recurso("css")."perfil_cuidador.css", array(), "1.0.0" );
	wp_enqueue_style( 'perfil_cuidador_responsive_css', get_recurso("css")."responsive/perfil_cuidador.css", array(), "1.0.0" );

    wp_enqueue_script('comments', getTema()."/js/comment.js", array("jquery"), '1.0.0');
	wp_enqueue_script('perfil_cuidadores', get_recurso("js")."perfil_cuidador.js", array("jquery"), '1.0.0');
    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');

    wp_enqueue_style('conocer', getTema()."/css/conocer.css", array(), '1.0.0');
    wp_enqueue_style('conocer_responsive', getTema()."/css/responsive/conocer_responsive.css", array(), '1.0.0');

	global $wpdb;
	global $post;

	$ACCION_ADICIONAL = vlz_get_page();


	$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = {$post->post_author} ");

	// if( !isset($_SESSION["CUIDADORES_USER_ID"][ $post->post_author ]) ){
		pre_carga_data_cuidadores([
			$cuidador->id
		]);
	// }

	$_cuidador = $_SESSION["DATA_CUIDADORES"][ $_SESSION["CUIDADORES_USER_ID"][ $post->post_author ] ];
	

	$current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    if( is_user_logged_in() && $_SESSION["save_uso_banner"] ){
	    set_uso_banner([
    		"user_id" => $user_id
    	]);
    	unset($_SESSION["save_uso_banner"]);
    }

	if( $_cuidador->activo == 0 && $current_user->roles[0] != "administrator" ){
		header("location: ".get_home_url());
	}

	get_header();

   	wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
   	wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

	wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );

	$favoritos = get_favoritos();

	$fav_check = 'false';
    $fav_del = '';
    if (in_array($_cuidador->id_post, $favoritos)) {
        $fav_check = 'true'; 
        $favtitle_text = esc_html__('Quitar de mis favoritos','kmimos');
        $fav_del = 'favoritos_delete';
        $fav_img_pc = 'Favorito';
    }
    $favorito = '
    	<div 
    		class="favorito '.$fav_del.'" 
            data-user="'.$user_id.'" 
            data-num="'.$_cuidador->id_post.'" 
            data-active="'.$fav_check.'"
    	></div>
    ';

	$anios_exp = $_cuidador->experiencia;
    if( $anios_exp > 1900 ){
        $anios_exp = date("Y")-$anios_exp;
    }

	$tama_aceptados = unserialize( $cuidador->tamanos_aceptados );
	$tamanos = array(
		"pequenos" => "Peq",
		"medianos" => "Med",
		"grandes"  => "Gde",
		"gigantes" => "Gig"
	);

	$aceptados = array();
	foreach ($tama_aceptados as $key => $value) {
		if( $value == 1){
			$aceptados[] = $tamanos[$key];
		}
	} 

	$edad_aceptada = unserialize( $cuidador->edades_aceptadas );
	$edades = array(
		'cachorros' => 'Cachorros',
		'adultos' => 'Adultos'
	);
	$edades_aceptadas = array();
	foreach ($edad_aceptada as $key => $value) {
		if( $value == 1){
			$edades_aceptadas[] = $edades[$key];
		}
	} 

	$_galeria = get_galeria($cuidador->id);
	/*echo "<pre>";
		print_r($galeria);
	echo "</pre>";*/
	if( is_array($_galeria[1]) ){
		foreach ($_galeria[1] as $key => $value) {
			$galeria .=
			"<div class='pc_galeria_item' data-img='".get_home_url()."/wp-content/uploads/cuidadores/galerias/".$value."'>".
				"<div class='pc_galeria_img' style='background-image: url(".get_home_url()."/wp-content/uploads/cuidadores/galerias/".$value.");'></div>".
			"</div>";
		}
	}
	$ocultar_siguiente_img = ( is_array($_cuidador->galeria_normales) && count($_cuidador->galeria_normales) > 1 ) ? '': 'Ocultar_Flecha';

    $foto = kmimos_get_foto($cuidador->user_id);

    $desc = $wpdb->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$cuidador->user_id} AND meta_key = 'description'");
    $mas_info = '';
    if( strlen($desc) > 500 ){
		$mas_info = mb_strcut($desc, 500, NULL, "UTF-8");
		$desc = mb_strcut($desc, 0, 500, "UTF-8").'<span class="mas_info" data-info="'.$mas_info.'">...</span> <span class="ver_mas">Ver más</span>';
	}

	$mascota_cuidador = unserialize( $cuidador->mascotas_cuidador );
	$mascotas_cuidador = array();
	foreach ($mascota_cuidador as $key => $value) {
		if( $value == 1){
			$mascotas_cuidador[] = $tamanos[$key];
		}
	}

	$housings = array(
		'1' => 'Casa',
		'2' => 'Departamento'
	);

	$acepto = ""; $t = count($aceptados);
	if( $t > 0 && $t < 4 ){
		$acepto .= implode(', ',$aceptados);
	}else{
		if( $t == 0 ){
			$acepto = "Ninguno";
		}else{
			$acepto = "Todos";
		}
	}

	$num_masc = "";
	if($cuidador->num_mascotas+0 > 0){ 
		if( count($mascotas_cuidador) > 0 ){
			$tams = '<br>('.implode(', ',$mascotas_cuidador).')';
		}else{
			$tams = "";
		} 
		if( $cuidador->num_mascotas > 1 ){
			$num_masc = $cuidador->num_mascotas.' Perros '; // .$tams
		}else{
			$num_masc = $cuidador->num_mascotas.' Perro '; // .$tams
		}
	}else{
		$num_masc = 'No tiene mascotas';
	}
	$num_masc = ($num_masc);

	$patio = ( $_cuidador->atributos['yard'] == 1 ) ? 'Tiene patio' : 'No tengo';
	$areas = ( $_cuidador->atributos['green'] == 1 ) ? 'Tiene áreas verdes' : 'No tengo';

	if( $cuidador->mascotas_permitidas > 1 ){
		$cuidador->mascotas_permitidas .= ' Perros';
	}else{
		$cuidador->mascotas_permitidas .= ' Perro';
	}


	$tipos_servicios = get_servicios('principales');
	$tamanos_data = getTamanosData();

	$data_servicios = [];
	$servicios_ids = $wpdb->get_results("
		SELECT 
			p.ID,
			tipo_servicio.slug
		FROM 
			wp_posts AS p
		INNER JOIN wp_term_relationships AS relacion ON ( p.ID = relacion.object_id )
		INNER JOIN wp_terms AS tipo_servicio ON ( tipo_servicio.term_id = relacion.term_taxonomy_id AND relacion.term_taxonomy_id != 28 )
		WHERE 
			post_author = {$_cuidador->user_id} AND 
			post_type = 'product' AND 
			post_status = 'publish'
	");

	$temp_id_servicio = "";
	foreach ($servicios_ids as $key => $value) {
		$value->slug = str_replace("-", "_", $value->slug);
		if( $temp_id_servicio == "" ){
			$temp_id_servicio = $value->ID;
		}
		$data_servicios[ $value->slug ] = $value->ID;
	}

	$id_hospedaje = $data_servicios[ "hospedaje" ];
	if( $id_hospedaje == "" ){
		$id_hospedaje = $temp_id_servicio;
	}

	$servicios_str = "<div class='servicios_container'>";
		foreach ($_cuidador->adicionales as $servicio_id => $servicio) {
			if( array_key_exists($servicio_id, $tipos_servicios) ) {
				if( $servicio_id == "hospedaje" || $_cuidador->adicionales["status_".$servicio_id]+0 == 1 ){
					$precios = ''; $desde = 0;
					foreach ($servicio as $key => $value) {
						if( $key == "pequenos"){ $desde = $value; }
						if( $value > 0 && $desde > $value ){ $desde = $value; }
						if( $desde == 0 ){ $desde = $value; }
						if( $value > 0 ){
							$precios .= '
								<a class="servicio_tamanio" href="'.get_home_url().'/reservar/'.$data_servicios[ $servicio_id ].'/">
									<div class="servicio_table">
										<div class="servicio_celda servicio_icon">
											<img src="'.get_recurso("img").'GENERALES/ICONOS/TAMANIOS/'.$key.'.svg" />
										</div>
										<div class="servicio_celda servicio_titulo">
											<span>'.mb_strtolower($tamanos_data[$key][0], 'UTF-8').'</span>
											<small>'.$tamanos_data[$key][1].'</small>
										</div>
										<div class="servicio_celda servicio_precio">
											MXN $'.number_format( ($value*getComision()) , 2, ',', '.').'
										</div>
										<div class="servicio_celda">
											<img class="check" src="'.get_recurso("img").'HOME/SVG/Check.svg" />
										</div>
									</div>
								</a>
							';
						}
					}
					if( $desde > 0 ){
						$servicios_str .= '
						<div class="servicio_item_box">
							<div class="servicio_item">
								<div class="servicio_table">
									<div class="servicio_celda servicio_icon">
										<img src="'.get_recurso("img").'GENERALES/ICONOS/SERVICIOS_PRINCIPALES/'.$servicio_id.'.svg" />
									</div>
									<div class="servicio_celda servicio_titulo">
										<span>'.$tipos_servicios[$servicio_id][0].'</span>
										<small>'.$tipos_servicios[$servicio_id][1].'</small>
									</div>
									<div class="servicio_celda servicio_desde">
										<small>Desde</small>
										<span>MXN $'.number_format( ($desde*getComision()) , 2, ',', '.').'</span>
									</div>
								</div>
							</div>
							<div class="servicio_precios">
								'.$precios.'
							</div>
						</div>';
					}
				}
			}
		}
	$servicios_str .= "</div>";

	
	if(is_user_logged_in()){
		include('partes/seleccion_boton_reserva.php');

		$activo_hoy = get_cupos_by_user_id( $post->post_author );
		$activo_hoy = ( $activo_hoy == null ) ? true : false;

		$btn_conocer = '<a onclick="evento_google_kmimos(\'conocer_ficha\'); evento_fbq_kmimos(\'conocer_ficha\');" role="button" href="#" class="boton boton_border_gris"><strong>No disponible para conocer</strong></a>';
		if( $activo_hoy ){
			$btn_conocer = '
				<a  role="button" href="#" 
					id="btn_conocer"
		            data-target="#popup-conoce-cuidador"
		            data-name="'.strtoupper( get_the_title() ).'" 
		            data-id="'.$cuidador->id_post.'"
					class="boton boton_border_gris" 
					onclick="evento_google_kmimos(\'conocer_ficha\'); evento_fbq_kmimos(\'conocer_ficha\');"
				>CON&Oacute;CELO +</a>
			';
		}

		$BOTON_RESERVAR = $btn_conocer.$BOTON_RESERVAR;
	}else{
		$BOTON_RESERVAR .= '
			<a  
				href="#" 
				data-target="#popup-iniciar-sesion" 
				role="button" 
				data-toggle="modal"
				class="boton boton_border_gris" 
				onclick="jQuery(\'#proceso\').val(\'conocer\'); evento_google_kmimos(\'conocer_ficha\'); evento_fbq_kmimos(\'conocer_ficha\');"
			>CON&Oacute;CELO +</a>
			<a
				href="#" 
				data-target="#popup-iniciar-sesion" 
				role="button" 
				data-toggle="modal"
				class="boton boton_verde" 
				onclick="jQuery(\'#proceso\').val(\'reservar\'); evento_google_kmimos(\'reservar_ficha\'); evento_fbq_kmimos(\'reservar_ficha\');"
			>RESERVAR</a>
		';
	}
    include ('partes/cuidador/conocelo.php');

    $_galeria = $galeria;
    $galeria = '';

    if( $cuidador->mascotas_permitidas > 6 ){
    	$cuidador->mascotas_permitidas = 6;
    }

    $txt_iconos = "";

	$ocultar_flash = "ocultar_flash";
	$ocultar_flash_none = "ocultar_flash_none";
	$ocultar_descuento = "ocultar_descuento";
	$ocultar_geo = "ocultar_geo";

	if( $_cuidador->atributos["flash"] == 1 ){
		$ocultar_flash = "";
		$ocultar_flash_none = "";
		$txt_iconos = "Disponible";
	}
	if( $_cuidador->atributos["destacado"]+0 == 1 ){
		$ocultar_descuento = "";
		$txt_iconos = "Descuento";
	}
	if( $_cuidador->atributos["geo"]+0 == 1 ){
		$ocultar_geo = "";
		$txt_iconos = "con GPS";
	}

	$ocultar_todo = "";
	if( $ocultar_flash != "" && $ocultar_descuento != "" && $ocultar_geo != "" ){
		$ocultar_todo = "ocultar_flash_descuento";
	}

	$_cuidador->estados = explode("=", $_cuidador->estados);
	$_cuidador->municipios = explode("=", $_cuidador->municipios);

	$mun = $wpdb->get_var("SELECT iso FROM states WHERE id = {$_cuidador->estados[1]}");
	$est = $wpdb->get_var("SELECT name FROM locations WHERE id = {$_cuidador->municipios[1]}");

	$est = htmlentities( utf8_decode($est) );
	$ubicacion = $est.', '. ucfirst( strtolower( $mun ) );

	echo "<pre>";
		print_r( $busqueda );
	echo "</pre>";

 	$HTML .= '
 		<script> 
			var lat = "'.$cuidador->latitud.'";
			var lng = "'.$cuidador->longitud.'";
 			var SERVICIO_ID = "'.$cuidador->id_post.'"; 
 			var GALERIA = "'.$_galeria.'";
 		</script>

 		<div class="pc_seccion_0" style="background-image:url('.getTema().'/images/new/km-ficha/km-bg-ficha.jpg);">
			<div class="overlay"></div>
		</div>

		<div class="solo_movil info_movil_1">

			<div style="position: relative;" data-total="'.(count($_cuidador->galeria)).'" data-actual="0" data-paso="4">
				<div class="pc_galeria_container_interno">
					<div class="pc_galeria_box">
						<div class="perfil_cuidador_cargando">
							<div style="background-image: url('.getTema().'/images/cargando.gif);" ></div> Cangando Galer&iacute;a...
						</div>
					</div>
				</div>
				<img onclick="imgAnterior( jQuery(this) );" class="Flechas Flecha_Izquierda Ocultar_Flecha" src="'.get_recurso("img").'PERFIL_CUIDADOR/Flecha_2.svg" />
				<img onclick="imgSiguiente( jQuery(this) );" class="Flechas Flecha_Derecha '.$ocultar_siguiente_img.'" src="'.get_recurso("img").'PERFIL_CUIDADOR/Flecha_1.svg" />
			</div>

			<div class="pc_seccion_1">
				<div class="pc_img_container">
					<div class="pc_img" data-img="'.$foto.'" style="background-image:url('.$foto.');"></div>
				</div>
				<div class="pc_info_container">
					<div class="pc_info_titulo">'.strtoupper( get_the_title() ).'</div>
					<div class="pc_info_experiencia">
						'.$anios_exp.' años de experiencia
					</div>
					<div class="pc_info_precio">
						Desde MXN $ '.number_format( ($_cuidador->hospedaje_desde*getComision()) , 2, ',', '.').'
					</div>
					<div class="pc_info_ranking">
						'.kmimos_petsitter_rating($_cuidador->id_post).'
					</div>
					<div class="pc_info_valoraciones">
						'.$_cuidador->valoraciones.' valoraciones <a href="#km-comentario">(Ver comentarios)</a>
					</div>
					<div class="pc_info_favorito favorito_replicas">
						'.$favorito.'
					</div>
				</div>
			</div>
			<div class="pc_info_iconos_container '.$ocultar_todo.'">
				<div class="pc_info_iconos icono_disponibilidad">
					<span>'.$txt_iconos.'</span>
				</div>
				<div class="pc_info_iconos icono_flash '.$ocultar_flash_none.'"><span></span></div>
				<div class="pc_info_iconos icono_descuento '.$ocultar_descuento.'"><span></span></div>
				<div class="pc_info_iconos icono_geo '.$ocultar_geo.'"><span></span></div>
			</div>

			<hr style="margin: 20px 15px;">

		</div>

		<div class="solo_movil info_movil_2">
			<label>Servicios que ofrezco</label>
			<div>
				'.$servicios_str.'
			</div>
		</div>

		<div class="solo_pc pc_seccion_1_container">
			<div class="pc_seccion_1">
				<div class="pc_img_container">
					<div class="pc_img" data-img="'.$foto.'" style="background-image:url('.$foto.');"></div>

					<div class="pc_info_iconos_container '.$ocultar_todo.'">
						<div class="pc_info_iconos icono_disponibilidad">
							<span>'.$txt_iconos.'</span>
						</div>
						<div class="pc_info_iconos icono_flash '.$ocultar_flash_none.'"><span></span></div>
						<div class="pc_info_iconos icono_descuento '.$ocultar_descuento.'"><span></span></div>
						<div class="pc_info_iconos icono_geo '.$ocultar_geo.'"><span></span></div>
					</div>

				</div>
				<div class="pc_info_container">
					<div class="pc_info_titulo">'.strtoupper( get_the_title() ).'</div>
					<div class="pc_info_experiencia">
						'.$anios_exp.' años de experiencia
					</div>
					<div class="pc_info_ranking">
						'.kmimos_petsitter_rating($_cuidador->id_post).'
					</div>
					<div class="pc_info_valoraciones">
						'.$_cuidador->valoraciones.' valoraciones <a href="#km-comentario">(Ver comentarios)</a>
					</div>
					<div class="pc_info_favorito favorito_replicas">
						'.$favorito.'
					</div>
				</div>
				<div class="pc_galeria_container">
					<div style="position: relative; max-width: 390px; display: inline-block; width: 100%;" data-total="'.(count($_cuidador->galeria)).'" data-actual="0" data-paso="5">
						<div class="pc_galeria_container_interno">
							<div class="pc_galeria_box">
								<div class="perfil_cuidador_cargando">
									<div style="background-image: url('.getTema().'/images/cargando.gif);" ></div> Cangando Galer&iacute;a...
								</div>
							</div>
						</div>
						<img onclick="imgAnterior( jQuery(this) );" class="Flechas Flecha_Izquierda Ocultar_Flecha" src="'.get_recurso("img").'PERFIL_CUIDADOR/Flecha_2.svg" />
						<img onclick="imgSiguiente( jQuery(this) );" class="Flechas Flecha_Derecha '.$ocultar_siguiente_img.'" src="'.get_recurso("img").'PERFIL_CUIDADOR/Flecha_1.svg" />
					</div>
				</div>
			</div>
		</div>

		<div class="pc_seccion_2_container">
			<div class="pc_seccion_2">

				<div class="pc_seccion_2_izq">

					<div class="pc_scroll">

						<div class="solo_pc">
							<label>Acerca de</label>
							<p>
								'.$desc.'
							</p>
						</div>

						<label>Datos del cuidador</label>
						<div class="pc_seccion_2_datos">
							<div class="pc_seccion_2_datos_item">
								<div style="background-image: url( '.get_recurso("img").'PERFIL_CUIDADOR/Experiencia.svg )"></div>
								<span>Experiencia<br>'.($anios_exp+1).' años</span>
							</div>
							<div class="pc_seccion_2_datos_item">
								<div style="background-image: url( '.get_recurso("img").'PERFIL_CUIDADOR/Propiedad.svg )"></div>
								<span>Tipo de propiedad<br>&nbsp;'.$housings[ $_cuidador->atributos['propiedad'] ].'</span>
							</div>
							<div class="pc_seccion_2_datos_item">
								<div style="background-image: url( '.get_recurso("img").'PERFIL_CUIDADOR/Tamanios.svg )"></div>
								<span>Tam. aceptados<br>&nbsp;'.$acepto.'</span>
							</div>
							<div class="pc_seccion_2_datos_item">
								<div style="background-image: url( '.get_recurso("img").'PERFIL_CUIDADOR/Edades.svg )"></div>
								<span>Edades aceptadas<br>&nbsp;'.implode(', ',$edades_aceptadas).'</span>
							</div>
						</div>

						<label>Datos de propiedad</label>
						<div class="pc_seccion_2_datos pc_seccion_2_propiedad">
							<div class="pc_seccion_2_datos_item">
								<div style="background-image: url( '.get_recurso("img").'PERFIL_CUIDADOR/Mascotas.svg )"></div>
								<span>Mascotas en casa<br>&nbsp;'.$num_masc.'</span>
							</div>
							<div class="pc_seccion_2_datos_item">
								<div style="background-image: url( '.get_recurso("img").'PERFIL_CUIDADOR/Patio.svg )"></div>
								<span>Detalles de prop.<br>&nbsp;'.$patio.'</span>
							</div>
							<div class="pc_seccion_2_datos_item">
								<div style="background-image: url( '.get_recurso("img").'PERFIL_CUIDADOR/Areas_Verdes.svg )"></div>
								<span>Detalles de prop.<br>&nbsp;'.$areas.'</span>
							</div>
							<div class="pc_seccion_2_datos_item">
								<div style="background-image: url( '.get_recurso("img").'PERFIL_CUIDADOR/Monto_maximo.svg )"></div>
								<span>Monto máx. acep.<br>&nbsp;'.$cuidador->mascotas_permitidas.'</span>
							</div>
						</div>

						<div class="solo_movil">
							<label>Acerca de</label>
							<p>
								'.$desc.'
							</p>

							<label>Ubicación</label>
							<div class="mapa">
								<div id="mapa_movil"></div>
							</div>
						</div>

					</div>

				</div>

				<div class="pc_seccion_2_cen">

					<div class="pc_scroll">

						<div class="solo_pc">
							<label>Servicios que ofrezco</label>
							<div> '.$servicios_str.' </div>
						</div>

						<div>
							<div id="km-comentario" class="km-ficha-info">
								<div class="km-review">
									<div class="km-calificacion">0</div>
									<p class="km-tit-ficha">comentarios</p>
									<div class="km-calificacion-icono">
										<div class="km-calificacion-bondx">
											'.kmimos_petsitter_rating($cuidador->id_post).'
										</div>
										<p>0% Lo recomienda</p>
									</div>
								</div>
								<a href="javascript:;" class="km-btn-comentario" >ESCRIBE UN COMENTARIO</a>
								<label>Comentarios</label>
								<div class="BoxComment">';
								echo comprimir($HTML);
								comments_template('/template/comment.php'); $HTML = '</div>
								<div id="comentarios_box"> </div>
							</div>
						</div>

					</div>

				</div>

				<div class="pc_seccion_2_der">

					<div class="pc_scroll pc_scroll_der">

						<span>Servicios desde</span>
						<label>MXN $ '.number_format( ($_cuidador->hospedaje_desde*getComision()) , 2, ',', '.').'</label>
						<form class="fechas_container" id="form_cuidador" method="POST" action="'.getTema().'/procesos/reservar/redirigir_reserva.php">
							<div id="desde_container" class="fechas_box">
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/SVG/Fecha.svg" />
								<input type="text" id="checkin" name="checkin" placeholder="Desde" class="date_from" value="'.$_SESSION['busqueda']['checkin'].'" readonly>
								<small class="">Requerido</small>
							</div>
							<div id="hasta_container" class="fechas_box">
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/SVG/Fecha.svg" />
								<input type="text" id="checkout" name="checkout" placeholder="Hasta" class="date_to" value="'.$_SESSION['busqueda']['checkout'].'" readonly>
								<small class="">Requerido</small>
							</div>

							'.$BOTON_RESERVAR.'

							<div class="solo_movil">
								<div class="reservar_footer">
									<div id="btn_reservar_fixed">
										<img src="'.get_recurso("img").'PERFIL_CUIDADOR/RESERVAR.svg" /> RESERVAR >
									</div>
									<div class="footer_favorito">
										<div class="footer_favorito_box favorito_replicas">'.$favorito.'</div>
									</div>
								</div>
							</div>

						</form>

						<div class="solo_pc">
							<label>Ubicación</label>
							<div class="mapa">
								<div id="mapa"></div>
								<a href="#" style="display: none;">Expandir mapa</a>
							</div>
						</div>

					</div>

				</div>

			</div>
		</div>

		<div class="galeria_container_fixed">
			<div class="galeria_celda">
				<img src="http://localhost/kmimos/wp-content/uploads/cuidadores/galerias//611/1.jpg">
				<span id="cerrar_galeria" class="cerrar">×</span>
			</div>
		</div>
 	';

 	if( isset($_GET["ldg"]) ){
 		$HTML .= '
	 		<div class="modal_msg">
				<div class="modal_msg_container">
					<div class="modal_msg_box">
						Este cuidador pertenece a la zona de <strong>'.$ubicacion.'</strong>. Si el Cuidador no pertenece a tu Ciudad o si deseas encontrar uno más cercano, puedes usar nuestra nueva función de filtros perzonalizados.
						<div class="btn_container">
							<a href="'.get_home_url().'/personalizada" class="boton boton_verde">Ir a los filtros personalizados</a>
							<a id="ocultar_msg" href="#" class="boton boton_borde_verde">Ignorar este mensaje</a>
						</div>
					</div>
				</div>
			</div>
		';
 	}

	if( $_SESSION["wlabel"] == "petco" ){
		$HTML .= "
			<script type='text/javascript' src='https://a2.adform.net/serving/scripts/trackpoint/'></script>
		";
	}

	if( $ACCION_ADICIONAL == 1 ){
		$HTML .= "
			<script> jQuery(document).ready(function() {  jQuery('#btn_conocer').click(); }); </script>
		";
	}

	echo comprimir($HTML);

	/*
	echo "<pre>";
		print_r($_SESSION);
	echo "</pre>";
	*/

	get_footer();

	if( $_SESSION['sesion_proceso'] != "" ){
		$HTML = '<script>';
		switch ( $_SESSION['sesion_proceso'] ) {
			case 'conocer':
				$HTML .= 'jQuery("#btn_conocer").click();';
			break;
			case 'reservar':
				$HTML .= 'jQuery("#servicios").click();';
				$HTML .= 'jQuery("#btn_reservar").click();';
			break;
		}
		$HTML .= '</script>';
		echo comprimir($HTML);
	}
	$_SESSION['sesion_proceso'] = "";
?>