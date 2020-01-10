<?php
	/*
        Template Name: Club patitas Compartir
    */

	/*
	error_reporting(E_ERROR || E_WARNING);
	ini_set('display_errors', '1');
	*/

	$user = wp_get_current_user();
	$cupon = get_user_meta( $user->ID, 'club-patitas-cupon', true );
 	if( empty($cupon) ){
		header('location:'.get_home_url().'/club-patitas-felices');
	}
	
    $url_img = get_home_url() .'/wp-content/themes/kmimos/images/club-patitas/';
    //$no_top_menu = true;
	$nombre = "";
	if( $user->ID > 0 ){
		$nombre = get_user_meta( $user->ID, 'first_name', true );
		$nombre .= " ";
		$nombre .= get_user_meta( $user->ID, 'last_name', true );
	}


    wp_enqueue_style('club_style', getTema()."/css/club-patitas-felices.css", array(), '1.0.0');
    wp_enqueue_style('club_responsive', getTema()."/css/responsive/club-patitas-felices.css", array(), '1.0.0');
	wp_enqueue_script('club_script', getTema()."/js/club-patitas-felices.js", array(), '1.0.1');

	$metas = '
		<meta property="og:url"           content="https://www.kmimos.com.mx" />
		<meta property="og:type"          content="website" />
		<meta property="og:title"         content="Kmimos - Club de las Patitas Felices" />
		<meta property="og:description"   content="Deja a tu mejor amigo peludo en las mejores manos, las de un Cuidador Kmimos. ¡Utiliza mi código '. strtoupper($cupon) .' para que recibas $150 MXP de descuento en su primera estadía! visítanos en https://www.kmimos.com.mx" />
		<meta property="og:image"         content="https://www.kmimos.com.mx" />
	';

	$salir = wp_logout_url( get_home_url().'/club-patitas-felices' );

	get_header();
?>
	<!-- Load Facebook SDK for JavaScript -->
	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v3.2&appId=264829233920818&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

	<div class="content-compartir-club" style="z-index:5!important;">
		<aside id="compartir-club-cover" class="col-xs-12 col-sm-12 col-md-5" style="background-image: url(<?php echo getTema();?>/images/club-patitas/Kmimos-Club-de-las-patitas-felices-1.jpg);">
		</aside>
		<section class="col-xs-12 col-sm-12 col-md-7 compartir-section" style="<?php echo $center_content; ?>">
			<div class="col-md-8 col-md-offset-2">
				<section clss="text-center" style="padding: 20px 0px!important; ">
		            <a href="<?php echo get_home_url(); ?>/club-patitas-felices">Cómo funciona</a>
					<span style="padding:0px 10px;">|</span>
					<a href="<?php echo get_home_url(); ?>/club-patitas-felices/creditos">Ver mis créditos</a>
				</section>				
				<div class="row">
					<div class="col-md-12">
						
						<img src="<?php echo getTema().'/images/club-patitas/Kmimos-Club-de-las-patitas-felices-6.png'; ?>">
						<h2 class="titulo">¡Ya eres parte del club <?php echo $nombre; ?>!</h2>
						<p style="
							font-weight: bold; 
							font-size: 18px; 
							text-align: center;
							margin-top: 10%;
							">Tu código único del club</p>
						<div class="cupon">
							<?php echo strtoupper($cupon); ?>
						</div>

						<p style="font-weight: bold; font-size: 16px;">Hemos enviado tu código a la cuenta de correo registrada</p>
						<p>Recuerda, comparte tu código y cada vez que algún amigo haga su primera reserva con Kmimos <strong>tú ganarás $150 MXN</strong> acumulables.</p>
						<p style="font-weight:bold;font-size:16px;color:#0D7AD8;">!Más compartes, más ganas!</p>

					</div>
				</div>

				<div class="row btn-group-shared col-md-10 col-md-offset-1">
					
					<div style="margin-top: 10px;" class="col-md-12 col-sm-12 col-xs-12 text-center btn-twitter">
						<h4>Compártelo ahora</h4>
						<div style="margin-top: 10px;" class="col-md-4 col-sm-4 col-xs-4 text-center btn-twitter">
							<a style="width: 100%!important;" class="btn btn-info twitter-share-button"
						  		href="https://twitter.com/intent/tweet?text=
									Deja a tu mejor amigo peludo en las mejores manos, las de un Cuidador Kmimos. ¡Utiliza mi código <?php echo strtoupper($cupon);?> para que recibas $150 MXP de descuento en su primera estadía! visítanos en https://www.kmimos.com.mx"
						  		target="_blank" onClick="evento_google_kmimos('CPF_Compartir_twitter')">
								<i class="fa fa-twitter"></i> Tweet
							</a>
						</div>
						<div style="margin-top: 10px;" class="col-md-4 col-sm-4 col-xs-4 text-center btn-facebook" >
							<!-- div class="fb-share-button" data-href="<?php strtoupper($cupon);?>" data-layout="button" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u&amp;src=sdkpreparse">Compartir</a></div -->

							<div class="fb-share-button" data-href="https://kmimos.com.mx" data-layout="button" data-size="large" data-mobile-iframe="true"><a onClick="evento_google_kmimos('CPF_Compartir_facebook')" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=Deja a tu mejor amigo peludo en las mejores manos, las de un Cuidador Kmimos. ¡Utiliza mi código <?php echo strtoupper($cupon);?> para que recibas $150 MXP de descuento en su primera estadía! visítanos en https://www.kmimos.com.mx&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div> 
						</div>
						<div style="margin-top: 10px;" class="col-md-4 col-sm-4 col-xs-4 text-center btn-whatsapp">
							<a onclick="evento_google_kmimos('CPF_Compartir_whatsapp')" class="btn btn-sm" href="https://api.whatsapp.com/send?text=Deja a tu mejor amigo peludo en las mejores manos, las de un Cuidador Kmimos. ¡Utiliza mi código <?php echo strtoupper($cupon);?> para que recibas $150 MXP de descuento en su primera estadía! visítanos en https://www.kmimos.com.mx"><i class="fa fa-whatsapp"></i> Whatsapp</a>
						</div>
					</div>
					<div style="
					    margin-top: 15px;
					    border-top: 1px solid #f4f4f4;
					    padding-top: 10px;
					" class="col-md-12 col-sm-12 col-xs-12 text-center">
						<i class="fa fa-start"></i>
						<a style="color:white!important;" class="btn btn-club-azul" target="_blank" href="<?php echo get_home_url(); ?>/pdf/pdf_club.php">Guardar en dispositivo</a>
					</div>
						
				</div>

			</div>

		</section>
	</div>
<?php 
	$no_display_footer = true;
 	get_footer(); 
?>
