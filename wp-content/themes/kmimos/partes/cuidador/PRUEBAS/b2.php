<?php
	if( count($mascotas)>0 && $validar_perfil_completo ){

		if( !$pagado ){
			$HTML_CONOCER_REQUISITOS = '
				<p class="popup-tit">
					Conoce al Cuidador <span id="modal-name-cuidador"></span> antes de dejar a tu peludo bajo su cuidado
				</p>
				<div class="mensaje_1">
					No te preocupes, si no te convence puedes elegir un nuevo Cuidador o bien cancelar tu reservación
				</div>
			';
		}

		$HTML_CONOCER .= $HTML_CONOCER_REQUISITOS.'
		<form id="conoce_cuidador" style="padding:0px;" method="post">

			<input name="post_id" type="hidden" value="">
			<input name="user_id" type="hidden" value="'.$user_id.'">

			<div class="km-box-form">
				<div class="content-placeholder">

					<div class="km-calendario">
						<label>¿Cuándo deseas conocer al cuidador?</label>
						<input type="text" id="meeting_when" name="meeting_when" placeholder="dd/mm/aaaa" class="km-calendario date_from" readonly>
						<small data-error="meeting_when" style="display: none;">Debes ingresar una fecha</small>
					</div>
					<div class="km-datos-mascota">
						<select class="km-datos-mascota-opcion" data-charset="all" id="meeting_time" name="meeting_time">
							<option value="" class="vacio">¿A qué hora te convendría la reunión?</option>
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
						<small data-error="meeting_time" style="display: none;">Debes seleccionar una hora</small>
					</div>
					<div class="label-placeholder">
						<label>¿Dónde deseas conocer al cuidador?</label>
						<input type="text" id="meeting_where" name="meeting_where" data-charset="xlfalfnum" autocomplete="off" value="" class="input-label-placeholder date_form">
						<small data-error="meeting_where" style="display: none;">Debes ingresar un lugar</small>
					</div>
					<div class="km-group-checkbox">
						<label>¿Qué mascotas requieren el servicio?</label>
						<ul id="pet_conoce">';
							foreach ($mascotas as $mascota) {
	                            $HTML_CONOCER .= '
	                            <li>
                                	<input type="checkbox" name="pet_ids[]" id="pet_'.$mascota->ID.'" value="'.$mascota->ID.'" >
	                                <label for="pet_'.$mascota->ID.'" style="padding-top: 0px !important;">
	                                	'.$mascota->post_title.'
	                               	</label>
	                            </li>';
	                        } $HTML_CONOCER .= '
	                    </ul>
						<small data-error="pet_conoce" style="display: none;">Debes seleccionar al menos una mascota</small>
					</div>
					</br>							
					<a href="javascript:;" id="btn_enviar_conocer" data-id="enviar_datos" class="km-btn-basic">
						Enviar solicitud
					</a>
				</div>
			</div>
		</form>';
	}
?>