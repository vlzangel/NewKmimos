<?php

$SITIO = 'Kmivet';

$datos = kmimos_get_info_syte();
$referidos = get_referred_list_options();
$referidos_options = ""; $pixel_petco = "";
if( $_SESSION["wlabel"] != "petco" ){
	$referidos_options = '<option value="">Dónde nos conoció?</option>';
}else{
	$pixel_petco = "";
}
foreach ($referidos as $key => $value) {
	$selected="";
	if(array_key_exists("wlabel",$_SESSION)){
		$wlabel=$_SESSION['wlabel'];

		if($key=='Volaris' && $wlabel=='volaris'){
			$selected='selected';

		}else if($key=='Vintermex' && $wlabel=='viajesintermex'){
			$selected='selected';
		}else if($key=='Petco' && $wlabel=='petco'){
			$selected='selected';
		}
	}

	$referidos_options.= "<option value='{$key}' $selected>{$value}</option>";
}

$_comportamiento_gatos = get_post_meta($pet_id, 'comportamiento_gatos', true);
$_comportamiento_gatos = (array) json_decode($_comportamiento_gatos);

$comportamientos_db = $wpdb->get_results("SELECT * FROM comportamientos_mascotas");
foreach ($comportamientos_db as $value) {
    $comportamientos[ $value->slug ] =  $value->nombre;
}

$comportamientos_str = '<div class="km-registro-checkbox">';
$i = 0;
foreach ($comportamientos as $key => $value) {
    $comportamientos_str .= '
    	<div class="km-registro-checkbox-opcion">
			<p>'.$value.'</p>
			<div class="km-check-gato">
				<input type="checkbox" value="0" id="km-check-gato-'.$i.'" class="km-check-gatos" name="comportamiento_gatos_'.$key.'" />
				<label for="km-check-gato-'.$i.'"></label>
			</div>
		</div>
    ';
    $i++;
}

$comportamientos_str .= '</div>';

$nuevo_banner = '
	<div class="banner_descuento_registro_container" >
		<img class="publicidad_solo_pc" src="'.get_recurso('img').'BANNERS/REGISTRO/fondo_2.png" />
		<img class="publicidad_solo_movil" src="'.get_recurso('img').'BANNERS/REGISTRO/fondo_movil_1.png" />
	</div>
';

$HTML .='
<!-- POPUPS REGISTRARTE -->
<div class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" id="popup-registrarte" style="padding: 40px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btn_cerrar_1" >×</button>
			<div class="popup-registrarte-1">
				<p class="popup-tit">REGISTRARME</p>
				
				<a href="#" class="km-btn-fb hidden" onclick="login_facebook();">
					<img src="'.getTema().'/images/new/icon/km-redes/icon-fb-blanco.svg">
					REGISTRARME CON FACEBOOK
				</a>
				
				<a href="#" class="google_auth hidden km-btn-border" id="customBtn1">
					<img src="'.getTema().'/images/new/icon/km-redes/icon-gmail.svg">
					REGISTRARME CON GOOGLE
				</a>
			
				<div class="alert alert-danger" style="
					display:none;
		            -webkit-transition: All 1s; /* Safari */
		            transition: All 1s;
					" 
					data-error="auth"></div>

				<div class="line-o hidden">
					<p class="text-line">o</p>
					<div class="bg-line"></div>
				</div>


				<a 	href="javascript: '.$pixel_petco.';" 
					class="km-btn-correo km-btn-popup-registrarte-1" 
					data-target="social-next-step">
					<img src="'.getTema().'/images/new/icon/km-redes/icon-mail-blanco.svg">
					REGISTRARME POR CORREO ELECTRÓNICO
				</a>


				<p style="color: #979797; margin-top: 20px;">Al crear una cuenta, aceptas las <a style="color: blue;" target="_blank" href="'.site_url().'/terminos-y-condiciones/">condiciones del servicio y la Política de privacidad</a> de '.$SITIO.'.</p>

				<p><b>Dudas escríbenos</b></p>
				<div class="row">
					<div class="col-xs-12 col-sm-4"><p><img style="width: 20px; margin-right: 5px; position: relative; top: -3px;" src="'.getTema().'/images/new/icon/km-redes/icon-wsp.svg"> +52 (33) 1261 4186</p></div>
					<div class="col-xs-12 col-sm-8"><p><img style="width: 12px; margin-right: 5px; position: relative; top: -1px;" src="'.getTema().'/images/new/icon/km-redes/icon-cel.svg"> Llamada : 01 (55) 8526 1162</p></div>
				</div>

				<hr>
				<div class="row">
					<div class="col-xs-5">
						<p>¿Ya tienes una cuenta?</p>
					</div>
					<div class="col-xs-7">
						<a href="#" class="modal_show km-btn-border" data-modal="#popup-iniciar-sesion"><b>INICIAR SESIÓN</b></a>
					</div>
				</div>
			</div>
			<div class="popuphide popup-registrarte-nuevo-correo">
				
				<span class="hidden">
					<p style="color: #979797; text-align: center;">Regístrate por <a href="#" onclick="login_facebook();">Facebook</a> o <a href="#" class="google_auth" id="customBtn2">Google</a></a></p>
				</span>

					<h3 style="margin: 0; text-align: center;">Completa tus datos</h3>
				<form id="form_nuevo_cliente" name="form_nuevo_cliente" enctype="multipart/form-data" method="POST" autocomplete="off">	
					<div class="km-box-form">
						<div class="content-placeholder">

							<input type="hidden" id="facebook_cliente_id" name="facebook_auth_id" 
								class="social_facebook_id" value="">
							<input type="hidden" id="google_cliente_id" name="google_auth_id" 
								class="social_google_id " value="">

							<div class="img_registro_cliente" style="position: relative">
								<div class="km-datos-foto vlz_rotar" id="km-datos-foto-profile" style="background-image: url('.getTema().'/images/popups/registro-veterinario-foto.png)">
									<div id="loading-perfil" style="width:100%;line-height: 100%;display:none" class="vlz_cargando">
										<img src="'.getTema().'/images/new/bx_loader.gif" class="img-responsive">
									</div>
								</div>

								<div id="rotar_i" class="btn_rotar" style="display: none;" data-orientacion="left"> <i class="fa fa-undo" aria-hidden="true"></i> </div>
				                <div id="rotar_d" class="btn_rotar" style="display: none;" data-orientacion="right"> <i class="fa fa-repeat" aria-hidden="true"></i> </div>

				                <div class="btn_aplicar_rotar" style="display: none;"> Aplicar Cambio </div>
							</div>
							
							<input type="file" class="hidden" id="carga_foto_profile" accept="image/*">
							<input type="hidden" id="img_profile" name="img_profile" value="" class="vlz_rotar_valor">

							<div class="label-placeholder">
								<label>Nombre</label>
								<input type="text" id="nombre" name="nombre" maxlength="30" data-charset="alfxlf" data-change="alfxlf" class="input-label-placeholder social_firstname" pattern=".{3,}">
							</div>
							<div class="label-placeholder">
								<label>Apellido</label>
								<input type="text" name="apellido" id="apellido" maxlength="30"  data-charset="alfxlf" data-change="alfxlf" class="input-label-placeholder social_lastname" pattern=".{3,}">
							</div>

							<div class="label-placeholder verify">
								<label>Correo electrónico</label>
								<input
									type="email" 
									name="email_1" 
									data-verify="active" 
									id="email_1" 
									class="verify_mail input-label-placeholder social_email" 
									data-charset="cormlfnum" 
									data-change="cor" 
									pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}">
								<span class="verify_result"></span>
							</div>
							<div class="label-placeholder">
								<label>Crea tu contraseña</label>
								<input type="password" name="pass" id="pass" maxlength="20"  class="input-label-placeholder" autocomplete="off">
							</div>
							<div class="label-placeholder">
								<label>Teléfono</label>
								<input type="text" name="movil" id="movil" class="input-label-placeholder" data-charset="num" data-change="num" maxlength="10">
							</div>
							<div class="km-datos-mascota">
								<select class="km-datos-mascota-opcion bg-select-custom" name="genero" id="genero">
									<option value="">Género</option>
									<option value="hombre">Masculino</option>
									<option value="mujer">Femenino</option>
								</select>
							</div>
							<div class="km-datos-mascota">
								<select class="km-datos-mascota-opcion bg-select-custom" name="edad" id="edad">
									<option value="">Edad</option>
									<option value="18-25">18-25 años</option>
									<option value="25-35">26-35 años</option>
									<option value="Mayor a 36">Mayor 36 años</option>
								</select>
							</div>
							<div class="km-datos-mascota hidden">
								<select class="km-datos-mascota-opcion bg-select-custom" name="fumador" id="fumador">
									<option value="">Es Fumador</option>
									<option value="SI">Si</option>
									<option value="NO">No</option>
								</select>
							</div>
							<div class="km-datos-mascota">
								<select id="referido" name="referido bg-select-custom" class="km-datos-mascota-opcion" data-title="Debes seleccionar una opción" required>
									'.$referidos_options.'
								</select>
							</div>
						</div>
					</div>
				</form>
							
				<a href="#" id="siguiente" class="km-btn-correo km-btn-popup-registrarte-nuevo-correo">SIGUIENTE</a>

				<p style="color: #979797; margin-top: 20px;">Al crear una cuenta, aceptas las <a style="color: blue;" target="_blank" href="'.site_url().'/terminos-y-condiciones/">condiciones del servicio y la Política de privacidad</a> de '.$SITIO.'.</p>
				
				<div class="row">
					<div class="col-xs-12 col-sm-4"><p><img style="width: 20px; margin-right: 5px; position: relative; top: -3px;" src="'.getTema().'/images/new/icon/km-redes/icon-wsp.svg">  +52 (33) 1261 4186</p></div>
					<div class="col-xs-12 col-sm-8"><p><img style="width: 12px; margin-right: 5px; position: relative; top: -1px;" src="'.getTema().'/images/new/icon/km-redes/icon-cel.svg"> Llamada: 01 (55) 8526 1162</p></div>
				</div>

				<hr>
				<div class="row">
					<div class="col-xs-5">
						<p>¿Ya tienes una cuenta?</p>
					</div>
					<div class="col-xs-7">
						<a href="#" class="modal_show km-btn-border km-link-login" data-modal="#popup-iniciar-sesion"><b>INICIAR SESIÓN</b></a>
					</div>
				</div>
			</div>


			<div class="popuphide popup-condiciones">
				<h3 style="margin: 0; text-align: center;">TERMINOS Y CONDICIONES</h3>
				<div class="terminos_container"></div>
				<a href="#" id="btn_si_acepto" class="km-btn-correo btn_disable" >
					ACEPTAR TERMINOS Y CONDICIONES
				</a>
				<a href="#" id="btn_no_acepto">
					No acepto los terminos y condiciones
				</a>
			</div>

			<div class="popuphide popup-registrarte-final" style="padding-bottom: 15px;">
				<h3 style="margin: 0; text-align: center;">¡FELICIDADES,<br>TU REGISTRO SE REALIZÓ CON ÉXITO!</h3>
				<img src="'.get_recurso('img').'VETERINARIO/REGISTRO/RESPONSIVE/exitoso.svg">
				<a href="javascript:;" onclick="cerrar_modal()" id="btn_iniciar_sesion" data-url="'.get_home_url().'/#buscar" class="km-btn-correo" data-dismiss="modal">INICIAR SESIÓN</a>
			</div>

		</div>
	</div>
</div>';