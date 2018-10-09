<?php
	wp_enqueue_style( 'generales_css', get_recurso("css")."generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'generales_responsive_css', get_recurso("css")."responsive/generales.css", array(), "1.0.0" );

	$HTML = '
		<footer>
			<div class="footer_alto">
				<div>
					<div>
						<h2>Entérate de los últimos ciudados para tu mascota</h2>
					</div>
					<div>
						<h2>Servicios</h2>
					</div>
					<div>
						<h2>Navega</h2>
					</div>
					<div>
						<h2>Contáctanos</h2>
					</div>
				</div>
				<div>
					<div class="footer_alto_col_1">
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
						<ul>
							<li>Quiero ser cuidador</li>
							<li>Buscar cuidador certificado</li>
							<li>Blog</li>
						</ul>
					</div>
					<div>
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
						<ul>
							<li>Teléfono: 01 (800) 9 564667</li>
							<li>Telef. Local: 01 (55) 8526 1162</li>
							<li>WhatsApp 1: +52 1 (33) 1261 4186</li>
							<li>WhatsApp 2: +52 1 (55) 6892 2182</li>
							<li>WhatsApp 3: +52 1 (55) 6560 2472</li>
							<li>Email: contactomex@kmimos.la</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="footer_medio">
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
			<div class="footer_bajo">
				<a href="'.get_home_url().'">Kmimos.com.</a> Todos los derechos reservados.
			</div>
		</footer>
	';

	echo comprimir($HTML);

	wp_footer();
?>