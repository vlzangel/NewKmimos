<?php

	$query = '';
	foreach ($_GET as $key => $value) {
		$separador = (!empty($query))? '&' : '' ;
		if( $key == 'utm_campaign'){
			$value = 'landing_' . $_GET['utm_campaign']; 
		}
		$query .= $separador.$key.'='.$value;
	}

?><!DOCTYPE html>
<html> 
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Kmimos Animado</title>

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<script src="js/jquery/jquery.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Lato:700,900" rel="stylesheet">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/animate.css">
        <link rel="stylesheet" href="css/kmimos.css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Google Tag Manager -->
		<script>
			(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-5SG9NM');
		</script>		
	<!-- End Google Tag Manager -->


	<style type="text/css">
		#message{position: fixed; width: 100%; height: 100%; bottom: 0; padding: 20px; text-align: center; box-shadow: 0 0 3px #CCC; background: rgba(0, 0, 0, 0.8); z-index: 2;}
		#message.Msubscribe .contain{position: relative; width: 95%; max-width: 100%; margin: 0 auto;}
		#PageSubscribe{position:relative; max-width: 700px;  margin: 0 auto;  padding: 25px;  top: 75px; color: #FFF; border-radius: 20px; /* background:#00bc00;*/
			background: #ba2287;  overflow: hidden;}
		#PageSubscribe .exit{float: right; cursor: pointer;}
		#PageSubscribe .section{ width: 50%; padding: 10px; float: left; font-size: 17px; text-align: left;}
		#PageSubscribe .section.section1{font-size: 20px;}
		#PageSubscribe .section.section1 span{font-size: 25px; font-weight: 400;}
		#PageSubscribe .section.section1 .images{padding:10px 0; text-align: center;}
		#PageSubscribe .section.section3{width: 100%; font-size: 17px; font-weight: bold; text-align: center;}
		#PageSubscribe .section.section2{}
		#PageSubscribe .section.section2 .message{font-size: 15px; border: none; background: none; opacity:0; visibility: : hidden; transition: all .3s;}
		#PageSubscribe .section.section2 .message.show{opacity:1; visibility: :visible;}
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
			#PageSubscribe .section.section2 input, #PageSubscribe .section.section2 button {font-size: 10px;}
		}
	</style>

	<script type='text/javascript'>
		//Subscribe
		function SubscribeSite(){
			clearTimeout(SubscribeTime);

			var dog = '<img height="70" align="bottom" src="https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-09.png">' +
				'<img height="20" align="bottom" src="https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-10.png">';

			var html='<div id="PageSubscribe"><i class="exit fa fa-times" aria-hidden="true" onclick="SubscribePopUp_Close(\'#message.Msubscribe\')"></i>' +
				'<div class="section section1"><span>G&aacute;nate <strong>$50 pesos</strong> en tu primera reserva</span><br>&#8216;&#8216;Aplica para clientes nuevos&#8217;&#8217;<div class="images">'+dog+'</div></div>' +
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
			var url = '../landing/newsletter.php?source=cuidadores-animados&email='+email;
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
	</script>

	
    </head>
    <body id="waypoint">       
       	<div class="col-sm-12">

			<section class="row" id="section-1">
				<header class="text-center">
		       	 	<img src="img/LogoKmimos.png" class="logo">
		       	</header>
		       	<article class="col-sm-5 hidden-xs">
		       		<img src="img/Character_section1.png" class=" img-kmimos img-responsive">
		       	</article>
	       	 	<article class="col-sm-5">
					<h1>¡Deja a tu mascota en casa de un cuidador certificado!</h1>
	       	 	</article>
		       	<article class="img-section-1 col-sm-5 pull-left  hidden-md hidden-sm hidden-lg">
		       		<img src="img/Character_section1.png" class="img-kmimos img-responsive">
		       	</article>
	       	 	<article class="col-xs-12 text-center margin-bottom-20">
		       	 	<a href="https://www.kmimos.com.mx/?home&utm_source=youtube&utm_medium=landing_page&utm_campaign=buscar_cuidador_disponible&utm_term=cuidador%2Bperros%2Bmexico&utm_content=landing_page#jj-landing-page" class="btn btn-kmimos">Buscar Cuidador Disponible</a>
	       	 	</article>
			</section>
			<div class="clearfix"></div>


	   	 	<div class="row text-center title"><span>Tu perrito</span></div>
			<section class="row" id="section-2">
		       	 	<article class="col-xs-12 col-sm-12 col-md-6 text-center ">
	       	 			<img src="img/Icon-1.gif" class="wow zoomIn">
		       	 		<h2>Se queda en la casa del cuidador</h2>
		       	 	</article>
		       	 	<article class="col-xs-12 col-sm-12 col-md-6 text-center ">
	       	 			<img src="img/Icon-2.gif" class="wow zoomIn">
		       	 		<h2>Duerme como un rey en salas y sof&aacute;s</h2>
		       	 	</article>
		       	 	<article class="col-xs-12 col-sm-12 col-md-6 text-center ">
	       	 			<img src="img/Icon-3.gif" class="wow zoomIn">
		       	 		<h2>Tendr&aacute; cobertura veterinaria</h2>
		       	 	</article>
		       	 	<article class="col-xs-12 col-sm-12 col-md-6 text-center ">
	       	 			<img src="img/Icon-4.gif" class="wow zoomIn">
		       	 		<h2>El costo depende de su tamaño</h2>
		       	 	</article>
			</section>
			<div class="clearfix"></div>


	   	 	<div class="row text-center title"><span>Pasos para reservar:</span></div>
			<section id="section-3">
		       	 	<article class="row wow bounceInRight" data-wow-delay="">
		       	 		<div class="col-sm-4 text-center">
		       	 			<img src="img/Icon-5.gif">
		       	 		</div>
		       	 		<h1>PASO 1</h1>
		       	 		<h3><i class="fa fa-point"></i> Busca cuidadores cerca de tí</h3>
		       	 	</article>
		       	 	<article class="row wow bounceInLeft"  data-wow-delay="">
		       	 		<div class="col-sm-4 text-center">
		       	 			<img src="img/Icon-6.gif">
		       	 		</div>
		       	 		<h1>PASO 2</h1>
		       	 		<h3><i class="fa fa-point"></i> Haz la reserva para tu perrito</h3>
		       	 	</article>
		       	 	<article class="row  wow bounceInRight" data-wow-delay="">
		       	 		<div class="col-sm-4 text-center">
		       	 			<img src="img/Icon-7.gif">
		       	 		</div>
		       	 		<h1>PASO 3</h1>
		       	 		<h3><i class="fa fa-point"></i> Tu mascota va a casa del cuidador</h3>
		       	 	</article>
		       	 	<article class="row wow bounceInLeft" data-wow-delay="">
		       	 		<div class="col-sm-4 text-center">
		       	 			<img src="img/Icon-8.gif">
		       	 		</div>
		       	 		<h1>PASO 4</h1>
		       	 		<h3><i class="fa fa-point"></i> Recibes fotos y videos durante su estad&iacute;a</h3>
		       	 	</article>
		       	 	<article class="row wow bounceInRight" data-wow-delay="">
		       	 		<div class="col-sm-4 text-center">
		       	 			<img src="img/Icon-9.gif">
		       	 		</div>
		       	 		<h1>PASO 5</h1>
		       	 		<h3><i class="fa fa-point"></i> &Eacute;l o ella regresa Feliz</h3>
		       	 	</article>
			</section>
			<div class="clearfix"></div>


			<section class="row" id="section-6">
				<div class="col-sm-10 col-sm-offset-1">			
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2 text-center xcontainer-iframe">
							<article class="xvideo xvideo-container">
								<iframe src="https://www.youtube.com/embed/_08UJ0aYDCk" 
									frameborder="0" allowfullscreen></iframe>
							</article>
							<article class="col-sm-12 text-center" style="padding-top:20px;">
								<a href="https://www.kmimos.com.mx/?home&utm_source=youtube&utm_medium=landing_page&utm_campaign=buscar_cuidador_disponible&utm_term=cuidador%2Bperros%2Bmexico&utm_content=landing_page#jj-landing-page" class="btn btn-kmimos">Buscar Cuidadores Disponibles</a>
							</article>
						</div>
					</div>
					<div>
						<aside class="col-sm-2 hidden-sm hidden-xs">
							<img src="img/object 1.png" width="80px">
						</aside>
						<aside class="col-sm-3 pull-right">
							<img src="img/Character 5.png" class="img-responsive ">
						</aside>
					</div>
				</div>

			</section>
			<div class="clearfix"></div>


			<footer class="row text-left">
				<div class="col-md-6 col-sm-offset-1">
					<h1>
						Nunca Ha Sido Tan Fácil Encontrar <br>
						Un Cuidador para tu Mascota
					</h1>
				</div>
				<div class="col-md-5 pull-right text-right circulo">
			       	<img src="img/character 1.png" class="img-cuidador">
				</div>
				<aside class="col-sm-12 text-center">
		       	 	<img src="img/LogoKmimos.png" width="150px">
		   		</aside>
			</footer>
		</div>

		<script
		  src="https://code.jquery.com/jquery-2.2.4.min.js?<?php echo time(); ?>"
		  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
		  crossorigin="anonymous"></script>
	    <script src="js/wow.js  "></script>
	    <script src="js/main.js?"></script>
    

    <script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-56422840-1', 'auto');
	  ga('send', 'pageview');

	</script>	
    </body>
</html>
