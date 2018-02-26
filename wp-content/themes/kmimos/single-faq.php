<?php 
  
    wp_enqueue_style('faq_style', getTema()."/css/faq.css", array(), '1.0.0');

	wp_enqueue_script('faq_script', getTema()."/js/faq.js", array(), '1.0.0');

    get_header();



	//$parents = get_ayuda_categoria( get_the_ID() );

	

	
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

			<!-- SubTitulos -->
			<section class="row text-center titulo-servicios">
				<span>SERVICIOS KMIMOS</span>
			</section>
  
 			 <?php get_categoria_pregunta(get_the_ID()); ?>
			<!-- Temas -->
			<section class="row text-left" aria-multiselectable="true">
				<?php if ( have_posts() ){ the_post(); ?>
					<article class="tema-container">
						<h3 style="font-size:20px;padding: 10px;" >
							<?php the_title(); ?>
						</h3>
						<div class="col-md-12">
							<div style="text-align:justify;" id="contenido" class="tema-content">
								<?php the_content(); ?>
							</div>
						</div>					
					</article>
				<?php } ?>				
			</section>
				<a style="text-decoration:none" href="javascript:history.back();"><h3 style="color: #2196F3;"><b>Volver</b></h3></a>
		</div>
	</div>

<?php 
	get_ayuda_relacionados(get_the_ID()); 
	get_footer(); 
?>

