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
    <title>Kmimos | Cuidador</title>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<script src="js/jquery/jquery.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css?family=Lato:700,900" rel="stylesheet">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/kmimos.css">
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
			#PageSubscribe .section.section2 input, #PageSubscribe .section.section2 button {font-size: 12px;}
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
			var url = '../landing/newsletter.php?source=nuevos-aspirantes&email='+email;
			if(email!=''){
				jQuery.post(url, jQuery(element).serialize(),function(data){
					//console.log(data);
					var textmessage="Error al guardar los datos";

					if( data == 1){
						textmessage="Registro Exitoso. Por favor revisa tu correo en la Bandeja de Entrada o en No Deseados";
					}else if( data == 2){
						textmessage="Formato de email invalido";
					}else if( data == 3){
						textmessage="Este correo ya est&aacute; registrado. Por favor intenta con uno nuevo";
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
<body>
<!-- CABECERA IMAGEN Y LOGO-->
   	<div class="container-fluid">
		<section id="section-1" class="col-xs-12">
			<div class="">
				<img src="img/logo-kmimos.png" alt="Logo-black" class="logo-negro">
			</div>
			<div class="contenido-section1">
				<p>
					<span class="span-kmimos">Kmimos</span> es un servicio digital que conecta 
					<br>Doglovers como t&uacute;, con personas que necesitan 
					<br>que les cuiden a sus peludos mientras no est&aacute;n 
					<br>en casa. 
				</p>

				<a href="https://www.kmimos.com.mx/quiero-ser-cuidador-certificado-de-perros/">
		    		<img src="img/quiero-ser-cuidador-certificado.png" alt="quiero-ser-cuidador-certificado" class="img-responsive">
		    	</a> 
			</div>
		</section>
	</div>
	<div id="ver"><a href="#section-2" id="1"><img src="img/bajar.png" alt="" class="bajar"></a></div>
<!-- COMUNIDAD	 -->
	<section id="section-2">
   	 	<article class="col-sm-12 col-md-12">
   	 		<img src="img/panel.png" alt="" class="panel hidden-xs">
   	 		<div class="col-xs-6 hidden-sm hidden-md hidden-lg">
   	 			<img src="img/fondo-panel.png" alt="" class="panel">
   	 		</div>
   	 		<img src="img/mapa.png" alt="mapa" class="mapa">
   	 		<p style="font-family: 'Lato', sans-serif; font-weight: 100;" class="hidden-xs">Cd. MX Edo. de M&eacute;xico, Guadalajara y su zona metropolitana <br>
   	 		Monterrey, Queretaro, Puebla, Tijuana, Acapulco y Canc&uacute;n</p>
   	 	</article>
   	 	<article class="col-sm-12 col-md-12 centrar">
   	 		<div class="col-sm-4 col-xs-12 espacio">
   	 			<img src="img/patita.png" alt="patita" class="patita">
   	 			<p class="p-patita"><span class="mas">+</span> de 22,500 <br>Noches reservadas</p>
   	 		</div>
   	 		<div class="col-sm-4 col-xs-12 espacio">
   	 			<img src="img/patita.png" alt="patita" class="patita">
   	 			<p class="p-patita"><span class="mas">+</span> de 4,500  <br> Perros cuidados</p>
   	 		</div>
   	 		<div class="col-sm-4 col-xs-12 espacio">
   	 			<img src="img/patita.png" alt="patita" class="patita">
   	 			<p class="p-patita"><span class="mas">+</span> de 3,600 <br> Clientes</p>
   	 		</div>
   	 	</article>
   	 	<div class="col-xs-12 hidden-lg hidden-md hidden-sm">
   	 			<div class="col-xs-4">
   	 				<img src="img/kmimos-presente.png" alt="" style="width: 83%;">
   	 			</div>
   	 			<div class="col-xs-8">
	   	 			<p style="font-family: 'Lato', sans-serif; font-weight: 100;">Cd. MX Edo. de M&eacute;xico, Guadalajara y su zona metropolitana <br>
	   	 			Monterrey, Queretaro, Puebla, Tijuana, Acapulco y Canc&uacute;n</p>
	   	 			</div>
   	 	</div>
   	 	<article class="col-sm-12 move hidden-xs">
   	 		<span class="span">Nos viste en:</span>
   	 		<div class="col-sm-2 col-xs-2">
   	 			<img src="img/expansion.png" alt="expansion" class="publicidad">
   	 		</div>
   	 		<div class="col-sm-2 col-xs-2">
   	 			<img src="img/reforma.png" alt="reforma" class="publicidad">
   	 		</div>
   	 		<div class="col-sm-2 col-xs-2">
   	 			<img src="img/entrepreneur.png" alt="entrepreneur" class="publicidad">
   	 		</div>
   	 		<div class="col-sm-2 col-xs-2">
   	 			<img src="img/elfinanciero.png" alt="elfinanciero" class="publicidad">
   	 		</div>
   	 		<div class="col-sm-2 col-xs-2">
   	 			<img src="img/eluniversal.png" alt="eluniversal" class="publicidad">
   	 		</div>
   	 	</article>
   	 	<article class="col-sm-12 move hidden-lg hidden-sm hidden-md">
   	 		<span class="span">Nos viste en:</span>
   	 		<div class="col-sm-2 col-xs-2" style="margin-left:-50px;">
   	 			<img src="img/expansion.png" alt="expansion" class="publicidad">
   	 		</div>
   	 		<div class="col-sm-2 col-xs-2" style="margin-left:20px;">
   	 			<img src="img/reforma.png" alt="reforma" class="publicidad">
   	 		</div>
   	 		<div class="col-sm-2 col-xs-2" style="margin-left:20px;">
   	 			<img src="img/entrepreneur.png" alt="entrepreneur" class="publicidad">
   	 		</div>
   	 		<div class="col-sm-2 col-xs-2" style="margin-left:20px;">
   	 			<img src="img/elfinanciero.png" alt="elfinanciero" class="publicidad">
   	 		</div>
   	 		<div class="col-sm-2 col-xs-2" style="margin-left:21px;">
   	 			<img src="img/eluniversal.png" alt="eluniversal" class="publicidad">
   	 		</div>
   	 	</article>
	</section>
<!-- FOTO MAS TEXTO -->
	<section id="section-3" class="col-sm-12">
		<h3 class="title">"Kmimos me da la oportunidad de generar <br> ingresos haciendo lo que m&aacute;s me gusta"</h3>
		<div><a href="#section-4"><img src="img/7.png" alt="" class="bajar-foto"></a></div>
	</section>
<!-- CONVIERTETE EN CUIDADOR -->
	<section id="section-4" class="col-xs-12">
   	 	<article class="col-xs-12 text-center">
			<h3>Conviert&eacute;te en cuidador certificado Kmimos</h3>
		</article>
		<div class="col-sm-12 col-md-12 lado">
	   	 	<article class="col-sm-4 col-md-4 col-xs-12">
	   	 		<div><img src="img/certi-1.png" alt="certi-1" class="certi"></div>
	   	 		<div>
	   	 			<h2>Horarios flexibles</h2>
	   	 			<p>T&uacute; eliges tus horarios y <br>cuando trabajar</p>
	   	 	</article>
	   	 	<article class="col-sm-4 col-md-4 col-xs-12">
	   	 		<div><img src="img/certi-2.png" alt="certi-2" class="certi"></div>
	   	 		<div>
	   	 			<h2>Ser&aacute;s tu propio jefe</h2>
	   	 			<p>En Kmimos t&uacute; decides los d&iacute;as en que <br>trabajas y como ofreces tu servicio</p>
	   	 	</article>
	   	 	<article class="col-sm-4 col-md-4 col-xs-12">
	   	 		<div><img src="img/certi-3.png" alt="certi-3" class="certi"></div>
	   	 		<div>
	   	 			<h2>Trabaja desde casa</h2>
	   	 			<p>Olvidate de las oficinas, tu <br> lugar de trabajo es tu hogar</p>
	   	 		</div>
	   	 	</article>
	   	 </div>
	   	 <article class="col-sm-9 col-sm-offset-2">
	   	 	<img src="img/gane30.png" alt="gane" class="img-gane">
	   	 </article>
	</section>
	<div class="col-sm-12 col-xs-12">
	 	<a href="#section-5"><img src="img/bajar.png" alt="" class="bajar-convi"></a>
	</div>
<!-- PARA SER PARTE SOLO NECESITAS -->
	<section id="section-5" class="col-xs-12">
   	 	<article class="col-xs-12 col-sm-12">
			<h3>Para ser parte s&oacute;lo <br> necesitas</h3>
		</article>
		<article class="col-sm-5 col-sm-offset-7">
			<div class="col-sm-8 col-xs-10">
				<p>Ser mayor de edad</p>
			</div>
			<div class="col-sm-4 col-xs-2">	
				<img src="img/huella.png" alt="">
			</div>
		</article>
		<article class="col-sm-5 col-sm-offset-7">
			<div class="col-sm-8 col-xs-10">
				<p>Experiencia cuidando perros <br>propios de al menos 3 años</p>
			</div>
			<div class="col-sm-4 col-xs-2">	
				<img src="img/huella.png" alt="">
			</div>
		</article>
		<article class="col-sm-5 col-sm-offset-7">
			<div class="col-sm-8 col-xs-10">
				<p>Confirmar que tu domicilio <br>puedes recibir mascotas</p>
			</div>
			<div class="col-sm-4 col-xs-2">	
				<img src="img/huella.png" alt="">
			</div>
		</article>
		<article class="col-sm-5 col-sm-offset-7">
			<div class="col-sm-8 col-xs-10">
				<p>Aprobar nuestras pruebas de <br>certificación gratuitas</p>
			</div>
			<div class="col-sm-4 col-xs-2">	
				<img src="img/huella.png" alt="">
			</div>
		</article>
		<div class="col-sm-12 col-xs-12">
	   	 	<a href="#section-6"><img src="img/7.png" alt="" class="bajar-parte"></a>
	   	 </div>
	</section>
<!-- COMO CONVERTIRTE EN CUIDADOR -->
	<section id="section-6">
		<article class="col-xs-12 col-sm-12">
			<h3>¿C&oacute;mo convertirte en cuidador certificado?</h3>
		</article>
		<article class="col-sm-12">
			<div class="col-sm-4 col-xs-12 left">
				<div style="text-align: center;">
					<img src="img/1.png" alt="1" class="relacion rel1 hidden-xs">
					<img src="img/paso1-responsive.png" alt="1" class="relacion rel1 hidden-sm hidden-md hidden-lg">
					<img src="img/paso1.png" alt="1" class="paso1 hidden-xs">
				</div>
				<div style="margin-left: 10%;">
					<p><span style="color: #e2b223; font-weight:300; font-family: 'PoetsenOne', sans-serif;">Llena tu formulario</span> y sube <br>tus documentos a nuestra <br>plataforma</p>
				</div>
			</div>
			<div class="col-sm-4 col-xs-12">
				<div style="text-align: center;">
					<img src="img/2.png" alt="2" class="relacion rel2 hidden-xs">
					<img src="img/paso2-responsive.png" alt="2" class="relacion rel2 hidden-lg hidden-md hidden-sm">
					<img src="img/paso2.png" alt="2" class="paso2 hidden-xs">
				</div>
				<div>
					<p>
						<span style="color: #e2b223; font-weight:300; font-family: 'PoetsenOne', sans-serif;">Realiza tus pruebas</span> psicometrícas y
						<br>de conocimientos veterinarios básicos
						<br>en línea. (revisa tu correo eléctronico,
						<br>ahí llegarán tus resultados)
					</p>
				</div>
			</div>
			<div class="col-sm-4 col-xs-12">
				<div style="text-align: center;">
					<img src="img/3.png" alt="3" class="relacion rel3 hidden-xs">
					<img src="img/paso3-responsive.png" alt="3" class="relacion rel3 hidden-lg hidden-sm hidden-md">
					<img src="img/paso3.png" alt="3" class="paso3 hidden-xs">
				</div>
				<div>
					<p>
						<span style="color: #746d6d; font-weight:300; font-family: 'PoetsenOne', sans-serif;">Crea tu perfil</span> de cuidador
						<br>Kmimos. Al completarlo, la
						<br>familia kmimos te realizará una
						<br>entrevista teléfonica.
					</p>
				</div>
			</div>
		</article>
		<article class="col-sm-12 move-up">
			<div class="col-sm-4 col-xs-12 col-sm-offset-3">
				<div>
					<img src="img/3-1.png" alt="3-1" class="relacion rel3-1 hidden-xs">
					<img src="img/paso4-responsive.png" alt="3" class="relacion rel3-1 hidden-lg hidden-sm hidden-md">
					<img src="img/paso4.png" alt="4" class="paso3-1 hidden-xs">
				</div>
				<div>
					<p>
						Una vez que seas activado en nuestra
						<br>plataforma recibirás una o varias <span style="color: #e2b223; font-weight:300; font-family: 'PoetsenOne', sans-serif;">visitas
						<br>aleatorias</span> para revisar que todo esté en
						<br>condiciones óptimas para recibir mascotas.
					</p>
				</div>
			</div>
			<div class="col-sm-4">
				<div>
					<img src="img/4.png" alt="4" class="relacion rel4 hidden-xs">
					<img src="img/paso5-responsive.png" alt="4" class="relacion rel4 hidden-lg hidden-md hidden-sm">
					<img src="img/check.png" alt="check" class="paso4 hidden-xs">
				</div>
				<div>
					<p>
						<span style="color: #e2b223; font-weight:300; font-family: 'PoetsenOne', sans-serif;">¡Listo!</span>, ahora eres un
						<br>cuidador certificado Kmimos,
						<br>¡A recibir peludos!
					</p>
				</div>
			</div>
		</article>
		<div class="col-sm-12 col-xs-12">
	   	 	<a href="#section-video"><img src="img/bajar.png" alt="" class="bajar-como"></a>
	   	 </div>
	</section>
<!--SECCION VIDEO-->
	<section id="section-video">
		<div class="col-sm-8 col-sm-offset-2">
			<article class="video video-container container-iframe">
				<iframe src="https://www.youtube.com/embed/Kqn7lOVk6bQ"
				frameborder="0" allowfullscreen></iframe>
			</article>
		</div>
		<div class="col-sm-12">
			<img src="img/logo-kmimos.png" alt="Logo" class="logo-video">
		</div>
	</section>
<!-- UNETE -->
	<section id="section-unete">
		<div class="col-sm-12 col-xs-12">
	   	 	<a href="#section-7"><img src="img/bajar.png" alt="" class="bajar-unete"></a>
	   	</div>
		<div class="col-sm-12">
			<h2><span style="color: #eebf31;">&Uacute;nete ya</span> <br> a nuestra gran familia de cuidadores</h2>
		</div>
		<a href="https://www.kmimos.com.mx/?">
    		<img src="img/quiero-ser-cuidador-certificado.png" alt="quiero-ser-cuidador-certificado" class="img-responsive">
    	</a>
		<div class="col-sm-12" id="img-unete">
			<!-- AQUI VA LA IMAGEN DE LOS PERROS	 -->
			<img src="img/muestra-responsive.png" alt="muestra-1" class="img-unete">
    	</div> 
	</section>
<!-- QUIERS CONOCER KMIMOS -->
	<section id="section-7" class="col-sm-12 bg-color-morado">
		<div class="col-md-12 col-sm-12">
			<h3><a href="https://www.kmimos.com.mx/quiero-ser-cuidador-certificado-de-perros/">¿Quieres conocer Kmimos?</a></h3>
		</div>
	</section>
<!-- FOOTER LOGO KMIMOS -->
	<section id="logo">
		<header>
			<img src="img/logo-kmimos.png" alt="logo-kmimos" class="logo-footer">
		</header>
	</section>

	<script
	  src="https://code.jquery.com/jquery-2.2.4.min.js"
	  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
	  crossorigin="anonymous"></script>
    <script src="js/wow.js  "></script>
    <script src="js/main.js?v=1.0.0"></script>
    <script>
    	$(function(){

		     $('a[href*="#"]').click(function() {

		     if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
		         && location.hostname == this.hostname) {

		             var $target = $(this.hash);

		             $target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');

		             if ($target.length) {

		                 var targetOffset = $target.offset().top;

		                 $('html,body').animate({scrollTop: targetOffset}, 1000);

		                 return false;

		            }
		       }
		   });
		});
    </script>
    <!--<script>
    	
    	// REGISTRO
    	var globalData = ""; 
    	var nombres, email;
        $(document).on("click", '#btn-inscribirme', function ( e ) {
        	
			if ($("#nomap").val() != "" && $("#email").val() != "" ) {
				$("[name='sp-name']").removeClass('span-name-show');
				$("[name='sp-name']").addClass('span-name-hide');
				if($("#email").val().indexOf('@', 0) == -1 || $("#email").val().indexOf('.', 0) == -1) {
            		$("[name='sp-email']").removeClass('span-email-show');
		        	$("[name='sp-email']").addClass('span-email-hide');
            		$("[name='sp-name-inc']").removeClass('span-email-hide');
		        	$("[name='sp-name-inc']").addClass('span-email-show');
		          	$("[name='sp-name-inc']").css('color','#fff');
            		return false;
        		}else{
        			nombres = $("#nomap").val();
					email = $("#email").val();
        			var data = {'email': email};
					globalData = getGlobalData('main.php','POST', data);
					if (globalData == 'SI') {
						$("[name='sp-email']").removeClass('span-email-show');
		        		$("[name='sp-email']").addClass('span-email-hide');
		        		$("[name='sp-name-inc']").removeClass('span-email-show');
		        		$("[name='sp-name-inc']").addClass('span-email-hide');
	                	$("[name='sp-email-uso']").removeClass('span-email-hide');
		        		$("[name='sp-email-uso']").addClass('span-email-show');
	                    $("[name='sp-email-uso']").css('color','#fff');
	                    e.preventDefault();
	                }else{
	                    var datos = {'nombres': nombres, 'email': email};
						var Data = getGlobalData('date.php','POST', datos);
						if (Data === 'SI') {
							$('#guardando').removeClass('span-name-hide');
							$('#guardando').addClass('span-name-show');
							$("[name='sp-email']").addClass('span-email-hide');
							$("[name='sp-name-inc']").addClass('span-email-hide');
							$("[name='sp-email-uso']").addClass('span-email-hide');
							$("[name='sp-email']").removeClass('span-email-show');
							$("[name='sp-name-inc']").removeClass('span-email-show');
							$("[name='sp-email-uso']").removeClass('span-email-show');
						}else{
							$('#guardando').removeClass('span-name-show');
							$('#guardando').append('span-name-hide');
							$('#guardando-err').removeClass('span-name-hide');
							$('#guardando-err').append('span-name-show');
						}
	                }
        		}      	
			}else{		
				
				if($("#nomap").val().length == 0){
					$("[name='sp-name']").removeClass('span-name-hide');
					$("[name='sp-name']").addClass('span-name-show');
		          	$("[name='sp-name']").css('color','#fff');
		          	// $("#nomap").focus(function() { $("[name='sp-name']").hide(); });
		        }else{
		          	$("[name='sp-name']").hide();
		        }
		        if($("#email").val().length == 0){
		        	$("[name='sp-email']").removeClass('span-email-hide');
		        	$("[name='sp-email']").addClass('span-email-show');
		          	$("[name='sp-email']").css('color','#fff');
		          	// $("#email").focus(function() { $("[name='sp-email']").hide(); });
		        }else{
		          	$("[name='sp-email']").hide();
		        }
				e.preventDefault();
			}
        });

      	// Validar tipos e datos en los campos
	    jQuery( document ).on('keypress', '[data-charset]', function(e){

	        var tipo= $(this).attr('data-charset');

	        if(tipo!='undefined' || tipo!=''){
	            var cadena = "";

	            if(tipo.indexOf('alf')>-1 ){ cadena = cadena + "abcdefghijklmnopqrstuvwxyzáéíóúñüÁÉÍÓÚÑÜ"; }
	            if(tipo.indexOf('xlf')>-1 ){ cadena = cadena + "abcdefghijklmnopqrstuvwxyzáéíóúñüÁÉÍÓÚÑÜ "; }
	            if(tipo.indexOf('mlf')>-1 ){ cadena = cadena + "abcdefghijklmnopqrstuvwxyz"; }
	            if(tipo.indexOf('num')>-1 ){ cadena = cadena + "1234567890"; }
	            if(tipo.indexOf('cur')>-1 ){ cadena = cadena + "1234567890,."; }
	            if(tipo.indexOf('esp')>-1 ){ cadena = cadena + "-_.$%&@,/()"; }
	            if(tipo.indexOf('cor')>-1 ){ cadena = cadena + ".-_@"; }
	            if(tipo.indexOf('rif')>-1 ){ cadena = cadena + "vjegi"; }
	            if(tipo.indexOf('dir')>-1 ){ cadena = cadena + ","; }

	            var key = e.which,
	                keye = e.keyCode,
	                tecla = String.fromCharCode(key).toLowerCase(),
	                letras = cadena;

	            if(letras.indexOf(tecla)==-1 && keye!=9&& (key==37 || keye!=37)&& (keye!=39 || key==39) && keye!=8 && (keye!=46 || key==46) || key==161){
	                e.preventDefault();
	            }
	        }

	      mensaje( $(this).attr('name'), '', true );
	       
	    });
		// FUNCION GLOBAL PARA ENVIAR POR AJAX      
	    function getGlobalData(url,method, datos){
			return $.ajax({
				data: datos,
				type: method,
				url: url,
				async:false,
				success: function(data){
		            //alert(data);
		            // $("#guardando").css('color','#fff');
					return data;
				}
			}).responseText;
		}
    </script>-->
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
