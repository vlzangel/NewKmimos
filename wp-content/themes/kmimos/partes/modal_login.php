<?php	
	$HTML .='
		<!-- POPUP INICIAR SESIÓN -->
		<div id="popup-iniciar-sesion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<div class="popup-iniciar-sesion-1">
						<p class="popup-tit">INICIAR SESIÓN</p>
						
						<form id="form_login">
							<div class="km-box-form">
								<div class="content-placeholder">
									<div class="label-placeholder">
										<!-- <label>Correo electrónico</label>-->
										<input type="text" id="usuario" placeholder="Correo El&eacute;ctronico" class="input-label-placeholder">
									</div>
									<div class="label-placeholder">
										<!--<label>Contraseña</label>-->
										<input type="password" id="clave" placeholder="Contraseña" class="input-label-placeholder">
									</div>
								</div>
							</div>
							<a href="#" id="login_submit" class="km-btn-basic">INICIAR SESIÓN AHORA</a>
							<div class="row km-recordatorio">
								<div class="col-xs-12 col-sm-4">
									<div class="km-checkbox">
										<input type="checkbox" value="None" id="km-checkbox" name="check" checked/>
										<label for="km-checkbox"></label>
									</div>
								</div>
							</form>

							<div class="col-xs-12 col-sm-8" style="text-align: right;">
								<a href="#" class="km-btn-contraseña-olvidada">¿OLVIDASTE TU CONTRASEÑA?</a>
							</div>
						</div>
						<div class="line-o">
							<p class="text-line">o</p>
							<div class="bg-line"></div>
						</div>
						<a href="#" class="km-btn-fb" onclick="login_facebook();">
							<img src="'.getTema().'/images/icons/km-redes/icon-fb-blanco.svg">
							CONÉCTATE CON FACEBOOK
						</a>
						<a href="#" class="km-btn-border" id="customBtn">
							<img src="'.getTema().'/images/icons/km-redes/icon-gmail.svg">
							CONÉCTATE CON GOOGLE
						</a>
						<script>//startApp();</script>
						
						
						<p style="color: #979797; margin-top: 20px;">Al crear una cuenta, aceptas las condiciones del servicio y la Política de privacidad de Kmimos.</p>
						<p><img style="width: 20px; margin-right: 5px; position: relative; top: -3px;" src="'.getTema().'/images/icons/km-redes/icon-wsp.svg">En caso de dudas escríbenos al whatsapp</p>
						<hr>
						<div class="row">
							<div class="col-xs-12 col-sm-5">
								<p style="margin-bottom: 0px;">¿No eres miembro todavía?</p>
								<p>REGÍSTRATE AHORA - Es Gratis</p>
							</div>
							<div class="col-xs-12 col-sm-7">
								<a href="#myModal" class="km-btn-border" data-toggle="modal" data-target="#myModal"><b>REGÍSTRATE</b></a>
							</div>
						</div>
					</div>
					<div class="popuphide popup-olvidaste-contrasena">
						<p class="popup-tit">¿OLVIDASTE TU CONTRASEÑA?</p>
						<p>No te preocupes, a todos nos pasa. Ingresa tu correo electrónico y listo!</p>
					<form id="form_recuperar">	
						<div class="km-box-form">
							<div class="content-placeholder">
								<div class="label-placeholder">
									<input type="text" id="usuario" placeholder="Correo El&eacute;ctronico" class="input-label-placeholder">
								</div>
									<div class="botones_box">
		                 			<a href="#" id="login_submit" class="km-btn-basic">ENVIAR CONTRASEÑA</a>
		                 		</div>
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
		<!-- FIN POPUP INICIAR SESIÓN -->
';