<?php 
    /*
        Template Name: Registro del cuidador
    */

	wp_enqueue_script('registro_cuidadores', getTema()."/js/registro_cuidadores.js", array("jquery"), '1.0.0');

	$config_link_registro = 'href="#" role="button" data-target="#popup-registro-cuidador1"' ;

	wp_enqueue_style('registro_cuidador', get_recurso("css")."registro_cuidador.css", array(), '1.0.0');
	wp_enqueue_style('registro_cuidador_responsive', get_recurso("css")."responsive/registro_cuidador.css", array(), '1.0.0');


	wp_enqueue_style('OLD_registro_cuidador', getTema()."/css/registro_cuidador.css", array("kmimos_style"), '1.0.0');
	wp_enqueue_style('OLD_registro_cuidador_responsive', getTema()."/css/responsive/registro_cuidador_responsive.css", array("kmimos_style"), '1.0.0');
	wp_enqueue_style( 'OLD_datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );

    get_header(); ?>

		<!-- INICIO SECCIÓN HEADER-->

			<!-- solo para movil -->
			<div class="hidden-md hidden-lg">			
				<div class="km-hero-bg" style="background-image:url(<?php echo getTema(); ?>/images/new/km-cuidador/head-responsive.jpg);">
					<div class="overlay"></div>
				</div>
				<header>
					<div class="container text-center">
						<a class="btn btn-kmimos-cuidador" <?php echo $config_link_registro; ?> >¡Crea tu perfil de cuidador aqu&iacute;!</a>
						<h2 class="titulo-principal">Kmimos necesita doglovers como tú</h2>
						<p class="titulo-secundario">Cada mascota llega como un huésped y consigue a un nuevo amigo. Convierte tu hobbie en dinero extra, con kmimos te ayudamos a alcanzarlo.</p>
					</div>
				</header>
			</div>

			<!-- solo para pc -->
			<div class="hidden-xs hidden-sm ">
				<header class="km-hero-bg" style="background-image:url(<?php echo getTema(); ?>/images/new/km-cuidador/head.jpg);">
					<div class="overlay"></div>
					<div class="km-titular-cuidador">
						<div class="container">
							<h2 class="titulo-principal">Kmimos necesita doglovers como tú</h2>
							<p class="titulo-secundario">Cada mascota llega como un huésped y consigue a un nuevo amigo. Convierte tu hobbie en dinero extra, con kmimos te ayudamos a alcanzarlo.</p>
							<a class="btn btn-kmimos-cuidador" <?php echo $config_link_registro; ?> >¡Crea tu perfil de cuidador aqu&iacute;!</a>
						</div>
					</div>
				</header>
			</div>

		<!-- FIN SECCIÓN HEADER-->

		<section class="container section-banner-top">
			<div class="col-md-10 col-md-offset-1">
				<p class="title">Kmimos es un servicio digital que conecta Doglovers como t&uacute;. con personas que necesitan que les cuiden a sus peludos mientras no están en casa.</p>
			</div>
		</section>

		<section class="container">
			<h2 class="text-center">ESTOS SON LOS BENEFICIOS DE SER UN CUIDADOR CERTIFICADO KMIMOS</h2>
			<div class="box-contenedor">
 
				<div class="tbl">

					<div class="box_col_left">

						<div class="box_item">
							<div class="box_icon">
								<img src="<?php echo getTema(); ?>/images/new/km-cuidador/Ganancias.svg">
							</div>
							<div class="box_info">
								<h3>Gana dinero con tu hobbie</h3>
								<p>
									En Kmimos siempre podrás colocar el precio que mejor se acomode, la decisión es tuya. Sin embargo quisiéramos recomendarte el rango de precios mostrados abajo, el cual esta creado basado en las tendencias de precios existentes en el mercado actual
								</p>
								<ul class="list-unstyled">
									<li><p><strong>Tamaño pequeño:</strong> 160 pesos por noches</p></li> <!-- 120 -->
									<li><p><strong>Tamaño mediano:</strong> 200 pesos por noches</p></li> <!-- 180 -->
									<li><p><strong>Tamaño grande:</strong> 240 pesos por noches</p> </li> <!-- 220 -->
									<li><p><strong>Tamaño gigante:</strong> 280 pesos por noches</p></li> <!-- 250 -->
								</ul>
							</div>
						</div>

						<div class="box_item">
							<div class="box_icon">
								<img src="<?php echo getTema(); ?>/images/new/km-cuidador/Tiempo.svg">
							</div>
							<div class="box_info">
								<h3>Elige tus propios horarios</h3>
								<p>
									Elige a los perros que quieras y cuando t&uacute; quieras, es 100% flexible.
								</p>
							</div>
						</div> 
					</div>

					<div class="box_col_right">

						<div class="box">
							<div class="titulo">
								¿Cómo me convierto en cuidador?
							</div>
							<ul class="item-list">
								<li>
									<div style="width: 14%;">
										<img src="<?php echo getTema(); ?>/images/new/km-cuidador/1.jpg" align="left">
									</div>
									<div style="width: 75%">
										<strong>Regístrate como aspirante para cuidador</strong>
									</div>
								</li>
								<li>
									<div style="width: 14%;">
										<img src="<?php echo getTema(); ?>/images/new/km-cuidador/2.jpg" align="left">
									</div>
									<div style="width: 75%">
										<strong>Aprueba los test de certificaci&oacute;n y sube fotos de tu hogar</strong>
										<p>Nuestras pruebas son psicométricas, de conocimientos veterinarios, y una persona del equipo de kmimos te visitara para una entrevista personal.</p>
									</div>
								</li>
								<li>
									<div style="width: 14%;">
										<img src="<?php echo getTema(); ?>/images/new/km-cuidador/3.jpg" align="left">
									</div>
									<div style="width: 75%">
										<strong>¡A recibir peludos! Gana dinero con tu hobbie y en tus propios horarios.</strong>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section style="padding: 5px;">
			<div class="container text-center">
				<h2 class="title-purpura">¡Genera hasta 30,000 MXN al mes con una buena demanda!</h2>
				<p>&Uacute;nete ya a nuestra gran familia de cuidadores</p>
			</div>
		</section>


			<!-- solo para movil -->
			<!-- solo para pc -->
				<section class="section-banner" style="background-image:url(<?php echo getTema(); ?>/images/new/km-cuidador/Banner.jpg);" class="text-left">
					<div class="hidden-md hidden-lg container">	
						<a <?php echo $config_link_registro; ?> ><img src="<?php echo getTema(); ?>/images/new/km-cuidador/Banner-responsive.png" class="img-responsive"></a>
					</div>
					<div class="hidden-xs hidden-sm">	
						<div class="container">
							<h2>CONVI&Eacute;RTETE EN CUIDADOR</h2>
							<h3>Llena tu casa de peludos mientras ganas dinero</h3>
							<a class="btn btn-kmimos-cuidador" <?php echo $config_link_registro; ?> >¡Crea tu perfil de cuidador aqu&iacute;!</a>
						</div>
					</div>
				</section>
			</div>

		<?php // Modal Registro Cuidador ?>
		<?php include_once( 'partes/cuidador/registro.php' ); ?>
		
		<!-- POPUPS TIPS  -->
		<div id="km-registro-tip1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<div class="km-registro-tip1">
						SDSD
					</div>
				</div>
			</div>
		</div>
		
<?php get_footer(); ?>