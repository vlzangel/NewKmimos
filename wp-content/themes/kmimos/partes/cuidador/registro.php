<!-- POPUPS REGISTRO -->
<?php 
	$info = kmimos_get_info_syte(); 
?>
<div id="popup-registro-cuidador1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="redireccionar();" >×</button>
			<div class="popup-registro-cuidador active">
				
				<a href="javascript:;" onClick="login_facebook();" class="km-btn-fb hidden"><img src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-fb-blanco.svg">REGISTRARME CON FACEBOOK</a>
				
				<a href="#"  id="registro_cuidador_google" class="google_auth km-btn-border hidden"><img src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-gmail.svg">REGISTRARME CON GOOGLE</a>

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
				<a href="#" data-target="social-next-step" class="km-btn-correo km-btn-popup-registro-cuidador">
					<img src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-mail-blanco.svg">REGISTRARME POR CORREO ELECTRÓNICO
				</a>
				<p style="color: #979797">Al crear una cuenta, <a href="<?php echo get_home_url(); ?>/terminos-y-condiciones/">aceptas las condiciones del servicio y la Política de privacidad</a> de Kmimos.</p>
				<p><b>Dudas escríbenos</b></p>
				<div class="row">
					<div class="col-xs-6"><p><img style="width: 20px; margin-right: 5px; position: relative; top: -3px;" src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-wsp.svg"> +52 1 55 7850 7572</p></div>
					<div class="col-xs-6"><p><a href="#"><img style="width: 15px; margin-right: 5px; position: relative; top: -1px;" src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-mail.svg">y.chaudary@kmimos.la</a></p></div>
				</div>
				<hr>
				<div class="row">
					<div class="col-xs-5">
						<p>¿Ya tienes una cuenta?</p>
					</div>
					<div class="col-xs-7">
						<a href="javascrip:;" data-modal="#popup-iniciar-sesion" class="modal_show km-btn-border"><b>INICIAR SESIÓN</b></a>
					</div>
				</div>
			</div>

		<form id="vlz_form_nuevo_cuidador" style="padding-bottom: 0px;" autocomplete="off" method="POST">
			<input type="hidden" name="google_auth_id"   class="social_google_id"	 value="">
			<input type="hidden" name="facebook_auth_id" class="social_facebook_id"  value="">

			<div class="popuphide popup-registro-cuidador-correo">
				
				<p class="hidden" style="color: #979797; text-align: center;">Regístrate por 
				<a href="javascript:;" onClick="login_facebook();">Facebook</a> o 
				<a href="#" class="google_auth" >Google</a></a></p>

				<h3 style="margin: 0; text-align: center;">Completa tus datos</h3>
				<div class="km-box-form">
					<div class="content-placeholder">
						<div class="label-placeholder">
							<label>Nombre</label>
							<input data-target="help" type="text" data-charset="xlf" id="rc_nombres" name="rc_nombres" value="" class="input-label-placeholder social_firstname solo_letras" maxlength="20">
							<small data-help="rc_nombres" class="text-help">
								Debes ingresar tu nombre <br> Este debe tener mínimo 3 caracteres.
							</small>
							<small data-error="rc_nombres" style="visibility: hidden;" class="text-help"></small>
						</div>
						<div class="label-placeholder">
							<label>Apellido</label>
							<input data-target="help" type="text" data-charset="xlf" name="rc_apellidos" value="" class="input-label-placeholder social_lastname solo_letras"  maxlength="20">
							<small data-help="rc_apellidos" class="text-help">
								Debes ingresar tu apellido <br> Este debe tener mínimo 3 caracteres.
							</small>
							<small data-error="rc_apellidos" style="visibility: hidden;"></small>
						</div>
						
						<div style="display: none;">
							<select data-target="help" name="rc_tipo_documento" class="select_tipo_doc km-select-custom" style="font-size: 13px !important;">
								<option>IFE / INE</option>
								<option value="">Seleccione Documento de Identidad</option>
								<option>IFE / INE</option>
								<option>Pasaporte</option>
							</select>
							<small data-help="rc_tipo_documento" class="text-help">
								Selecciona el tipo de documento de identidad
							</small>
							<small data-error="rc_tipo_documento" style="visibility: hidden;"></small>
						</div>
						<div id="rc_ife" class="label-placeholder" style="display: none;">
							<label>IFE/Documento de Identidad</label>
							<input data-target="help" type="text"  maxlength="13" minlength="13" data-charset="num" name="rc_ife" value="0000000000000" class="input-label-placeholder solo_numeros" data-toggle="tooltip" title="Coloca los 13 Números que se encuentran en la parte trasera de tu IFE o INE" >
							<small data-help="rc_ife" class="text-help">
								El DNI debe ser de al menos 13 dígitos
							</small>
							<small data-error="rc_ife" style="visibility: hidden;"></small>
						</div>
						<div id="rc_pasaporte" class="label-placeholder" style="display: none;">
							<label>Pasaporte</label>
							<input data-target="help" type="text" maxlength="28" name="rc_pasaporte" value="" class="input-label-placeholder" data-toggle="tooltip" title="Coloca tu n&uacute;mero de pasaporte" >
							<small data-help="rc_pasaporte" class="text-help">
								El DNI debe ser de al menos 28 dígitos
							</small>
							<small data-error="rc_pasaporte" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder fecha_placeholder">
							<label>Fecha de Nacimiento</label>
							<input data-target="help" type="text" name="fecha" id="fecha" class="input-label-placeholder" placeholder="dd/mm/yyyy" readonly />
							<small data-help="fecha" class="text-help">
								Indica tu fecha de nacimiento
							</small>
							<small data-error="fecha" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder">
							<label>Correo electrónico</label>
							<input data-target="help" type="email" name="rc_email"  maxlength="250" data-charset="cormlfnum" autocomplete="off" type='text' id='email_1' value="" class="social_email input-label-placeholder">
							<small data-help="rc_email" class="text-help">
								Ingresa tu E-mail <br> Ej: xxxx@xxx.xx
							</small>
							<small data-error="rc_email" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder">
							<label>Crea tu contraseña</label>
							<input data-target="help" type="password" data-clear name="rc_clave"  maxlength="50" value="" class="input-label-placeholder" autocomplete="off">
							<small data-help="rc_clave" class="text-help">
								Ingresa tu clave 
							</small>
							<small data-error="rc_clave" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder">
							<label>Teléfono</label>
							<input data-target="help" 
							type="text" 
							name="rc_telefono" 
							data-charset="num" 
							minlength="10" 
							maxlength="15" 
							value="" 
							class="input-label-placeholder solo_numeros"
							data-toggle="tooltip"
						>
							<small data-help="rc_telefono" class="text-help">
								El tel&eacute;fono debe tener entre 10 y 12 d&iacute;gitos
							</small>
							<small data-error="rc_telefono" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder">
							<label>¿Cómo nos conoció?</label>
							<select data-target="help" class="km-datos-estado-opcion km-select-custom" name="rc_referred"><?php
								if( $_SESSION["wlabel"] != "petco" ){
									echo '<option value="">Dónde nos conoció?</option>';
								}
								$list = get_referred_list_options();
								foreach( $list as $key => $item ){ ?>
									<option value="<?php echo $key; ?>"><?php echo $item; ?></option> <?php
								} ?>
							</select>
							<small data-help="rc_referred" class="text-help">
								Indica donde nos conosciste
							</small>
							<small data-error="rc_referred" style="visibility: hidden;"></small>
						</div>
					</div>
				</div>
				<a href="#" class="km-btn-correo km-btn-popup-registro-cuidador-correo">SIGUIENTE</a>

				<p style="color: #979797">Al crear una cuenta, <a href="<?php echo get_home_url(); ?>/terminos-y-condiciones/">aceptas las condiciones del servicio y la Política de privacidad</a> de Kmimos.</p>
				
				<p><b>Dudas escríbenos</b></p>
				<div class="row">
					<div class="col-xs-6"><p><img style="width: 20px; margin-right: 5px; position: relative; top: -3px;" src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-wsp.svg"> +52 1 55 7850 7572</p></div>
					<div class="col-xs-6"><p><a href="#"><img style="width: 15px; margin-right: 5px; position: relative; top: -1px;" src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-mail.svg">y.chaudary@kmimos.la</a></p></div>
				</div>

				<hr>
				<div class="row">
					<div class="col-xs-5">
						<p>¿Ya tienes una cuenta?</p>
					</div>
					<div class="col-xs-7">
						<a href="javascrip:;" data-modal="#popup-iniciar-sesion" class="modal_show km-btn-border"><b>INICIAR SESIÓN</b></a>
					</div>
				</div>
			</div>

			<div class="popuphide popup-condiciones">
				<h3 style="margin: 0; text-align: center;">TERMINOS Y CONDICIONES</h3>
				<div class="terminos_container"></div>
				<a href="#" id="btn_si_acepto_cuidador" class="km-btn-correo btn_disable">
					ACEPTAR TERMINOS Y CONDICIONES
				</a>
				<a href="#" id="btn_no_acepto_cuidador" class="">
					No acepto los terminos y condiciones
				</a>
			</div>
			
			<div class="popuphide popup-registro-exitoso" style="padding: 20px 20px 40px;">
				<div class="overlay" style="background: rgba(0, 0, 0, 0.75);"></div>
				<div class="popup-registro-exitoso-text">

					<h3>¡Genial! <span data-target="name"></span></h3>
					<p style="font-size: 20px;">Ya creaste tu perfil como Cuidador Kmimos con éxito.</p>
					<p style="font-size: 20px;">Te invitamos a seguir enriqueciendo tu perfil en</p>
					<p style="font-size: 25px; line-height: 25px;">¡Tres simples pasos!</p>
					<p style="font-size: 20px; line-height: 20px; font-weight: 800;">Mientras más completo esté tu perfil, mayor será tu ganancia.</p>
					<a href="#" class="km-btn km-btn-popup-registro-exitoso">COMENZAR</a>

				</div>
			</div>

			<div class="popuphide popup-registro-cuidador-paso1">
				<div class="page-reservation" style="background-color: transparent; margin-bottom: 30px;">
					<ul class="steps-numbers">
						<li>
							<span data-step="1" class="number active">1</span>
						</li>
						<li class="line"></li>
						<li>
							<span class="number">2</span>
						</li>
						<li class="line"></li>
						<li>
							<span class="number">3</span>
						</li>
					</ul>
				</div>
				<h3 style="margin: 0;">Foto de perfil</h3>
				<p style="color: #979797">Mu&eacute;stranos tu mejor sonrisa</p>

				<div class="img_registro_cliente" style="position: relative">
					<div class="km-datos-foto vlz_rotar" id="perfil-img-a" style="background-image: url(<?php echo getTema(); ?>/images/new/icon/icon-fotoperfil.svg);">
						<div id="loading-perfil" style="width:100%; height: 100%; display:none;" class="vlz_cargando">
							<img 
								src="<?php echo getTema(); ?>/images/new/bx_loader.gif" 
							/>
						</div>
					</div>

					<div id="rotar_i" class="btn_rotar" style="display: none;" data-orientacion="left"> <i class="fa fa-undo" aria-hidden="true"></i> </div>
	                <div id="rotar_d" class="btn_rotar" style="display: none;" data-orientacion="right"> <i class="fa fa-repeat" aria-hidden="true"></i> </div>
	                <div id="quitar_foto" class="btn_quitar_foto"> <i class="fa fa-times" aria-hidden="true"></i> </div>

	                <div class="btn_aplicar_rotar" style="display: none;"> Aplicar Cambio </div>

	                <input type="hidden" id="vlz_img_perfil" name="rc_vlz_img_perfil" value="" class="vlz_rotar_valor">
					<br>
					<small data-error="rc_vlz_img_perfil" style="visibility: hidden; color: red; padding: 5px 0px; border: solid 1px; display: block margin-bottom: 5px;"></small>
	                
				</div>

				<!-- <a href="#" data-load='portada' id="perfil-img-a" class="vlz_rotar">
					<img class="img-circle" id="perfil-img" src="<?php echo getTema(); ?>/images/new/icon/icon-fotoperfil.svg">
				</a>
				<div class="kmimos_cargando" style="visibility: hidden;">
					<span>Cargando...</span>
				</div>
				<div id="rotar" data-id="perfil-img-a" class="km-btn-border" style="display: none;">ROTAR</div> -->
				
				<small style="display:block; border-radius: 5px; margin-bottom: 5px;" class="text-help">
					Te recomendamos que en la foto de perfil, aparezcas tú sonriente con perritos.
				</small>
				<a 
					href="#" 
					data-load='portada' 
					class="km-btn-border"
					data-toggle="tooltip"
					title='Te recomendamos que en la foto de perfil, aparezcas tú, sonriente, con perritos'
				>ACCEDER A TU GALERÍA</a>
            	<input data-target="help" class="hidden" type="file" id="portada" name="rc_portada" accept="image/*" />

				<h3 style="margin-top: 20px;">Descripción de tu perfil</h3>
				<p style="color: #979797">Preséntate en la comunidad de Cuidadores Kmimos</p>
				<textarea 
					style="margin-bottom: 0px;"
					data-target="help"
					name="rc_descripcion" 
					class="km-descripcion-peril-cuidador" 
					data-toggle="tooltip"
					title='Cu&eacute;ntanos sobre ti, tus cualidades y por que deberían escogerte a ti para cuidar sus perritos'
					placeholder="Ejemplo: Hola soy María, soy Cuidadora profesional desde hace 15 años, mi familia y yo amamos a los perros, esto no es solo un trabajo sino una pasión para mí, poder darle todo el cuidado y hacerlo sentir en casa es mi propósito. Te garantizo tu mascota regresará feliz.">¡Hola! Soy ________, tengo ___ años y me encantan los animales. Estaré 100% al cuidado de tu perrito, lo consentiré y recibirás fotos diarias de su estancia conmigo. Mis huéspedes peludos duermen dentro de casa SIN JAULAS NI ENCERRADOS. Cuento con _______ para que jueguen, además cerca de casa hay varios parques donde los saco a pasear diariamente. En su estancia tu perrito contará con cobertura de gastos veterinarios, que en caso de emergencia se encuentra a dentro de mi colonia, muy cerca de mi casa. Cualquier duda que tengas no dudes en contactarme.
				</textarea>
				<small class="text-help" data-help="rc_descripcion">
					Cu&eacute;ntanos sobre ti, tus cualidades y porque deberían escogerte a ti para cuidar sus perritos
				</small>
				<small data-error="rc_descripcion" style="visibility: hidden;"></small>

				<div class="fotos_btn">
					Cargar las fotos de mi galería
            		<input type="file" id="fotos" name="rc_fotos" accept="image/*" multiple />
				</div>
				<div class="galeria_container"></div>
				<small data-error="vlz_galeria" style="visibility: hidden;"></small>
				<small class="text-help" data-help="rc_descripcion">
					Solo se permiten 6 imágenes maximo
				</small>

				<a href="#" class="km-btn-correo km-btn-popup-registro-cuidador-paso1">SIGUIENTE</a>
				<!-- <a href="#km-registro-tip1" class="km-registro-tip" role="button" data-toggle="modal"></a> -->
			</div>
			
			<div class="popuphide popup-registro-cuidador-paso2">
				<div class="page-reservation" style="background-color: transparent; margin-bottom: 30px;">
					<ul class="steps-numbers">
						<li>
							<span data-step="1" class="number checked">1</span>
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
				</div>
				<h3 style="margin: 0;">Dirección</h5>
				<p style="color: #979797">Queremos saber tu dirección actual</p>
				<a href="#" class="km-btn-border obtener_direccion">UBICACIÓN ACTUAL</a>
				<div class="line-o">
					<p class="text-line">o</p>
					<div class="bg-line"></div>
				</div>
				<div class="km-box-form">
					<div class="content-placeholder">
						<div class="label-placeholder">
							<label>Estado</label>
							<select data-target="help" class="km-datos-estado-opcion km-select-custom" name="rc_estado">
								<option value="">Selección de Estado</option>
								<?php
									global $wpdb;
								    $estados = $wpdb->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY name ASC");
								    $str_estados = "";
								    foreach($estados as $estado) { 
								        $str_estados .= "<option value='".$estado->id."'>".$estado->name."</option>";
								    } 
								    echo $str_estados = utf8_decode($str_estados);
								?>
							</select>
							<small class="text-help" data-help="rc_estado">
								Selecciona tu estado de residencia
							</small>
							<small data-error="rc_estado" style="visibility: hidden;"></small>

						</div>
						<div class="label-placeholder">
							<label>Municipio</label>
							<select data-target="help" class="km-datos-municipio-opcion km-select-custom" name="rc_municipio">
								<option value="">Selección de Municipio</option>
							</select>
							<small class="text-help" data-help="rc_municipio">
								Selecciona tu municipio de residencia
							</small>
							<small data-error="rc_municipio" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder">
							<label>Dirección</label>
							<input data-target="help" 
								type="text" 
								id="rc_direccion" 
								name="rc_direccion" 
								value="" 
								class="input-label-placeholder"
								data-toggle="tooltip"
								title="Escribe la dirección que aparece en tu comprobante de domicilio."
							>
							<small class="text-help" data-help="rc_direccion">
								Escribe la dirección que aparece en tu comprobante de domicilio.
							</small>
							<small data-error="rc_direccion" style="visibility: hidden;"></small>
						</div>
					</div>

					<div class="inputs_containers row_3" style="padding-bottom: 10px;"> 
				        <div class="info_map">Puedes establecer con m&aacute;s precisi&oacute;n tu ubicaci&oacute;n desplazando el PIN en el mapa.</div>            
				        <div id="map_canvas" style="width:100%; height:300px;"></div>
				        <input type="hidden" name="latitud" id="lat" />
				        <input type="hidden" name="longitud" id="long" />
				    </div>
				</div>
				<a href="#" class="km-btn-correo km-btn-popup-registro-cuidador-paso2">SIGUIENTE</a>
				<!-- <a href="#" class="km-registro-tip" role="button" data-toggle="modal"></a> -->
			</div>
			
			<div class="popuphide popup-registro-cuidador-paso3">
				<div class="page-reservation" style="background-color: transparent; margin-bottom: 30px;">
					<ul class="steps-numbers">
						<li>
							<span data-step="1" class="number checked">1</span>
						</li>
						<li class="line"></li>
						<li>
							<span data-step="2" class="number checked">2</span>
						</li>
						<li class="line"></li>
						<li>
							<span class="number active">3</span>
						</li>
					</ul>
				</div>
				<h3 style="margin: 0;"><span data-target="name"></span>,</h5>
				<h3 style="margin: 0 0 10px;">¡TE FALTA MUY POCO!</h5>
				<p style="color: #979797">Proporciona la información requerida a continuación.</p>
				<div class="km-block">
					<div class="km-block-1">
						<p>Número de mascotas que aceptas</p>
					</div>
					<div class="km-block-2">
						<div class="page-reservation km-cantidad">
							<div class="km-content-step">
								<div class="km-content-new-pet">
									<div class="km-quantity">
										<a href="#" id="cr_minus" class="cr_minus disabled">-</a>
										<span class="km-number">1</span>
										<a href="#" id="cr_plus" class="cr_minus">+</a>
										<input data-target="help"  type="text" name="rc_num_mascota" value="1" style="display:none;">
									</div>
								</div>
							</div>
						</div>
					</div>
					<h3 style="margin: 20px 0px;">Precios de Hospedaje</h5>
					<div class="box_info_modal">
						<p style="text-align: justify; font-size: 13px !important;">
							En Kmimos siempre podrás colocar el precio que mejor se acomode, la decisión es tuya. Sin embargo quisiéramos recomendarte el rango de precios mostrados abajo, el cual esta creado basado en las tendencias de precios existentes en el mercado actual
						</p>
						<div style="text-align: center;">
							<table class="tam_pc" border="0" cellpadding="0" cellspacing="0" style="text-align: justify;">
								<tr><td><strong>Tamaño pequeño</strong> &nbsp;&nbsp;</td><td style="text-align: right;"> 160 pesos por noches </td></tr>
								<tr><td><strong>Tamaño mediano</strong> &nbsp;&nbsp;</td><td style="text-align: right;"> 200 pesos por noches </td></tr>
								<tr><td><strong>Tamaño grande</strong> &nbsp;&nbsp;</td><td style="text-align: right;"> 240 pesos por noches </td> </tr>
								<tr><td><strong>Tamaño gigante</strong> &nbsp;&nbsp;</td><td style="text-align: right;"> 280 pesos por noches </td></tr>
							</table>


							<table class="tam_movil" border="0" cellpadding="0" cellspacing="0" style="text-align: justify;">
								<tr><td><strong>Tam. pequeño</strong> &nbsp;&nbsp;</td><td style="text-align: right;"> 160 pesos/noche</td></tr>
								<tr><td><strong>Tam. mediano</strong> &nbsp;&nbsp;</td><td style="text-align: right;"> 200 pesos/noche </td></tr>
								<tr><td><strong>Tam. grande</strong> &nbsp;&nbsp;</td><td style="text-align: right;"> 240 pesos/noche </span> </td> </tr>
								<tr><td><strong>Tam. gigante</strong> &nbsp;&nbsp;</td><td style="text-align: right;"> 280 pesos/noche </td></tr>
							</table>
						</div>
					</div>
					<div class="precios_registro">
						<div class="km-block-1">
							<p>Mascota Pequeña</p>
						</div>
						<div class="km-block-2">
							<p> <input type="number" name="precios[pequenos]" /> </p>
						</div>
						<div class="km-block-1">
							<p>Mascota Mediana</p>
						</div>
						<div class="km-block-2">
							<p> <input type="number" name="precios[medianos]" /> </p>
						</div>
						<div class="km-block-1">
							<p>Mascota Grande</p>
						</div>
						<div class="km-block-2">
							<p> <input type="number" name="precios[grandes]" /> </p>
						</div>
						<div class="km-block-1">
							<p>Mascota Gigante</p>
						</div>
						<div class="km-block-2">
							<p> <input type="number" name="precios[gigantes]" /> </p>
						</div>
					</div>
					<small data-error="rc_precios" style="visibility: hidden;"></small>
				</div>
				<hr>
				<a href="#" class="km-btn-correo km-btn-popup-registro-cuidador-paso3">SIGUIENTE</a>
				<!-- <a href="#" class="km-registro-tip"></a href="#"> -->
			</div>
			
			<div class="popuphide popup-registro-exitoso-final">
				<div class="overlay" style="background: rgba(0, 0, 0, 0.75);"></div>
				<div class="popup-registro-exitoso-text" style="overflow: hidden;">
					<h2 style="font-size: 18px; color: white;">Tu perfil está listo <span data-target="name"></span>!</h2>
					<h2 style="font-size: 18px; color: white;">Recibimos tu solicitud para sumarte a la familia de cuidadores Kmimos.</h2>		
					<h2 style="font-size: 25px; color: white; text-align: center;">SIGUIENTES PASOS PARA ACTIVAR TU PERFIL:</h2>		

					<aside class="text-left col-sm-10 col-sm-offset-1">
						<p style="font-size: 18px;">1. En el transcurso del día, te enviaremos un correo con la liga de las pruebas de conocimientos y psicometría, es necesario que las contestes para poder continuar con el proceso.</p>
						<p style="font-size: 18px;">2. El equipo de certificación Kmimos te contactará en breve para acompañarte y resolver tus dudas, también puedes contactarnos a través de Whatsapp al +52 1 55 7850 7572.</p>
						<p style="font-size: 18px;">Te recordamos tus credenciales para tu perfil de Kmimos:</p>
						<!--
						<p style="font-size: 18px;">1. Da click en el botón CONTINUAR (mostrado abajo), serás redirigido de inmediato a las pruebas de Conocimientos Veterinarios.</p>
						<p style="font-size: 18px;">2. Al final del día te enviaremos un correo con la liga de las pruebas de conocimientos veterinarios (guarda este correo en caso que necesites retomar la prueba más adelante y/o cargar tus documentos).</p>
						<p style="font-size: 18px;">3. Al dar click en el botón CONTINUAR, deberás iniciar sesión con las siguientes credenciales:</p>
						-->
						<p style="text-align: center; font-size: 18px;">
							<strong>Usuario:</strong> <span data-id="ilernus-user"></span>
							</br>
							<strong>Contraseña:</strong> <span data-id="ilernus-pass"></span>
						</p>
					</aside>
					<div class="col-sm-12">
						<a style="cursor:pointer;"  id="finalizar-registro-cuidador" 
							data-href="<?php echo get_home_url(); ?>/perfil-usuario/" 
							class="km-btn">CONTINUAR</a>
					</div>
					<!--
						<p style="font-size: 15px;">Completaste tu perfil perfectamente</p>
					-->

				</div>
			</div>
		</form>

		</div>
	</div>
</div>