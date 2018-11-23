<?php
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
?>