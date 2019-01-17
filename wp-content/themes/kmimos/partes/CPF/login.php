<?php
/*wp_enqueue_script('index.js', getTema()."/js/index.js", array("jquery"), '1.0.0');*/

$datos = kmimos_get_info_syte();
?>

<!-- POPUP INICIAR SESIÓN -->
<div id="popup-iniciar-sesion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content ">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			
			<div login-group class="popup-iniciar-sesion-1" style="padding: 20px;">
				<p class="popup-tit">Inicia sesi&oacute;n en el club de las patitas</p>
				<p class="text-center col-md-8 col-md-offset-2" style="color:#0D7AD8;">Recuerda tener a la mano las credenciales que te hemos enviado al correo electr&oacute;nico</p>
				<form id="form_login" autocomplete="off">
					<input type="hidden" id="proceso" name="proceso" value="" />
					<input type="hidden" id="is_cpf" name="is_cpf" value="1" />

					<div class="km-box-form">
						<div class="content-placeholder">
							<div class="label-placeholder">
								<!-- <label>Correo electrónico</label>-->
								<input type="text" id="usuario" placeholder="Usuario &oacute; Correo El&eacute;ctronico" class="text-left input-label-placeholder">
								<img class="cpf-img-user" width="15px;" src="<?php echo getTema(); ?>/recursos/img/PERFILES/Perfil.svg">
							</div>
							<div class="text-left label-placeholder">
								<!--<label>Contraseña</label>-->
								<img class="cpf-img-pass" width="15px;" src="<?php echo getTema(); ?>/recursos/img/PERFILES/pass.svg">
								<input type="password" id="clave" placeholder="Contraseña" class="input-label-placeholder" autocomplete="off">
							</div>
						</div>
					</div>
					<div class="row km-recordatorio">
						<div class="col-xs-12 col-sm-4">
							<div class="km-checkbox_">
								<label for="km-checkbox">
									<input type="checkbox" value="active" id="km-checkbox_" name="check" checked/> 
									RECORDARME
								</label>
							</div>
						</div>
						<div class="col-xs-12 col-sm-8" style="text-align: right;">
							<a href="#" class="km-btn-contraseña-olvidada" id="olvidaste-contrasena">Recuperar credenciales</a>
							<div class="clear"></div>
						</div>
					</div>
					<div class="row km-recordatorio" style="margin-top:5px;">
						<div class="col-md-12 text-center">
							<div class="clear"></div>
							<input type="submit" name="enviar" class="hidden">
							<!-- <a href="#" id="login_submit" class="btn btn-club btn-lg btn-info">INICIAR SESIÓN AHORA</a> -->

							<button id="login_submit" type="submit" class="btn btn-club btn-lg btn-info">
								INGRESAR
							</button>
						</div>
					</div>
				</form>
			</div>

			<div login-group class="popuphide popup-olvidaste-contrasena" style="padding: 20px;">
				<div class="col-md-12">				
					<div class="col-md-1 text-left">
						<a href="javascript:;" data-atras="true"><i class="fa fa-arrow-left fa-2x" aria-hidden="true"></i></a>
					</div>
					<div class="col-md-10 text-center">
						<p class="popup-tit">¿OLVIDASTE TU CONTRASEÑA?</p>
					</div>
				</div>
				<p>No te preocupes, a todos nos pasa. Ingresa tu correo electrónico y listo!</p>
				<form id="form_recuperar" onsubmit="return false;">
					<div class="km-box-form">
						<div class="content-placeholder">
							<div class="label-placeholder verify" style="margin: 20px 0;">
								<input type="email" id="usuario" data-verify="noactive" placeholder="Ingresar dirección de email"  maxlength="250" class="verify_mail input-label-placeholder">
								<span class="verify_result"></span></div>
							<div class="botones_box text-center">
								<button type="button" class="btn btn-club btn-lg btn-info" style=" outline: none; border: none;" id="recovery-clave">
									ENVIAR CONTRASEÑA
								</button>

								<!-- button type="button" style=" outline: none; border: none; width: 100%;" id="recuperar_submit-1" class="km-btn-basic recover_pass">ENVIAR CONTRASEÑA</button --></div>
							<div class="response"></div>
						</div>
					</div>
				</form>
			</div>

			<div login-group class="popuphide popup-msg-registrado row" style="padding: 20px;">
				<div class="col-md-12">
					<div class="col-md-1 text-left">
						<a href="javascript:;" data-atras="true"><i class="fa fa-arrow-left fa-2x" aria-hidden="true"></i></a>
					</div>
					<div class="col-md-10 text-center">
						<p class="popup-tit">¡UUUPS!</p>
					</div>
				</div>
				<div class="col-md-12 text-center">
					<div class="col-md-4 col-md-offset-4">
						<img src="<?php echo getTema(); ?>/recursos/img/PERFILES/Dog.svg" class="img-responsive">
					</div>
				</div>
				<div class="col-md-12 text-center">
					<h3 style="font-size: 16px;"><strong>¡Parece que ya te has registrado en el club!</strong></h3>
					<p style="color:#4f4f4f;font-weight:100;">Inicia sesi&oacute;n para ver tus cr&eacute;ditos</p>
					<button id="show-iniciar-sesion" type="submit" class="btn btn-club btn-lg btn-info">
						Iniciar sesi&oacute;n
					</button>
					<div class="clear"></div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- FIN POPUP INICIAR SESIÓN -->
