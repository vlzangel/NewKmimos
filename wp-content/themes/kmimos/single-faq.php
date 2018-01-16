<?php 
  
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
							<img src="https://mx.kmimos.la/wp-content/themes/kmimos/images/new/km-buscador.svg" width="18px" alt="Mucho mejor que una pensión para perros &amp;#8211; Cuidadores Certificados &amp;#8211; kmimos.com.mx">
						</button>
					</span>
				</div>
			</section>

			<!-- SubTitulos -->
			<section class="row text-center titulo-servicios">
				<span>SERVICIOS KMIMOS</span>
			</section>
  
			<!-- Temas -->
			<section class="row text-left" aria-multiselectable="true">
				<?php if ( have_posts() ){ the_post(); ?>
					<article class="tema-container">
						<h3>
							<?php the_title(); ?>
						</h3>
						<div class="col-md-12">
							<div class="tema-content">
								<?php the_content(); ?>
							</div>
						</div>					
					</article>
				<?php } ?>				
			</section>

		</div>
	</div>

<?php 

	$parents = get_ayuda_categoria( get_the_ID() );
echo $parents;	
	if( !empty($parents) ){
		get_ayuda_sugeridos( $parents,  get_the_ID() ); 
	}

	get_footer(); 
?>

