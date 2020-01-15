<?php 
    /*
        Template Name: Finalizar
    */

	if( !isset($_SESSION)){ session_start(); } unset($_SESSION["pagando"]);

    wp_enqueue_style('finalizar', get_recurso('css')."finalizar.css", array(), '1.0.0');
	wp_enqueue_style('finalizar_responsive', get_recurso('css')."responsive/finalizar.css", array(), '1.0.0');

	wp_enqueue_script('finalizar', get_recurso('js')."finalizar.js", array("jquery"), '1.0.0');

	get_header();

		include __DIR__.'/partes/finalizar/data_finalizar.php';

		$HTML .= '
	 		<div class="km-content km-step-end" style="max-width: 1170px;">
				<div class="izq">
					<img src="'.get_recurso("img").'FINALIZAR/img_superior.png" />
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
					<div style="padding-top: 20px;">
						<a class="boton boton_verde" href="'.get_home_url().'/perfil-usuario/historial/">Ver mis reservas</a>
					</div>

					<a class="banner_inferior" href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros">
						<img class="solo_PC" src="'.get_recurso('img').'FINALIZAR/banner_inferior.png" />
						<img class="solo_movil" src="'.get_recurso('img').'FINALIZAR/banner_inferior_resp.png" />
					</a>
				</div>
				<div class="der">
					<img src="'.get_recurso("img").'FINALIZAR/bg-cachorro.png" style="max-width: 100%;">
				</div>
			</div>
	 	';

		include __DIR__.'/partes/finalizar/data_finalizar_2.php';
			
		echo comprimir($HTML);

    get_footer(); 
?>