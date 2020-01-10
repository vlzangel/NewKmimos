<?php
	
	function get_menu(){
		$defaults = array(
		    'theme_location'  => 'pointfinder-main-menu',
		    'menu'            => '',
		    'container'       => '',
		    'container_class' => '',
		    'container_id'    => '',
		    'menu_class'      => '',
		    'menu_id'         => '',
		    'echo'            => false,
		    'fallback_cb'     => 'wp_page_menu',
		    'before'          => '',
		    'after'           => '',
		    'link_before'     => '',
		    'link_after'      => '',
		    'items_wrap'      => '%3$s',
		    'depth'           => 0
		);
		return wp_nav_menu( $defaults );
	}

	function getBusqueda(){
		if( !isset($_SESSION) ){ session_start(); }
		$busqueda = array();
		if( isset($_SESSION["busqueda"]) ){
			$busqueda = ($_SESSION["busqueda"]);
		}
		return $busqueda;
	}

	if(!function_exists('_get_destacados')){
        function _get_destacados($estado){
            global $wpdb;
            $estado_des = $wpdb->get_var("SELECT name FROM states WHERE id = ".$estado);
            $sql_top = "SELECT * FROM destacados WHERE estado = '{$estado}'";
            $tops = $wpdb->get_results($sql_top);
            $top_destacados = ""; $cont = 0;
            foreach ($tops as $value) {
                $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$value->cuidador}");
                $data = $wpdb->get_row("SELECT post_title AS nom, post_name AS url FROM wp_posts WHERE ID = {$cuidador->id_post}");
                $nombre = $data->nom;
                $img_url = kmimos_get_foto_cuidador($value->cuidador);
                $url = get_home_url() . "/petsitters/" . $data->url;
                $top_destacados .= "
                    <a class='vlz_destacados_contenedor' href='{$url}'>
                        <div class='vlz_destacados_contenedor_interno'>
                            <div class='vlz_destacados_img'>
                                <div class='vlz_descado_img_fondo' style='background-image: url({$img_url});'></div>
                                <div class='vlz_descado_img_normal' style='background-image: url({$img_url});'></div>
                                <div class='vlz_destacados_precio'><sub style='bottom: 0px;'>Hospedaje desde</sub><br>MXN $".($cuidador->hospedaje_desde*getComision() )."</div>
                            </div>
                            <div class='vlz_destacados_data' >
                                <div class='vlz_destacados_nombre'>{$nombre}</div>
                                <div class='vlz_destacados_adicionales'>".vlz_servicios($cuidador->adicionales)."</div>
                            </div>
                        </div>
                    </a>
                ";
                $cont++;
            }
            if( $cont > 0 ){
                if( $top_destacados != '' ){
                    $top_destacados = $top_destacados."</div>"; 
                }
                $top_destacados = utf8_decode( '<div class="pfwidgettitle"> <div class="widgetheader">Destacados Kmimos en: '.$estado_des.' '.$municipio_des.'</div> </div> <div class="row" style="margin: 10px auto 20px;">').$top_destacados;
            }
            return comprimir_styles($top_destacados);
        }
    }

	function get_servicio_cuidador($slug){
		switch ($slug) {
			case 'hospedaje':
				return '
					<div class="servicio-tit">
						<img src="'.getTema().'/images/new/icon/km-servicios/icon-hospedaje.svg">
						<div>HOSPEDAJE<br>DÍA Y NOCHE</div>
					</div>';
			break;
			case 'guarderia':
				return '
					<div class="servicio-tit">
						<img src="'.getTema().'/images/new/icon/km-servicios/icon-hospedaje.svg">
						<div>GUARDERÍA<br>DÍA</div>
					</div>';
			break;
			case 'paseos':
				return '
					<div class="servicio-tit">
						<img src="'.getTema().'/images/new/icon/km-servicios/icon-hospedaje.svg">
						<div>PASEOS</div>
					</div>';
			break;
			case 'adiestramiento-basico':
				return '
					<div class="servicio-tit">
						<img src="'.getTema().'/images/new/icon/km-servicios/icon-hospedaje.svg">
						<div>ENTRENAMIENTO<br>B&Aacute;SICO</div>
					</div>';
			break;
			case 'adiestramiento-intermedio':
				return '
					<div class="servicio-tit">
						<img src="'.getTema().'/images/new/icon/km-servicios/icon-hospedaje.svg">
						<div>ENTRENAMIENTO<br>INTERMEDIO</div>
					</div>';
			break;
			case 'adiestramiento-avanzado':
				return '
					<div class="servicio-tit">
						<img src="'.getTema().'/images/new/icon/km-servicios/icon-hospedaje.svg">
						<div>ENTRENAMIENTO<br>AVANZADO</div>
					</div>';
			break;
		}
	}

	function get_tamano($slug, $precios, $activo, $tamanos, $status="nopublish", $tipo_retorno = "HTML" ){

		$class = "";
		$tamano = "";
		preg_match_all("#peque#", $slug, $matches);
		if( count( $matches[0] ) == 1 ){
			$tamano = "pequenos";
		}
		
		preg_match_all("#medi#", $slug, $matches);
		if( count( $matches[0] ) == 1 ){
			$tamano = "medianos";
		}
		
		preg_match_all("#grand#", $slug, $matches);
		if( count( $matches[0] ) == 1 ){
			$tamano = "grandes";
		}

		preg_match_all("#gigan#", $slug, $matches);
		if( count( $matches[0] ) == 1 ){
			$tamano = "gigantes";
		}

		preg_match_all("#gato#", $slug, $matches);
		if( count( $matches[0] ) == 1 ){
			$tamano = "gatos";
		}

		if( is_array($tamanos) ){
			if( $activo && in_array($tamano, $tamanos) ){
				$class .= "km-servicio-opcionactivo";
			}
		}

		if($status=="publish"){
			$class .= " km-servicio-opcionactivo ";
		}
  		
  		$HTML = "";
  		$ARRAY = array();
		switch ( $tamano ) {
			case 'pequenos':

				$ARRAY = array(
					"tamano" => 'pequenos',
					"precio" => $precio
				);

				$prec = "";
				if( is_array($precio) ){

				}

				if( $precios["pequenos"] > 0 ){
					$HTML = '
					<div class="km-servicio-opcion km-servicio-opcionactivo">
						<div class="km-servicio-desc">
							<img src="'.getTema().'/images/new/icon/icon-pequenio.svg">
							<div class="km-opcion-text"><b>PEQUEÑO</b><br>0 a 25 cm</div>
						</div>
						<div class="km-servicio-costo"><b>$'.($precios["pequenos"]*getComision() ).'</b></div>
					</div>';
				}else{
					$HTML = '
					<div class="km-servicio-opcion">
						<div class="km-servicio-desc">
							<img src="'.getTema().'/images/new/icon/icon-pequenio.svg">
							<div class="km-opcion-text"><b>PEQUEÑO</b><br>0 a 25 cm</div>
						</div>
						<div class="km-servicio-costo"><b></b></div>
					</div>';
				}
			break;
			case 'medianos':

				$ARRAY = array(
					"tamano" => 'medianos',
					"precio" => $precio
				);

				if( $precios["medianos"] > 0 ){
					$HTML = '
					<div class="km-servicio-opcion km-servicio-opcionactivo">
						<div class="km-servicio-desc">
							<img src="'.getTema().'/images/new/icon/icon-mediano.svg">
							<div class="km-opcion-text"><b>MEDIANO</b><br>25 a 58 cm</div>
						</div>
						<div class="km-servicio-costo"><b>$'.($precios["medianos"]*getComision() ).'</b></div>
					</div>';
				}else{
					$HTML = '
					<div class="km-servicio-opcion">
						<div class="km-servicio-desc">
							<img src="'.getTema().'/images/new/icon/icon-mediano.svg">
							<div class="km-opcion-text"><b>MEDIANO</b><br>25 a 58 cm</div>
						</div>
						<div class="km-servicio-costo"><b></b></div>
					</div>';
				}
			break;
			case 'grandes':

				$ARRAY = array(
					"tamano" => 'grandes',
					"precio" => $precio
				);

				if( $precios["grandes"] > 0 ){
					$HTML = '
					<div class="km-servicio-opcion km-servicio-opcionactivo">
						<div class="km-servicio-desc">
							<img src="'.getTema().'/images/new/icon/icon-grande.svg">
							<div class="km-opcion-text"><b>GRANDE</b><br>58 a 73 cm</div>
						</div>
						<div class="km-servicio-costo"><b>$'.($precios["grandes"]*getComision() ).'</b></div>
					</div>';
				}else{
					$HTML = '
					<div class="km-servicio-opcion">
						<div class="km-servicio-desc">
							<img src="'.getTema().'/images/new/icon/icon-grande.svg">
							<div class="km-opcion-text"><b>GRANDE</b><br>58 a 73 cm</div>
						</div>
						<div class="km-servicio-costo"><b></b></div>
					</div>';
				}
			break;
			case 'gigantes':

				$ARRAY = array(
					"tamano" => 'gigantes',
					"precio" => $precio
				);

				if( $precios["gigantes"] > 0 ){
					$HTML = '
					<div class="km-servicio-opcion km-servicio-opcionactivo">
						<div class="km-servicio-desc">
							<img src="'.getTema().'/images/new/icon/icon-gigante.svg">
							<div class="km-opcion-text"><b>GIGANTE</b><br>73 a 200 cm</div>
						</div>
						<div class="km-servicio-costo"><b>$'.($precios["gigantes"]*getComision() ).'</b></div>
					</div>';
				}else{
					$HTML = '
					<div class="km-servicio-opcion">
						<div class="km-servicio-desc">
							<img src="'.getTema().'/images/new/icon/icon-gigante.svg">
							<div class="km-opcion-text"><b>GIGANTE</b><br>73 a 200 cm</div>
						</div>
						<div class="km-servicio-costo"><b></b></div>
					</div>';
				}
			case 'gatos':

				$ARRAY = array(
					"tamano" => 'gatos',
					"precio" => $precio
				);

				if( $precios["gatos"] > 0 ){
					$HTML = '
					<div class="km-servicio-opcion km-servicio-opcionactivo">
						<div class="km-servicio-desc">
							<img src="'.getTema().'/images/gatos/cat_blue.svg" style="width: 20px;">
							<div class="km-opcion-text"><b>GATOS</b><br>Indistinto</div>
						</div>
						<div class="km-servicio-costo"><b>$'.($precios["gatos"]*getComision() ).'</b></div>
					</div>';
				}else{
					$HTML = '
					<div class="km-servicio-opcion">
						<div class="km-servicio-desc">
							<img src="'.getTema().'/images/gatos/cat_blue.svg" style="width: 20px;">
							<div class="km-opcion-text"><b>GATOS</b><br>Indistinto</div>
						</div>
						<div class="km-servicio-costo"><b></b></div>
					</div>';
				}
			break;
		}

		if( $tipo_retorno == "HTML" ){
			return array(
				$tamano,
				$HTML
			);
		}else{
			return $ARRAY;
		}
	}

	function getTamanos(){
		$tamanios = array(
			"pequenos" => "PEQUEÑO 0 a 25cm",
			"medianos" => "MEDIANO 25 a 58cm",
			"grandes"  => "GRANDE 58cm a 73cm",
			"gigantes" => "GIGANTE 73cm a 200cm",
			"gatos"    => "GATOS",
		);

		return $tamanios;
	}

	function getTamanosData(){
		$tamanios = array(
			"pequenos" => [
				"PEQUEÑO",
				"0 a 25cm",
				"icon-pequenio.svg",
				"Pequeña",
				"Peq."
			],
			"medianos" => [
				"MEDIANO",
				"25 a 58cm",
				"icon-mediano.svg",
				"Mediana",
				"Med."
			],
			"grandes"  => [
				"GRANDE",
				"58cm a 73cm",
				"icon-grande.svg",
				"Grande",
				"Gra."
			],
			"gigantes" => [
				"GIGANTE",
				"73cm a 200cm",
				"icon-gigante.svg",
				"Gigante",
				"Gig."
			],
			"gatos"    => [
				"GATOS",
				"Sociables",
				"cat_blue.svg",
				"Gatos"
			],
		);

		return $tamanios;
	}

	function tieneGatos(){
		global $wpdb;
		global $USER_ID;

		$_mascotas = $wpdb->get_var("
			SELECT 
				count(*) 
			FROM 
				wp_posts AS mascota
			INNER JOIN wp_postmeta AS tipo ON ( tipo.post_id = mascota.ID AND tipo.meta_key = 'pet_type' AND tipo.meta_value = '2608' ) 
			WHERE 
				post_author = '{$USER_ID}' AND post_type = 'pets' AND post_status = 'publish' 
		");

		return ($_mascotas != 0);
	}

	function tienePerros(){
		global $wpdb;
		global $USER_ID;

		$_mascotas = $wpdb->get_var("
			SELECT 
				count(*) 
			FROM 
				wp_posts AS mascota
			INNER JOIN wp_postmeta AS tipo ON ( tipo.post_id = mascota.ID AND tipo.meta_key = 'pet_type' AND tipo.meta_value = '2605' ) 
			WHERE 
				post_author = '{$USER_ID}' AND post_type = 'pets' AND post_status = 'publish' 
		");

		return ($_mascotas != 0);
	}

	function getPrecios($data, $precarga = array(), $aceptados = array() ){
		$resultado = "";
		$tamanos = getTamanos();

		global $USER_ID;
		global $cuidador;
		global $wpdb;

		global $tieneGatos;
		global $tienePerros;
		
		$tamanos_aceptados = unserialize($cuidador->tamanos_aceptados);

		foreach ($tamanos as $key => $value) {
			$mostrar = false;

			if( isset($data[$key]) && $data[$key] > 0 && ( $tamanos_aceptados[$key] == 1 || $key == 'gatos' ) ){ $mostrar = true; }
			
			$bloquear_gatos = '';
			if( $key == 'gatos' ){ 
				if( !$tieneGatos ) { 
					$bloquear_gatos = ' bloquear_gatos';
					$bloquear_gatos_control = ' disabled';
				}else{
					$bloquear_gatos = ' ';
					$bloquear_gatos_control = ' ';
				}
			}else{
				if( !$tienePerros ) { 
					$bloquear_gatos = ' bloquear_gatos';
					$bloquear_gatos_control = ' disabled';
				}else{
					$bloquear_gatos = ' ';
					$bloquear_gatos_control = ' ';
				}
			}

			if( $mostrar ){
				$catidad = 0;
				if( isset($precarga[$key]) ){
					$catidad = $precarga[$key];
				}
				$resultado .= '
					<div class="km-quantity-height '.$bloquear_gatos.'">
						<div class="km-quantity">
							<a href="#" class="km-minus disabled">-</a>
								<span class="km-number">'.$catidad.'</span>
								<input type="hidden" value="'.$catidad.'" name="'.$key.'" class="tamano" data-valor="'.($data[$key]*getComision() ).'" />
							<a href="#" class="km-plus '.$bloquear_gatos_control.'">+</a>
						</div>
						<div class="km-height">
							'.$tamanos[$key].'
							<span>$'.($data[$key]*getComision() ).'</span>
						</div>
					</div>
				';
			}
		}
		return $resultado;
	}

	function getTransporte($data, $precarga){
		$resultado = "";
		$transportes = array(
			"transportacion_sencilla" => "Transp. Sencillo",
			"transportacion_redonda" => "Transp. Redondo"
		);
		$rutas = array(
			"corto" => "Rutas Cortas",
			"medio" => "Rutas Medias",
			"largo" => "Rutas Largas"
		);
		foreach ($transportes as $key => $value) {
			if( isset($data[$key]) ){
				$opciones = "";
				foreach ($data[$key] as $ruta => $precio) {
					if( $precio > 0 ){
						$selected = "";
						if( $precarga == strtoupper($value.' - '.$rutas[ $ruta ]) ){
							$selected = "selected";
						}
						$opciones .= '
							<option value="'.($precio*getComision() ).'" data-value="'.($value.' - '.$rutas[ $ruta ]).'" '.$selected.'>
								'.strtoupper($rutas[ $ruta ]).' ( $'.($precio*getComision() ).' )
				 			</option>
						';
					}
				}
				if( $opciones != "" ){
					$resultado .= '<optgroup label="'.$value.'">'.$opciones.'</optgroup>';
				}
			}
		}
		return $resultado;
	}

	function getAdicionales($data, $precarga = array()){
		$resultado = "";
		$adicionales = array(
			"bano" => "BAÑO Y SECADO",
			"corte" => "CORTE DE UÑAS Y PELO",
			"limpieza_dental" => "LIMPIEZA DENTAL",
			"acupuntura" => "ACUPUNTURA",
			"visita_al_veterinario" => "VISITA AL VETERINARIO"
		);
		foreach ($adicionales as $key => $value) {
			if( isset($data[$key]) && $data[$key] > 0 ){
				if( isset($precarga[$key]) ){
					$resultado .= '
						<div class="km-service-col">
							<label class="optionCheckout active" for="'.$key.'">'.$adicionales[$key].' ( $'.($data[$key]*getComision() ).')</label><br>
							<input type="checkbox" id="'.$key.'" name="'.$key.'" value="'.($data[$key]*getComision() ).'" style="display: none;" class="active" checked>
						</div>
					';
				}else{
					$resultado .= '
						<div class="km-service-col">
							<label class="optionCheckout" for="'.$key.'">'.$adicionales[$key].' ( $'.($data[$key]*getComision() ).')</label><br>
							<input type="checkbox" id="'.$key.'" name="'.$key.'" value="'.($data[$key]*getComision() ).'" style="display: none;">
						</div>
					';
				}
			}
		}
		return $resultado;
	}

	function getSaldo(){
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;

		global $wpdb;

		$saldo = get_user_meta($user_id, "kmisaldo", true);

		$cupon = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_name = 'saldo-{$user_id}' ");
		kmimos_cupon_saldo($saldo);
		return array(
			"saldo" => $saldo,
			"cupon" => "saldo-".$user_id
		);
	}

	function get_servicios($tipo = null){
		switch ( $tipo ) {
			case 'principales':
				return array(
					"hospedaje" 				=> [ "Hospedaje", "Día y noche" ],
		            "guarderia" 				=> [ "Guardería", "Solo día" ],
		            "paseos" 					=> [ "Paseos", "" ],
		            "adiestramiento_basico" 	=> [ "Adiestramiento Básico", "" ],
		            "adiestramiento_intermedio" => [ "Adiestramiento Intermedio", "" ],
		            "adiestramiento_avanzado" 	=> [ "Adiestramiento Avanzado", "" ]
				);
			break;
			case 'adicionales':
				return array(
		            "corte" 					=> "Corte",
		            "bano" 						=> "Ba&ntilde;o",
		            "visita_al_veterinario" 	=> "Visita al veterinario",
		            "limpieza_dental" 			=> "Limpieza dental",
		            "acupuntura" 				=> "Acupuntura"
				);
			break;
			case 'transporte':
				return array(
		            "transportacion_sencilla" 	=> "Transportacion sencilla",
		            "transportacion_redonda" 	=> "Transportacion redonda"
				);
			break;
			default:
				return array(
					"hospedaje" 				=> "Hospedaje",
		            "guarderia" 				=> "Guarderia",
		            "paseos" 					=> "Paseos",
		            "adiestramiento" 			=> "Adiestramiento",
		            "transportacion_sencilla" 	=> "Transportacion sencilla",
		            "transportacion_redonda" 	=> "Transportacion redonda",
		            "corte" 					=> "Corte",
		            "bano" 						=> "Ba&ntilde;o",
		            "visita_al_veterinario" 	=> "Visita al veterinario",
		            "limpieza_dental" 			=> "Limpieza dental",
		            "acupuntura" 				=> "Acupuntura"
				);
			break;
		}
	}

	function get_filtros($busqueda){
		$filtros = "";
		foreach ($busqueda as $key => $value) {
			
			switch ($key) {
				case 'ubicacion_txt':
					$filtros .= "
						<li>
							<strong>Ubicaci&oacute;n:</strong>
							<span>".$value."</span>
						</li>
					";
				break;
				case 'checkin':
					$filtros .= "
						<li>
							<strong>Entrada:</strong>
							<span>".$value."</span>
						</li>
					";
				break;
				case 'checkout':
					$filtros .= "
						<li>
							<strong>Salida:</strong>
							<span>".$value."</span>
						</li>
					";
				break;
				case 'nombre':
					$filtros .= "
						<li>
							<strong>Nombre de cuidador:</strong>
							<span>".$value."</span>
						</li>
					";
				break;
				case 'servicios':
					$servs = get_servicios();
					$filtros .= "<li> <strong>Servicios:</strong> <div class='items_filtros'>";
						foreach ($value as $servicio) {
							$filtros .= "<div> ".$servs[$servicio]." </div>";
						}
					$filtros .= "</div></li>";
				break;
				case 'tamanos':
					$tamas = array(
						"pequenos" => "Peque&ntilde;os",
						"medianos" => "Medianos",
						"grandes"  => "Grandes",
						"gigantes" => "Gigantes"
					);
					$filtros .= "<li> <strong>Tama&ntilde;os:</strong> <div class='items_filtros'>";
						foreach ($value as $tamano) {
							$filtros .= "<div> ".$tamas[$tamano]." </div>";
						}
					$filtros .= "</div></li>";
				break;
			}
		}

		if( $filtros != "" ){
			return "<ul class='filtros_aplicados'>".$filtros."</ul>";
		}else{
			return "";
		}
		
	}

    function dateFormat($fecha, $format = "d/m/Y"){
		return date( $format, strtotime( str_replace("/", "-", $fecha) ) );
	}
?>