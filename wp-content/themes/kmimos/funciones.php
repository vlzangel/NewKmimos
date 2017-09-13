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
			$busqueda = unserialize($_SESSION["busqueda"]);
		}
		return $busqueda;
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

	function get_tamano($slug, $precios, $activo, $tamanos, $tipo_retorno = "HTML"){

		$tamano = "";
		preg_match_all("#Peque#", $slug, $matches);
		if( count( $matches[0] ) == 1 ){
			$tamano = "pequenos";
		}
		
		preg_match_all("#Medi#", $slug, $matches);
		if( count( $matches[0] ) == 1 ){
			$tamano = "medianos";
		}
		
		preg_match_all("#Grand#", $slug, $matches);
		if( count( $matches[0] ) == 1 ){
			$tamano = "grandes";
		}

		preg_match_all("#Gigan#", $slug, $matches);
		if( count( $matches[0] ) == 1 ){
			$tamano = "gigantes";
		}

		if( is_array($tamanos) ){
			if( $activo && in_array($tamano, $tamanos) ){
				$class = "km-servicio-opcionactivo";
			}
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

				$HTML = '
				<div class="km-servicio-opcion '.$class.'">
					<div class="km-servicio-desc">
						<img src="'.getTema().'/images/new/icon/icon-pequenio.svg">
						<div class="km-opcion-text"><b>PEQUEÑO</b><br>0 a 25 cm</div>
					</div>
					<div class="km-servicio-costo"><b>$'.($precios["pequenos"]*1.2).'</b></div>
				</div>';
			break;
			case 'medianos':

				$ARRAY = array(
					"tamano" => 'medianos',
					"precio" => $precio
				);

				$HTML = '
				<div class="km-servicio-opcion '.$class.'">
					<div class="km-servicio-desc">
						<img src="'.getTema().'/images/new/icon/icon-mediano.svg">
						<div class="km-opcion-text"><b>MEDIANO</b><br>25 a 28 cm</div>
					</div>
					<div class="km-servicio-costo"><b>$'.($precios["medianos"]*1.2).'</b></div>
				</div>';
			break;
			case 'grandes':

				$ARRAY = array(
					"tamano" => 'grandes',
					"precio" => $precio
				);

				$HTML = '
				<div class="km-servicio-opcion '.$class.'">
					<div class="km-servicio-desc">
						<img src="'.getTema().'/images/new/icon/icon-grande.svg">
						<div class="km-opcion-text"><b>GRANDE</b><br>58 a 73 cm</div>
					</div>
					<div class="km-servicio-costo"><b>$'.($precios["grandes"]*1.2).'</b></div>
				</div>';
			break;
			case 'gigantes':

				$ARRAY = array(
					"tamano" => 'gigantes',
					"precio" => $precio
				);

				$HTML = '
				<div class="km-servicio-opcion '.$class.'">
					<div class="km-servicio-desc">
						<img src="'.getTema().'/images/new/icon/icon-gigante.svg">
						<div class="km-opcion-text"><b>GIGANTE</b><br>73 a 200 cm</div>
					</div>
					<div class="km-servicio-costo"><b>$'.($precios["gigantes"]*1.2).'</b></div>
				</div>';
			break;
		}

		if( $tipo_retorno == "HTML" ){
			return $HTML;
		}else{
			return $ARRAY;
		}
	}

	function getTamanos(){
		return array(
			"pequenos" => "PEQUEÑO 0 a 25cm",
			"medianos" => "MEDIANO 25 a 58cm",
			"grandes"  => "GRANDE 58cm a 73cm",
			"gigantes" => "GIGANTE 73cm a 200cm"
		);
	}

	function getPrecios($data){
		$resultado = "";
		$tamanos = getTamanos();
		foreach ($tamanos as $key => $value) {
			if( isset($data[$key]) && $data[$key] > 0 ){
				$resultado .= '
					<div class="km-quantity-height">
						<div class="km-quantity">
							<a href="#" class="km-minus disabled">-</a>
								<span class="km-number">0</span>
								<input type="hidden" value="0" name="'.$key.'" class="tamano" data-valor="'.($data[$key]*1.2).'" />
							<a href="#" class="km-plus">+</a>
						</div>
						<div class="km-height">
							'.$tamanos[$key].'
							<span>$'.($data[$key]*1.2).'</span>
						</div>
					</div>
				';
			}
		}
		return $resultado;
	}

	function getTransporte($data){
		$resultado = "";
		$transportes = array(
			"transportacion_sencilla" => "Transportaci&oacute;n Sencilla",
			"transportacion_redonda" => "Transportaci&oacute;n Redonda"
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
						$opciones .= '
							<option value="'.($precio*1.2).'">
								'.$rutas[ $ruta ].' ( $'.($precio*1.2).' )
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

	function getAdicionales($data){
		$resultado = "";
		$adicionales = array(
			"bano" => "BAÑO Y SECADO",
			"corte" => "CORTE DE UÑAS Y PELO",
			"limpieza_dental" => "LIMPIEA DENTAL",
			"acupuntura" => "ACUPUNTURA",
			"visita_al_veterinario" => "VISITA AL VETERINARIO"
		);
		foreach ($adicionales as $key => $value) {
			if( isset($data[$key]) && $data[$key] > 0 ){
				$resultado .= '
					<div class="km-service-col">
						<label class="optionCheckout" for="'.$key.'">'.$adicionales[$key].' ( $'.($data[$key]*1.2).')</label><br>
						<input type="checkbox" id="'.$key.'" name="'.$key.'" value="'.($data[$key]*1.2).'" style="display: none;">
					</div>
				';
			}
		}
		return $resultado;
	}
?>