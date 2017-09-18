<?php

	$query = '';
	foreach ($_GET as $key => $value) {
		$separador = (!empty($query))? '&' : '' ;
		if( $key == 'utm_campaign'){
			$value = 'landing_' . $_GET['utm_campaign']; 
		}
		$query .= $separador.$key.'='.$value;
	}

?>

<!DOCTYPE html>
<html> 
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Kmimos | Volaris</title>

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<script src="js/jquery/jquery.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="https://fonts.googleapis.com/css?family=Lato:700,900" rel="stylesheet">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/animate.css">
        <link rel="stylesheet" href="css/kmimos.css">

    <script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-56422840-1', 'auto');
	  ga('send', 'pageview');

	</script>

	<style type="text/css">
		#message{position: fixed; width: 100%; height: 100%; bottom: 0; padding: 20px; text-align: center; box-shadow: 0 0 3px #CCC; background: rgba(0, 0, 0, 0.8); z-index: 2;}
		#message.Msubscribe .contain{position: relative; width: 95%; max-width: 100%; margin: 0 auto;}
		#PageSubscribe{position:relative; max-width: 700px;  margin: 0 auto;  padding: 25px;  top: 75px; color: #FFF; border-radius: 20px;  background:#00bc00;  overflow: hidden;}
		#PageSubscribe .exit{float: right; cursor: pointer;}
		#PageSubscribe .section{ width: 50%; padding: 10px; float: left; font-size: 17px; text-align: left;}
		#PageSubscribe .section.section1{font-size: 20px;}
		#PageSubscribe .section.section1 span{font-size: 25px;}
		#PageSubscribe .section.section1 .images{padding:10px 0; text-align: center;}
		#PageSubscribe .section.section3{width: 100%; font-size: 17px; font-weight: bold; text-align: center;}
		#PageSubscribe .section.section2{}
		#PageSubscribe .section.section2 .message{font-size: 15px; border: none; background: none; opacity:0; visible: hidden; transition: all .3s;}
		#PageSubscribe .section.section2 .message.show{opacity:1; visible:visible;}
		#PageSubscribe .section.section2 .icon{width: 30px; padding: 5px 0;}
		#PageSubscribe .section.section2 .subscribe {margin: 20px 0;  }
		#PageSubscribe .section.section2 form{margin: 0; display:flex;}
		#PageSubscribe .section.section2 input,
		#PageSubscribe .section.section2 button{width: 100%; max-width: calc(100% - 60px); margin: 5px; padding: 5px 10px; color: #CCC; font-size: 15px; border-radius: 20px;  border: none; background: #FFF; }
		#PageSubscribe .section.section2 button {padding: 10px;  width: 40px;}

		@media screen and (max-width:480px), screen and (max-device-width:480px) {
			#PageSubscribe { top: 15px;}
			#PageSubscribe .section{ width: 100%; padding: 10px 0; font-size: 12px;}
			#PageSubscribe .section.section1 {font-size: 15px;}
			#PageSubscribe .section.section1 span {font-size: 20px;}
			#PageSubscribe .section.section3 {font-size: 12px;}
		}
	</style>

	<!--<script type='text/javascript'>
		//Subscribe
		function SubscribeSite(){
			clearTimeout(SubscribeTime);

			var dog = '<img height="70" align="bottom" src="https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-09.png">' +
				'<img height="20" align="bottom" src="https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-10.png">';

			var html='<div id="PageSubscribe"><i class="exit fa fa-times" aria-hidden="true" onclick="SubscribePopUp_Close(\'#message.Msubscribe\')"></i>' +
				'<div class="section section1"><span>G&aacute;nate <strong>$150</strong> pesos en tu primera reserva</span><br>&#8216;&#8216;Aplica para clientes nuevos&#8217;&#8217;<div class="images">'+dog+'</div></div>' +
				'<div class="section section2"><span><strong>&#161;SUSCR&Iacute;BETE!</strong> y recibe el Newsletter con nuestras <strong>PROMOCIONES, TIPS DE CUIDADOS PARA MASCOTAS,</strong> etc.!</span>'+

				'<div class="subscribe">'+
				'<form onsubmit="form_subscribe(this); return false;">'+
				'<input type="hidden" name="section" value="landing-volaris"/>'+
				'<input type="mail" name="mail" value="" placeholder="Introduce tu correo aqu&iacute" required/>'+
				'<button type="submit"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>'+
				'</form>'+
				'<div class="message"></div>'+
				'</div>'+

				'</div>'+
				'<div class="section section3">*Dentro de 48 hrs. Te enviaremos v&iacute;a email tu c&uacute;pon de descuento</div>' +
				'</div>';
			SubscribePopUp_Create(html);
		}

		function SubscribePopUp_Create(html){
			var element = '#message.Msubscribe';
			if(jQuery(element).length==0){
				jQuery('body').append('<div id="message" class="Msubscribe"></div>');
				jQuery(element).append('<div class="contain"></div>');
			}

			jQuery(element).find('.contain').html(html);
			jQuery(element).fadeIn(500,function(){
				/*
				 vsetTime = setTimeout(function(){
				 SubscribePopUp_Close(element);
				 }, 6000);
				 */
			});
		}

		jQuery(document).ready(function(e){
			SubscribeTime = setTimeout(function(){
				SubscribeSite();
			}, 7400);
		});

		function form_subscribe(element){
			var subscribe = jQuery(element).closest('.subscribe');
			var message = subscribe.find('.message');
			var email = subscribe.find('input[name="mail"]').val();
			var url = '/landing/newsletter.php?source=landing_volaris&email='+email;
			if(email!=''){
				jQuery.post(url, jQuery(element).serialize(),function(data){
					//console.log(data);
					var textmessage="Error al guardar los datos";

					if( data == 1){
						textmessage="Datos guardados";
					}else if( data == 2){
						textmessage="Formato de email invalido";
					}else if( data == 3){
						textmessage="Ya est&aacute;s registrado en la lista, Gracias!";
					}else{
						textmessage="Error al guardar los datos";
					}

					if(message.length>0){
						message.addClass('show');
						message.html('<i class="icon fa fa-envelope"></i>'+textmessage+'');
						vsetTime = setTimeout(function(){
							message_subscribe(message);
						}, 5000);
					}
				});
			}
			return false;
		}

		function message_subscribe(element){
			clearTimeout(vsetTime);
			element.removeClass('show');
			element.html('');
			return true;
		}

		function SubscribePopUp_Close(element){
			if(jQuery(element).length>0){
				jQuery(element).fadeOut(500,function(){
					jQuery(element).remove();
				});
			}
		}

	</script>-->
	
    </head>
    <body id="waypoint">       
       	<div class="col-sm-12">

    	   	<div class="row">       		
				<section id="section-4" class="col-xs-12">
					<div class="container">
						<!-- <article class="col-xs-6 col-sm-6 col-md-4 text-right pull-right">
							<img src="img/logo.png" class="img-responsive" id="section4-logo">
						</article> -->
						<!-- <article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<h2>
								Promoci&oacute;n por ser cliente<br>
								<span class="color-volaris">VOLARIS</span>, si te suscribes HOY:
							</h2>
						</article> -->
						<article id="tu-mascota" class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<p>Tu mascota regresa feliz</p>
						</article> 
						
						<article id="section-4-cuadro" class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<p class="hidden-xs">Promoci&oacute;n por ser <br> cliente <span style="color: #a1126d;">VOLARIS</span>, si <br> te suscribes HOY:</p>
							<p class="hidden-lg hidden-sm hidden-md">Promoci&oacute;n por ser cliente <span style="color: #a1126d; font-size: 12px!important;">VOLARIS</span>, si te suscribes HOY:</p>
							<h2 class="color-volaris">Obt&eacute;n</h2>
							<h2 class="monto">$150</h2>
							<!-- <h3 class="color-volaris">DE DESCUENTO</h3> -->
							<!-- <span>En tu primera reserva</span> -->
						</article>

					</div>
				</section>
				<section id="section-5" class="col-sm-12 bg-volaris" style="padding-right: 0px;">
					<dir class="bg-transparent container">
						<article class="title col-xs-12 col-sm-6 col-md-8 col-lg-8">
							Para obtener tu cup&oacute;n de descuento, debes hacer uno de estos sencillos pasos
						</article>
					</dir>
					<div class="row" style="margin:0px;">
						<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6 move">
							<label class="col-xs-offset-2 col-xs-1 col-md-1 text-right">
								1.</label>
							<p class="col-xs-9 col-md-10">

							¡SUSCR&Iacute;BETE
							y recibe el Newsletter con nuestras <strong>Promociones</strong>, <strong>Tips de cuidado para mascotas</strong>, etc.!</p>

							<div id="section-5-form" class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-offset-2 col-lg-offset-2 text-left pull-left">
								<input type="text" value="" name="email" id="email" placeholder="Introduce tu correo aqu&iacute;">
								<button id="newsletter" style="border:0px; background: transparent; "><img src="img/flecha.png" width="52px"></button>
								<br>
								<div id="msg-content" class="text-left" style="
									padding: 10px;
								    background: transparent;
								    border-radius: 50px;
								    width: 90%;
								    border: 1px solid #fff;
								    margin-top: 10px;
								    text-align: center;
								    display: none;">	
									<span id="mensaje"></span>
								</div>
							</div>
            				<span class="o xs col-xs-12 ">&oacute;</span></p>
						</article>
						<span class="o lg"><strong><i>&oacute;</i></strong></span>
						<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6 move1">
							<label class="col-xs-offset-2 col-xs-1 col-md-1 text-right hidden-xs">
								2.</label>
							<label class="col-xs-offset-2 col-xs-1 col-md-1 text-right hidden-lg hidden-md hidden-sm" style="font-size: 33px!important;">
								2.</label>
							<p class="col-xs-9 col-md-10">
								Reg&iacute;strate en www.kmimos.com.mx y disruta de todos nuestros servicios.
							</p>
							<div id="section-5-link" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left pull-right">
								<a href="https://www.kmimos.com.mx/?<?php echo $query; ?>">Click aqu&iacute;</a>
							</div>
						</article>

					</div>
					<br>
				</section> 
	       	</div>
			<div class="clearfix"></div>
			
			<section class="row text-center" id="section-1">
	       	 	<article id="top-content" class="col-xs-12 col-sm-12 col-md-offset-3 col-lg-offset-3 col-md-6 col-lg-6">
					<h1>¿Te vas de viaje?</h1>
					<h2>Deja a tu mascota con un CUIDADOR <br> CERTIFICADO. <strong>Libre de jaulas y encierros.</strong></h2>
	       	 	</article>
				<!-- <article id="top-image" class="col-md-12">
					<img src="img/bg-section1a.jpg" width="100%">
				</article> -->
			</section>
			<div class="clearfix"></div>

			<section class="row bg-white">
	       	 	<article id="article-1-button" class="col-xs-12 text-center">
		       	 	<a href="https://www.kmimos.com.mx/?<?php echo $query; ?>" class="btn btn-kmimos">Buscar Cuidador Disponible</a>
	       	 	</article>
				<div class="clearfix"></div>
	       	 	<article class="col-sm-12"  id="section1-footer">
	       	 		<h3>¡Contamos con una Red <i class="fa fa-plus" ></i> de 1,000 Cuidadores Certificados!</h3> 
	       	 	</article>
			</section>
			<div class="clearfix"></div>

			<section class="content">
				<div class="row" id="section-content">

					<section id="section-3" class="col-xs-12 bg-white">
			       	 	<article class="row">
			       	 		<div class="col-xs-12 col-sm-12 col-md-4 text-center">
			       	 			<img src="img/s1.png" class="img-responsive">
			       	 		</div>
			       	 		<div class="col-xs-12 col-sm-12 col-md-8">
				       	 		<p><br>Tu perro ser&aacute; un huesped dentro de la casa del cuidador certificado que escojas.</p>
			       	 		</div>
			       	 	</article>
			       	 	<article class="row">
			       	 		<div class="col-xs-12 col-sm-12 col-md-4 text-center">
			       	 			<img src="img/s2.png" class="img-responsive">
			       	 		</div>
			       	 		<div class="col-xs-12 col-sm-12 col-md-8">
			       	 			<p><br>Dormir&aacute; como un rey en salas, sof&aacute; y a veces ¡hasta en la cama del cuidador!</p>
			       	 		</div>
			       	 	</article>
			       	 	<article class="row">
			       	 		<div class="col-xs-12 col-sm-12 col-md-4 text-center">
			       	 			<img src="img/s3.png" class="img-responsive">
			       	 		</div>
			       	 		<div class="col-xs-12 col-sm-12 col-md-8">
			       	 			<p><br>¡Tu amigo estar&aacute; protegido por una cobertura de servicios veterinarios <br> durante su estad&iacute;a!</p>
			       	 		</div>
			       	 	</article>
			       	 	<article class="row">
			       	 		<div class="col-xs-12 col-sm-12 col-md-4 text-center">
			       	 			<img src="img/s4.png" class="img-responsive">
			       	 		</div>
			       	 		<div class="col-xs-12 col-sm-12 col-md-8">
			       	 			<p class="hidden-xs">El tamaño de tu perro y el cuidador que elijas determinar&aacute; el costo del servicio.<br>
			       	 			Por ejemplo: para un perro chico la estad&iacute;a costar&aacute entre 100 a 200 pesos</p>
			       	 			<p class="hidden-lg hidden-sm hidden-md">El tamaño de tu perro y el cuidador que elijas determinar&aacute; el costo del servicio.<br>
			       	 			Por ejemplo: para un perro chico la estad&iacute;a costar&aacute entre <br> (100 a 200 pesos)</p>
			       	 		</div>
			       	 	</article>
					</section>
					
					<article id="proceso-reserva" class="bg-volaris col-xs-12 text-center">
						<h1 style="font-family: 'PoetsenOne', sans-serif; font-size: 2.5em; ">¿Cómo es el proceso para reservar?</h1>
					</article>

					<section class="col-sm-12" id="section-7">
						<div class="container">
							<div class="col-sm-4">
								<img src="img/img1.png" class="img-responsive">
								<p class="section-7-principal">Entra en www.kmimos.com.mx Busca y Compara Cuidadores Cerca de Ti</p>
							</div>
							<div class="col-sm-8">
								<article class="col-sm-6">
									<img src="img/img2.png" class="img-responsive">
									<p class="section-7-secundario">Una vez que escojas a tu cuidador, p&iacute;cale a reservar</p>
								</article>
								<article class="col-sm-6">
									<img src="img/img3.png" class="img-responsive">
									<p class="section-7-secundario">Lleva a tu amigo a la casa del cuidador (o selecciona transportaci&oacute;n si quieres que pasen por &eacute;l)</p>
								</article>
								<article class="col-sm-6">
									<img src="img/img4.png" class="img-responsive">
									<p class="section-7-secundario">Recibes fotos y v&iacute;deos diarios de tu perro durante su estad&iacute;a</p>
								</article>
								<article class="col-sm-6">
									<img src="img/img5.png" class="img-responsive">
									<p class="section-7-secundario">Tu Perro Regresa a casa FELIZ al final de la estad&iacute;a</p>
								</article>
							</div>
						</div>
					</section>

					<div class="bg-gris col-sm-12">					
						<div class="row">
							<section id="section-6" class="text-center">
								<div class="col-xs-12 col-sm-6 col-sm-offset-3 text-center color-volaris">
									<h1 class="hidden-xs" style="width: 53%;">P&iacute;cale al video de abajo, te va a encantar <br> la nueva alianza VOLARIS - KMIMOS</h1>
									<h1 class="hidden-lg hidden-sm hidden-md" style="width: 100%;">P&iacute;cale al video de abajo, te va a encantar la nueva alianza VOLARIS - KMIMOS</h1>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3" id="video">
									<iframe src="https://www.youtube.com/embed/8T_yhObrq3s" 
										frameborder="0" allowfullscreen width="100%" height="100%"></iframe>
								</div>
							</section>
							<section id="section-6-action" class="bg-volaris text-center col-sm-12">
								<div class="col-xs-12 col-sm-12 col-md-6">
									<span>
										NUESTRA PROMESA:<br>
										¡Tu mascota regresa feliz!
									</span>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6">
									<a href="https://www.kmimos.com.mx/?<?php echo $query ?>" class="btn btn-kmimos-inverso">Buscar Cuidador Disponible</a>
								</div>
								<div class="clearfix"></div>
							</section>
						</div>
					</div>

					<section id="contactos" class="col-sm-12">
						<div class="container bg-black hidden-xs">
							<article class="col-xs-12 col-sm-6" style="margin-top:10px;margin-bottom:10px;">
								<span>Cont&aacute;ctanos a:</span> 
								<!-- <span>(01)55 4742 3162</span> -->
								<span> 01 8000 564667 (KMIMOS)</span>
								<!-- <span>Whatsapp: (55) 6892 2182 </span>-->
								<br>
								<span>contactomex@kmimos.la</span>
							</article>
							<article class="col-xs-12 col-sm-6 text-right">
								<img src="img/logo.png" class="img-responsive logo-kmimos" width="150px" >
								<img src="img/logo-volaris.png" class="img-responsive logo-volaris" width="150px">
							</article>
						</div>
						<div class="container bg-black hidden-md hidden-sm hidden-lg">
							<article class="col-xs-12 col-sm-6" style="margin-top:10px;margin-bottom:10px;">
								<span>Cont&aacute;ctanos a:</span> 
								<!-- <span>(01)55 4742 3162</span> -->
								<!-- <span> 01 8000 564667 (KMIMOS)</span> -->
								<!-- <span>Whatsapp: (55) 6892 2182 </span>-->
								<span>contactomex@kmimos.la</span>
								<img src="img/logo.png" class="img-responsive logo-kmimos"  >
								<img src="img/logo-volaris.png" class="img-responsive logo-volaris" >
							</article>
						</div>
					</section>

				</div>
				<div class="clearfix"></div>
			</section> 


		</div>
		
		<script
		  src="https://code.jquery.com/jquery-2.2.4.min.js?<?php echo time(); ?>"
		  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		  crossorigin="anonymous"></script>
	    <script src="js/wow.js  "></script>
	    <script src="js/main.js?v=1.0.0"></script>
    </body>
</html>
