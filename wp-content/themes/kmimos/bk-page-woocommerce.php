<?php 
    /*
        Template Name: Woocommerce
    */

    error_reporting(0);

    if( !isset($_SESSION) ){ session_start(); }

	$post_id = vlz_get_page();

	global $wpdb;

	$author = $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = ".$post_id);
	$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = ".$author);

	if( $cuidador->activo == 0 ){
		header("location: ".get_home_url());
	}

    wp_enqueue_style('producto', getTema()."/css/producto.css", array(), '1.0.0');
	wp_enqueue_style('producto_responsive', getTema()."/css/responsive/producto_responsive.css", array(), '1.0.0');

	wp_enqueue_script('producto', getTema()."/js/producto.js", array("jquery"), '1.0.0');
    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');

	wp_enqueue_script('openpay-v1', getTema()."/js/openpay.v1.min.js", array("jquery"), '1.0.0');
	wp_enqueue_script('openpay-data', getTema()."/js/openpay-data.v1.min.js", array("jquery", "openpay-v1"), '1.0.0');
    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');


	get_header();

		date_default_timezone_set('America/Mexico_City');

		if( !isset($_SESSION)){ session_start(); }
		$post = get_post( $post_id );
		$D = $wpdb;
		$id_user = get_current_user_id();
		$busqueda = getBusqueda();
		$servicio_id = $post_id;
		$hoy = date("Y-m-d");

		$cats = array(
            2601 => "paseos"                    ,
            2602 => "adiestramiento_basico"     ,
            2606 => "adiestramiento_intermedio" ,
            2607 => "adiestramiento_avanzado"   ,
            2599 => "guarderia"                 ,
            2598 => "hospedaje"                 
        );

		$cupos = $wpdb->get_results("SELECT * FROM cupos WHERE servicio = '{$servicio_id}' AND fecha >= '".date("Y-m-d", time())."'" );

		$sql = "
	        SELECT
	            tipo_servicio.term_id AS slug
	        FROM 
	            wp_term_relationships AS relacion
	        LEFT JOIN wp_terms as tipo_servicio ON ( tipo_servicio.term_id = relacion.term_taxonomy_id )
	        WHERE 
	            relacion.object_id = '{$servicio_id}' AND
	            relacion.term_taxonomy_id != 28
	    ";
		$tipo = $wpdb->get_var($sql);

		$cuidador = $wpdb->get_row( "SELECT * FROM cuidadores WHERE user_id = ".$post->post_author );

		$cuidador_name = $wpdb->get_var( "SELECT post_title FROM wp_posts WHERE ID = ".$cuidador->id_post );
		$servicio_name = $wpdb->get_var( "SELECT post_title FROM wp_posts WHERE ID = ".$servicio_id );

		$servicio_name_corto = explode(" - ", $servicio_name);
		$servicio_name_corto = $servicio_name_corto[0];

		$USER_ID = $id_user;

		$tieneGatos = tieneGatos();
		$tienePerros = tienePerros();


		if( $USER_ID != "" ){

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

		}

	    $precios = "";
	    
		$adicionales = unserialize($cuidador->adicionales);
		$precargas = array();
		$id_seccion = 'MR_'.get_the_ID()."_".md5($id_user);
        if( isset($_SESSION[$id_seccion] ) ){
        	$cupos_menos = $_SESSION[$id_seccion]["variaciones"]["cupos"];
        	$ini = strtotime( $_SESSION[$id_seccion]["fechas"]["inicio"] );
        	$fin = strtotime( $_SESSION[$id_seccion]["fechas"]["fin"] );
        	foreach ($cupos as $value) {
        		$xfecha = strtotime( $value->fecha );
        		if( $ini >= $xfecha && $xfecha <= $fin ){
        			$value->cupos -= $cupos_menos;
        			$value->full = 0;
        			$value->no_disponible = 0;
        		}
        	}
            $HTML .= "
                <a href='".getTema()."/procesos/perfil/update_reserva.php?b=".get_the_ID()."_".md5($id_user)."' class='theme_button btn_modificar'>
                    Salir de modificar reserva
                </a>
            ";
            $busqueda["checkin"] = date("d/m/Y", strtotime($_SESSION[$id_seccion]["fechas"]["inicio"]) );
            $busqueda["checkout"] = date("d/m/Y", strtotime($_SESSION[$id_seccion]["fechas"]["fin"]) );

            $precargas["tamanos"] = $_SESSION[$id_seccion]["variaciones"];
            if( isset($_SESSION[$id_seccion]["transporte"][0])){
            	$precargas["transp"] = $_SESSION[$id_seccion]["transporte"][0];
            }
            $precargas["adicionales"] = $_SESSION[$id_seccion]["adicionales"];
        }

        if( isset($cats[$tipo]) ){
	   		$tipo = $cats[$tipo];
        }

	    if( $tipo == "hospedaje" ){
	    	$precios = getPrecios( unserialize($cuidador->hospedaje), $precargas["tamanos"], unserialize($cuidador->tamanos_aceptados) );
	    }else{
	    	$precios = getPrecios( $adicionales[ $tipo ], $precargas["tamanos"], unserialize($cuidador->tamanos_aceptados) );
	    } 

		$transporte = getTransporte($adicionales, $precargas["transp"]);
		if( $transporte != "" ){
			$transporte = '
				<div class="km-service-title"> TRANSPORTACI&Oacute;N </div>
				<div class="km-services">
					<select id="transporte" name="transporte" class="km-input-custom"><option value="">SELECCIONE UNA OPCI&Oacute;N</option>'.$transporte.'</select>
				</div>
			';
		}

		$adicionales = getAdicionales($adicionales, $precargas["adicionales"]);
		if( $adicionales != "" ){
			$adicionales = '
				<div class="km-service-title"> SERVICIOS ADICIONALES </div>
				<div id="adicionales" class="km-services">
					'.$adicionales.'
				</div>
			';
		}

		$productos .= '</div>';

		$email = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID = {$id_user}");

		$saldo = getSaldo();

		$saldoTXT = "";
		$saldoTXT = $saldo["cupon"];

		$atributos = unserialize($cuidador->atributos);

		$error = "";
		if( $id_user  == ""){
			$error = "
				<h1 align='justify'>Debes iniciar sesión para poder realizar reservas.</h1>
				<h2 align='justify'>Pícale <span id='cerrarModal' onclick=\"document.getElementById('login').click(); jQuery('.vlz_modal').css('display', 'none')\" style='color: #00b69d; font-weight: 600; cursor: pointer;'>Aquí</span> para acceder a kmimos.<h2>";
		}

		if( $error  == ""){
			$propietario = $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = ".get_the_ID() );
			if( $propietario == $id_user ){
				$error = "
					<h1 align='justify'>No puedes realizarte reservas a tí mismo.</h1>
					<h2 align='justify'>Pícale <a href='".get_home_url()."/busqueda/' style='color: #00b69d; font-weight: 600;'>Aquí</a> para buscar entre cientos de cuidadores certificados kmimos.<h2>
				";
			}
		}

		if( $error  == ""){
			$meta = get_user_meta($id_user);
			if( $meta['first_name'][0] == '' ||  $meta['last_name'][0] == '' || ( $meta['user_mobile'][0] == '' ) && ( $meta['user_phone'][0] == '' )){
				$error = "
					<h1 align='justify'>Kmiusuario, para continuar con tu reserva debes ir a tu perfil para completar algunos datos de contacto.</h1>
					<h2 align='justify'>Pícale <a href='".get_home_url()."/perfil-usuario/?ua=profile' target='_blank' style='color: #00b69d; font-weight: 600;'>Aquí</a> para cargar tu información.<h2>
				";
			}
		}

		if( $error  == ""){
			$mascotas = $wpdb->get_var("SELECT count(*) FROM wp_posts WHERE post_type = 'pets' AND post_author = ".$id_user );
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

		if( $error  == ""){
			if( $atributos["gatos"] != "Si" && !$tienePerros ){
				$error = "
					<h1 align='justify'>Debes cargar por lo menos un <strong>Perro</strong> para poder realizar esta reserva.</h1>
					<h2 align='justify'>Pícale <a href='".get_home_url()."/perfil-usuario/mascotas/nueva/' style='color: #00b69d; font-weight: 600;'>Aquí</a> para agregarlo.<h2>
				";
			}
		}


		//$error = "";

		$hoy = date("d/m/Y");
		$manana = date("d/m/Y", strtotime("+1 day") );

		if( $busqueda["checkin"] == "" ){
			$busqueda["checkin"] = $hoy;
			$busqueda["checkout"] = $manana;
		}

		$bloquear_adicionales = false;
		$infoGatos = '';
		if( $atributos["gatos"] == "Si" && !$tieneGatos ){
			$infoGatos = '
				<div class="infoGatos">
					Estimado cliente, este cuidador también acepta <strong>Gatos</strong> en su servicio de <strong>'.$servicio_name_corto.'</strong>, sin embargo en este momento dicha opción
					se encuentra <strong>no disponible</strong>, debido a que usted no ha registrado al menos un <strong>Gato</strong> entre sus mascotas.<br><br>
					Puede picarle <a href="'.get_home_url().'/perfil-usuario/mascotas/nueva/" style="color: #20a2ef; font-weight: 600;">Aquí</a> si desea agregarlos.
				</div>
			';
		}

		if( !$tienePerros ){
			$infoGatos = '
				<div class="infoGatos">
					Estimado cliente, este cuidador también acepta <strong>Perros</strong> en su servicio de <strong>'.$servicio_name_corto.'</strong>, sin embargo en este momento dicha opción
					se encuentra <strong>no disponible</strong>, debido a que usted no ha registrado al menos un <strong>Perro</strong> entre sus mascotas.<br><br>
					Puede picarle <a href="'.get_home_url().'/perfil-usuario/mascotas/nueva/" style="color: #20a2ef; font-weight: 600;">Aquí</a> si desea agregarlos.
				</div>
			';
			$bloquear_adicionales = true;
		}

		//$NOW = (strtotime("now")+25200);
		$NOW = (strtotime("now"));

		if( isset($_GET["hora"]) ){
			$NOW = ( strtotime( date("Y-m-d")." ".$_GET["hora"].":00:00") );
		}

		$hora = date("G", $NOW);

		$bloquear = "";
		$ES_FLASH = "NO";
		$msg_bloqueador = "
			<div class='alerta_flash'>
				<div class='alerta_flash_importante'>IMPORTANTE</div>
				<div class='alerta_flash_mensaje'>
					Este cuidador, <strong>no tiene opci&oacute;n de Reserva Inmediata</strong>, por lo tanto existe la posibilidad de que la reserva no sea confirmada el d&iacute;a de hoy.
					Te invitamos a seguir uno de los siguientes pasos:
				</div>
				<div class='alerta_flash_pasos'>
					<div class='alerta_flash_paso'>
						<div class='alerta_flash_paso_titulo'>Opci&oacute;n 1</div>
						<div class='alerta_flash_paso_img'> <img src='".getTema()."/images/alerta_flash/opcion_1.png' /> </div>
						<div class='alerta_flash_paso_txt'>Cambia las fechas de Reserva</div>
					</div>
					<div class='alerta_flash_paso'>
						<div class='alerta_flash_paso_titulo'>Opci&oacute;n 2</div>
						<div class='alerta_flash_paso_img'> <img src='".getTema()."/images/alerta_flash/opcion_2.png' /> </div>
						<div class='alerta_flash_paso_txt'>Busca un cuidador que permita <strong>reserva inmediata</strong></div>
					</div>
					<div class='alerta_flash_paso'>
						<div class='alerta_flash_paso_titulo'>Opci&oacute;n 3</div>
						<div class='alerta_flash_paso_img'> <img src='".getTema()."/images/alerta_flash/opcion_3.png' /> </div>
						<div class='alerta_flash_paso_txt'>Ll&aacute;manos al<br> (55) 8526 1162</div>
					</div>
				</div>
			</div>
		";

		if(  $_SESSION['admin_sub_login'] != 'YES' ){
			if( $atributos["flash"] == 1){
				$ES_FLASH = "SI";
			}else{
				if( ( $hoy == $busqueda["checkin"] || $busqueda["checkin"] == "" ) && date("G", $NOW )+0 < 9 ){
					$ES_FLASH = "SI";
				}
				if(  ( $manana == $busqueda["checkin"] ) && date("G", $NOW )+0 < 18 ){
					$ES_FLASH = "SI";
				}
			}
			
			if( $ES_FLASH == "NO" ){
				$msg_bloqueador = "<div id='vlz_msg_bloqueo' class='vlz_bloquear_msg'>".$msg_bloqueador."</div>";
			}else{
				$msg_bloqueador = "<div id='vlz_msg_bloqueo' class='vlz_NO_bloquear_msg'>".$msg_bloqueador."</div>";
			}
		}else{
			$ES_FLASH = "SI";
			$msg_bloqueador = "<div id='vlz_msg_bloqueo' class='vlz_NO_bloquear_msg'>".$msg_bloqueador."</div>";
		}

		$msg_mismo_dia = "";
		if( ( $hoy == $busqueda["checkin"] || $busqueda["checkin"] == "" ) && date("G", $NOW )+0 < 9 ){
			/*$msg_mismo_dia = "
				<div class='msg_mismo_dia'>
					En caso de que necesites atención dentro de las siguientes 4 a 6 horas, por favor llámanos al: (01) 55 3137 4829.
				</div>
			";*/
			$msg_mismo_dia = "
				<div class='msg_mismo_dia'>
					En caso de que necesites atención dentro de las siguientes 4 a 6 horas, por favor llámanos al: (55) 8526 1162.
				</div>
			";
		}

		$msg_bloqueador_no_valido = "";
		$caracteristicas = "";
		if(  $_SESSION['admin_sub_login'] != 'YES' ){
			foreach ($filtros as $key => $value) {
				if( $value == 2 ){
					$caracteristicas .= "<li>".$filtros_txt[ $key ]."</li>";
				}
			}
			if( $caracteristicas != "" ){
				$msg_bloqueador_no_valido = "
					<div class='msg_bloqueador_no_valido'>
						Lo sentimos, este cuidador no es compatible con las siguientes caracter&iacute;sticas de tu(s) mascota(s):
						<ul style='padding: 10px 20px;' >
							$caracteristicas
						</ul>
						<div>
							Por favor cont&aacute;ctanos al tel&eacute;fono (55) 8526 1162 o al Whatsapp (55) 6892 2182 para ayudarte a encontrar el cuidador adecuado.
						</div>
					</div>

					<a href='".get_home_url()."' class='km-end-btn-form vlz_btn_new_search'>
						<span>Nueva Busqueda</span>
					</a>
				";
			}
		}

		if(  $_SESSION['admin_sub_login'] != 'YES' ){
			if( 
				( $hoy == $busqueda["checkin"] || $busqueda["checkin"] == "" ) && ( ($hora >= 0 && $hora <= 6) || ( $hora == 23 ) )  ||
				( $manana == $busqueda["checkin"] && ( $hora == 23 ) )
			){
				// 570 x 320
				$msg_bloqueador_madrugada = "
					<div id='vlz_msg_bloqueo_madrugada' class='vlz_bloquear_msg_madrugada'>
						<img src='".getTema()."/images/alerta_flash/Contenido_3.png' />
					</div>
				";
				$bloquear_madrugada = "bloquear_madrugada";

				$msg_mismo_dia = "";
				$msg_bloqueador = "";
			}else{
				$msg_bloqueador_madrugada = "
					<div id='vlz_msg_bloqueo_madrugada' class='vlz_NO_bloquear_msg_madrugada'>
						<img src='".getTema()."/images/alerta_flash/Contenido_3.png' />
					</div>
				";
			}
		}

		$paquetes = [
			"1 semena",
			"1 mes",
			"2 meses",
			"3 meses"
		];

		$bloq_checkout = '';
		if( $tipo == "paseos" ){
			$PAQUETE = "var PAQUETE = '".$busqueda["paquete"]."';";
			$bloq_checkout = 'disabled';
		}else{
			$PAQUETE = "var PAQUETE = '';";
		}

		include( dirname(__FILE__)."/procesos/funciones/config.php" );

		$super_admin = (  $_SESSION['admin_sub_login'] != 'YES' ) ? 'No': 'Si';

		$HTML .= "
		<script> 
			var SERVICIO_ID = '".get_the_ID()."';
			var cupos = eval('".json_encode($cupos)."');
			var tipo_servicio = '".$tipo."'; 
			var name_servicio = '".$servicio_name."'; 
			var cliente = '".$id_user."'; 
			var cuidador = '".$cuidador->id_post."'; 
			var email = '".$email."'; 
			var saldo = '".$saldoTXT."';
			var acepta = '".$cuidador->mascotas_permitidas."';
			var OPENPAY_TOKEN = '".$MERCHANT_ID."';
			var OPENPAY_PK = '".$OPENPAY_KEY_PUBLIC."';
			var OPENPAY_PRUEBAS = ".$OPENPAY_PRUEBAS.";
			var FLASH = '".$ES_FLASH."';
			var HOY = '".$hoy."';
			var MANANA = '".$manana."';
			var HORA = '".(date("G", $NOW )+0)."';
			var SUPERU = '".$super_admin."';
			var BLOQUEAR_ADICIONALES = ".( ($bloquear_adicionales) ? 1 : 0 ).";
			".$PAQUETE."
		</script>";

		if( $error != "" ){
			$actual = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$referencia = $_SERVER['HTTP_REFERER'];

			if( $actual == $referencia ){
				$referencia = get_home_url();
			} 

			$HTML .= "
			<style>
				.vlz_modal{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; display: table; z-index: 10000; background: rgba(0, 0, 0, 0.8); vertical-align: middle !important; }
				h1{ font-size: 18px; }
				h2{ font-size: 16px; }
				.vlz_modal_interno{ display: table-cell; text-align: center; vertical-align: middle !important; }
				.vlz_modal_ventana{ position: relative; display: inline-block; width: 60%!important; text-align: left; box-shadow: 0px 0px 4px #FFF; border-radius: 5px; z-index: 1000; }
				.vlz_modal_titulo{ background: #FFF; padding: 15px 10px; font-size: 18px; color: #52c8b6; font-weight: 600; border-radius: 5px 5px 0px 0px; }
				.vlz_modal_contenido{ background: #FFF; height: 450px; box-sizing: border-box; padding: 5px 15px; border-top: solid 1px #d6d6d6; border-bottom: solid 1px #d6d6d6; overflow: auto; text-align: justify; }
				.vlz_modal_pie{ background: #FFF; padding: 15px 10px; border-radius: 0px 0px 5px 5px; }
				.vlz_modal_fondo{ position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 500; }
				.vlz_boton_siguiente{ padding: 10px 50px; background-color: #a8d8c9; display: inline-block; font-size: 16px; border: solid 1px #2ca683; border-radius: 3px; float: right; cursor: pointer; } 
				@media screen and (max-width: 750px){ .vlz_modal_ventana{ width: 90% !important; } }
			</style>

			<div id='jj_modal_ir_al_inicio' class='vlz_modal'>
				<div class='vlz_modal_interno'>
					<div class='vlz_modal_ventana jj_modal_ventana'S>
						<div class='vlz_modal_titulo'>¡Oops!</div>
						<div class='vlz_modal_contenido' style='height: auto;'>
							{$error}
						</div>
						<div class='vlz_modal_pie' style='border-radius: 0px 0px 5px 5px!important; height: 70px;'>
							<a href='".$referencia."' ><input type='button' style='text-align: center;' class='vlz_boton_siguiente' value='Volver'/></a>
						</div>
					</div>
				</div>
			</div>";

			echo comprimir_styles($HTML);
		}else{

			$descripcion = $wpdb->get_var("SELECT post_excerpt FROM wp_posts WHERE ID = {$post_id}");

			preg_match_all("#-(.*?)\n#i", "-".$descripcion, $matches_1);
			preg_match_all("#<small>(.*?)</small>#", $descripcion, $matches_2);
			$descripcion_1 = $matches_1[1][0];
			$descripcion_2 = $matches_2[1][0];

			$_adicionales = '<div id="contenedor-adicionales" class="contenedor-adicionales">'.$adicionales.'</div>';
			if( $bloquear_adicionales ){
				$_adicionales = '<div style="display: none;" id="contenedor-adicionales" class="contenedor-adicionales">'.$adicionales.'</div>';
			}

			$dias_str = '';
			if( $tipo == "paseos" ){
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
			    	$letra = substr( $value, 0, 1);
			    	$checked = ( in_array($key, $_SESSION['busqueda']['dias']) ) ? "checked": "";
			    	$dias_str .= 
			    	'	<label class="input_check_box" title="'.$value.'" for="'.$key.'">'.
					'		<input type="checkbox" id="'.$key.'" name="dias[]" value="'.$key.'" '.$checked.' />'.
					'		<span>'.$letra.'</span>'.
					'		<div class="top_check"></div>'.
					'	</label>'
			    	;
			    }
			    $dias_str = '<div class="dias_container">'.$dias_str.'</div>';
			}	

			$bloq_checkout_str = '';

			if( $bloq_checkout != "" ){
				$bloq_checkout_str = '
					<div style="margin-bottom: 15px; font-size: 15px;" class="msg_bloqueador_no_valido">
						Estimado usuario el <strong>checkout</strong> será establecido de manera automatica para coincidir con
						el tiempo del paquete seleccinado de <strong>'.$paquetes[ $busqueda["paquete"]-1 ].'</strong>.
					</div>
				';
			}

			$precios = $bloq_checkout_str.'
				<div class="km-dates-step" style="margin-bottom: 5px;">
					<div class="km-ficha-fechas">
						<input type="text" id="checkin" name="checkin" placeholder="DESDE" value="'.$busqueda["checkin"].'" class="date_from" readonly />
						<input type="text" id="checkout" name="checkout" placeholder="HASTA" value="'.$busqueda["checkout"].'" readonly '.$bloq_checkout.' />
					</div>
				</div>

				'.$dias_str.'
				'.$msg_mismo_dia.'
				'.$msg_bloqueador.'
				'.$msg_bloqueador_madrugada.'
				'.$infoGatos.'

				<div id="bloque_info_servicio" class="km-content-step '.$bloquear.' '.$bloquear_madrugada.'">
					<div class="km-content-new-pet">
						'.$precios.'
						<div class="km-services-content">
							<div class="contenedor-adicionales">'.$transporte.'</div>
							'.$_adicionales.'
						</div>

						<div class="km-services-total km-total-calculo">
							<div class="valido">
								<span class="km-text-total">TOTAL</span>
								<span class="km-price-total">$0.00</span>
							</div>
							<div class="invalido">
								
							</div>
						</div>

					</div>
				</div>

				<a href="#" id="reserva_btn_next_1" class="km-end-btn-form km-end-btn-form-disabled disabled vlz_btn_reservar">
					<span>SIGUIENTE</span>
				</a>
			';
			if( $msg_bloqueador_no_valido != "" ){
				$precios = $msg_bloqueador_no_valido;
			}

			if( $_SESSION["wlabel"] == "petco" ){
				$HTML .= "
					<script type='text/javascript'>
					    window._adftrack.push({
					        pm: 1453019,
					        divider: encodeURIComponent('|'),
					        pagename: encodeURIComponent('MX_Kmimos_Reservar_180907')
					    });
					</script>
					<noscript>
					    <p style='margin:0;padding:0;border:0;'>
					        <img src='https://a2.adform.net/Serving/TrackPoint/?pm=1453019&ADFPageName=MX_Kmimos_Reservar_180907&ADFdivider=|' width='1' height='1' alt='' />
					    </p>
					</noscript>
				";
			}

			$HTML .= '
		 		<form id="reservar" class="km-content km-content-reservation">
					<div id="step_1" class="km-col-steps">
						<div class="km-col-content">
							<ul class="steps-numbers">
								<li><span class="number active">1</span></li>
								<li class="line"></li><li><span class="number">2</span></li>
								<li class="line"></li><li><span class="number">3</span></li>
							</ul>
							<div class="km-title-step">
								RESERVACIÓN '.$servicio_name_corto.'
								<div class="km-info-box">
									<i class="fa fa-info-circle km-info"></i>
									<div>'.$descripcion.'</div>
								</div>
							</div>
							<div class="km-sub-title-step">
								Reserva las fechas y los servicios con tu cuidador(a) '.$cuidador_name.'
							</div>
							'.$precios.'
						</div>
					</div>

					<div id="step_2" class="km-col-steps">
						<div class="km-col-content">
							<div id="atras_1" class="atras"> < </div>
							<ul class="steps-numbers">
								<li>
									<span class="number checked">1</span>
								</li>
								<li class="line"></li>
								<li>
									<span class="number active">2</span>
								</li>
								<li class="line"></li>
								<li>
									<span class="number">3</span>
								</li>
							</ul>
							<div class="km-title-step">
								RESUMEN DE TU RESERVA
							</div>
							<div class="km-sub-title-step">
								Queremos confirmar tu reservación y tu método de pago
							</div>
							<div class="km-content-step km-content-step-2">
								<div class="km-option-resume">
									<span class="label-resume">CUIDADOR SELECCIONADO</span>
									<span class="value-resume">'.$cuidador_name.'</span>
								</div>
								<div class="km-option-resume">
									<span class="label-resume">FECHA</span>
									<span class="value-resume">
										<span class="fecha_ini"></span>
										&nbsp; &gt; &nbsp;
										<span class="fecha_fin"></span>
									</span>
								</div>
								<div class="km-option-resume">
									<div class="km-option-resume-service">
										<span class="label-resume-service">'.$servicio_name.'</span>
									</div>
									<div class="items_reservados"></div>
								</div>
								<div class="cupones_desglose km-option-resume">
									<span class="label-resume">Descuentos</span>
									<div></div>
								</div>
								<div class="km-services-total">
									<span class="km-text-total">TOTAL</span>
									<span class="km-price-total2">$420.00</span>
								</div>
							</div>

							<div class="km-select-method-paid" style="display: none;">
								<div class="km-method-paid-title" style="display: none !important;">
									SELECCIONA PAGO PARCIAL ó TOTAL
								</div>

								<div class="km-method-paid-options">
									<div class="km-method-paid-option km-option-deposit" style="display: none !important;">
										<div class="km-text-one">
											RESERVA CON PAGO PARCIAL
										</div>
										<div class="km-text-two">
											Pague ahora el 20% y el restante
										</div>
										<div class="km-text-three">
											AL CUIDADOR EN EFECTIVO
										</div>
									</div>

									<div class="km-method-paid-option km-option-total active" style="width: 100% !important; height: 60px;">
										<div class="km-text-one" style="margin-top: 20px !important;">
											PAGO TOTAL DE LA RESERVA
										</div>
									</div>
								</div>
							</div>

							<div class="km-detail-paid-deposit" style="display:block;">
								<div class="km-detail-paid-line-one">
									<span class="km-detail-label">SUBTOTAL</span>
									<span id="" class="sub_total km-detail-value"></span>
								</div>

								<div class="km-detail-paid-line-one">
									<span class="km-detail-label">DESCUENTO</span>
									<span id="" class="descuento km-detail-value">$0.00</span>
								</div>

								<div class="km-detail-paid-line-one">
									<span class="km-detail-label">TOTAL</span>
									<span id="" class="monto_total km-detail-value"></span>
								</div>

								<div class="km-detail-paid-line-two">
									<span class="km-detail-label">MONTO A PAGAR <b>EN EFECTIVO AL CUIDADOR</b></span>
									<span id="" class="pago_cuidador km-detail-value"></span>
								</div>

								<div class="km-detail-paid-line-three">
									<span class="km-detail-label">PAGUE AHORA</span>
									<span id="" class="pago_17 km-detail-value"></span>
								</div>
							</div>

							<div class="km-cupones">
								<div>
									<input type="text" id="cupon" placeholder="Ingresa tu cupón">
								</div>
								<div class="">
									<span id="cupon_btn">Cup&oacute;n</span>
								</div>
							</div>

							<span id="reserva_btn_next_2" class="km-end-btn-form vlz_btn_reservar">
								<span>SIGUIENTE</span>
							</span>

						</div>

					</div>

					<div id="step_3" class="km-col-steps">

						<div class="km-col-content">

							<div id="atras_2" class="atras"> < </div>

							<ul class="steps-numbers">
								<li>
									<span class="number checked">1</span>
								</li>
								<li class="line"></li>
								<li>
									<span class="number checked">2</span>
								</li>
								<li class="line"></li>
								<li>
									<span class="number active">3</span>
								</li>
							</ul>

							<div class="km-title-step">
								RESUMEN DE TU RESERVA
							</div>

							<div class="km-tab-content" style="display: block;">
								<div class="km-content-step km-content-step-2">
									<div class="km-option-resume">
										<span class="label-resume">CUIDADOR SELECCIONADO</span>
										<span class="value-resume">'.$cuidador_name.'</span>
									</div>

									<div class="km-option-resume">
										<span class="label-resume">FECHA</span>
										<span class="value-resume">
											<span class="fecha_ini"></span>
											&nbsp; &gt; &nbsp;
											<span class="fecha_fin"></span>
										</span>
									</div>

									<div class="km-option-resume">
										<div class="km-option-resume-service">
											<span class="label-resume-service">'.$servicio_name.'</span>
										</div>
										<div class="items_reservados"></div>
									</div>

									<div class="cupones_desglose km-option-resume">
										<span class="label-resume">Descuentos</span>
										<div></div>
									</div>

									<div class="km-services-total">
										<span class="km-text-total">TOTAL</span>
										<span class="km-price-total2"></span>
									</div>

									<div class="km-detail-paid-deposit">
										<div class="km-detail-paid-line-one">
											<span class="km-detail-label">SUBTOTAL</span>
											<span id="" class="sub_total km-detail-value"></span>
										</div>

										<div class="km-detail-paid-line-one">
											<span class="km-detail-label">DESCUENTO</span>
											<span id="" class="descuento km-detail-value">$0.00</span>
										</div>

										<div class="km-detail-paid-line-one">
											<span class="km-detail-label">TOTAL</span>
											<span id="" class="monto_total km-detail-value"></span>
										</div>

										<div class="km-detail-paid-line-two">
											<span class="km-detail-label">MONTO A PAGAR <b>EN EFECTIVO AL CUIDADOR</b></span>
											<span id="" class="pago_cuidador km-detail-value">$809.25</span>
										</div>

										<div class="km-detail-paid-line-three">
											<span class="km-detail-label">PAGUE AHORA</span>
											<span id="" class="pago_17 km-detail-value">$165.75</span>
										</div>
									</div>

								</div>
							</div>

							<div id="metodos_pagos">
								<div class="km-tab-content" style="display: block;">
									<div class="km-content-method-paid-inputs">

										<div class="km-select-method-paid">
											<div class="km-method-paid-title">
												MEDIO DE PAGO
											</div>

											<div class="km-method-paid-options km-medio-paid-options" style="display: none;">

												<div class="km-method-paid-option km-tienda km-option-3-lineas active">
													<div class="km-text-one">
													PAGO EN TIENDA
													</div>
													<div class="km-text-three">
													DE CONVENIENCIA
													</div>

												</div>

												<div class="km-method-paid-option km-tarjeta km-option-3-lineas ">
													<div class="km-text-one">
														<div class="km-text-one">								
														PAGO CON TARJETA
														</div>
														<div class="km-text-three">
															DE CRÉDITO O DÉBITO
														</div>
													</div>

												</div>

											</div>
										</div>

										<select id="tipo_pago" style="display: none;">
											<option value="tienda">PAGO EN TIENDA DE CONVENIENCIA</option>
											<option value="tarjeta">PAGO CON TARJETA DE CRÉDITO O DÉBITO</option>
										</select>

										<div class="errores_box">
											Datos de la tarjeta invalidos
										</div>

										<div id="tienda_box" class="metodos_container" style="display:block;">
											<img src="'.get_template_directory_uri().'/images/tiendas.png" />
											<img src="'.get_template_directory_uri().'/images/pasos.png" />
										</div>
										<div id="tarjeta_box" class="metodos_container" style="display:none;">

											<div class="label-placeholder">
												<label>Nombre del tarjetahabiente*</label>
												<input type="text" id="nombre" name="nombre" value="" class="input-label-placeholder solo_letras" data-openpay-card="holder_name">
											</div>

											<div class="label-placeholder">
												<label>Número de Tarjeta*</label>
												<input type="text" id="numero" name="numero" class="input-label-placeholder next solo_numeros maxlength" data-max="19" data-next="mes">
												<input type="hidden" id="numero_oculto" data-openpay-card="card_number">
											</div>

											<div class="content-placeholder">
												<div class="label-placeholder">
													<label>Expira (MM AA)</label>
													<input type="text" id="mes" name="mes" class="input-label-placeholder next expiration solo_numeros maxlength" data-max="2" data-next="anio" maxlength="2" data-openpay-card="expiration_month">
													<input type="text" id="anio" name="anio" class="input-label-placeholder next expiration solo_numeros maxlength" data-max="2" data-next="codigo" maxlength="2" data-openpay-card="expiration_year">
												</div>

												<div class="label-placeholder">
													<label>Código de seguridad(CVV)</label>
													<input type="text" id="codigo" name="codigo" class="input-label-placeholder next solo_numeros maxlength" data-max="4" maxlength="4" data-next="null" data-openpay-card="cvv2">
													<small>Número de tres dígitos en el reverso de la tarjeta</small>
												</div>
											</div>
											<!--
											<div class="km-msje-minimal">
												*Recuerda que tus datos deben ser los mismos que el de tu tarjeta
											</div>
											-->
										</div>

									</div>
								</div>
							</div>

							<div class="km-term-conditions">
								<label>
									<input type="checkbox" id="term-conditions" name="term-conditions" value="1">
									Acepto los <a href="'.get_home_url().'/terminos-y-condiciones/" target="_blank">términos y condiciones</a>
								</label>
							</div>

							<span id="reserva_btn_next_3" class="km-end-btn-form vlz_btn_reservar disabled">
								<div class="perfil_cargando" style="background-image: url('.getTema().'/images/cargando.gif);" ></div> <span>TERMINAR RESERVA</span>
							</span>

						</div>
					</div>

					<div class="km-col-empty">
						<img src="'.getTema().'/images/new/bg-cachorro.png" style="max-width: 100%;">
					</div>
				</form>

				<!-- SECCIÓN BENEFICIOS -->
				<div class="km-beneficios km-beneficios-footer" style="margin-top: 60px;">
					<div class="container">
						<div class="row">
							<div class="col-xs-4">
								<div class="km-beneficios-icon">
									<img src="'.getTema().'/images/new/km-pago.svg">
								</div>
								<div class="km-beneficios-text">
									<h5 class="h5-sub">PAGO EN EFECTIVO O CON TARJETA</h5>
								</div>
							</div>
							<div class="col-xs-4 brd-lr">
								<div class="km-beneficios-icon">
									<img src="'.getTema().'/images/new/km-certificado.svg">
								</div>
								<div class="km-beneficios-text">
									<h5 class="h5-sub">CUIDADORES CERTIFICADOS</h5>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="km-beneficios-icon">
									<img src="'.getTema().'/images/new/km-veterinaria.svg">
								</div>
								<div class="km-beneficios-text">
									<h5 class="h5-sub">COBERTURA VETERINARIA</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
		 	';

			echo comprimir_styles($HTML);

		}

		/*echo "<pre>";
			print_r( unserialize($_SESSION["busqueda"]) );
		echo "</pre>";*/

		unset($_SESSION["pagando"]);

    get_footer(); 
?>