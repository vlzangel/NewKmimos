<?php 
    /*
        Template Name: Busqueda
    */

    wp_enqueue_style('busqueda', get_recurso("css")."busqueda.css", array(), '1.0.0');
    wp_enqueue_style('busqueda_responsive', get_recurso("css")."responsive/busqueda.css", array(), '1.0.0');

    wp_enqueue_style('conocer', getTema()."/css/conocer.css", array(), '1.0.0');
    wp_enqueue_style('conocer_responsive', getTema()."/css/responsive/conocer_responsive.css", array(), '1.0.0');

	wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');

    wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

    wp_enqueue_script('markerclusterer_js', getTema()."/js/markerclusterer.js", array("jquery"), '1.0.0');
    wp_enqueue_script('oms_js', getTema()."/js/oms.min.js", array("jquery"), '1.0.0');

	wp_enqueue_script('buscar_home', get_recurso("js")."busqueda.js", array(), '1.0.0');
    wp_enqueue_script('select_localidad', getTema()."/js/select_localidad.js", array(), '1.0.0');
    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');

    get_header();

    $user_id = get_current_user_id();

    /* FILTROS */

	    $tam = getTamanos();
	    foreach ($tam as $key => $value) {
	    	$check = ( is_array($_SESSION['busqueda']['tamanos']) && in_array($key, $_SESSION['busqueda']['tamanos']) ) ? 'checked': '';
	    	$tam[ $key ] = $check;
	    }

	    $tipos = ['perros' => '', 'gatos' => ''];
	    foreach ($tipos as $key => $value) {
	    	$check = ( is_array($_SESSION['busqueda']['mascotas']) && in_array($key, $_SESSION['busqueda']['mascotas']) ) ? 'checked': '';
	    	$tipos[ $key ] = $check;
	    }

	    $servicios = [];
	    if( is_array($_SESSION['busqueda']['servicios']) ){
	    	foreach ( $_SESSION['busqueda']['servicios'] as $key => $value) {
	    		$servicios[$value] = 'checked';
	    	}
	    }

	/* DESTACADOS */

		if( !isset($_SESSION["DATA_CUIDADORES"]) ){
			$_temp = pre_carga_data_cuidadores();
			$_SESSION["DATA_CUIDADORES"] = $_temp[0];
			$_SESSION["CUIDADORES_USER_ID"] = $_temp[1];
		}
		
		$ordenamientos = array(
	    	'rating_desc' => array(
	    		'Valoración (mayor a menor)',
	    		'rating_desc'
	    	),
			'rating_asc' => array(
				'Valoración (De menor a mayor)',
				'rating_asc'
			),
		/*	'distance_asc' => array(
				'Distancia al cuidador (De cerca a lejos)',
				'distance_asc'
			),
			'distance_desc' => array(
				'Distancia al cuidador (De lejos a cerca)',
				'distance_desc'
			),*/
			'price_asc' => array(
				'Precio del Servicio (De menor a mayor)',
				'price_asc'
			),
			'price_desc' => array(
				'Precio del Servicio (De mayor a menor)',
				'price_desc'
			),
			'experience_desc' => array(
				'Experiencia (De menos a más años)',
				'experience_desc'
			),
			'experience_asc' => array(
				'Experiencia (De más a menos años)',
				'experience_asc'
			)
	    );

	    $titulo_ordenamiento = "ORDENAR POR";
	    if( $_POST['orderby'] != "" ){
	    	$titulo_ordenamiento = $ordenamientos[ $_POST['orderby'] ][0];
	    }
	    $ordenamiento = "";
	    foreach ( $ordenamientos as $clave => $valor ) {
	    	$check = ( $_SESSION["busqueda"]["orderby"] == $valor[1] ) ? "selected": "";
	    	$ordenamiento .= '<option value="'.$valor[1].'">'.$valor[0].'</option>';
	    }

	    $check_descuento = ( $_SESSION["busqueda"]["descuento"] == 1 ) ? "checked": "";
	    $check_flash = ( $_SESSION["busqueda"]["flash"] == 1 ) ? "checked": "";

	/* PRINCIPALES */

		$servicios_principales = get_servicios("principales");
		$servicios_principales_hospedaje_str = '';
		$servicios_principales_str = '';
		foreach ($servicios_principales as $key => $servicio) {
			$_checked = ( is_array($_SESSION["busqueda"]["servicios"]) && in_array($key, $_SESSION["busqueda"]["servicios"]) ) ? "checked" : "";
			$_temp = '
				<label class="input_check_box" for="'.$key.'">
					<input type="checkbox" id="'.$key.'" name="servicios[]" value="'.$key.'" '.$_checked.' />
					<div class="principales_img prin_icon_'.$key.'"></div>
					<div class="principales_info">
						<div>'.$servicio[0].'</div>
						<span>'.$servicio[1].'</span>
					</div>
				</label>
			';
			if( $key == "hospedaje" ){
				$servicios_principales_hospedaje_str = $_temp;
			}else{
				$servicios_principales_str[] = $_temp;
			}
		}

    include ('partes/cuidador/conocelo.php');

    $HTML = '
    	<div class="busqueda_container">
    		<div class="filtos_container">

    			<div class="cerrar_filtros_movil"> x </div>
    			
    			<form id="buscar" action="'.getTema().'/procesos/busqueda/buscar.php" method="POST">

					<input type="hidden" name="USER_ID" value="'.$user_id.'" />

					<div class="filtros_generales_container">
						<label class="filtro_check check_descuento" for="descuento" >
							<input type="checkbox" id="descuento" name="descuento" value="1" '.$check_descuento.' />
							<div class="check_icon"></div>
							<div>con descuento</div>
							<div class="check_control"></div>
						</label>
						<label class="filtro_check check_disponibilidad" for="flash" >
							<input type="checkbox" id="flash" name="flash" value="1" '.$check_flash.' />
							<div class="check_icon"></div>
							<div>reserva inmediata</div>
							<div class="check_control"></div>
						</label>
					</div>

					<div class="principales_container">
						<i class="fa fa-caret-down" aria-hidden="true"></i>
						'.$servicios_principales_hospedaje_str.'
						<div class="principales_box">
							'.implode('', $servicios_principales_str).'
						</div>
					</div>

					<div class="ubicacion_container">
						<img class="ubicacion_localizacion" src="'.get_recurso("img").'BUSQUEDA/SVG/Localizacion_2.svg" />
						<input type="text" class="ubicacion_txt" name="ubicacion_txt" value="'.$_SESSION['busqueda']['ubicacion_txt'].'" placeholder="Ubicación estado municipio" autocomplete="off" />
						<input type="hidden" class="ubicacion" name="ubicacion" value="'.$_SESSION['busqueda']['ubicacion'].'" />	
					    <div class="cerrar_list_box">
					    	<div class="cerrar_list">X</div>
					    	<ul class="ubicacion_list"></ul>
					    </div>

						<i id="_mi_ubicacion" class="fa icon_left ubicacion_gps"></i>
						<img class="mi_ubicacion" src="'.get_recurso("img").'HOME/SVG/GPS_Off.svg" />

						<div class="barra_ubicacion"></div>
						<small class="hidden" data-error="ubicacion">Función disponible solo en México</small>

						<input type="hidden" class="latitud" name="latitud" value="'.$_SESSION['busqueda']['latitud'].'" />
						<input type="hidden" class="longitud" name="longitud" value="'.$_SESSION['busqueda']['longitud'].'" />

					</div>
					<div class="fechas_container">
						<div id="desde_container">
							<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/SVG/Fecha.svg" />
							<input type="text" id="checkin" name="checkin" placeholder="Desde" class="date_from" value="'.$_SESSION['busqueda']['checkin'].'" readonly>
							<small class="">Requerido</small>
						</div>
						<div>
							<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/SVG/Fecha.svg" />
							<input type="text" id="checkout" name="checkout" placeholder="Hasta" class="date_to" value="'.$_SESSION['busqueda']['checkout'].'" readonly>
							<small class="">Requerido</small>
						</div>
					</div>
					<div class="tipo_mascota_container">
						<label class="input_check_box" for="perro">
							<input type="checkbox" id="perro" name="mascotas[]" value="perros" '.$tipos['perros'].' />
							<span>
								<div class="tam_label_pc">Perro</div>
							</span>
							<div class="top_check"></div>
						</label>
						<label class="input_check_box" for="gato">
							<input type="checkbox" id="gato" name="mascotas[]" value="gatos" '.$tipos['gatos'].' />
							<span>
								<div class="tam_label_pc">Gato</div>
							</span>
							<div class="top_check"></div>
						</label>
					</div>
					<div class="tamanios_container">
						<label class="input_check_box" for="paqueno">
							<input type="checkbox" id="paqueno" name="tamanos[]" value="pequenos" '.$tam['pequenos'].' />
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/RESPONSIVE/SVG/Pequenio.svg" />
								<div class="tam_label_pc">Peq.</div>
								<small>0 a 25 cm</small>
							</span>
							<div class="top_check"></div>
						</label>
						<label class="input_check_box" for="mediano">
							<input type="checkbox" id="mediano" name="tamanos[]" value="medianos" '.$tam['medianos'].' />
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/RESPONSIVE/SVG/Mediano.svg" />
								<div class="tam_label_pc">Med.</div>
								<small>25 a 58 cm</small>
							</span>
							<div class="top_check"></div>
						</label>
					</div>
					<div class="tamanios_container tamanios_container_derecho">
						<label class="input_check_box" for="grande">
							<input type="checkbox" id="grande" name="tamanos[]" value="grandes" '.$tam['grandes'].' />
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/RESPONSIVE/SVG/Grande.svg" />
								<div class="tam_label_pc">Gde</div>
								<small>58 a 73 cm</small>
							</span>
							<div class="top_check"></div>
						</label>
						<label class="input_check_box" for="gigante">
							<input type="checkbox" id="gigante" name="tamanos[]" value="gigantes" '.$tam['gigantes'].' />
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/RESPONSIVE/SVG/Gigante.svg" />
								<div class="tam_label_pc">Gte.</div>
								<small>73 a 200 cm</small>
							</span>
							<div class="top_check"></div>
						</label>
					</div>
					<div>
						<input type="text" name="nombre" placeholder="Buscar Cuidador por Nombre" class="input" value="'.$_SESSION['busqueda']['nombre'].'" />
					</div>
					<div class="adicionales_container">
						<label class="input_check_box" for="corte">
							<input type="checkbox" id="corte" name="servicios[]" value="corte" '.$servicios['corte'].' />
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/SVG/servicios/AZUL/Corte.svg" />
								<small>CORTE DE PELO Y UÑAS</small>
							</span>
							<div class="top_check"></div>
						</label>
						<label class="input_check_box" for="bano">
							<input type="checkbox" id="bano" name="servicios[]" value="bano" '.$servicios['bano'].' />
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/SVG/servicios/AZUL/Banio.svg" />
								<small>BAÑO Y SECADO</small>
							</span>
							<div class="top_check"></div>
						</label>
						<label class="input_check_box" for="limpieza_dental">
							<input type="checkbox" id="limpieza_dental" name="servicios[]" value="limpieza_dental" '.$servicios['limpieza_dental'].' />
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/SVG/servicios/AZUL/Dental.svg" />
								<small>LIMPIEZA DENTAL</small>
							</span>
							<div class="top_check"></div>
						</label>
						<label class="input_check_box" for="visita_al_veterinario">
							<input type="checkbox" id="visita_al_veterinario" name="servicios[]" value="visita_al_veterinario" '.$servicios['visita_al_veterinario'].' />
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/SVG/servicios/AZUL/Veterinario.svg" />
								<small>VISITA AL VETERINARIO</small>
							</span>
							<div class="top_check"></div>
						</label>
						<label class="input_check_box" for="acupuntura">
							<input type="checkbox" id="acupuntura" name="servicios[]" value="acupuntura" '.$servicios['acupuntura'].' />
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/SVG/servicios/AZUL/Acupuntura.svg" />
								<small>ACUPUNTURA</small>
							</span>
							<div class="top_check"></div>
						</label>
						<label class="input_check_box" for="transportacion_sencilla">
							<input type="checkbox" id="transportacion_sencilla" name="servicios[]" value="transportacion_sencilla" '.$servicios['transportacion_sencilla'].' />
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/SVG/servicios/AZUL/Trans_Sencillo.svg" />
								<small>TRANSPORTE SENCILLO</small>
							</span>
							<div class="top_check"></div>
						</label>
						<label class="input_check_box" for="transportacion_redonda">
							<input type="checkbox" id="transportacion_redonda" name="servicios[]" value="transportacion_redonda" '.$servicios['transportacion_redonda'].' />
							<span>
								<img class="icon_fecha" src="'.get_recurso("img").'BUSQUEDA/SVG/servicios/AZUL/Trans_Redondo.svg" />
								<small>TRANSPORTE REDONDO</small>
							</span>
							<div class="top_check"></div>
						</label>
					</div>

					<select name="orderby" class="filtros_ordenamiento">
						'.$ordenamiento.'
					</select>

					<div class="filtros_botones">
						<input type="reset" class="boton" value="Limpiar" />
						<input type="submit" class="boton boton_verde" value="Buscar" />
					</div>

					<!-- <a href="#" class="mas_filtros">Más filtros</a> -->

    			</form>

    		</div>
    		
			<div class="cerrar_filtros_movil_panel"></div>
    		
    		<div class="resultados_container">

    			<form id="buscar_2" action="'.getTema().'/procesos/busqueda/buscar.php" method="POST">
    				<input type="hidden" name="USER_ID" value="'.$user_id.'" />
					<div class="ubicacion_container">
						<img class="ubicacion_localizacion" src="'.get_recurso("img").'BUSQUEDA/SVG/Localizacion.svg" />
						<input type="text" class="ubicacion_txt" class="ubicacion_txt" name="ubicacion_txt" value="'.$_SESSION['busqueda']['ubicacion_txt'].'" placeholder="Ubicación estado municipio" autocomplete="off" />
						<input type="hidden" class="ubicacion" class="ubicacion" name="ubicacion" value="'.$_SESSION['busqueda']['ubicacion'].'" />	
					    <div class="cerrar_list_box">
					    	<div class="cerrar_list">X</div>
					    	<ul class="ubicacion_list"></ul>
					    </div>
						<i class="fa fa-crosshairs icon_left ubicacion_gps mi_ubicacion"></i>
						<div class="barra_ubicacion"></div>
						<small class="hidden" data-error="ubicacion">Función disponible solo en México</small>
						<input type="hidden" class="latitud" name="latitud" value="'.$_SESSION['busqueda']['latitud'].'" />
						<input type="hidden" class="longitud" name="longitud" value="'.$_SESSION['busqueda']['longitud'].'" />
					</div>

					<div class="filtros_movil_table">
						<div class="filtros_movil_cell">

							<label class="filtro_check check_descuento" for="descuento_movil" >
								<input type="checkbox" id="descuento_movil" name="descuento" '.$check_descuento.' />
								<div class="check_icon"></div>
								<div>con descuento</div>
								<div class="check_control"></div>
							</label>
							<label class="filtro_check check_disponibilidad" for="flash_movil" >
								<input type="checkbox" id="flash_movil" name="flash" '.$check_flash.' />
								<div class="check_icon"></div>
								<div>reserva inmediata</div>
								<div class="check_control"></div>
							</label>

						</div>
						<div class="filtros_movil_cell filtros_movil">

							<div id="ver_filtros_fechas" class="boton boton_border_gris boton_block"> 
								<!-- '.$_SESSION['busqueda']['checkin'].' - '.$_SESSION['busqueda']['checkout'].' -->
								FILTROS
							</div>
							<div id="ver_filtros" class="boton boton_border_gris">
								<img src="'.get_recurso("img").'BUSQUEDA/RESPONSIVE/SVG/Ver_fichas.svg" />
							</div>
							<div id="ver_mapa" class="boton boton_border_gris">
								<img src="'.get_recurso("img").'BUSQUEDA/RESPONSIVE/SVG/Ver_mapa.svg" />
							</div>

						</div>
					</div>
    			</form>

    			<div class="mesaje_reserva_inmediata_container disponibilidad_PC">
    				<div class="mesaje_reserva_inmediata_izq"></div>
    				<div class="mesaje_reserva_inmediata_der">
    					<strong>Tu reserva comienza en 3 días.</strong> Utiliza el filtro de reserva inmediata en la sección de <strong>filtros</strong> que aparece a tu izquierda, para encontrar cuidadores con los que puedas reservar al momento.
    				</div>
    			</div>

    			<div id="seccion_destacados">
    				'.$destacados.'
    			</div>

    			<div class="mesaje_reserva_inmediata_container disponibilidad_MOVIl">
    				<div class="mesaje_reserva_inmediata_izq"></div>
    				<div class="mesaje_reserva_inmediata_der">
    					<strong>Tu reserva comienza en 3 días.</strong> Utiliza el filtro de reserva inmediata en la sección de <strong>filtros</strong> que aparece aquí arriba a tu izquierda, para encontrar cuidadores con los que puedas reservar al momento.
    				</div>
    			</div>
    			
    			<div class="cantidad_resultados_container">
    				<div class="disponibilidad_PC">Hay <strong><span>'.count($_SESSION['resultado_busqueda']).'</span> cuidadores</strong> cerca de ti, con las características que necesitas.</div>
    				<div class="disponibilidad_MOVIl"><strong>Resultado de búsqueda,</strong> <span>'.count($_SESSION['resultado_busqueda']).'</span> cuidadores disponibles</div>
    			</div>
    			<div class="resultados_box">

    				<div class="resultados_box_interno">'.$resultados.'</div>

    				<div class="cargando_mas_resultados">
	    				<i class="fa fa-spinner fa-spin"></i>
	    			</div>

    				<div class="paginacion_container"></div>
    			</div>
    		</div>
    		
    		<div class="mapa_container">
    			<div class="cerrar_mapa_movil"> x </div>
    			<label>
    				Actualizar al mover en el mapa 
    				<input type="checkbox" id="update_to_move" />
    			</label>
    			<div id="mapa"></div>
    		</div>

    	</div>

    	<div class="cargando_mas_resultados_externo">
			<i class="fa fa-spinner fa-spin"></i>
		</div>
    ';

    echo comprimir( $HTML );
    
   	get_footer(); 

?>