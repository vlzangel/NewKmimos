<?php 
    /*
        Template Name: FAQ
    */
    
    wp_enqueue_style('faq_style', getTema()."/css/faq.css", array(), '1.0.0');

	wp_enqueue_script('faq_script', getTema()."/js/faq.js", array(), '1.0.0');

    get_header();

<<<<<<< HEAD
    /* Temas de ayuda Destacados */
	$destacados = get_posts(
	    array(
			'post_status' => 'publish', 
			'posts_per_page' => -1, 
	        'post_type' => 'faq',
	        'tax_query' => array(
		        array(
		            'taxonomy' => 'seccion',
		            'field'    => 'slug',
		            'terms'    => 'destacado'
		        )
		    )
	    )
	);

=======
>>>>>>> 4056a33178623807cebcc992024274c1668739cd
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
							<img src="https://mx.kmimos.la/wp-content/themes/kmimos/images/new/km-buscador.svg" width="18px" alt="Mucho mejor que una pensión para perros &amp;#8211; Cuidadores Certificados &amp;#8211; kmimos.com.mx">
						</button>
					</span>
				</div>
			</section>

<<<<<<< HEAD
			<!-- Presentacion -->
			<section class="row text-center presentacion" data-group="presentacion">
				
				<?php
					foreach ($destacados as $post) { get_posts( $posts->ID ); ?>
 						<article>
							<a href="<?php echo get_permalink(); ?>">
								<img class="img-responsive" width="50%" src="<?php echo get_the_post_thumbnail_url(); ?>">
								<h2><?php the_title(); ?></h2>
							</a>
						</article>
				<?php } ?>

			</section>
 
		</div>
	</div>


<?php get_ayuda_sugeridos('sugeridos'); ?>

<?php get_footer(); ?>
=======
			<!-- SubTitulos -->
			<section class="row text-center titulo-servicios hidden">
				<span>SERVICIOS KMIMOS</span>
			</section>

			<!-- Presentacion -->
			<section class="row text-center presentacion" data-group="presentacion">
				<article>
					<img class="img-responsive" width="50%" src="<?php echo getTema().'/images/new/ayuda/perro.png'; ?>">
					<h2>¿Necesitas que alguien cuide a tu peludo?</h2>
				</article>
				<article>
					<img class="img-responsive" width="50%" src="<?php echo getTema().'/images/new/ayuda/cuidador.png'; ?>">
					<h2>¿Quieres ser cuidador certificado kmimos?</h2>
				</article>
			</section>

			<!-- Temas Destacados -->
			<section class="row ayuda-temas-destacados hidden" style="display:none;">
				<article class="tema-container destacar-info">
					<a  data-toggle="collapse" data-parent="#accordion" href="#item<?php echo $i;?>" 
						aria-expanded="false" aria-controls="collapseOne">
					  Lorem ipsum dolor sit amet, consectetuer adipiscing elit?
					</a>
					<div class="collapse" id="item<?php echo $i; ?>">
						<div class="tema-content">
					    	Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
						</div>
					</div>					
				</article>
			</section>

			<!-- Temas -->
			<section class="row hidden text-left" aria-multiselectable="true">
				
				<div id="group1">
					<?php for ($a=0; $a < 10; $a++) { ?>
						<a data-toggle="collapse" data-parent="#group1" href="#group-item<?php echo $a; ?>">
							<h3><strong>Ayuda para encontrar a un cuidador</strong></h3>
						</a>
						<div class="collapse" id="group-item<?php echo $a; ?>"  id="temas">
							<?php for ($i=0; $i < 10; $i++) { ?>
								<article class="tema-container">
									<a  data-toggle="collapse" data-parent="#temas" href="#item<?php echo $i;?>" 
										aria-expanded="false" aria-controls="collapseOne">
									  Lorem ipsum dolor sit amet, consectetuer adipiscing elit?
									</a>
									<div class="collapse" id="item<?php echo $i; ?>">
										<div class="tema-content">
									    	Lorem ipsum dolor sit amet, consectetuer adipiscing elit Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
										</div>
									</div>					
								</article>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				
			</section>

			<!-- Temas Sugeridos -->
			<section class="row temas-sugeridos ">
				<span class="title">Temas sugeridos</span>
				<div class="content">
					<article class="tema-content">
						<div>esta es una pregunta</div>
						<a href="#">Como reservar</a>
					</article>
					<article class="tema-content">
						<div>esta es una pregunta</div>
						<a href="#">Como reservar</a>
					</article>
				</div>
			</section>

		</div>
	</div>

<?php
	get_footer(); 
?>
>>>>>>> 4056a33178623807cebcc992024274c1668739cd

