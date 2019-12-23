<?php 
    /*
        Template Name: Registro del veterinario
    */

    $HEADER = 'kmivet';

	$link_registro = '  data-toggle="modal" data-target="#popup-registro-veterinario" ' ;

	wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );
    wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

	wp_enqueue_style('registro_cuidador', get_recurso("css")."registro_cuidador.css", array(), '1.0.0');


	wp_enqueue_style('OLD_registro_cuidador', getTema()."/css/registro_cuidador.css", array("kmimos_style"), '1.0.0');
	wp_enqueue_style('OLD_registro_cuidador_responsive', getTema()."/css/responsive/registro_cuidador_responsive.css", array("kmimos_style"), '1.0.0');
	wp_enqueue_style( 'OLD_datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );

	wp_enqueue_style('registro_cuidador_responsive', get_recurso("css")."responsive/registro_cuidador.css", array(), '1.0.0');

	wp_enqueue_style('registro_veterinario', get_recurso("css")."registro_veterinario.css?v=".time(), array(), '1.0.0');
	wp_enqueue_style('registro_veterinario_responsive', get_recurso("css")."responsive/registro_veterinario.css?v=".time(), array(), '1.0.0');

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
				<img class="banner-top" src="<?= get_recurso('img') ?>VETERINARIO/REGISTRO/banner.png" <?= $link_registro ?> />
			</div>

		<!-- FIN SECCIÓN HEADER-->

		<section class="container section-banner-top">
			<div class="col-md-10 col-md-offset-1">
				<p class="title">Kmivet es una iniciativa de Kmimos que ofrece la mayor red de veterinarios que atienden a domicilio o a través de consulta virtual en todo México.</p>
			</div>
		</section>

		<section class="container">
			<h2 class="text-center">ESTOS SON LOS BENEFICIOS DE SER UN KMIVETERINARIO</h2>
			<div class="box-contenedor">
				<div class="tbl">
					<div class="box_col_left">
						<div class="box_item">
							<div class="box_icon">
								<img src="<?php echo getTema(); ?>/images/new/km-cuidador/Ganancias.svg">
							</div>
							<div class="box_info">
								<h3>Aumenta tus ingresos</h3>
								<p>
									Cuantas más consultas realices, más ingresos generas. Depende de tí cuánto quieres ganar.
									Recibe tus pagos cada viernes.
								</p>
							</div>
						</div>
						<div class="box_item">
							<div class="box_icon">
								<img src="<?php echo getTema(); ?>/images/new/km-cuidador/Tiempo.svg">
							</div>
							<div class="box_info">
								<h3>Cerca de ti</h3>
								<p>
									Consigue pacientes cerca de tu ubicación, así no tendrás que trasladarte muy lejos.
								</p>
							</div>
						</div> 
						<div class="box_item">
							<div class="box_icon">
								<img src="<?php echo getTema(); ?>/images/new/km-cuidador/Tiempo.svg">
							</div>
							<div class="box_info">
								<h3>¡Tu eliges!</h3>
								<p>
									Tú decides cuándo y cómo quieres trabajar.
									Organiza tu propia agenda, ve tus citas en el calendario.
								</p>
							</div>
						</div> 
					</div>
					<div class="box_col_right">
						<div class="box">
							<div class="titulo">
								¿Cómo me convierto en kmiveterinario?
							</div>
							<ul class="item-list">
								<li>
									<div style="width: 14%;">
										<img src="<?php echo getTema(); ?>/images/new/km-cuidador/1.jpg" align="left">
									</div>
									<div style="width: 75%">
										<strong>Registrate en la página aquí</strong>
										<p>y envianos la documentación necesaria que te pedíremos en un correo.</p>
									</div>
								</li>
								<li>
									<div style="width: 14%;">
										<img src="<?php echo getTema(); ?>/images/new/km-cuidador/2.jpg" align="left">
									</div>
									<div style="width: 75%">
										<strong>Capacítate</strong>
										<p>Realizarás una capacitación y te daremos todos los concejos para convertirte en un veterinario de 5 estrellas.</p>
									</div>
								</li>
								<li>
									<div style="width: 14%;">
										<img src="<?php echo getTema(); ?>/images/new/km-cuidador/3.jpg" align="left">
									</div>
									<div style="width: 75%">
										<strong>Comienza a atender mascotas</strong>
										<p>Cuando tu petición sea tramitada, crea un perfil atractivo para los clientes que entren en la página y comparte tu perfil en tus redes.</p>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section style="padding: 5px;">
			<div class="container text-rigth">
				<a class="boton boton_morado landing_btn_crea" href="#" <?= $link_registro ?> >Crea tu perfil aquí</a>
			</div>
		</section>

		<section class=""> <?php
			$_SERVICIOS = [
				[
					'Veterinario a domicilio',
					get_recurso("img").'KMIVET/SERVICIOS/IMGs/1.jpg',
					'Los pacientes agendan en línea, el veterinario los ve en su ubicación, y todo el seguimiento es digital, tratamiento, recetas.'
				],
				[
					'Asesoria virtual',
					get_recurso("img").'KMIVET/SERVICIOS/IMGs/2.jpg',
					'Al igual que una visita en persona, el veterinario toma su historial y síntomas, realiza un examen y puede recomendar un tratamiento, que incluye receta y análisis de laboratorio.'
				],
				/*
				'Servicio de ambulancia',
				'Kmivet lnn',
				'Enfermería',
				'Farmacia',
				*/
			];

			$_items = '';
			foreach ($_SERVICIOS as $key => $value) {
				$active = ( $key == 0 ) ? ' active ' : '';
				$_items .= '
				<div class="vlz_item_servicios '.$active.'" data-item="'.($key+1).'" data-tit="'.$value[0].'" data-img="'.$value[1].'" data-desc="'.$value[2].'" >
					<div> <div style="background-image: url( '.get_recurso("img").'KMIVET/SERVICIOS/'.($key+1).'.svg );"></div> </div>
					<div> '.$value[0].' </div>
				</div>';
			}

			echo '
				<div class="vlz_info_servicios">
					<div> Qué servicios puedes ofrece </div>
					<div class="vlz_info_items"> '.$_items.' </div>
					<div class="vlz_info_servicios_box">
						<div class="vlz_info_servicios_img" style="background-image: url( '.get_recurso("img").'KMIVET/SERVICIOS/IMGs/1.jpg );"></div>
						<div class="vlz_info_servicios_data">
							<div>Veterinario a domicilio</div>
							<p>
								Los pacientes agendan en línea, el veterinario los ve en su ubicación, y todo el seguimiento es digital, tratamiento, recetas.
							</p>
						</div>
					</div>
				</div>
			';

		?></section>

		<section class="section-preguntas">

			<div class="titulo">
				Preguntas frecuentes
			</div>

			<div class="preguntas_container">
				<?php
					$preguntas = [
						[
							'Quién puede formar parte de Kmivet',
							'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed faucibus sem. Praesent eu porttitor sapien. Nulla molestie in leo in tempor. Aliquam finibus blandit massa, quis cursus orci pretium et. Mauris egestas lacus libero, et ultrices dolor volutpat id. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris tincidunt orci libero. Vivamus ante urna, blandit et ipsum et, interdum eleifend felis. Maecenas fermentum, augue a semper tincidunt, leo tortor eleifend justo, nec tempor turpis enim at felis.',
						],
						[
							'Que necesito para ser parte',
							'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed faucibus sem. Praesent eu porttitor sapien. Nulla molestie in leo in tempor. Aliquam finibus blandit massa, quis cursus orci pretium et. Mauris egestas lacus libero, et ultrices dolor volutpat id. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris tincidunt orci libero. Vivamus ante urna, blandit et ipsum et, interdum eleifend felis. Maecenas fermentum, augue a semper tincidunt, leo tortor eleifend justo, nec tempor turpis enim at felis.',
						],
						[
							'Cuánto puedo ganar dando consultas a domicilio',
							'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed faucibus sem. Praesent eu porttitor sapien. Nulla molestie in leo in tempor. Aliquam finibus blandit massa, quis cursus orci pretium et. Mauris egestas lacus libero, et ultrices dolor volutpat id. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris tincidunt orci libero. Vivamus ante urna, blandit et ipsum et, interdum eleifend felis. Maecenas fermentum, augue a semper tincidunt, leo tortor eleifend justo, nec tempor turpis enim at felis.',
						],
						[
							'Tengo que cubrir un horario determinado',
							'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed faucibus sem. Praesent eu porttitor sapien. Nulla molestie in leo in tempor. Aliquam finibus blandit massa, quis cursus orci pretium et. Mauris egestas lacus libero, et ultrices dolor volutpat id. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris tincidunt orci libero. Vivamus ante urna, blandit et ipsum et, interdum eleifend felis. Maecenas fermentum, augue a semper tincidunt, leo tortor eleifend justo, nec tempor turpis enim at felis.',
						],
						[
							'Si trabajo días festivos me recompensan',
							'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed faucibus sem. Praesent eu porttitor sapien. Nulla molestie in leo in tempor. Aliquam finibus blandit massa, quis cursus orci pretium et. Mauris egestas lacus libero, et ultrices dolor volutpat id. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris tincidunt orci libero. Vivamus ante urna, blandit et ipsum et, interdum eleifend felis. Maecenas fermentum, augue a semper tincidunt, leo tortor eleifend justo, nec tempor turpis enim at felis.',
						]
					];

					$t = count($preguntas);
					$m = ceil($t / 2);

					echo '<div>';

						for ($i=0; $i < $m; $i++) { 
							echo '
								<div>
									<div>¿'.$preguntas[$i][0].'?</div>
									<p>'.$preguntas[$i][1].'</p>
								</div>
							';
						}

					echo '</div><div>';

						for ($i=$m; $i < $t; $i++) { 
							echo '
								<div>
									<div>¿'.$preguntas[$i][0].'?</div>
									<p>'.$preguntas[$i][1].'</p>
								</div>
							';
						}

					echo '</div>';

				?>
			</div>

		</section>

		<section class="section-banner-bottom">
			<img class="banner-top" src="<?= get_recurso('img') ?>VETERINARIO/REGISTRO/banner-bottom.png" <?= $link_registro ?>  />
		</section>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#popup-registro-veterinario">
  Launch demo modal
</button>
		<?php // Modal Registro Cuidador ?>
		<?php // include_once( 'partes/veterinario/registro.php' ); ?>
		
		<!-- POPUPS TIPS  -->
		<div id="popup-registro-veterinario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<div class="km-registro-tip1">
						SDSD
					</div>
				</div>
			</div>
		</div>
		
<?php 
	wp_enqueue_script('registro_veterinario', get_recurso('js')."registro_veterinario.js", array("jquery"), '1.0.0');
	get_footer(); 
?>