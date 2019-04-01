<?php
	
	$HTML_CONOCER .= '
		<div style="font-size: 16px; margin: 30px 0px;"> 
			En este momento esta función no está disponible, te invitamos a darle click al botón Reservar. 
		</div>   
		<div class="km-buttons" style="text-align: center; margin-top: 20px;">  
			<a id="btn_reserva_conocer" href="" class="km-btn-secondary">RESERVAR</a>  
		</div>
	';

/*
	if( count($mascotas)>0 && $validar_perfil_completo ){

		if( $ES_PERFIL == 'YES' ){
			$puede_conocer = true;
		}

		if( $puede_conocer == false ){

			$pendientes = get_cupos_conocer_pendientes($user_id);

			$pendientes_str = '';
			if( $pendientes != null ){
				$metadata = json_decode($pendientes->metadata);
				$HTML_CONOCER .= '

					<input name="post_id" type="hidden" value="">
					<div style="padding: 20px 0px 0px; font-weight: 600; color: #7c169e; font-size: 17px; text-align: center;">
						Ya casi acabamos! solo necesitas pagar en una tienda de conveniencia para tener tus créditos disponibles

						<div style="text-align: center; padding: 30px 0px 0px;">
							<a href="'.$metadata->pdf.'" target="_blank" class="km-btn-basic" style="text-transform: uppercase; font-weight: 600;">Descargar comprobante de pago</a>
						</div>

						<div style="margin-top: 20px;">
							<span id="recargar_saldo" class="km-btn-basic" style="text-transform: uppercase; font-weight: 600;">Generar otra solicitud de pago</span>
						</div> 
					</div>
				';
			}else{
				$HTML_CONOCER .= '
					<p class="popup-tit">
						Conoce al cuidador <span id="modal-name-cuidador"></span> antes de Reservar su servicio
					</p>
					<div class="pre_requisitos">
						<p>Para poder conocer al cuidador primero tienes que:</p>
						<ul>
							<li>Completa tu perfil y registra a tus mascotas</li>
							<li>Adquiere por $30 pesos, 3 créditos para conocer a cualquier cuidador*</li>
							<li>Solicita tu cita con tu cuidador y posteriormente reserva.</li>
						</ul>
					</div>

					<input name="post_id" type="hidden" value="">

					<p style="text-align: justify;">
						*Al adquirir el paquete de conocer cuidador de $30 pesos, tendrás la opción de escoger 3 cuidadores en un lapso de 3 meses máximo, y en caso de reservar con alguno de esos 3 cuidadores tus servicios para tu peludo, te bonificaremos esos $30 pesos en la reservación o te lo reembolsaremos en efectivo.
					</p>
					<p style="text-align: justify;">
						En caso de que el cuidador cancele la solicitud de conocerte o la reserva, se te regresará tu saldo a favor para que tengas la opción de conocer a otro cuidador disponible. Si tu cancelas la solicitud para conocer al cuidador, se tomará esa cancelación a cuenta del paquete que contrataste.
					</p>

					'.$pendientes_str.'

					<div style="margin-top: 50px;">
						<span id="recargar_saldo" class="km-btn-basic" style="text-transform: uppercase; font-weight: 600;">Adquirir solicitudes</span>
					</div> 
				';
			}

		}else{
			
			if( !$pagado ){
				$HTML_CONOCER_REQUISITOS = '
					<p class="popup-tit">
						Conoce al cuidador <span id="modal-name-cuidador"></span> antes de Reservar su servicio
					</p>
					<div style="font-weight: 600; color: #7c169e; font-size: 17px; text-align: center;">
						Tienes '.$cupones.' en tu cuenta para conocer cuidadores
						'.$ocupa.'
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
						<div class="km-calendario">
							<label>¿Desde cuándo requieres el servicio?</label>
							<input type="text" id="service_start" name="service_start" placeholder="dd/mm/aaaa" class="date_from" readonly>
							<small data-error="service_start" style="display: none;">Debes ingresar una fecha</small>
						</div>
						<div class="km-calendario">
							<label>¿Hasta cuándo requieres el servicio?</label>
							<input type="text" id="service_end" name="service_end" placeholder="dd/mm/aaaa" class="date_from" readonly>
							<small data-error="service_end" style="display: none;">Debes ingresar una fecha</small>
						</div>
						</br>							
						<a href="javascript:;" id="btn_enviar_conocer" data-id="enviar_datos" class="km-btn-basic" style="margin-bottom: 0px !important;">ENVIAR SOLICITUD</a>
					</div>
				</div>
			</form>';
		} 
	}
	*/
?>