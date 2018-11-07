<?php
	wp_enqueue_style( 'generales_css', get_recurso("css")."generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'generales_responsive_css', get_recurso("css")."responsive/generales.css", array(), "1.0.0" );
	
	wp_enqueue_script('global_js', getTema()."/js/global.js", array("jquery"), '1.0.0');
	wp_enqueue_script('global_new_js', get_recurso("js")."global.js", array("jquery"), '1.0.0');
    wp_enqueue_script('boostrap.min.js', getTema()."/js/bootstrap.min.js", array("jquery"), '1.0.0');

    wp_enqueue_script('favorites', getTema()."/js/favoritos.js", array("jquery"), '1.0.0');

	$HTML = '
		<footer>
			<div class="footer_alto">
				<div>
					<div class="footer_alto_col_1">
						<h2>Entérate de los últimos ciudados para tu mascota</h2>
						<ul>
							<li style="font-size: 13px;">¡Inscribete a nuestro blog y conócelas!</li>
						</ul>
						<form id="suscribirse">
							<input type="email" id="email" name="email" placeholder="Ingresa tu correo" />
							<input type="submit" value="Inscribirme al blog" />
						</form>
						<div class="siguenos_alto">
							<span>Siguenos en</span>
							<a href="https://www.facebook.com/Kmimosmx/" target="_blank">
								<img src="'.get_recurso("img").'HOME/SVG/Facebook.svg" />
							</a>
							<a href="https://twitter.com/kmimosmx/" target="_blank">
								<img src="'.get_recurso("img").'HOME/SVG/Instagram.svg" />
							</a>
							<a href="https://www.instagram.com/kmimosmx/" target="_blank">
								<img src="'.get_recurso("img").'HOME/SVG/Youtube.svg" />
							</a>
						</div>
					</div>
					<div>
						<h2>Servicios</h2>
						<ul>
                            <p><a href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros">Quiero ser cuidador</a></p>
                            <p><a href="'.get_home_url().'/busqueda">Buscar cuidador certificado</a></p>
                            <p><a target="blank" href="https://kmimos.com.mx/blog">Blog</a></p>
						</ul>
					</div>
					<div>
						<h2>Navega</h2>
						<ul>
                            <p><a href="'.get_home_url().'">Nosotros</a></p>
                            <p><a href="'.get_home_url().'/faq">Preguntas y Respuestas</a></p>
                            <p><a href="'.get_home_url().'/coberturas-de-servicios-veterinarios/">Cobertura Veterinaria</a></p>
                            <p><a href="'.get_home_url().'">Comunicados de prensa</a></p>
                            <p><a href="'.get_home_url().'/terminos-y-condiciones/">Términos y Condiciones</a></p>
                            <p><a href="'.get_home_url().'">Nuestros Aliados</a></p>
                            <p><a href="'.get_home_url().'/contacta-con-nosotros/">Contáctanos</a></p> 
						</ul>
					</div>
					<div>
						<h2>Contáctanos</h2>
						<ul class="info_contacto">
							<li><i class="fa fa-phone" aria-hidden="true"></i> <span>Teléfono:</span> 01 (800) 9 564667</li>
							<li><i class="fa fa-phone" aria-hidden="true"></i> <span>Telef. Local:</span> 01 (55) 85261162</li>
							<li><i class="fa fa-whatsapp" aria-hidden="true"></i> <span>WhatsApp:</span> +52 1 (33) 12614186</li>
							<li><i class="fa fa-whatsapp" aria-hidden="true"></i> <span>WhatsApp:</span> +52 1 (55) 68922182</li>
							<li><i class="fa fa-whatsapp" aria-hidden="true"></i> <span>WhatsApp:</span> +52 1 (55) 65602472</li>
							<li><i class="fa fa-envelope-o" aria-hidden="true"></i> <span>Email:</span> contactomex@kmimos.la</li>
						</ul>
					</div>
					<div class="siguenos_bajo">
						<span>Siguenos en</span>
						<a href="https://www.facebook.com/Kmimosmx/" target="_blank">
							<img src="'.get_recurso("img").'HOME/SVG/Facebook.svg" />
						</a>
						<a href="https://twitter.com/kmimosmx/" target="_blank">
							<img src="'.get_recurso("img").'HOME/SVG/Instagram.svg" />
						</a>
						<a href="https://www.instagram.com/kmimosmx/" target="_blank">
							<img src="'.get_recurso("img").'HOME/SVG/Youtube.svg" />
						</a>
					</div>
				</div>
			</div>
			<div class="footer_bajo">
				<a href="'.get_home_url().'">Kmimos.com.mx</a> Todos los derechos reservados.
			</div>
		</footer>
	';

	if( is_front_page() && $_SESSION["POPUP_HOME"] == "" ) {
		if( $_SERVER["HTTP_REFERER"] != "https://www.kmimos.com.mx/google-adwords/" && $_SERVER["HTTP_REFERER"] != "https://kmimos.com.mx/google-adwords/" ){
	     	include_once( dirname(__DIR__).'/partes/footer/SubscribeSite.php' );
	     	$_SESSION["POPUP_HOME"] = "YES"; 
	    }
    }

	echo comprimir($HTML);

	wp_footer();

	echo "</body></html>";
?>