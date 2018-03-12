<?php 
     /*
        Template Name: FAQ General
    */
    wp_enqueue_style('faq_style', getTema()."/css/faq.css", array(), '1.0.0');

	wp_enqueue_script('faq_script', getTema()."/js/faq.js", array(), '1.0.0');

    get_header();


	
	if(!isset($_SESSION['ayuda']['default'])){
		$redirect = true;
		include ( 'procesos/ayuda/filtrar.php' ); 
		$redirect = false;
	}
	unset($_SESSION['ayuda']['default']);

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


			<section class="row text-left">

				<?php
				/* *************************************** *
				 * Mostrar todos los temas de la ayuda
				 * *************************************** */?>
				<?php 
				if( !isset($_SESSION['ayuda']['filtro']) ){
				/* *************************************** *
				 * Resultado para las busquedas
				 * *************************************** */
					$resultado = ( isset($_SESSION['ayuda']['resultado']) )? $_SESSION['ayuda']['resultado'] : [] ;
					if(empty($resultado)){ 
				?>

						<div class="alert alert-info">
							<div>No se encontraron articulos relacionados con la busqueda <span style="font-style: italic;">"<?php echo $_SESSION['ayuda']['terminos']; ?>"</span></div>
						</div>
						<?php $_SESSION['ayuda']['filtro'] = get_ayuda_secciones(); ?>
						<article class="col-xs-12 col-md-12 ayuda-group">
							<h3>
								<strong>Temas sugeridos</strong>
							</h3>
						</article>

					<?php }else{ ?>
						<article class="col-xs-12 col-md-12 ayuda-group">
							<h3>
								<strong>Resultados de busqueda para <span style="font-style: italic;">"<?php echo $_SESSION['ayuda']['terminos']; ?>"</span></strong>
							</h3>
						</article>

						<?php unset($_SESSION['ayuda']['filtro']); ?>
					<?php } ?>


					<?php foreach ($resultado as $post) { 
						if(!empty($post->post_title) && !empty($post->post_content) ){?>
							<article class="col-xs-12 col-md-12 ayuda-items">
								<a style="text-decoration:none" href="<?php echo get_the_permalink($post->ID); ?>">
									 <h3>
										<?php echo kmimos_ucfirst($post->post_title); ?>
									</h3>
								</a> 
								<div><hr></div>
							</article>
						<?php }?>
					<?php }?>

				<?php } ?>

				<?php if( isset($_SESSION['ayuda']['filtro']) ){

					$secciones = $_SESSION['ayuda']['filtro'];
					foreach ($secciones as $seccion) {
						$seccion_ignore = ['ayuda-para-clientes','ayuda-para-cuidadores','destacado','destacado', 'destacados_cuidadores','sugeridos', 'sugeridos-cuidadores'];
						if( !in_array( $seccion->slug, $seccion_ignore ) ){
						?>
							<article class="col-xs-12 col-md-12 ayuda-group">

								<h3 role="button" data-toggle="collapse" href="#seccion<?php echo $seccion->term_id; ?>">
									<strong><?php echo $seccion->name; ?></strong>
								</h3>
								<div class="collapse" id="seccion<?php echo $seccion->term_id; ?>">
									
									<?php
										$posts = get_ayuda_postBySeccion( $seccion->slug ); 
									
										foreach ($posts as $post) {
									?>
										<article class="col-xs-12 col-md-12 ayuda-items">
											<a style="text-decoration:none" href="<?php echo get_the_permalink($post->ID); ?>">
												 <h3>
													<?php echo $post->post_title; ?>
												</h3>
											</a>
											<!--<div class="collapse" id="item<?php echo $post->ID; ?>">
												<div class="well">
													<?php echo $post->post_content; ?>
												</div>
											</div>-->
											<div><hr></div>
										</article>
									<?php }?>
									
								</div>

							</article>
						<?php }?>
					<?php }?>
				

				<?php } ?>

			</section>
				<a style="text-decoration:none" href="javascript:history.back();"><h3 style="color: #2196F3;"><b>Volver</b></h3></a>

		</div>
	</div>

	<section class="temas-sugeridos">
		<div class="sugeridos-content text-center">
			<div class="container">
			¿No encontraste lo que buscabas? <a href="<?php echo get_home_url(); ?>/contacta-con-nosotros/"><b> Cont&aacute;ctanos</b></a>
			</div>
		</div>
	</section>

<?php
	get_footer(); 
?>

