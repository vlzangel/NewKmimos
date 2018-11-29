<?php 
    /*
        Template Name: Finalizar
    */

	if( !isset($_SESSION)){ session_start(); } unset($_SESSION["pagando"]);

    wp_enqueue_style('finalizar', getTema()."/css/finalizar.css", array(), '1.0.0');
	wp_enqueue_style('finalizar_responsive', getTema()."/css/responsive/finalizar_responsive.css", array(), '1.0.0');

	wp_enqueue_script('finalizar', getTema()."/js/finalizar.js", array("jquery"), '1.0.0');

	get_header();

		include __DIR__.'/NEW/data_finalizar.php';

		$HTML .= '
	 		<div class="km-content km-step-end" style="max-width: 1170px;">
				<div class="izq">
					<img src="'.get_recurso("img").'/RESERVA/img_superior.png" />
					<div class="finalizar_titulo">
						¡Genial '.get_user_meta($data_reserva["cliente"]["id"], "first_name", true).' '.get_user_meta($data_reserva["cliente"]["id"], "last_name", true).'!
					</div>
					<div class="finalizar_sub_titulo">
						Reservaste Exitosamente
					</div>
					<div class="que_debo_hacer">
						<div>
							Te acabamos de enviar un correo a tu dirección registrada con ésta información.
						</div>
						<span>
							Por favor revisa tu Buzón de Entrada o Buzón de No Deseados.
						</span>
					</div>
					<div style="container_general" >
						'.$CONTENIDO.'
					</div>
					'.$que_hacer.'
					<div style="padding-top: 20px;">
						'.$pdf.'
						<button class="boton boton_border_gris" data-id="'.$data_reserva['cliente']['id'].'" data-target="emitir_factura">
						    ¿Deseas emitir tu comprobante fiscal digital?
						</button>
						<a class="boton boton_verde" href="'.get_home_url().'/perfil-usuario/historial/">Ver mis reservas</a>
					</div>

					<a class="banner_inferior" href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros">
						<img class="solo_PC" src="'.get_recurso('img').'/RESERVA/banner_inferior.png" />
						<img class="solo_movil" src="'.get_recurso('img').'/RESERVA/banner_inferior_resp.png" />
					</a>
				</div>
				<div class="der">
					<img src="'.getTema().'/images/new/bg-cachorro.png" style="max-width: 100%;">
				</div>
			</div>

			<div class="modal fade" tabindex="-1" role="dialog" id="emitir_factura">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h5 class="modal-title text-center">¿DESEAS EMITIR TU COMPROBANTE FISCAL DIGITAL?</h5>
			      </div>
			      <div class="modal-body"><p style="font-size:14px; text-align: center;">'.$mensaje_facturacion.'</p></div>
			    </div>
			  </div>
			</div>
	 	';

		include __DIR__.'/NEW/data_finalizar_2.php';
			
		echo comprimir($HTML);

    get_footer(); 
?>