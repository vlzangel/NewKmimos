<?php
	function get_recurso($tipo){
		return getTema()."/recursos/".$tipo."/";
	}

	function get_destacados_new(){
		if( !isset($_SESSION) ){ session_start(); }
        global $wpdb;
        $_POST = $_SESSION['busqueda'];
        $resultados = $_SESSION['resultado_busqueda'];
        $lat = $_POST["latitud"];
        $lng = $_POST["longitud"];
        $top_destacados = ""; $cont = 0;

        if( $lat != "" && $lng != "" ){
            $sql_top = $wpdb->get_results("SELECT * FROM destacados");
            $destacados = [];
            foreach ($sql_top as $key => $value) { $destacados[] = $value->cuidador; }
            $DESTACADOS_ARRAY = [];
            $cont = 0;
            foreach ($resultados as $key => $_cuidador) {
                if( in_array($_cuidador->id, $destacados) && $cuidador->DISTANCIA <= 200 ){ //
                    $cont++;
                    $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$_cuidador->id}");
                    $data = $wpdb->get_row("SELECT post_title AS nom, post_name AS url FROM wp_posts WHERE ID = {$cuidador->id_post}");
                    $nombre = $data->nom;
                    $img_url = kmimos_get_foto($cuidador->user_id);
                    $url = get_home_url() . "/petsitters/" . $data->url;
                    $anios_exp = $cuidador->experiencia;
                    if( $anios_exp > 1900 ){
                        $anios_exp = date("Y")-$anios_exp;
                    }
                    $DESTACADOS_ARRAY[] = [
                    	"img" => $img_url,
                    	"nombre" => $nombre,
                    	"url" => $url,
                    	"desde" => ($cuidador->hospedaje_desde*getComision()),
                    	"distancia" => floor($_cuidador->DISTANCIA),
                    	"ranking" => kmimos_petsitter_rating($cuidador->id_post),
                    	"experiencia" => $anios_exp,
                		"valoraciones" => $cuidador->valoraciones
                    ];
                }
                if( $cont >= 4 ){ break; }
            }
        }else{
            $ubicacion = explode("_", $_POST["ubicacion"]);
            if( count($ubicacion) > 0 ){ $estado = $ubicacion[0]; }
            $estado_des = $wpdb->get_var("SELECT name FROM states WHERE id = ".$estado);
            $sql_top = "SELECT * FROM destacados WHERE estado = '{$estado}'";
            $tops = $wpdb->get_results($sql_top);
            foreach ($tops as $value) {
                $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$value->cuidador}");
                $data = $wpdb->get_row("SELECT post_title AS nom, post_name AS url FROM wp_posts WHERE ID = {$cuidador->id_post}");
                $nombre = $data->nom;
                $img_url = kmimos_get_foto($cuidador->user_id);
                $url = get_home_url() . "/petsitters/" . $data->url;
                $anios_exp = $cuidador->experiencia;
                if( $anios_exp > 1900 ){
                    $anios_exp = date("Y")-$anios_exp;
                }
                $DESTACADOS_ARRAY[] = [
                	"img" => $img_url,
                	"nombre" => $nombre,
                	"url" => $url,
                	"desde" => ($cuidador->hospedaje_desde*getComision()),
                	"distancia" => floor($_cuidador->DISTANCIA),
                	"ranking" => kmimos_petsitter_rating($cuidador->id_post),
                	"experiencia" => $anios_exp,
                	"valoraciones" => $cuidador->valoraciones
                ];
            }
        }

        if( count($DESTACADOS_ARRAY) > 0 ){
	        foreach ($DESTACADOS_ARRAY as $key => $destacado) {
	        	$top_destacados .= '
	        		<div class="destacados_item">
	        			<div class="desacado_img">
	        				<div class="desacado_img_interna" style="background-image: url( '.$destacado["img"].' );"></div>
	        			</div>
	        			<div class="desacado_img_normal" style="background-image: url( '.$destacado["img"].' );"></div>
	        			<div class="desacado_title">
	        				<span>Dest</span> '.$destacado["nombre"].'
	        			</div>
	        			<div class="desacado_experiencia">'.$destacado["experiencia"].' años de experiencia</div>
	        			<div class="desacado_monto">Desde <strong>MXN $ '.$destacado["desde"].'</strong></div>
	        			<div class="desacado_ranking_container">'.$destacado["ranking"].'</div>
	        			<div class="desacado_experiencia">'.$destacado["valoraciones"].' valoraciones</div>
	        			<a class="desacado_boton_reservar">Reservar</a>
	        		</div>
	            ';
	        }
	        $top_destacados = '<div class="destacados_container"><div class="destacados_box">'.$top_destacados.'</div></div>';
        }

        return comprimir($top_destacados);
	}

	function get_resultados_new(){
		if( !isset($_SESSION) ){ session_start(); }
        global $wpdb;
		$resultados = $_SESSION['resultado_busqueda'];
		$HTML = ""; $cont = 1;
		foreach ($resultados as $key => $cuidador) {
			$_cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = {$cuidador->user_id}");
			$img_url = kmimos_get_foto($cuidador->user_id);

			$desde = explode(".", number_format( ($_cuidador->hospedaje_desde*getComision()) , 2, '.', ',') );

			$dir = explode(",", $_cuidador->direccion);

			$HTML .= '
				<div class="resultado_item">
					<div class="resultados_hover"></div>
					<div class="resultado_item_container">
						<div class="resultados_item_top">

						</div>
						<div class="resultados_item_middle">
							<div class="resultados_item_info_container">
								<div class="resultados_item_info_img_container">
									<div class="resultados_item_info_img_box">
										<div class="resultados_item_info_img" style="background-image: url('.$img_url.');"></div>
									</div>
								</div>
								<div class="resultados_item_info">
									<div class="resultados_item_titulo"> <span>'.$cont.'.</span> '.utf8_encode($cuidador->titulo).'</div>
									<div class="resultados_item_subtitulo">"Tus mascotas se sentirán como en casa mietras se queden"</div>
									<div class="resultados_item_direccion">'.strtolower($dir[0]).'</div>
									<div class="resultados_item_servicios">
										'.get_servicios_new($_cuidador->adicionales).'
										<div class="resultados_item_comentarios">
											'.$_cuidador->valoraciones.' comentarios
										</div>
										<div class="resultados_item_ranking">
											'.kmimos_petsitter_rating($_cuidador->id_post).'
										</div>
									</div>
									<div class="resultados_item_precio_container">
										<span>Desde</span>
										<div>MXN$ <strong>'.$desde[0].'<span>,'.$desde[1].'</span></strong></div>
										<span>Por noche</span>
									</div>
								</div>
							</div>
						</div>
						<div class="resultados_item_bottom">
							<a href="#" class="boton boton_border_gris">Solicitud de conocer</a>
							<a href="#" class="boton boton_verde">Reservar</a>
						</div>
					</div>
				</div>
			';
			$cont++;
		}

		return $HTML;
	}

	function get_list_servicios_adicionales(){
		return [
			"corte" => true,
			"bano" => true,
			"transportacion_sencilla" => true,
			"transportacion_redonda" => true,
			"visita_al_veterinario" => true,
			"limpieza_dental" => true,
			"acupuntura" => true
		];
	}


    if(!function_exists('get_servicios_new')){
        function get_servicios_new($adicionales){
            $r = "";
            
            $adicionales = unserialize($adicionales);
            $adicionales_array = get_list_servicios_adicionales();

            if( count($adicionales) > 0 ){
                foreach($adicionales as $key => $value){
                    switch ($key) {
                        case 'corte':
                            if( $value > 0){
                                $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/MORADOS/Corte.svg' height='40' title='Corte de pelo y u&ntilde;as'> ";
                                $adicionales_array[$key] = false;
                            }
                        break;
                        case 'bano':
                            if( $value > 0){
                                $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/MORADOS/Banio.svg' height='40' title='Ba&ntilde;o'> ";
                                $adicionales_array[$key] = false;
                            }
                        break;
                        case 'transportacion_sencilla':
                        	$entro = false;
                        	foreach ($value as $_key => $precio) {
	                            if( $precio > 0){
	                                $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/MORADOS/Trans_Sencillo.svg' height='40' title='Transporte Sencillo'> ";
	                                $entro = true;
	                                break;
	                            }
	                        }

	                        if( $entro ){
	                            $adicionales_array[$key] = false;
	                        }
                        break;
                        case 'transportacion_redonda':
                        	$entro = false;
                        	foreach ($value as $_key => $precio) {
	                            if( $precio > 0){
	                                $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/MORADOS/Trans_Redondo.svg' height='40' title='Transporte Redondo'> ";
	                                $entro = true;
	                                break;
	                            }
	                        }

	                        if( $entro ){
	                            $adicionales_array[$key] = false;
	                        }
                        break;
                        case 'visita_al_veterinario':
                            if( $value > 0){
                                $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/MORADOS/Veterinario.svg' height='40' title='Visita al Veterinario'> ";
                                $adicionales_array[$key] = false;
                            }
                        break;
                        case 'limpieza_dental':
                            if( $value > 0){
                                $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/MORADOS/Dental.svg' height='40' title='Limpieza Dental'> ";
                                $adicionales_array[$key] = false;
                            }
                        break;
                        case 'acupuntura':
                            if( $value > 0){
                                $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/MORADOS/Acupuntura.svg' height='40' title='Acupuntura'> ";
                                $adicionales_array[$key] = false;
                            }
                        break;

                        /* Servicio  Principales */
	                        case 'paseos':
	                            
	                        break;
	                        case 'guarderia':
	                            
	                        break;
	                        case 'adiestramiento_basico':
	                            
	                        break;
	                        case 'adiestramiento_intermedio':
	                            
	                        break;
	                        case 'adiestramiento_avanzado':
	                            
	                        break;
                    }
                }
            }

            foreach ($adicionales_array as $key => $value) {
            	if( $value ){
	                switch ($key) {
	                    case 'corte':
	                        $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/GRISES/Corte.svg' height='40' title='Corte de pelo y u&ntilde;as'> ";
	                    break;
	                    case 'bano':
	                        $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/GRISES/Banio.svg' height='40' title='Ba&ntilde;o'> ";
	                    break;
	                    case 'transportacion_sencilla':
	                    	$r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/GRISES/Trans_Sencillo.svg' height='40' title='Transporte Sencillo'> ";
	                    break;
	                    case 'transportacion_redonda':
	                    	$r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/GRISES/Trans_Redondo.svg' height='40' title='Transporte Redondo'> ";
	                    break;
	                    case 'visita_al_veterinario':
	                        $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/GRISES/Veterinario.svg' height='40' title='Visita al Veterinario'> ";
	                    break;
	                    case 'limpieza_dental':
	                        $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/GRISES/Dental.svg' height='40' title='Limpieza Dental'> ";
	                    break;
	                    case 'acupuntura':
	                        $r .= "<img src='".get_recurso("img")."BUSQUEDA/SVG/servicios/GRISES/Acupuntura.svg' height='40' title='Acupuntura'> ";
	                    break;

	                }
                }
            }

            return $r;
        }
    }
?>