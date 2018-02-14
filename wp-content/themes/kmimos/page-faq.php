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
			<?php get_form_filtrar_ayuda(); ?>
			<section class="row">
			<!-- Ayuda Cliente-Cuidadores -->
			<?php get_form_ayuda_cliente_cuidador(); ?>
			</section>

			<!-- Presentacion -->
			<section class="row text-center presentacion" data-group="presentacion">
				
				<?php
					foreach ($destacados as $post) { get_posts( $posts->ID ); ?>
 						<article>
							<a href="<?php echo get_permalink(); ?>">
								<img class="img-responsive" width="50%" src="<?php echo get_the_post_thumbnail_url(); ?>">
								<h2 style="font-size:25px"><?php the_title(); ?></h2>
							</a>
						</article>
				<?php } ?>

			</section>
		</div>
	</div>
<?php get_ayuda_sugeridos(); ?>
<?php get_footer(); ?>

