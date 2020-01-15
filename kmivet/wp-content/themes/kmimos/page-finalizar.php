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
	 		<div class="km-content km-step-end" style="max-width: 1000px;">
				<div class="izq">
					<img src="'.get_recurso("img").'FINALIZAR/img_superior.svg" />
					<div class="finalizar_titulo">
						¡Genial '.get_user_meta($data_reserva["cliente"]["id"], "first_name", true).' '.get_user_meta($data_reserva["cliente"]["id"], "last_name", true).'!
					</div>
					<div class="finalizar_sub_titulo">
						Reservaste Exitosamente
					</div>
					<div class="info_mail">
						<div> Te acabamos de enviar un correo a tu dirección registrada con ésta información. </div>
						<span> Por favor revisa tu Buzón de Entrada o Buzón de No Deseados. </span>
					</div>
					<div style="container_general" >
						'.$CONTENIDO.'
						'.$que_hacer.'
					</div>
					<div style="padding-top: 20px;">
						<a class="boton boton_verde" href="'.get_home_url().'/perfil-usuario/historial/">Ver mis citas</a>
					</div>

					<a class="banner_inferior" href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros">
						<img class="solo_PC" src="'.get_recurso('img').'FINALIZAR/banner_inferior.png" />
						<img class="solo_movil" src="'.get_recurso('img').'FINALIZAR/banner_inferior.png" />
					</a>
				</div>
				<div class="der">
					<img src="'.get_recurso("img").'FINALIZAR/bg-cachorro.jpg" style="max-width: 100%;">
				</div>
			</div>
	 	';
			
		echo comprimir($HTML);

    get_footer(); 
?>