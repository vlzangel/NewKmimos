<!-- POPUP CONOCE AL CUIDADOR -->
<?php
global $wpdb;

global $current_user;
date_default_timezone_set('America/Mexico_City');
$user_id = $current_user->ID;
$user_email = $current_user->user_email;
$mascotas = kmimos_get_my_pets($user_id);

$btn_perfil['icon'] = '<i class="fa fa-check" style="color: #3c763d;"></i>';
$btn_perfil['btn'] = 'tu perfil';

$btn_login['btn'] = 'iniciado sesión';
$btn_login['icon'] = '<i class="fa fa-check" style="color: #3c763d;"></i>';

$btn_mascota['btn'] = 'lista de mascotas';
$btn_mascota['icon'] = '<i class="fa fa-check" style="color: #3c763d;"></i>';

$validar_perfil_completo = false;
if ( !is_user_logged_in() ){ 
	// Login
	$btn_login['btn'] = '<a style="color:#337ab7;" href="#" data-target="#popup-iniciar-sesion" role="button" data-toggle="modal"><strong>iniciado sesión</strong></a>';
	$btn_login['icon'] = '<i class="fa fa-close" style="color: #c72929;"></i>';

	// Perfil	
	$btn_perfil['btn'] = '<a  style="color:#337ab7;" href="#" data-target="#popup-iniciar-sesion" role="button" data-toggle="modal"><strong> tu perfil</strong></a>';
	$btn_perfil['icon'] = '<i class="fa fa-close" style="color: #c72929;"></i>';

	$btn_mascota['btn'] = '<a  style="color:#337ab7;" href="#" data-target="#popup-iniciar-sesion" role="button" data-toggle="modal"><strong> lista de mascotas</strong></a>';
	$btn_mascota['icon'] = '<i class="fa fa-close" style="color: #c72929;"></i>';

}else{

	/* Validar mascotas */
	if ( count($mascotas) < 1 ){ 
		$btn_mascota['btn'] = '<a href="'.get_home_url().'/perfil-usuario/mascotas" style="color:#337ab7;" role="button" ><strong>lista de mascotas</strong></a>';
		$btn_mascota['icon'] = '<i class="fa fa-close" style="color: #c72929;"></i>';
	}				

	/* Validar perfil de usuario*/
	$validar_perfil_completo = validar_perfil_completo();
	if( !$validar_perfil_completo ){
		$btn_perfil['btn'] = '<a  style="color:#337ab7;" role="button" href="'.get_home_url().'/perfil-usuario" style="color:#337ab7;" role="button"><strong> tu perfil</strong></a>';
		$btn_perfil['icon'] = '<i class="fa fa-close" style="color: #c72929;"></i>';		
	}
}

$puede_conocer = false;
$pagado = false;
$ocupa = '';
$saldo_conocer = get_cupos_conocer_registro($user_id);
if( $saldo_conocer->usos > 0 ){
	$puede_conocer = true;
	$metas = json_decode( $saldo_conocer->metadata );
	if( $metas->show_pago == 1 ){
		$pagado = true;
		$metas->show_pago = 0;
		$metadata = json_encode($metas);
		$wpdb->query("UPDATE conocer_pedidos SET metadata='{$metadata}' WHERE id = ".$saldo_conocer->id);
	}else{
		$cupones = ( $saldo_conocer->usos == 1 ) ? $saldo_conocer->usos." crédito" : $saldo_conocer->usos." créditos";
		$ocupa = ( $saldo_conocer->usos == 3 ) ? "<br>Ocupa tu primer crédito!" : "";
	}
}

$msg_pago = '';
if( $pagado ){
	$HTML_CONOCER_REQUISITOS = '
		<p class="popup-tit" style="text-align: center;">Pago Exitoso!</p>
		<div style="font-weight: 600; color: #7c169e; font-size: 17px; text-align: center;">
			Tienes 3 créditos en tu cuenta para conocer cuidadores<br>
			Ocupa tu primer cupón!
		</div>
	';
}


$HTML_CONOCER = '
<div id="popup-conoce-cuidador" class="modal fade modal_conocer_'.$_SESSION['test_conocer'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<button type="button" class="close cerrar_conocer_'.$_SESSION['test_conocer'].'" data-dismiss="modal" aria-hidden="true">×</button>
			<div class="popup-iniciar-sesion-1 popup_conocer_'.$_SESSION['test_conocer'].'">';

				if ( !is_user_logged_in() ){ 
					$HTML_CONOCER .= '
					<div class="pre_requisitos">
						<p>Para poder conocer al cuidador primero tienes que:</p>
						<ol class="list-unstyled">
							<li>'.$btn_login['icon'].' Haberte registrado en nuestro portal y haber '.$btn_login['btn'].'</li>
							<li>'.$btn_perfil['icon'].' Completar todos los datos requeridos en '.$btn_perfil['btn'].'</li>
							<li>'.$btn_mascota['icon'].' Completar tu '.$btn_mascota['btn'].' en tu perfil</li>
						</ol>
					</div>';
				}else{

					if( $ES_PERFIL == 'YES' ){
						$PRUEBA = 'b2';
					}else{
						// $PRUEBA = 'a';
						$PRUEBA = 'b';
						switch ( $_SESSION['test_conocer'] ) {
							case 'b':
								$PRUEBA = 'b';
							break;
							case 'c':
								$PRUEBA = 'c';
							break;
							
							default:
								// $PRUEBA = 'a';
								$PRUEBA = 'b';
							break;
						}
					}

					include( dirname(__FILE__)."/PRUEBAS/".$PRUEBA.".php" );

				} 

		$HTML_CONOCER .= '
			</div>
			<div class="popup-iniciar-sesion-2" style="display: none;">
				<img src="'.getTema().'/images/new/km-reserva/img-end-step.png" width="197">
				<br>
				<h2>
					¡Genial '.get_user_meta($user_id, "first_name", true).'!<br>
					Solicitud Enviada Exitosamente
				</h2>
				<br>

				
				<div style="text-align:justify;">
				<p style=" font-family: Arial;  font-weight: bold; color:#6b1c9b;" >Te acabamos de enviar un correo a tu dirección registrada con ésta información. Por favor revisa tu Buzón de Entrada o Buzón de No Deseados.</p>
				<br>  

				<p id="te_quedan" style="font-weight: 800; font-size: 15px;">Te quedan <span id="cupos_disponibles"></span> solicitudes disponibles </p>
				<br>  

				<p>Recibimos la solicitud realizada para Conocer a un Cuidador Kmimos.</p>
				<p >Tu codigo de solicitud es: <B><span id="n_solicitud"></span></B></p>
				<div>
				    <div> 
				    <br>   
				        <p>Datos del cuidador</p>					    
						<p>Nombre: <B><span id="nombre"></span></B></p>
						<p>Telefono: <span id="telefono"></span> / '.get_user_meta($user_id, "user_mobile", true).'</p>
					    <p>Correo: <span id="email"></span></p>
					</div>
				     <div> 
				     <br>   
						<p >DATOS DE LA REUNION</p>
						<p >Fecha: <B><span id="fecha"></span></B></p>
						<p >Hora: <span id="hora_reu"></span> horas</p>
						<p >Lugar: <span id="lugar_reu"></span></p>
						<br>
						<p >POSIBLE FECHA DE ESTADIA</p>
						<p >Inicio: <span id="fecha_ini"></span></p>
						<p >Fin: <span id="fecha_fin"></span></p>
					</div>	
					<div  style="clear:both;"></div>

					<a class="btn_otra_solicitud" href="'.get_home_url().'/busqueda/">HACER OTRA SOLICITUD</a>
				</div>

				<div>
					<p style=" font-family: Arial; font-size:16px; font-weight: bold; color:#6b1c9b; text-align:center;">IMPORTANTE</p>
					<label> Dentro de las siguientes 2-4 horas recibirás una llamada o correo electrónico por parte del Cuidador y/o de un asesor Kmimos para confirmar tu cita o brindarte soporte con este proceso. También podrás contactar al cuidador a partir de este momento, a los teléfonos y/o correos mostrados a continuación para acelerar el proceso si así lo deseas.</label>
					<label> Para cualquier duda y/o comentario puedes contactar al Staff Kmimos a los teléfonos 01 (55) 8526 1162 y WhatsApp +52 1 (33) 1261 41 86, o al correo contactomex@kmimos.la</label>
				</div>
			</div>
			<br>
			<br>
				<div>
					'.$pdf.'
					<a class="btn_fin_reserva" href="'.get_home_url().'/perfil-usuario/solicitudes/">VER MIS SOLICITUDES</a>
				</div>

				'.get_publicidad("solicitud").'

			</div>
		</div>
	</div>
</div>';

if( $PRUEBA != 'b2' ){
	wp_enqueue_script('conocer_al_cuidador_js', getTema()."/js/conocer_al_cuidador.js", array("jquery"), '1.0.0');
}

echo comprimir_styles( $HTML_CONOCER );

?>
