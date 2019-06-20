<?php
	session_start();
	include dirname(dirname(dirname(dirname(__DIR__))))."/wp-load.php";
	extract($_POST);

	echo get_resultados_admin($page*10, explode(",", $sugerencias) );


	function get_resultados_admin($PAGE = 0, $ids){
		if( !isset($_SESSION) ){ session_start(); }
        global $wpdb;
		$HTML = ""; 
		$resultados = $_SESSION['resultado_busqueda'];
		$total = count($resultados);

		$fin = ( $total > ($PAGE+10) ) ? $PAGE+10 : $total;

		$verificar_cache = [];
		for ($i = $PAGE; $i < $fin; $i++ ) {
			// if( !isset($_SESSION["DATA_CUIDADORES"][ $resultados[$i]->id ]) ){
				$verificar_cache[] = $resultados[$i]->id;
			// }
		}
		if( count($verificar_cache) > 0 ){
			pre_carga_data_cuidadores($verificar_cache);
		}

		# Crear ficha de cuidadores
		for ($i = $PAGE; $i < $fin; $i++ ) {
			$cuidador = $resultados[$i];
			$contador++;
			$HTML .= crear_ficha_admin([
				'i' => $contador,
				'cuidador'=>$cuidador, 
				'favoritos'=>$favoritos,
				'user_id'=>$user_id,
				'seleccionado' => in_array($cuidador->id, $ids)
			]);
		}

		return $HTML;
	}

	function crear_ficha_admin($parametros=[]){
		global $wpdb;
		$HTML = '';
		extract($parametros);
		if( isset($_SESSION["DATA_CUIDADORES"][ $cuidador->id ]) ){

			$_cuidador = $_SESSION["DATA_CUIDADORES"][ $cuidador->id ];

			$anios_exp = $_cuidador->experiencia;
            if( $anios_exp > 1900 ){
                $anios_exp = date("Y")-$anios_exp;
            }

			$img_url = kmimos_get_foto($_cuidador->user_id);
			$desde = $_cuidador->hospedaje_desde;
		
			$desde = explode(".", number_format( ($desde*getComision()) , 2, '.', ',') );

			$direccion = $_cuidador->direccion;
			if( strlen($_cuidador->direccion) > 50 ){
				$direccion = mb_strcut($_cuidador->direccion, 0, 50, "UTF-8")."...";
			}

			if( $direccion == "0" ){ $direccion = ""; }

			$ocultar_flash = "ocultar_flash";
			$ocultar_flash_none = "ocultar_flash_none";
			$ocultar_descuento = "ocultar_descuento";
			$ocultar_geo = "ocultar_geo";
			if( $_cuidador->atributos["flash"]+0 == 1 ){
				$ocultar_flash = "";
				$ocultar_flash_none = "";
			}
			if( $_cuidador->atributos["destacado"]+0 == 1 ){
				$ocultar_descuento = "";
			}

			if( $_cuidador->atributos["geo"]+0 == 1 ){
				$ocultar_geo = "";
			}

			$ocultar_todo = "";
			if( $ocultar_flash != "" && $ocultar_descuento != "" && $ocultar_geo != "" ){
				$ocultar_todo = "ocultar_flash_descuento";
			}

			$galeria =
				'<div class="resultados_item_info_img" style="background-image: url('.$img_url.');">'.
					'<div class="img_fondo" style="background-image: url('.$img_url.');"></div>'.
					'<div class="img_normal" style="background-image: url('.$img_url.');"></div>'.
				'</div>';
	

			$mensaje_disp = "Disponibilidad inmediata";
			$mensaje_disp_movil = "Disponible";
			$mensaje_disp_movil_corto = "";
			$show_msg_desc = "";
			if( $ocultar_descuento == "" ){
				$mensaje_disp = "50% en 2da mascota. 25% en 3era.";
				$mensaje_disp_movil = "50% en 2da masc. 25% en 3era.";
				$show_msg_desc = "show_msg_descuento";
				$mensaje_disp_movil_corto = "Descuento";
			}

			if( $ocultar_geo == "" ){
				$mensaje_disp = "con GPS";
				$mensaje_disp_movil = "con GPS";
				$show_msg_desc = "show_msg_descuento";
				$mensaje_disp_movil_corto = "con GPS";
			}

			$checked = ( $seleccionado ) ? ' checked ' : '';

			$HTML .= '
				<div class="resultado_item">
					<div class="resultados_hover"></div>
					<div class="resultado_item_container">
						<div class="resultados_item_middle">
							<div class="resultados_item_info_container">
								<div class="resultados_item_info_img_container">
									<a href="'.get_home_url().'/petsitters/'.$_cuidador->user_id.'" class="resultados_item_info_img_box">
										'.$galeria.'
									</a>
								</div>
								<div class="resultados_item_info">
									<a href="'.get_home_url().'/petsitters/'.$_cuidador->user_id.'" class="resultados_item_titulo"> <span>'.$i.'.</span> '.($_cuidador->titulo).'</a>
									<div class="resultados_item_servicios">
										'.get_servicios_new($_cuidador->adicionales).'
									</div>
									<div class="resultados_item_experiencia">
										'.$anios_exp.' años de experiencia
									</div>
									<div class="resultados_item_precio_container">
										<span>Desde</span>
										<div>MXN$ <strong>'.$desde[0].'<span>,'.$desde[1].'</span></strong></div>
										<span class="por_noche">'.$por_noche_paseo.'</span>
									</div>
									<div class="resultados_item_valoraciones">
										<strong>Rating</strong>: '.$_cuidador->rating.' estrellas
									</div>
									<div class="resultados_item_valoraciones">
										<strong>Valoraciones</strong>: '.$_cuidador->valoraciones.' 
									</div>
									<label class="resultados_item_select" id="checkbox_'.$_cuidador->id.'">
										<strong>Mostar en Correo</strong> 
										<input 
											type="checkbox" 
											id="checkbox_'.$_cuidador->id.'" 
											onchange="seleccionar_sugerencia( jQuery(this) )"
											data-id="'.$_cuidador->id.'" 
											data-name="'.$_cuidador->titulo.'" 
											'.$checked.'
										/>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
		}
		return $HTML;
	}

?>

