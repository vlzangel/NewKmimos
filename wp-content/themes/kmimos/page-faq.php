<?php 
    /*
        Template Name: FAQ
    */
    
    wp_enqueue_style('faq_style', getTema()."/css/faq.css", array(), '1.0.0');

	wp_enqueue_script('faq_script', getTema()."/js/faq.js", array(), '1.0.0');

    get_header();

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

