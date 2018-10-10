<?php
	wp_enqueue_style( 'generales_css', get_recurso("css")."generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'generales_responsive_css', get_recurso("css")."responsive/generales.css", array(), "1.0.0" );

	$HTML = '
		<footer>
			<div class="footer_alto">
				<div>
					<div class="footer_alto_col_1">
						<h2>Entérate de los últimos ciudados para tu mascota</h2>
						<ul>
							<li>¡Inscribete a nuestro blog y conócelas!</li>
						</ul>
						<form id="suscribirse">
							<input type="email" id="email" name="email" placeholder="Ingresa tu correo" />
							<input type="submit" value="Inscribirme al blog" />
						</form>
						<div class="siguenos_alto">
							<span>Siguenos en</span>
							<a href="">
								<img src="'.get_recurso("img").'SVG/Facebook.svg" />
							</a>
							<a href="">
								<img src="'.get_recurso("img").'SVG/Instagram.svg" />
							</a>
							<a href="">
								<img src="'.get_recurso("img").'SVG/Youtube.svg" />
							</a>
						</div>
					</div>
					<div>
						<h2>Servicios</h2>
						<ul>
							<li>Quiero ser cuidador</li>
							<li>Buscar cuidador certificado</li>
							<li>Blog</li>
						</ul>
					</div>
					<div>
						<h2>Navega</h2>
						<ul>
							<li>Nosotros</li>
							<li>Preguntas y respuestas</li>
							<li>Cobertura veterinaria</li>
							<li>Comunicados de prensa</li>
							<li>Términos y condiciones</li>
							<li>Nuestros aliados</li>
							<li>Contáctanos</li>
						</ul>
					</div>
					<div>
						<h2>Contáctanos</h2>
						<ul>
							<li>Teléfono: 01 (800) 9 564667</li>
							<li>Telef. Local: 01 (55) 85261162</li>
							<li>WhatsApp 1: +52 1 (33) 12614186</li>
							<li>WhatsApp 2: +52 1 (55) 68922182</li>
							<li>WhatsApp 3: +52 1 (55) 65602472</li>
							<li>Email: contactomex@kmimos.la</li>
						</ul>
					</div>
					<div class="siguenos_bajo">
						<span>Siguenos en</span>
						<a href="">
							<img src="'.get_recurso("img").'SVG/Facebook.svg" />
						</a>
						<a href="">
							<img src="'.get_recurso("img").'SVG/Instagram.svg" />
						</a>
						<a href="">
							<img src="'.get_recurso("img").'SVG/Youtube.svg" />
						</a>
					</div>
				</div>
			</div>
			<div class="footer_bajo">
				<a href="'.get_home_url().'">Kmimos.com.mx</a> Todos los derechos reservados.
			</div>
		</footer>
	';

	echo comprimir($HTML);

	wp_footer();
?>