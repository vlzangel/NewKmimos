<?php 
  
    wp_enqueue_style('faq_style', getTema()."/css/faq.css", array(), '1.0.0');

	wp_enqueue_script('faq_script', getTema()."/js/faq.js", array(), '1.0.0');

    get_header();



	//$parents = get_ayuda_categoria( get_the_ID() );

	

	
?>

	<div class="km-ficha-bg" style="background-image: url(<?php echo getTema().'/images/new/ayuda/kmimos_ayuda.jpg'; ?>)">
		<div class="overlay"></div>
	</div>
	<div class="body-ayuda">

		<setion id="ayuda-content" class="col-sm-6 col-sm-offset-1">
			
			<!-- Busqueda -->
			<article id="form-ayuda-detalle" class="col-sm-8">
				<?php get_form_filtrar_ayuda(); ?>
			</article>
 
 			<article class="col-sm-12 text-left titulo-secundario">
				<h3>SERVICIOS KMIMOS</h3>
			</article>

			<!-- Ayuda Cliente-Cuidadores -->
			<article class="col-sm-12">
				<h3 class="title-category"><?php get_categoria_pregunta(get_the_ID()); ?></h3>
				<?php if ( have_posts() ){ the_post(); ?>
					<h3 class="title-post">
						<?php kmimos_ucfirst(the_title()); ?>
					</h3>
					<div class="col-md-12 text-justify text-content">	
						<?php the_content(); ?>
					</div>					
				<?php } ?>
			</article>
			
		</setion>
		<aside id="sidebar" class="col-sm-4 col-sm-offset-1">
			<div class="sidebar-content">			
				<h3 class=" text-left">Temas sugeridos</h3>
				<?php get_ayuda_relacionados(get_the_ID()); ?>
				<article>
					<h3 class="title-category">¿No encontraste lo que buscas?</h3>
					<a href="<?php echo get_home_url() ?>'/contacta-con-nosotros/">
						<h3 class="title-post contacta-con-nosotros">Contáctanos</h3>
					</a>
				</article>
			</div>
		</aside>
	</div>

<?php get_footer(); ?>