<?php 
     /*
        Template Name: FAQ General
    */
    wp_enqueue_style('faq_style', getTema()."/css/faq.css", array(), '1.0.0');

	wp_enqueue_script('faq_script', getTema()."/js/faq.js", array(), '1.0.0');

    get_header();

?>

	<div class="km-ficha-bg" style="background-image: url(<?php echo getTema().'/images/new/ayuda/kmimos_ayuda.jpg'; ?>)">
		<div class="overlay"></div>
	</div>
	<div class="body body-ayuda container">
		<div id="ayuda-content" class="main">
			
			<section class="row">
				<h1 class="titulo-principal">¿C&oacute;mo podemos ayudarte?</h1>
			</section>
			
			<!-- Busqueda -->
			<section class="row km-caja-filtro ayuda-busqueda">
				<div class="input-group km-input-content col-md-offset-3">
					<input type="text" name="nombre" value="" placeholder="BUSCAR TEMAS DE AYUDA" class=" ">
					<span class="input-group-btn">
						<button type="submit">
							<img src="https://mx.kmimos.la/wp-content/themes/kmimos/images/new/km-buscador.svg" width="18px">
						</button>
					</span>
				</div>
			</section>

			<!-- SubTitulos -->
			<section class="row text-center titulo-servicios">
				<span>SERVICIOS KMIMOS</span>
			</section>


			<section class="row text-left">

				<?php for ($g=0; $g < 4; $g++) { ?>
					<article class="col-xs-12 col-md-12">

						<h3 role="button" data-toggle="collapse" href="#seccion<?php echo $g; ?>">
							<strong>Grupo #<?php echo $g; ?></strong>
						</h3>
						<div class="collapse" id="seccion<?php echo $g; ?>">
							
							<?php for ($i=0; $i < 4; $i++) { ?>
								<article class="col-xs-12 col-md-12">
									<h3 role="button" data-toggle="collapse" href="#item<?php echo $i; ?>">
										Item #<?php echo $i; ?>
									</h3>
									<div class="collapse" id="item<?php echo $i; ?>">
										<p class="well">
											Link with hrefvLink with hrefLink with hrefLink with hrefLink with hrefLink with href
										</p>
									</div>
								</article>
							<?php }?>
							
						</div>

					</article>
				<?php }?>

			</section>



			<!-- Temas Sugeridos -->
			<section class="row temas-sugeridos hidden ">
				<span class="title">Temas sugeridos</span>
				<div class="content">
					<?php for ($i=0; $i < 4; $i++) { ?>
						<article class="col-xs-12 col-md-12">
							<h3 role="button" data-toggle="collapse" href="#item<?php echo $i; ?>">
								Item #<?php echo $i; ?>
							</h3>
							<div class="collapse" id="item<?php echo $i; ?>">
								<p class="well">
									Link with hrefvLink with hrefLink with hrefLink with hrefLink with hrefLink with href
								</p>
							</div>
						</article>
					<?php }?>
				</div>
			</section>

		</div>
	</div>

<?php
	get_footer(); 
?>

