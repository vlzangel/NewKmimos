<?php 
    /*
        Template Name: FAQ
    */
    
    wp_enqueue_style('faq_style', getTema()."/css/faq.css", array(), '1.0.0');

	wp_enqueue_script('faq_script', getTema()."/js/faq.js", array(), '1.0.0');

    get_header();

    

    if($_GET['ayuda']){

    	if($_GET['ayuda']=="clientes"){
			$ayuda="destacado";
			$sugerido="sugeridos";

    	}else if ($_GET['ayuda']=="cuidadores"){
			$ayuda="destacados_cuidadores";
			$sugerido="sugeridos-cuidadores";

    	}

		

    }else{

    	$ayuda="destacado";
    	$sugerido="sugeridos";
    }



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
		            'terms'    => $ayuda
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
			'<section class="row km-caja-filtro ayuda-busqueda">
				<div class="col-sm-6">
					<input type="button" id="ayudaclientes" onClick="cambiarAyuda(this.id);" style="font-size:20px;margin:6px;" class="km-btn-primary" value="Ayuda para Clientes">
					</div>
				<div class="col-sm-6">
					<input type="button" id="ayudacuidador" onClick="cambiarAyuda(this.id);" style="font-size:20px;margin:6px;" class="km-btn-primary" value="Ayuda para Cuidadores">
					</div>
					</section>
			</section>

			<!-- Presentacion -->
			<section class="row text-center presentacion" data-group="presentacion">
					
				<?php
					foreach ($destacados as $post) { get_posts( $posts->ID ); ?>
					<div class="col-sm-6" id="destacados">				
									<a href="<?php echo get_permalink(); ?>">
										<img class="img-responsive" width="50%" src="<?php echo get_the_post_thumbnail_url(); ?>">
										<h2 style="font-size:25px"><?php the_title(); ?></h2>
									</a>
	
						</div>
				<?php } ?>
					
			</section>
		</div>
	</div>
<?php get_ayuda_sugeridos($sugerido); ?>
<?php get_footer(); ?>

