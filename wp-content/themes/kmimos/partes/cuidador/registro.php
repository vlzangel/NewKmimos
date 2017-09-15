<!-- POPUPS REGISTRO -->
<div id="popup-registro-cuidador1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<div class="popup-registro-cuidador active">
				
				<a href="javascript:;" onClick="login_facebook();" class="km-btn-fb"><img src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-fb-blanco.svg">REGISTRARME CON FACEBOOK</a>
				
				<a href="#"  id="registro_cuidador_google" class="google_auth km-btn-border"><img src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-gmail.svg">REGISTRARME CON GOOGLE</a>



				<div class="row hidden">
					
					<div class="line-o">
						<p class="text-line">o</p>
						<div class="bg-line"></div>
					</div>
					<a href="javascript:;" onClick="auth_facebook();" class="km-btn-fb"><img src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-fb-blanco.svg"> CONÉCTATE CON FACEBOOK</a>
					<a href="#" class="google_login km-btn-border"><img src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-gmail.svg"> CONÉCTATE CON GOOGLE</a>
				
				</div>


				<div class="line-o">
					<p class="text-line">o</p>
					<div class="bg-line"></div>
				</div>
				<a href="#" class="social-next-step  km-btn-correo km-btn-popup-registro-cuidador"><img src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-mail-blanco.svg">REGISTRARME POR CORREO ELECTRÓNICO</a>
				<p style="color: #979797">Al crear una cuenta, aceptas las condiciones del servicio y la Política de privacidad de Kmimos.</p>
				<p><b>Dudas escríbenos</b></p>
				<div class="row">
					<div class="col-xs-4"><p><img style="width: 20px; margin-right: 5px; position: relative; top: -3px;" src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-wsp.svg">Whatsapp</p></div>
					<div class="col-xs-4"><p><a href="#"><img style="width: 15px; margin-right: 5px; position: relative; top: -1px;" src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-mail.svg">a.vera@kmimos.la</a></p></div>
					<div class="col-xs-4"><p><img style="width: 12px; margin-right: 5px; position: relative; top: -1px;" src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-cel.svg">(55) 6178 0320</p></div>
				</div>
				<hr>
				<div class="row">
					<div class="col-xs-5">
						<p>¿Ya tienes una cuenta?</p>
					</div>
					<div class="col-xs-7">
						<a href="#" data-target="#login" class="km-btn-border"><b>INICIAR SESIÓN</b></a>
					</div>
				</div>
			</div>

		<form id="vlz_form_nuevo_cuidador" style="padding-bottom: 0px;">
			<input type="hidden" name="longitude" value="">
			<input type="hidden" name="latitude"  value="">
			<input type="hidden" name="google_auth_id"   class="social_google_id"	 value="">
			<input type="hidden" name="facebook_auth_id" class="social_facebook_id"  value="">

			<div class="popuphide popup-registro-cuidador-correo">
				<p style="color: #979797; text-align: center;">Regístrate por 
				<a href="javascript:;" onClick="login_facebook();">Facebook</a> o 
				<a href="#" class="google_auth" >Google</a></a></p>
				<h3 style="margin: 0; text-align: center;">Completa tus datos</h3>
				<div class="km-box-form">
					<div class="content-placeholder">
						<div class="label-placeholder">
							<label>Nombre</label>
							<input type="text" data-charset="xlf" name="rc_nombres" value="" class="input-label-placeholder social_firstname">
							<small data-error="rc_nombres" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder">
							<label>Apellido</label>
							<input type="text" data-charset="xlf" name="rc_apellidos" value="" class="input-label-placeholder social_lastname">
							<small data-error="rc_apellidos" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder">
							<label>IFE/Documento de Identidad</label>
							<input type="text" data-charset="num" name="rc_ife" value="" class="input-label-placeholder">
							<small data-error="rc_ife" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder">
							<label>Correo electrónico</label>
							<input type="email" name="rc_email" data-charset="cormlfnum" autocomplete="off" type='text' id='email_1' value="" class="social_email input-label-placeholder">
							<small data-error="rc_email" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder">
							<label>Crea tu contraseña</label>
							<input type="password" data-clear name="rc_clave" value="" class="input-label-placeholder">
							<small data-error="rc_clave" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder">
							<label>Teléfono</label>
							<input type="text" name="rc_telefono" data-charset="num" minlength="7" maxlength="15" value="" class="input-label-placeholder">
							<small data-error="rc_telefono" style="visibility: hidden;"></small>
						</div>
					</div>
				</div>
				<a href="#" class="km-btn-correo km-btn-popup-registro-cuidador-correo">SIGUIENTE</a>

				<p style="color: #979797; margin-top: 20px;">Al crear una cuenta, aceptas las condiciones del servicio y la Política de privacidad de Kmimos.</p>
				<p><img style="width: 20px; margin-right: 5px; position: relative; top: -3px;" src="<?php echo getTema(); ?>/images/new/icon/km-redes/icon-wsp.svg">En caso de dudas escríbenos al whatsapp</p>
				<hr>
				<div class="row">
					<div class="col-xs-5">
						<p>¿Ya tienes una cuenta?</p>
					</div>
					<div class="col-xs-7">
						<a href="#" class="km-btn-border"><b>INICIAR SESIÓN</b></a>
					</div>
				</div>
			</div>
			
			<div class="popuphide popup-registro-exitoso">
				<div class="overlay"></div>
				<div class="popup-registro-exitoso-text">
					<h3>¡Genial! <span data-target="name"></span>,<br>ya creaste tu perfil como Cuidador Kmimos con éxito</h3>
					<p style="font-size: 15px;">A mayores datos, mayor ganancia.</p>
					<p style="font-size: 15px;">Te invitamos a seguir enriqueciendo tu perfil en</p>
					<h5 style="font-size: 20px">¡3 simples pasos!</h5>
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
				<p style="color: #979797">Brinda a tus futuros amigos</p>

				<a href="#" data-load='portada'>
					<img class="img-circle" id="perfil-img" src="<?php echo getTema(); ?>/images/new/icon/icon-fotoperfil.svg">
				</a>
				<a href="#" data-load='portada' class="km-btn-border">ACCEDER A TU GALERÍA</a>

            	<input class="hidden" type="file" id="portada" name="rc_portada" accept="image/*" />
	            <input class="hidden" type="text" id="vlz_img_perfil" name="rc_vlz_img_perfil" value="">

				<h3 style="margin-top: 20px;">Descripción de tu perfil</h3>
				<p style="color: #979797">Preséntate en la comunidad de Cuidadores Kmimos</p>
				
				<textarea name="rc_descripcion" class="km-descripcion-peril-cuidador" placeholder="Ejemplo: Hola soy María, soy Cuidadora profesional desde hace 15 años, mi familia y yo amamos a los perros, esto no es solo un trabajo sino una pasión para mí, poder darle todo el cuidado y hacerlo sentir en casa es mi propósito. Te garantizo tu mascota regresará feliz.">¡Hola! Soy ________, tengo ___ años y me encantan los animales. Estaré 100% al cuidado de tu perrito, lo consentiré y recibirás fotos diarias de su estancia conmigo. Mis huéspedes peludos duermen dentro de casa SIN JAULAS NI ENCERRADOS. Cuento con _______ para que jueguen, además cerca de casa hay varios parques donde los saco a pasear diariamente. En su estancia tu perrito contará con seguro de gastos veterinarios, que en caso de emergencia se encuentra a dentro d mi colonia, muy cerca de mi casa. Cualquier duda que tengas no dudes en contactarme.
				</textarea>
				<small data-error="rc_descripcion" style="visibility: hidden;"></small>

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
							<span data-step="2" class="number active">2</span>
						</li>
						<li class="line"></li>
						<li>
							<span class="number">3</span>
						</li>
					</ul>
				</div>
				<h3 style="margin: 0;">Dirección</h5>
				<p style="color: #979797">Queremos saber tu dirección actual</p>
				<a href="#" class="km-btn-border">UBICACIÓN ACTUAL</a>
				<div class="line-o">
					<p class="text-line">o</p>
					<div class="bg-line"></div>
				</div>
				<div class="km-box-form">
					<div class="content-placeholder">
						<div class="label-placeholder">
							<label>Estado</label>
							<select class="km-datos-estado-opcion km-select-custom" name="rc_estado">
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
							<small data-error="rc_estado" style="visibility: hidden;"></small>

						</div>
						<div class="label-placeholder">
							<label>Municipio</label>
							<select class="km-datos-municipio-opcion km-select-custom" name="rc_municipio">
								<option value="">Selección de Municipio</option>
							</select>
							<small data-error="rc_municipio" style="visibility: hidden;"></small>
						</div>
						<div class="label-placeholder">
							<label>Dirección</label>
							<input type="text" name="rc_direccion" value="" class="input-label-placeholder">
							<small data-error="rc_direccion" style="visibility: hidden;"></small>
						</div>
					</div>
				</div>
				<a href="#" class="km-btn-correo km-btn-popup-registro-cuidador-paso2">SIGUIENTE</a>
				<!-- <a href="#" class="km-registro-tip" role="button" data-toggle="modal"></a> -->
			</div>
			
			<div class="popuphide popup-registro-cuidador-paso3">
				<div class="page-reservation" style="background-color: transparent; margin-bottom: 30px;">
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
				</div>
				<h3 style="margin: 0;"><span data-target="name"></span>,</h5>
				<h3 style="margin: 0;">¡TE FALTA MUY POCO!</h5>
				<p style="color: #979797">Llena tus datos para un mayor perfil en la Comunidad Kmimos</p>
				<div class="km-block">
					<div class="km-block-1">
						<p>Número de mascotas que aceptas</p>
					</div>
					<div class="km-block-2">
						<div class="page-reservation km-cantidad">
							<div class="km-content-step">
								<div class="km-content-new-pet">
									<div class="km-quantity">

										<a href="#" id="cr_minus" class="km-minus km-plus disabled">-</a>
										<span class="km-number">1</span>
										<a href="#" id="cr_plus" class="km-plus">+</a>

										<input  type="text" name="rc_num_mascota" value="1" 
												style="display:none;">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<a href="#" class="km-btn-correo km-btn-popup-registro-cuidador-paso3">SIGUIENTE</a>
				<!-- <a href="#" class="km-registro-tip"></a href="#"> -->
			</div>
			
			<div class="popuphide popup-registro-exitoso-final">
				<div class="overlay"></div>
				<div class="popup-registro-exitoso-text">
					<h2 style="font-size: 18px; color: white;">¡LISTO <span data-target="name"></span>!</h2>
					<h2 style="font-size: 18px; color: white;">Recibimos con éxito tu solicitud para sumarte a la familia de Cuidadores Kmimos</h2>
					<p style="font-size: 15px;">Completaste tu perfil perfectamente</p>
					<a href="index-sesion.html" class="km-btn">VER MI PERFIL</a>
				</div>
			</div>
		</form>

		</div>
	</div>
</div>