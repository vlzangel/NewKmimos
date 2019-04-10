<?php
	
	include dirname(__FILE__).'/reconfiguracion.php';

	/* RECOMENDACIONES HOME 2 */

		function get_recomendaciones_homa_2(){
			global $wpdb;
			$cuidadores = $wpdb->get_results("SELECT * FROM cuidadores WHERE activo = 1 ORDER BY valoraciones DESC LIMIT 0, 10");

			$resultado = [];
			foreach ($cuidadores as $key => $cuidador) {

				$atributos = unserialize($cuidador->atributos);
				$anios_exp = $cuidador->experiencia;
                if( $anios_exp > 1900 ){ $anios_exp = date("Y")-$anios_exp; }
                $expe = ( $anios_exp == 1 ) ? $anios_exp." año de experiencia" : $anios_exp." años de experiencia";
                
                $desde = $cuidador->hospedaje_desde;
				$desde = explode(".", number_format( ($desde*getComision()) , 2, '.', ',') );

				$cuidador->valoraciones = ( $cuidador->valoraciones == 1 ) ? $cuidador->valoraciones." valoración": $cuidador->valoraciones." valoraciones";
				
				$cuidador->estados = explode("=", $cuidador->estados);
				$cuidador->municipios = explode("=", $cuidador->municipios);

				$mun = $wpdb->get_var("SELECT iso FROM states WHERE id = {$cuidador->estados[1]}");
				$est = $wpdb->get_var("SELECT name FROM locations WHERE id = {$cuidador->municipios[1]}");

				$est = htmlentities( utf8_decode($est) );
				$ubicacion = $est.', '. ucfirst( strtolower( $mun ) );

				$resultado[] = (object)[
					"id" => $cuidador->user_id,
					"img" => kmimos_get_foto($cuidador->user_id),
					"nombre" => $cuidador->titulo,
					"link" => get_home_url()."/petsitters/".$cuidador->user_id,
					"ranking" => kmimos_petsitter_rating($cuidador->id_post),
					"experiencia" => $expe,
					"destacado" => $atributos["destacado"],
					"precio" => number_format( $cuidador->hospedaje_desde*getComision(), 2, ',', '.'),
					"valoraciones" => $cuidador->valoraciones,
					"ubicacion" => $ubicacion
				];
			}
			return $resultado;
		}

	/* DESTACADOS HOME */

		function get_destacados_home($ids_validos = ''){
			global $wpdb;

			$resultado = [];

			if( is_array($ids_validos) ){

				foreach ($ids_validos as $key => $id) {
					$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$id}");

					$atributos = unserialize($cuidador->atributos);
					$anios_exp = $cuidador->experiencia;
                    if( $anios_exp > 1900 ){ $anios_exp = date("Y")-$anios_exp; }
                    $expe = ( $anios_exp == 1 ) ? $anios_exp." año de experiencia" : $anios_exp." años de experiencia";
                    
                    $msg_destacado = $wpdb->get_row("SELECT * FROM wp_comments WHERE comment_ID = ".$atributos["msg_destacado"]);
                    $_msg_destacado = mb_substr($msg_destacado->comment_content, 0, 80);
                    if( $_msg_destacado != "" ){
                    	$msg_destacado = ( strlen($msg_destacado->comment_content) > 80 ) ? $_msg_destacado.'...' : $_msg_destacado;
                    }
                    
                    $cliente_id = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email = ".$msg_destacado->comment_author_email );

                    $desde = $cuidador->hospedaje_desde;
					$desde = explode(".", number_format( ($desde*getComision()) , 2, '.', ',') );

					$cuidador->estados = explode("=", $cuidador->estados);
					$cuidador->municipios = explode("=", $cuidador->municipios);

					$mun = $wpdb->get_var("SELECT iso FROM states WHERE id = {$cuidador->estados[1]}");
					$est = $wpdb->get_var("SELECT name FROM locations WHERE id = {$cuidador->municipios[1]}");

					$est = htmlentities( utf8_decode($est) );
					$ubicacion = $est.', '. ucfirst( strtolower( $mun ) );

					$atributos = unserialize($cuidador->atributos);

					$resultado[] = (object)[
						"atributos" => $atributos,

						"img" => kmimos_get_foto($cuidador->user_id),
						"cliente" => kmimos_get_foto( $cliente_id ),
						"nombre" => $cuidador->titulo,
						"link" => get_home_url()."/petsitters/".$cuidador->user_id,
						"ranking" => kmimos_petsitter_rating($cuidador->id_post),
						"msg" => $msg_destacado,
						"experiencia" => $expe,
						"ubicacion" => $ubicacion,
						"destacado" => $atributos["destacado"],
						"precio" => '
						<span>Desde</span>
						<div>MXN$ <strong>'.$desde[0].'<span>,'.$desde[1].'</span></strong></div>
						<span class="por_noche">Por noche</span>',
					];
				}

			}else{

				$destacados = $wpdb->get_results("SELECT * FROM cuidadores WHERE activo = 1 AND atributos LIKE '%destacado_home\";s:1:\"1%' ");
				
				if( is_array($destacados) ){
					foreach ($destacados as $key => $cuidador) {
						$valido = true;
						if( is_array($ids_validos) && !in_array($cuidador->id, $ids_validos) ){
							$valido = false;
						}

						if( $valido  ){
							$atributos = unserialize($cuidador->atributos);
							$anios_exp = $cuidador->experiencia;
		                    if( $anios_exp > 1900 ){ $anios_exp = date("Y")-$anios_exp; }
		                    $expe = ( $anios_exp == 1 ) ? $anios_exp." año de experiencia" : $anios_exp." años de experiencia";
		                    
		                    $msg_destacado = $wpdb->get_row("SELECT * FROM wp_comments WHERE comment_ID = ".$atributos["msg_destacado"]);
		                    $_msg_destacado = mb_substr($msg_destacado->comment_content, 0, 80);
		                    if( $_msg_destacado != "" ){
		                    	$msg_destacado = ( strlen($msg_destacado->comment_content) > 80 ) ? $_msg_destacado.'...' : $_msg_destacado;
		                    }
		                    
		                    $cliente_id = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email = ".$msg_destacado->comment_author_email );

		                    $desde = $cuidador->hospedaje_desde;
							$desde = explode(".", number_format( ($desde*getComision()) , 2, '.', ',') );

							$cuidador->estados = explode("=", $cuidador->estados);
							$cuidador->municipios = explode("=", $cuidador->municipios);

							$mun = $wpdb->get_var("SELECT iso FROM states WHERE id = {$cuidador->estados[1]}");
							$est = $wpdb->get_var("SELECT name FROM locations WHERE id = {$cuidador->municipios[1]}");

							$est = htmlentities( utf8_decode($est) );
							$ubicacion = $est.', '. ucfirst( strtolower( $mun ) );

							$atributos = unserialize($cuidador->atributos);

							$resultado[] = (object)[
								"img" => kmimos_get_foto($cuidador->user_id),
								"cliente" => kmimos_get_foto( $cliente_id ),
								"nombre" => $cuidador->titulo,
								"link" => get_home_url()."/petsitters/".$cuidador->user_id,
								"ranking" => kmimos_petsitter_rating($cuidador->id_post),
								"msg" => $msg_destacado,
								"experiencia" => $expe,
								"ubicacion" => $ubicacion,
								"destacado" => $atributos["destacado"],
								"precio" => '
								<span>Desde</span>
								<div>MXN$ <strong>'.$desde[0].'<span>,'.$desde[1].'</span></strong></div>
								<span class="por_noche">Por noche</span>',
							];
						}
					}
				}

			}

			return $resultado;
		}

	/* OTROS */

	function quitar_cupos_conocer($user_id){
		global $wpdb;
		$cupos = get_cupos_conocer_registro($user_id);
		$metas = json_decode( $cupos->metadata );
		$metas->cupos_quitados = $cupos->usos;
		$metadata = json_encode( $metas );
		$wpdb->query("UPDATE conocer_pedidos SET usos = 0, metadata='{$metadata}' WHERE user_id = {$user_id} AND status = 'Pagado'");
	}

	function revertir_cupo_conocer($user_id){
		global $wpdb;
		$wpdb->query("UPDATE conocer_pedidos SET usos = usos + 1 WHERE user_id = {$user_id} AND status = 'Pagado'");
	}

	function revertir_saldo_conocer($user_id){
		global $wpdb;
		$cupos = get_cupos_conocer_registro($user_id);
		$metas = json_decode( $cupos->metadata );
		$cupos_quitados = $metas->cupos_quitados;
		$metas->cupos_quitados = 0;
		$metadata = json_encode( $metas );
		$wpdb->query("UPDATE conocer_pedidos SET usos = '{$cupos_quitados}', metadata='{$metadata}' WHERE user_id = {$user_id} AND status = 'Pagado'");
	}

	function usar_cupo_conocer($user_id){
		global $wpdb;
		$cupos = get_cupos_conocer($user_id);
		$wpdb->query("UPDATE conocer_pedidos SET usos = usos - 1 WHERE user_id = {$user_id} AND status = 'Pagado'");
	}

	function get_cupos_conocer($user_id){
		global $wpdb;
		$cupos = get_cupos_conocer_registro($user_id);
		return $cupos->usos;
	}

	function get_cupos_conocer_registro($user_id){
		global $wpdb;
		return $wpdb->get_row("SELECT * FROM conocer_pedidos WHERE user_id = {$user_id} AND status = 'Pagado' ORDER BY id DESC");
	}

	function get_cupos_conocer_pendientes($user_id){
		global $wpdb;
		$cupos = $wpdb->get_row("SELECT * FROM conocer_pedidos WHERE user_id = {$user_id} AND tipo_pago = 'Tienda' AND status = 'Pendiente' ORDER BY id  DESC");
		if( $cupos !== false ){
			return $cupos;
		}
		return false;
	}

	function get_tipo($servicio_id){
		global $wpdb;
		$cats = array(
            2601 => "paseos"                    ,
            2602 => "adiestramiento_basico"     ,
            2606 => "adiestramiento_intermedio" ,
            2607 => "adiestramiento_avanzado"   ,
            2599 => "guarderia"                 ,
            2598 => "hospedaje"                 
        );
		return $cats[ $wpdb->get_var( "SELECT ts.term_id AS slug FROM wp_term_relationships AS r LEFT JOIN wp_terms as ts ON ( ts.term_id = r.term_taxonomy_id ) WHERE r.object_id = '{$servicio_id}' AND r.term_taxonomy_id != 28" ) ];
	}

	function get_cupos($servicio_id){
		global $wpdb;
		return $wpdb->get_results("SELECT * FROM cupos WHERE servicio = '{$servicio_id}' AND fecha >= '".date("Y-m-d", time())."'" );
	}

	function get_cupos_by_user_id($user_id){
		global $wpdb;
		$hoy = date("Y-m-d", time());
		return $wpdb->get_results("SELECT * FROM cupos WHERE cuidador = '{$user_id}' AND fecha = '{$hoy}' AND no_disponible = 1");
	}

	function COMPROBAR_ERRORES(){
		global $wpdb;
		global $USER_ID;

		global $tieneGatos;
		global $tienePerros;

		$error = "";
		if( $USER_ID  == ""){
			$error = "
				<h1 align='justify'>Debes iniciar sesión para poder realizar reservas.</h1>
				<h2 align='justify'>
					Pícale <span id='cerrarModal' onclick=\"document.getElementById('login').click(); jQuery('.vlz_modal').css('display', 'none')\" style='color: #00b69d; font-weight: 600; cursor: pointer;'>Aquí</span> para acceder a kmimos.
				<h2>
			";
		}

		if( $error  == ""){
			$propietario = $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = ".vlz_get_page() );
			if( $propietario == $USER_ID ){
				$error = "
					<h1 align='justify'>No puedes realizarte reservas a tí mismo.</h1>
					<h2 align='justify'>Pícale <a href='".get_home_url()."/busqueda/' style='color: #00b69d; font-weight: 600;'>Aquí</a> para buscar entre cientos de cuidadores certificados kmimos.<h2>
				";
			}
		}

		if( $error  == ""){
			$meta = get_user_meta($USER_ID);
			if( $meta['first_name'][0] == '' ||  $meta['last_name'][0] == '' || ( $meta['user_mobile'][0] == '' ) && ( $meta['user_phone'][0] == '' )){
				$error = "
					<h1 align='justify'>Kmiusuario, para continuar con tu reserva debes ir a tu perfil para completar algunos datos de contacto.</h1>
					<h2 align='justify'>Pícale <a href='".get_home_url()."/perfil-usuario/?ua=profile' target='_blank' style='color: #00b69d; font-weight: 600;'>Aquí</a> para cargar tu información.<h2>
				";
			}
		}

		if( $error  == ""){
			$mascotas = $wpdb->get_var("SELECT count(*) FROM wp_posts WHERE post_type = 'pets' AND post_author = ".$USER_ID );
			if( $mascotas == 0 ){
				$error = "
					<h1 align='justify'>Debes cargar por lo menos una mascota para poder realizar una reserva.</h1>
					<h2 align='justify'>Pícale <a href='".get_home_url()."/perfil-usuario/mascotas/' style='color: #00b69d; font-weight: 600;'>Aquí</a> para agregarlas.<h2>
				";
			}
		}

		if( $error  == ""){
			$mascotas__ = $busqueda["mascotas"];
			if( is_array($mascotas__) && in_array("gatos", $mascotas__) ) {
				if( $atributos["gatos"] == "Si" && !$tieneGatos ){
					$error = "
						<h1 align='justify'>Debes cargar por lo menos un <strong>Gato</strong> para poder realizar esta reserva.</h1>
						<h2 align='justify'>Pícale <a href='".get_home_url()."/perfil-usuario/mascotas/nueva/' style='color: #00b69d; font-weight: 600;'>Aquí</a> para agregarlo.<h2>
					";
				}
			}
		}

		if( $error  == "" ){
			if( $atributos["gatos"] != "Si" && !$tienePerros ){
				$error = "
					<h1 align='justify'>Debes cargar por lo menos un <strong>Perro</strong> para poder realizar esta reserva.</h1>
					<h2 align='justify'>Pícale <a href='".get_home_url()."/perfil-usuario/mascotas/nueva/' style='color: #00b69d; font-weight: 600;'>Aquí</a> para agregarlo.<h2>
				";
			}
		}

		if( $error != "" ){
			$actual = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$referencia = $_SERVER['HTTP_REFERER'];
			if( $actual == $referencia ){ $referencia = get_home_url(); }
			$HTML .= "
				<style>
					body{ font-family: Arial; }
					.vlz_modal{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; display: table; z-index: 10000; background: rgba(0, 0, 0, 0.8); vertical-align: middle !important; }
					h1{ font-size: 18px; }
					h2{ font-size: 16px; }
					.vlz_modal_interno{ display: table-cell; text-align: center; vertical-align: middle !important; }
					.vlz_modal_ventana{ position: relative; display: inline-block; width: 60%!important; text-align: left; box-shadow: 0px 0px 4px #FFF; border-radius: 5px; z-index: 1000; }
					.vlz_modal_titulo{ background: #FFF; padding: 15px 10px; font-size: 18px; color: #52c8b6; font-weight: 600; border-radius: 5px 5px 0px 0px; }
					.vlz_modal_contenido{ background: #FFF; height: 450px; box-sizing: border-box; padding: 5px 15px; border-top: solid 1px #d6d6d6; border-bottom: solid 1px #d6d6d6; overflow: auto; text-align: justify; height: auto; }
					.vlz_modal_pie{ background: #FFF; padding: 15px 10px; border-radius: 0px 0px 5px 5px; border-radius: 0px 0px 5px 5px!important; height: auto; overflow: hidden; }
					.vlz_modal_fondo{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 500; }
					.vlz_boton_siguiente{ padding: 10px 50px; display: inline-block; font-size: 16px; border: solid 1px #00d2c6; border-radius: 3px; float: right; cursor: pointer; background-color: #00d2c6; color: #FFF; } 
					@media screen and (max-width: 750px){ .vlz_modal_ventana{ width: 90% !important; } }
				</style>
				<div id='jj_modal_ir_al_inicio' class='vlz_modal'>
					<div class='vlz_modal_interno'>
						<div class='vlz_modal_ventana jj_modal_ventana'S>
							<div class='vlz_modal_titulo'>¡Oops!</div>
							<div class='vlz_modal_contenido'>".$error."</div>
							<div class='vlz_modal_pie'>
								<a href='".$referencia."' ><input type='button' style='text-align: center;' class='vlz_boton_siguiente' value='Volver'/></a>
							</div>
						</div>
					</div>
				</div>
			";
			echo comprimir($HTML);
			exit();
		}
		
	}

	function COMPROBAR_ERRORES_CONOCER(){
		global $wpdb;
		global $USER_ID;

		global $tieneGatos;
		global $tienePerros;

		$error = "";
		if( $USER_ID  == ""){
			$error = "
				<h1 align='justify'>Debes iniciar sesión para poder realizar reservas.</h1>
				<h2 align='justify'>
					Pícale <span id='cerrarModal' onclick=\"document.getElementById('login').click(); jQuery('.vlz_modal').css('display', 'none')\" style='color: #00b69d; font-weight: 600; cursor: pointer;'>Aquí</span> para acceder a kmimos.
				<h2>
			";
		}
		if( get_cupos_conocer($USER_ID) > 0 ){
			$error = "
				<h1 align='justify'>Usted ya cuenta con cupos para realzar solicitudes</h1>
				<h2 align='justify'>
					Pícale <span id='cerrarModal' onclick=\"document.getElementById('login').click(); jQuery('.vlz_modal').css('display', 'none')\" style='color: #00b69d; font-weight: 600; cursor: pointer;'>Aquí</span> para acceder a kmimos.
				<h2>
			";
		}

		if( $error != "" ){
			$actual = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$referencia = $_SERVER['HTTP_REFERER'];
			if( $actual == $referencia ){ $referencia = get_home_url(); }
			$HTML .= "
				<style>
					body{ font-family: Arial; }
					.vlz_modal{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; display: table; z-index: 10000; background: rgba(0, 0, 0, 0.8); vertical-align: middle !important; }
					h1{ font-size: 18px; }
					h2{ font-size: 16px; }
					.vlz_modal_interno{ display: table-cell; text-align: center; vertical-align: middle !important; }
					.vlz_modal_ventana{ position: relative; display: inline-block; width: 60%!important; text-align: left; box-shadow: 0px 0px 4px #FFF; border-radius: 5px; z-index: 1000; }
					.vlz_modal_titulo{ background: #FFF; padding: 15px 10px; font-size: 18px; color: #52c8b6; font-weight: 600; border-radius: 5px 5px 0px 0px; }
					.vlz_modal_contenido{ background: #FFF; height: 450px; box-sizing: border-box; padding: 5px 15px; border-top: solid 1px #d6d6d6; border-bottom: solid 1px #d6d6d6; overflow: auto; text-align: justify; height: auto; }
					.vlz_modal_pie{ background: #FFF; padding: 15px 10px; border-radius: 0px 0px 5px 5px; border-radius: 0px 0px 5px 5px!important; height: auto; overflow: hidden; }
					.vlz_modal_fondo{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 500; }
					.vlz_boton_siguiente{ padding: 10px 50px; display: inline-block; font-size: 16px; border: solid 1px #00d2c6; border-radius: 3px; float: right; cursor: pointer; background-color: #00d2c6; color: #FFF; } 
					@media screen and (max-width: 750px){ .vlz_modal_ventana{ width: 90% !important; } }
				</style>
				<div id='jj_modal_ir_al_inicio' class='vlz_modal'>
					<div class='vlz_modal_interno'>
						<div class='vlz_modal_ventana jj_modal_ventana'S>
							<div class='vlz_modal_titulo'>¡Oops!</div>
							<div class='vlz_modal_contenido'>".$error."</div>
							<div class='vlz_modal_pie'>
								<a href='".$referencia."' ><input type='button' style='text-align: center;' class='vlz_boton_siguiente' value='Volver'/></a>
							</div>
						</div>
					</div>
				</div>
			";
			echo comprimir($HTML);
			exit();
		}
		
	}

	function get_filtros_user($USER_ID){
		global $wpdb;
		global $cuidador;

		$filtros = [];

		$filtros_txt = array(
			"agresivo_mascotas" => "Agresivas con otras mascotas",
			"agresivo_personas" => "Agresivas con humanos",
			"pequenos" => "Peque&ntilde;as",
			"medianos" => "Medianas",
			"grandes" => "Grandes",
			"gigantes" => "Gigantes"
		);

		$filtros = array(
			"agresivo_mascotas" => 0,
			"agresivo_personas" => 0,
			"pequenos" => 0,
			"medianos" => 0,
			"grandes" => 0,
			"gigantes" => 0
		);

		$_mascotas = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_author = '{$USER_ID}' AND post_type = 'pets' AND post_status = 'publish' ");
		$mascotas = array();
		foreach ($_mascotas as $key => $value) {
			$_metas = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE post_id = '{$value->ID}' AND meta_key IN ('aggressive_with_humans', 'aggressive_with_pets', 'size_pet')");
			$metas = array();
			foreach ($_metas as $key2 => $value2) {
				$metas[ $value2->meta_key ] = $value2->meta_value;
				switch ( $value2->meta_key ) {
					case 'aggressive_with_humans':
						if( $value2->meta_value == 1 ){
							$filtros["agresivo_personas"] = 1;
						}
					break;
					case 'aggressive_with_pets':
						if( $value2->meta_value == 1 ){
							$filtros["agresivo_mascotas"] = 1;
						}
					break;
					case 'size_pet':
						switch ($value2->meta_value) {
							case 0:
								$filtros["pequenos"] = 1;
							break;
							case 1:
								$filtros["medianos"] = 1;
							break;
							case 2:
								$filtros["grandes"] = 1;
							break;
							case 3:
								$filtros["gigantes"] = 1;
							break;
						}
					break;
				}
			}
			$mascotas[] = $metas;
		}
		
		foreach ($filtros as $key => $value) {
			if( $value == 0 ){
				unset($filtros[$key]);
			}
		}

		$FILTRO_ESPECIA = array();

		$tamanos_aceptados = unserialize( $cuidador->tamanos_aceptados );
		$conductas = unserialize( $cuidador->comportamientos_aceptados );

		if( $filtros["agresivo_mascotas"] == 1 ){
			if( $conductas["agresivos_perros"]+0 == 0 && $conductas["agresivos_mascotas"]+0 == 0 ){
				$filtros["agresivo_mascotas"]++;
			}
		}

		if( $filtros["agresivo_personas"] == 1 ){
			if( $conductas["agresivos_personas"]+0 == 0 && $conductas["agresivos_humanos"]+0 == 0 ){
				$filtros["agresivo_personas"]++;
			}
		}

		foreach ($filtros as $key => $value) {
			if( $key != "agresivo_mascotas" && $key != "agresivo_personas" ){
				if( $tamanos_aceptados[ $key ]+0 == 0 ){
					$filtros[ $key ]++;
				}
			}
		}

		return [
			$filtros,
			$mascotas
		];
	}

	function set_uso_banner($params){
		extract($params);
		if( !isset($_SESSION) ){ session_start(); }
        global $wpdb;

        /*
			user_id
			type
			reserva_id > Opcional
			conocer_id > Opcional
        */

		$item = $wpdb->get_row("SELECT * FROM usos_banner WHERE user_id = '{$user_id}' ");
		if( $item == false ){ 
			$wpdb->query("INSERT INTO usos_banner VALUES (NULL, '{$user_id}', '', '', NOW(), '' )"); 
			$item = $wpdb->get_row("SELECT * FROM usos_banner WHERE user_id = '{$user_id}' ");
		}

		switch ( $type ) {
			case 'reserva':
				if( $item->reservas == "" ){
					$reservas = [];
				}else{
					$reservas = json_decode($item->reservas);
				}
				$reservas[] = $reserva_id;
				$reservas = json_encode($reservas);
				$wpdb->query("UPDATE usos_banner SET reservas = '{$reservas}' WHERE id = '{$item->id}';");
			break;
			case 'conocer':
				if( $item->conocer == "" ){
					$conocer = [];
				}else{
					$conocer = json_decode($item->conocer);
				}
				$conocer[] = $conocer_id;
				$conocer = json_encode($conocer);
				$wpdb->query("UPDATE usos_banner SET conocer = '{$conocer}' WHERE id = '{$item->id}';");
			break;
		}

		if( isset($tag) && !empty($tag) ){
			$wpdb->query("UPDATE usos_banner SET tag = '{$tag}' WHERE id = '{$item->id}';");
		}

        
	}

	function get_recurso($tipo){
		return getTema()."/recursos/".$tipo."/";
	}

	function get_destacados_new(){
		if( !isset($_SESSION) ){ session_start(); }
        global $wpdb;
        $_POST = $_SESSION['busqueda'];

        if( $_POST["descuento"] == 1 || $_POST["flash"] == 1 ){
        	return "";
        }

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
            if( count($resultados) > 0 ){
	            foreach ($resultados as $key => $_cuidador) {
	                if( in_array($_cuidador->id, $destacados) && $_cuidador->DISTANCIA <= 30 ){
	                    $cont++;
	                    $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$_cuidador->id}");

	                    $agregar = true;
	                    $adicionales = unserialize($cuidador->adicionales);
	                    if( ( $_SESSION['landing_paseos'] == 'yes' ) && $adicionales["status_paseos"] != 1 ){
	                    	$agregar = false;
	                    }

	                    if( $agregar){
		                    $data = $wpdb->get_row("SELECT post_title AS nom, post_name AS url FROM wp_posts WHERE ID = {$cuidador->id_post}");
		                    $nombre = $data->nom;
		                    $img_url = kmimos_get_foto($cuidador->user_id);
		                    $url = get_home_url() . "/petsitters/" . $cuidador->user_id;
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
		                		"valoraciones" => $cuidador->valoraciones,
			                	"latitud" => $cuidador->latitud,
			                	"longitud" => $cuidador->longitud
		                    ];
	                    }
	                }
	                if( $cont >= 4 ){ break; }
	            }
            }
        }else{
            $ubicacion = explode("_", $_POST["ubicacion"]);
            if( count($ubicacion) > 0 ){ $estado = $ubicacion[0]; }
            $estado_des = $wpdb->get_var("SELECT name FROM states WHERE id = ".$estado);
            $sql_top = "SELECT * FROM destacados WHERE estado = '{$estado}'";
            $tops = $wpdb->get_results($sql_top);
            foreach ($tops as $value) {
            	if( in_array($value->cuidador, $_SESSION['cuidadores']) ){
	                $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$value->cuidador}");
	                $data = $wpdb->get_row("SELECT post_title AS nom, post_name AS url FROM wp_posts WHERE ID = {$cuidador->id_post}");
	                $nombre = $data->nom;
	                $img_url = kmimos_get_foto($cuidador->user_id);
	                $url = get_home_url() . "/petsitters/" . $cuidador->user_id;
	                $anios_exp = $cuidador->experiencia;
	                if( $anios_exp > 1900 ){
	                    $anios_exp = date("Y")-$anios_exp;
	                }
	                $DESTACADOS_ARRAY[] = [
	                	"img" => $img_url,
	                	"id_post" => $cuidador->id_post,
	                	"nombre" => $nombre,
	                	"url" => $url,
	                	"desde" => ($cuidador->hospedaje_desde*getComision()),
	                	"ranking" => kmimos_petsitter_rating($cuidador->id_post),
	                	"experiencia" => $anios_exp,
	                	"valoraciones" => $cuidador->valoraciones,
	                	"latitud" => $cuidador->latitud,
	                	"longitud" => $cuidador->longitud
	                ];
            	}
            }
        }

        $user_id = get_current_user_id();
        $favoritos = get_favoritos();

        if( is_array($DESTACADOS_ARRAY) && count($DESTACADOS_ARRAY) > 0 ){
	        foreach ($DESTACADOS_ARRAY as $key => $destacado) {

	        	$fav_check = 'false';
		        $fav_del = '';
		        $fav_img = 'Corazon';
		        if (in_array($destacado["id_post"], $favoritos)) {
		            $fav_check = 'true'; 
		            $favtitle_text = esc_html__('Quitar de mis favoritos','kmimos');
		            $fav_del = 'favoritos_delete';
		            $fav_img = 'Favorito';
		        }

		        $favorito_movil = '
		        	<div 
		        		class="favorito '.$fav_del.'" '.$style_icono.'" 
		        		data-reload="false"
			            data-user="'.$user_id.'" 
			            data-num="'.$destacado["id_post"].'" 
			            data-active="'.$fav_check.'"
			            data-favorito="'.$fav_check.'"
		        	></div>
		        ';

		        $distancia = '';
		        if( isset($destacado["distancia"]) ){
		        	$distancia = '<div class="desacado_experiencia">a '.$destacado["distancia"].' de tu búsqueda</div>';
		        }

	        	$top_destacados .= '
	        		<div class="destacados_item" data-latitud="'.$destacado["latitud"].'" data-longitud="'.$destacado["longitud"].'">
	        			<div class="desacado_img">
	        				<div class="desacado_img_interna" style="background-image: url( '.$destacado["img"].' );"></div>
	        			</div>
	        			<div class="desacado_img_normal" style="background-image: url( '.$destacado["img"].' );"></div>
        				'.$favorito_movil.'
	        			<div class="desacado_title">
	        				<span>Dest</span> '.$destacado["nombre"].'
	        			</div>
	        			<div class="desacado_experiencia">'.$destacado["experiencia"].' años de experiencia</div>
	        			<div class="desacado_monto">Desde <strong>MXN $ '.round($destacado["desde"]).'</strong></div>
	        			'.$distancia.'
	        			<div class="desacado_ranking_container">'.$destacado["ranking"].'</div>
	        			<div class="desacado_experiencia">'.$destacado["valoraciones"].' valoraciones</div>
	        			<a class="desacado_boton_reservar" href="'.$destacado["url"].'">Reservar</a>
	        			<a class="desacado_reservar_abs" href="'.$destacado["url"].'"></a>
	        		</div>
	            ';
	        }
	        $top_destacados = '
	        	<div class="destacados_container"  data-total="'.(count($DESTACADOS_ARRAY)).'" data-actual="0">
	        		<h2>Cuidadores destacados</h2>
	        		<div class="destacados_box">'.$top_destacados.'</div>
	        	</div>
				<div class="Flecha_Izquierda Ocultar_Flecha">
					<img onclick="destacadoAnterior( jQuery(this) );" src="'.get_recurso("img").'BUSQUEDA/SVG/iconos/Flecha_Izquierda.svg" />
				</div>
				<div class="Flecha_Derecha '.$ocultar_siguiente_img.'">
					<img onclick="destacadoSiguiente( jQuery(this) );" src="'.get_recurso("img").'BUSQUEDA/SVG/iconos/Flecha_Derecha.svg" />
				</div>';
        }

        return comprimir($top_destacados);
	}

	function get_resultados_new($PAGE = 0){
		if( !isset($_SESSION) ){ session_start(); }
        global $wpdb;
		$HTML = ""; 
		$user_id = get_current_user_id();
		$favoritos = get_favoritos();
		$resultados = $_SESSION['resultado_busqueda'];
		$total = count($resultados);

		$orden_default = $_SESSION['orden_default'];

		$_PAGE = $PAGE / 10;
		for ($invertir_orden=0; $invertir_orden <= 1; $invertir_orden++) 
		{
			if( $orden_default == 'NO' || $total <= 10 ){
				$testing = 'DEF';
				$invertir_orden = 2;
				$fin = ( $total > ($PAGE+10) ) ? $PAGE+10 : $total;
				$contador = ( $_PAGE>0 )? $_PAGE * 10 : $PAGE ;
			}else{
				# Ajustar numero de cuidadores por pagina
				if( !isset($contador) ){
					$PAGE = $_PAGE * 8;
					$contador = ( $_PAGE>0 )? $_PAGE * 10 : $PAGE ;
				}

				# Resultados invertidos
				if( $invertir_orden == 1 ){
					$testing = 'INV';

					// BEGIN Random
					if( isset($_SESSION['random_by_page'][ $PAGE ]) && $_SESSION['random_by_page'][ $PAGE ] > 0 ){
						$PAGE = $_SESSION['random_by_page'][ $PAGE ];
					}else{
						$fin_paginas = $total / 2;
						$fin_paginas = round( $fin_paginas, 0, PHP_ROUND_HALF_DOWN );
						$rand_page = rand( 1, $fin_paginas );
						$_SESSION['random_by_page'][ $PAGE ] = $rand_page;
						$PAGE = $rand_page;
					}
					// END Random


					$fin = ( $total > ($PAGE+2) ) ? $PAGE+2 : $total;
					$resultados = array_reverse($resultados, false);



				# Resultados ordenados
				}else{				
					$testing = 'ORD';
					$fin = ( $total > ($PAGE+8) ) ? $PAGE+8 : $total;			
				}
			}

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
				$HTML .= crear_ficha_busqueda([
					'testing' => $testing,
					'i' => $contador,
					'cuidador'=>$cuidador, 
					'favoritos'=>$favoritos,
					'user_id'=>$user_id,
				]);
			}
		}

		return $HTML;
	}

	function crear_ficha_busqueda($parametros=[]){
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
			if( $_SESSION['landing_paseos'] == 'yes' ){
				$desde = $_cuidador->paseos_desde;
			}
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

			$fav_check = 'false';
	        $fav_del = '';
	        $fav_img = 'Corazon';
	        if (in_array($_cuidador->id_post, $favoritos)) {
	            $fav_check = 'true'; 
	            $favtitle_text = esc_html__('Quitar de mis favoritos','kmimos');
	            $fav_del = 'favoritos_delete';
	            $fav_img = 'Favorito';
	        }

	        $favorito_movil = '
	        	<div 
	        		class="favorito '.$fav_del.'" 
	        		data-reload="false"
		            data-user="'.$user_id.'" 
		            data-num="'.$_cuidador->id_post.'" 
		            data-active="'.$fav_check.'"
		            data-favorito="'.$fav_check.'"
	        	></div>
	        ';

			$comentario = '';
			if( isset($_cuidador->comentario->comment_author_email) ){
				if( strlen($_cuidador->comentario->comment_content) > 200 ){
					$_cuidador->comentario->comment_content = mb_strcut($_cuidador->comentario->comment_content, 0, 200, "UTF-8")."...";
				}
				$comentario = '
					<div class="resultados_item_comentario">
						<div class="resultados_item_comentario_img">
							<div class="resultados_item_comentario_avatar" style="background-image: url( '.$_cuidador->comentario->foto.' );"></div>
						</div>
						<div class="resultados_item_comentario_contenido">
							'.( $_cuidador->comentario->comment_content ).' <a href="'.get_home_url().'/petsitters/'.$_cuidador->user_id.'/#km-comentario">(Ver más)</a>
						</div>
						<div class="resultados_item_comentario_favorito">
							<span>
								'.$favorito_movil.'
							</span>
						</div>
					</div>
				';
			}else{
				$comentario = '
					<div class="resultados_item_comentario">
						<div class="resultados_item_comentario_img"></div>
						<div class="resultados_item_comentario_contenido"></div>
						<div class="resultados_item_comentario_favorito">
							<span>
								'.$favorito_movil.'
							</span>
						</div>
					</div>
				';
			}

			$galeria =
				'<div class="resultados_item_info_img" style="background-image: url('.$img_url.');">'.
					'<div class="img_fondo" style="background-image: url('.$img_url.');"></div>'.
					'<div class="img_normal" style="background-image: url('.$img_url.');"></div>'.
				'</div>';
			if( is_array($_cuidador->galeria) ){
				foreach ($_cuidador->galeria as $key => $value) {
					$galeria .=
						'<div class="resultados_item_info_img">'.
							'<div class="img_fondo" style="background-image: url('.get_home_url().'/wp-content/uploads/cuidadores/galerias/'.$value.');"></div>'.
							'<div class="img_normal" style="background-image: url('.get_home_url().'/wp-content/uploads/cuidadores/galerias/'.$value.');"></div>'.
						'</div>';
				}
			}
			if( is_array($_cuidador->galeria) ){
				$ocultar_siguiente_img = ( count($_cuidador->galeria) > 1 ) ? '': 'Ocultar_Flecha';
			}else{
				$ocultar_siguiente_img = 'Ocultar_Flecha';
			}

			if( isset($cuidador->DISTANCIA) ){
				$distancia = '<div class="resultados_item_subtitulo">a '.floor($cuidador->DISTANCIA).' km de tu búsqueda</div>';
			}

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

			if( !is_array($_cuidador->galeria) ){
				$_cuidador->galeria = [];
			}

			$btn_conocer = '<strong>No disponible para conocer</strong>';
			if( $_cuidador->activo_hoy ){

				$hospedaje_id = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_type = 'product' AND post_author = '{$_cuidador->user_id}' AND post_name LIKE '%hospedaje%'");
				$reservar_url = 'reservar/'.$hospedaje_id;
				$data_url = 'petsitters/'.$_cuidador->url.'?r=1';

				$btn_conocer = '
					<a 
						role="button" href="#" 
                        data-name="'.$_cuidador->titulo.'" 
                        data-id="'.$_cuidador->id_post.'" 
                        data-url="'.$data_url.'" 
                        data-reservar="'.$reservar_url.'" 
                        data-target="#popup-conoce-cuidador"
                        href="#" class="boton boton_border_gris"
                        onclick="evento_google_kmimos(\'conocer_busqueda\'); evento_fbq_kmimos(\'conocer_busqueda\');"
                    >
						<span class="boton_conocer_PC">Solicitud de conocer</span>
						<span class="boton_conocer_MOVIl"><span class="boton_conocer_MOVIl">Conocer</span>
					</a>
				';
			}

			$por_noche_paseo = ( $_SESSION['landing_paseos'] == 'yes' ) ? 'Por paseo' : 'Por noche';

			$HTML .= '
				<div class="resultado_item">
					<div class="resultados_hover"></div>
					<div class="resultado_item_container">
						<div class="resultados_item_top">

							<div class="resultados_item_iconos_container '.$ocultar_todo.'">
								<div class="resultados_item_icono icono_disponibilidad '.$ocultar_flash.' '.$show_msg_desc.'">
									<span class="disponibilidad_PC">'.$mensaje_disp.'</span>
									<span class="disponibilidad_MOVIl">
										<div class="msg_largo">'.$mensaje_disp_movil.'</div> 
										<div class="msg_corto">'.$mensaje_disp_movil_corto.'</div>
									</span>
								</div>
								<div class="resultados_item_icono icono_flash '.$ocultar_flash_none.'"><span></span></div>
								<div class="resultados_item_icono icono_descuento '.$ocultar_descuento.'"><span></span></div>
								<div class="resultados_item_icono icono_geo '.$ocultar_geo.'"><span></span></div>
							</div>

						</div>
						<div class="resultados_item_middle">
							<div class="resultados_item_info_container">
								<div class="resultados_item_info_img_container" data-total="'.(count($_cuidador->galeria)+1).'" data-actual="0">
									<a href="'.get_home_url().'/petsitters/'.$_cuidador->user_id.'" class="resultados_item_info_img_box">
										'.$galeria.'
									</a>
									<img onclick="imgAnterior( jQuery(this) );" class="Flechas Flecha_Izquierda Ocultar_Flecha" src="'.get_recurso("img").'BUSQUEDA/SVG/iconos/Flecha_2.svg" />
									<img onclick="imgSiguiente( jQuery(this) );" class="Flechas Flecha_Derecha '.$ocultar_siguiente_img.'" src="'.get_recurso("img").'BUSQUEDA/SVG/iconos/Flecha_1.svg" />
								</div>
								<div class="resultados_item_info">
									<a href="'.get_home_url().'/petsitters/'.$_cuidador->user_id.'" class="resultados_item_titulo"> <span>'.$i.'.</span> '.($_cuidador->titulo).'</a>
									'.$distancia.'
									<div class="resultados_item_direccion" title="'.$_cuidador->direccion.'">'.($direccion).'</div>
									<div class="resultados_item_servicios">
										'.get_servicios_new($_cuidador->adicionales).'
										<!-- <div class="resultados_item_comentarios">
											'.$_cuidador->valoraciones.' comentarios
										</div> -->
											<div class="resultados_item_ranking">
												'.kmimos_petsitter_rating($_cuidador->id_post).'
											</div>
									</div>
									<div class="resultados_item_experiencia">
										'.$anios_exp.' años de experiencia
									</div>
									<div class="resultados_item_precio_container">
										<span>Desde</span>
										<div>MXN$ <strong>'.$desde[0].'<span>,'.$desde[1].'</span></strong></div>
										<span class="por_noche">'.$por_noche_paseo.'</span>
									</div>
									<div class="resultados_item_ranking_movil">
										'.kmimos_petsitter_rating($_cuidador->id_post).'
									</div>
									<div class="resultados_item_valoraciones">
										'.$_cuidador->valoraciones.' valoraciones
									</div>
									'.$comentario.'
								</div>
							</div>
						</div>
						<div class="resultados_item_bottom">
							'.$btn_conocer.'
							<a href="'.get_home_url().'/petsitters/'.$_cuidador->user_id.'" onclick="evento_google_kmimos(\'reservar_busqueda\'); evento_fbq_kmimos(\'reservar_busqueda\');" class="boton boton_verde">Reservar</a>
						</div>
					</div>
				</div>
			';
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
            
            //$adicionales = unserialize($adicionales);
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

    function pre_carga_data_cuidadores($cuidadores = null){
    	global $wpdb;

    	if( !isset($_SESSION) ){ session_start(); }

    	$con_cuidadores = ( $cuidadores != null ) ? ' AND cuidadores.id IN ('.implode(",", $cuidadores).')' : '';

    	$cuidadores = $wpdb->get_results("
    		SELECT 
    			cuidadores.id,
    			cuidadores.activo,
    			cuidadores.user_id,
    			cuidadores.id_post,
    			cuidadores.experiencia,
    			cuidadores.latitud,
    			cuidadores.longitud,
    			cuidadores.direccion,
    			cuidadores.hospedaje_desde,
    			cuidadores.paseos_desde,
    			cuidadores.adicionales,
    			cuidadores.atributos,
    			cuidadores.rating,
    			cuidadores.valoraciones,
    			cuidadores.titulo,
    			cuidadores.estados,
    			cuidadores.municipios,
    			cuidadores.url
    		FROM 
    			cuidadores
    		WHERE 
    			activo = 1 ".$con_cuidadores."
    	");

    	$_cuidadores_user_id = [];
    	$_cuidadores = [];
    	foreach ($cuidadores as $key => $value) {
    		$_cuidadores_user_id[ $value->id ] = $value->user_id;
    		$cuidadores[ $key ]->adicionales = unserialize($value->adicionales);
    		$cuidadores[ $key ]->atributos = unserialize($value->atributos);

    		$galeria = get_galeria($value->id);
    		$cuidadores[ $key ]->galeria = $galeria[0];
    		$cuidadores[ $key ]->galeria_normales = $galeria[1];

    		$activo_hoy = get_cupos_by_user_id( $value->user_id );
    		$cuidadores[ $key ]->activo_hoy = ( $activo_hoy == null ) ? true: false;

    		$cuidadores[ $key ]->comentario = get_comment_cuidador($value->id_post);

			$desde = $value->hospedaje_desde;
    		if( $value->hospedaje_desde == 0 ){
				$adic = $cuidadores[ $key ]->adicionales;
				foreach ($adic as $key_1 => $value_1) {
					if( is_array($value_1) && count($value_1) >= 4 ){
						foreach ($value_1 as $key_2 => $value_2) {
							if( $desde == 0 ){
								$desde = $value_2;
							}
							if( $value_2 != 0 && $value_2 < $desde ){
								$desde = $value_2;
							}
						}
					}
				}
			}

			$cuidadores[ $key ]->hospedaje_desde = $desde;

			if( $_SESSION['landing_paseos'] == 'yes' ){
				$cuidadores[ $key ]->hospedaje_desde = $cuidadores[ $key ]->paseos_desde;
			}

    		$_cuidadores[ $value->id ] = $cuidadores[ $key ];
    		$_cuidadores_user_id[ $value->user_id ] = $value->id;

    		$_SESSION["DATA_CUIDADORES"][ $value->id ] = $cuidadores[ $key ];
			$_SESSION["CUIDADORES_USER_ID"][ $value->user_id ] = $value->id;

    	}

    	// return [ $_cuidadores, $_cuidadores_user_id ];

    }

    function get_galeria($cuidador_id){
		$id_cuidador = ($cuidador_id)-5000;
		$sub_path_galeria = "/".$id_cuidador."/";
		$path_galeria = dirname(dirname(dirname(dirname(__DIR__))))."/wp-content/uploads/cuidadores/galerias/".$id_cuidador."/";
		$galeria_array = array();
		if( is_dir($path_galeria) ){
			if ($dh = opendir($path_galeria)) { 
				$imagenes_mini = array();
				$imagenes_normales = array();
				$cont = 0;
		        while ( ( ($file = readdir($dh)) !== false ) ) { 
		            if (!is_dir($path_galeria.$file) && $file!="." && $file!=".."){ 
		               	$imagenes_normales[] = $sub_path_galeria.$file;
		               	if( $cont <= 7 ){
		               		$imagenes_mini[] = $sub_path_galeria.$file;
		               	}
		            } 
		            $cont++;
		        } 
		      	closedir($dh);
		      	return [ $imagenes_mini, $imagenes_normales ];
	  		} 
		}
		return "";
    }

    function get_comment_cuidador($cuidador_post_id){
    	global $wpdb;

    	$comentario = $wpdb->get_row("
    		SELECT 
    			c.comment_author_email,
    			c.comment_content 
    		FROM 
    			wp_comments AS c
    		INNER JOIN wp_commentmeta AS m ON ( m.comment_id = c.comment_ID AND m.meta_key = 'trust' )
    		WHERE 
    			c.comment_post_ID = {$cuidador_post_id} AND c.comment_approved = 1 AND c.comment_content != ''
    		ORDER BY 
    			c.comment_ID DESC
    		LIMIT 0, 1
    	");

    	if( !empty($comentario) ){
    		$user_id = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_email = '{$comentario->comment_author_email}'");
			$comentario->foto = kmimos_get_foto( $user_id );
		}else{
			$comentario = false;
		}

    	return $comentario;
    }
?>