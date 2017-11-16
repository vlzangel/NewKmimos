<?php 
	
	$path = explode("/", substr($_SERVER["REDIRECT_URL"], 1));


	get_header();

		echo '
 		<div class="km-ficha-bg" style="background-image:url('.getTema().'/images/new/km-ficha/km-bg-ficha.jpg);">
			<div class="overlay"></div>
		</div>
		<div class="km-content km-step-end">
			<div style="padding: 20px 40px 20px; background: #FFF;">
				<h1 style="text-align: center;">Error, pagina no encontrada.</h1>
			</div>
		</div>
		';

	get_footer();
?>