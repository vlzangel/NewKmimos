<!-- POPUP CONOCE AL CUIDADOR -->
<?php
	wp_enqueue_script('conocer_al_cuidador_js', getTema()."/js/conocer_al_cuidador.js", array("jquery"), '1.0.0');

global $current_user;
date_default_timezone_set('America/Mexico_City');
$user_id = $current_user->ID;

?>
<div id="popup-conoce-cuidador" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<div class="popup-iniciar-sesion-1">
				<p class="popup-tit">Solicitud para conocer al <span id="modal-name-cuidador"></span></p>
				<div>
					<p>Para poder conocer al cuidador primero tienes que:</p>
					<ol>
						<li>Haberte registrado en nuestro portal y haber iniciado sesión.</li>
						<li>Completar todos los datos requeridos en tu perfil</li>
						<li>Completar tu lista de mascotas en tu perfil</li>
					</ol>
				</div>
				<form id="conoce_cuidador" style="padding:0px;" method="post">

					<input name="post_id" type="hidden" value="">

					<div class="km-box-form">
						<div class="content-placeholder">

							<div class="km-calendario">
								<label>¿Cuando deseas conocer al cuidador?</label>
								<input type="date" id="meeting_when" name="meeting_when" placeholder="¿Cuando deseas conocer al cuidador?" class="km-calendario date_from">
								<small data-error="fech_conocer" style="visibility: hidden;"></small>
							</div>
							<div class="km-datos-mascota">
								<select class="km-datos-mascota-opcion" data-charset="all" id="meeting_time" name="meeting_time">
									<option value="">¿A qué hora te convendría la reunión?</option>
		                        	<option value="07:00:00" data-id="7">07:00  a.m.</option>
		                        	<option value="07:30:00" data-id="7.5">07:30  a.m.</option>
		                        	<option value="08:00:00" data-id="8">08:00  a.m.</option>
		                        	<option value="08:30:00" data-id="8.5">08:30  a.m.</option>
		                        	<option value="09:00:00" data-id="9">09:00  a.m.</option>
		                        	<option value="09:30:00" data-id="9.5">09:30  a.m.</option>
		                        	<option value="10:00:00" data-id="10">10:00  a.m.</option>
		                        	<option value="10:30:00" data-id="10.5">10:30  a.m.</option>
		                        	<option value="11:00:00" data-id="11">11:00  a.m.</option>
		                        	<option value="11:30:00" data-id="11.5">11:30  a.m.</option>
		                        	<option value="12:00:00" data-id="12">12:00  m</option>
		                        	<option value="12:30:00" data-id="12.5">12:30  m</option>
		                        	<option value="13:00:00" data-id="13">01:00  p.m.</option>
		                        	<option value="13:30:00" data-id="13.5">01:30  p.m.</option>
		                        	<option value="14:00:00" data-id="14">02:00  p.m.</option>
		                        	<option value="14:30:00" data-id="14.5">02:30  p.m.</option>
		                        	<option value="15:00:00" data-id="15">03:00  p.m.</option>
		                        	<option value="15:30:00" data-id="15.5">03:30  p.m.</option>
		                        	<option value="16:00:00" data-id="16">04:00  p.m.</option>
		                        	<option value="16:30:00" data-id="16.5">04:30  p.m.</option>
		                        	<option value="17:00:00" data-id="17">05:00  p.m.</option>
		                        	<option value="17:30:00" data-id="17.5">05:30  p.m.</option>
		                        	<option value="18:00:00" data-id="18">06:00  p.m.</option>
		                        	<option value="18:30:00" data-id="18.5">06:30  p.m.</option>
		                        	<option value="19:00:00" data-id="19">07:00  p.m.</option>
		                        </select>
								<small data-error="hora_conoce" style="visibility: hidden;"></small>
							</div>
							<div class="label-placeholder">
								<label>¿Dónde deseas conocer al cuidador?</label>
								<input type="text" id="meeting_where" name="meeting_where" data-charset="all" autocomplete="off" value="" class="input-label-placeholder date_form">
								<small data-error="lugar_conoce" style="visibility: hidden;"></small>
							</div>
							<div class="km-group-checkbox">
								<label>¿Qué mascotas requieren el servicio?</label>

			                    <ul><?php
			                        $mascotas = kmimos_get_my_pets($user_id);
									foreach ($mascotas as $mascota) { ?>
			                            <li>
		                                	<input type="checkbox" name="pet_ids[]" id="pet_<?php echo $mascota->ID; ?>" value="<?php echo $mascota->ID; ?>">
			                                <label for="pet_<?php echo $mascota->ID; ?>">
			                                	<?php echo $mascota->post_title; ?>
			                               	</label>
			                            </li> 
			                        <?php } ?>
			                    </ul>
								<small data-error="pet_conoce" style="visibility: hidden;"></small>
							</div>
							<div class="km-calendario">
								<label>¿Desde cuando requieres el servicio?</label>
								<input type="date" id="service_start" name="service_start" placeholder="¿Desde cuando requieres el servicio?" class="date_from">
								<small data-error="fech_conocer" style="visibility: hidden;"></small>
							</div>
							<div class="km-calendario">
								<label>¿Hasta cuando requieres el servicio?</label>
								<input type="date" id="service_end" name="service_end" placeholder="¿Hasta cuando requieres el servicio?" class="date_from">
								<small data-error="fech_conocer" style="visibility: hidden;"></small>
							</div>
							</br>							
							<a href="javascript:;" data-id="enviar_datos" class="km-btn-basic">ENVIAR SOLICITUD</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
