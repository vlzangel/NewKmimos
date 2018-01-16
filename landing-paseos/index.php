<?php include_once(dirname(__DIR__).'/wp-load.php'); ?>
<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Kmimos Paseos</title>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<script src="js/jquery/jquery.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/kmimos1.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	
   	
	<script src="js/script.js"></script>

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
        #PageSubscribe{position:relative; max-width: 700px;  margin: 0 auto;  padding: 25px;  top: 75px; border-radius: 20px;  background: #ba2287;  overflow: hidden;}
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
        .span-email-show{ display: list-item; }
        .span-email-hide{ display: none; }
        @media screen and (max-width:480px), screen and (max-device-width:480px) {
            #PageSubscribe { top: 15px;}
            #PageSubscribe .section{ width: 100%; padding: 10px 0; font-size: 12px;}
            #PageSubscribe .section.section1 {font-size: 15px;}
            #PageSubscribe .section.section1 span {font-size: 20px;}
            #PageSubscribe .section.section3 {font-size: 12px;}
        }

        .container-fluid {
            padding-right: 0;
            padding-left: 0;
        }
        .row {
            margin-right: 0;
            margin-left: 0;
        }
    </style>
	<script type="text/javascript" src="https://www.kmimos.com.mx/wp-content/plugins/kmimos/subscribe/includes/js/script.js?ver=4.4.7"></script>
	 <script type='text/javascript'>
            //Subscribe
            function SubscribeSite(){
                clearTimeout(SubscribeTime);

                var CampaignMonitor = '<div id="subForm">'+
                '<input id="fieldEmail" name="mail" type="email" placeholder="Introduce tu correo aqu&iacute" required />'+
                '<button onclick="register()" id="btn-envio"><i class="fa fa-arrow-right" aria-hidden="true"></i></button></div>'+
                '<div id="msg" class="span-email-hide">Registro Exitoso. Por favor revisa tu correo en la Bandeja de Entrada o en No Deseados</div>'+
                '<div id="msg-vacio" class="span-email-hide">Debe completar los datos</div>'+
                '<div id="msg-register" class="span-email-hide">Este correo ya estaba registrado. Por favor intentar con uno nuevo</div>'+
                '<div id="msg-error" class="span-email-hide">El email no es valido</div>';
                
                var dog = '<img height="70" align="bottom" src="https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-09.png">' +
                    '<img height="20" align="bottom" src="https://www.kmimos.com.mx/wp-content/uploads/2017/07/propuestas-banner-10.png">';

                var html='<div id="PageSubscribe"><i class="exit fa fa-times" aria-hidden="true" onclick="SubscribePopUp_Close(\'#message.Msubscribe\')"></i>' +
                    '<div class="section section1"><span>G&aacute;nate <strong>$50 pesos</strong> en tu primera reserva</span><br>&#8216;&#8216;Aplica para clientes nuevos&#8217;&#8217;<div class="images">'+dog+'</div></div>' +
                    '<div class="section section2"><span><strong>&#161;SUSCR&Iacute;BETE!</strong> y recibe el Newsletter con nuestras <strong>PROMOCIONES, TIPS DE CUIDADOS PARA MASCOTAS,</strong> etc.!</span>'+CampaignMonitor+
                    '</div>';


                SubscribePopUp_Create(html);
            }

            function register(){     
                if( jQuery('#fieldEmail').val() == ""){
                    jQuery("#msg-vacio").removeClass('span-email-hide');
                    jQuery("#msg-vacio").addClass('span-email-show');
                    return false;
                }else{
                    var mail= jQuery('#fieldEmail').val();
                    var datos = {'source': 'landing-paseos', 'email': mail}
                    var result = getGlobalData("../../../landing/newsletter.php?source=landing-paseos&email="+mail,'GET', null);
                        console.log(result);
                    if (result == 1) {
                        jQuery("#msg-vacio").removeClass('span-email-show');
                        jQuery('#msg-error').removeClass('span-email-show');
                        jQuery('#msg-register').removeClass('span-email-show');
                        jQuery("#msg-vacio").addClass('span-email-hide');
                        jQuery('#msg-error').addClass('span-email-hide');
                        jQuery('#msg-register').addClass('span-email-hide');
                        jQuery('#msg').removeClass('span-email-hide');
                        jQuery('#msg').addClass('span-email-show');
                        // var datos = {'campo':'cm-vydldy-vydldy',
                                     // 'email': mail,
                                     // 'lista': 'http://kmimos.intaface.com/t/j/s/vydldy/'}
                        // var resp = getGlobalData('https://www.kmimos.com.mx/landing-volaris/suscribir_lista.php','POST', datos);
                    }else if (result == 2){
                        jQuery("#msg-vacio").removeClass('span-email-show');
                        jQuery('#msg-error').removeClass('span-email-hide');
                        jQuery('#msg').removeClass('span-email-show');
                        jQuery('#msg-register').addClass('span-email-hide');
                        jQuery('#msg-register').removeClass('span-email-show');
                        jQuery("#msg-vacio").addClass('span-email-hide');
                        jQuery('#msg-error').addClass('span-email-show');
                        jQuery('#msg').addClass('span-email-hide');
                    }else if (result == 3){
                        jQuery("#msg-vacio").removeClass('span-email-show');
                        jQuery('#msg-error').removeClass('span-email-hide');
                        jQuery('#msg-register').removeClass('span-email-hide');
                        jQuery("#msg-vacio").addClass('span-email-hide');
                        jQuery('#msg-error').addClass('span-email-hide');
                        jQuery('#msg-register').addClass('span-email-show');
                        jQuery('#msg').removeClass('span-email-show');
                        jQuery('#msg').addClass('span-email-hide');
                    }else{
                        jQuery("#msg-vacio").removeClass('span-email-hide');
                        jQuery('#msg-error').removeClass('span-email-show');
                        jQuery('#msg-register').removeClass('span-email-show');
                        jQuery("#msg-vacio").addClass('span-email-show');
                        jQuery('#msg-error').addClass('span-email-hide');
                        jQuery('#msg-register').addClass('span-email-hide');
                        jQuery('#msg').removeClass('span-email-show');
                        jQuery('#msg').addClass('span-email-hide');
                    }
                }
            }

            jQuery(document).ready(function(e){
                SubscribeTime = setTimeout(function(){
                    SubscribeSite();
                }, 20000);
            });


            function message_subscribe(element){
                clearTimeout(vsetTime);
                element.removeClass('show');
                element.html('');
                return true;
            }

            
            function getGlobalData(url,method, datos){
                return jQuery.ajax({
                    data: datos,
                    type: method,
                    url: url,
                    async:false,
                    success: function(data){
                        return data;
                    }
                }).responseText;
            }
    </script>
    <?php wp_head(); ?>
</head>
<body>  
<!-- seccion de cabecera -->
	<div class="container-fluid">
		<header>
	 		<img src="img/LogoKmimos.png" class="logo">
		</header>
		<section class="row ancho" id="section-1" id="contenedor">       	
	       	<article class="col-md-12">
				<h1 class="hidden-xs">¿Te resulta familiar <br>esta escena?
				</h1>
				<h1 class="hidden-md hidden-sm hidden-lg">
				¿Te resulta familiar <br>esta escena?						
				</h1>
	       	</article>
		    <a href="https://www.kmimos.com.mx/?">
		    	<img src="img/quiero-conocer-cuidador-n.png" alt="quiero-ser-cuidador" class="img-responsive">
		    </a> 	
		</section>
	</div>
	<!--seccion video-->
	<section id="section-2">
		<div class="morado">
		    <div class="blanco">
				<h1>En kmimos, paseamos a tu perrihijo para mantenelo ejercitado y sano. <br>
					Te garantizamos que regresará a casa feliz.
					</h1>
		    </div>
		</div>
		<div class="col-sm-8 col-sm-offset-2">
			<article class="video video-container container-iframe">
				<iframe src="https://www.youtube.com/embed/xjyAXaTzEhM?rel=0" frameborder="0" allowfullscreen></iframe>
			</article>
		</div>
	</section>
		
	<!-- En que consta el servicio -->
		<section id="section-4">
			<article class="container">
				<div class="col-md-12">
					<h2 id="h2">¿En qu&eacute; consta el servicio?</h2>
					<!-- <h2 id="h2 hidden-lg hidden-md hidden-sm">¿De qu&eacute; consta el servicio?</h2> -->
				</div>
			</article>
			<article class="col-sm-offset-6 col-sm-6 hidden-sm hidden-lg hidden-md">
		  		<img src="img/perro-servi.png" alt="perro-servi" class="img-servicio">
		  	</article>
			<article class="col-sm-12 col-md-12">
	    		<div class="conte-serv-1">
	        		<p class="hidden-xs">Todo el d&iacute;a se divertir&aacute;</p>
	        		<p class="hidden-lg hidden-md hidden-sm">Todo el d&iacute;a <br>se divertir&aacute;</p>
	    		</div>
	    		<div class="conte-serv-2">
	        		<p>Estar&aacute; seguro con <br>cuidadores certificados.</p>  
	    		</div>
	    		<div class="conte-serv-3">
	        		<p class="hidden-xs">Te enviar&aacute;n fotos <br>y videos  durante el d&iacute;a.</p>
	        		<p class="hidden-lg hidden-md hidden-sm">Te enviar&aacute;n fotos <br>y videos  durante <br>el d&iacute;a.</p>
	    		</div>
		  	</article>
		  	<article class="col-sm-offset-6 col-sm-6  hidden-xs">
		  		<img src="img/perro-servi.png" alt="perro-servi" class="img-servicio">
		  	</article>
		</section>
	<!-- Ventajas de dejarlo con un cuidador certificado -->
		<section id="section-3">	
   	 		<div class="col-sm-12 col-xs-12 row-1">
	   	 		<article class="col-sm-12 col-xs-12">
					<h2>Ventajas de <br> pasear a tu perrito</h2>
				</article>
	   	 		<article class="col-sm-12 col-xs-12  colores">
	   	 			<div class="col-sm-4 col-xs-12  color1">Tu mejor amigo <br> no tendr&aacute; estr&eacute;s.</div>
	   	 			<!-- <div class="col-sm-3 col-xs-12 color2">Tu Mejor amigo se <br>queda en la casa del <br>cuidador.</div> -->
	   	 			<div class="col-sm-4 col-xs-12 color3">Prevendr&aacute; la obesidad y <br> algunas enfermedades.</div>
	   	 			<div class="col-sm-4 col-xs-12 color4">Se mantendr&aacute; <br>saludable y en forma.</div>
	   	 		</article>
   	 		</div>
		</section>  
	<!-- ¿Cómo es el proceso para reservar? -->
		<section id="section-5">
			<article class="container ">
				<div class="col-sm-offset-6 col-md-6 col-xs-12">
					<h2 id="h2-req">¿C&oacute;mo es el proceso <br>para reservar?</h2>
				</div>
				<br>
				<article class="col-md-12">
						<div class="col-sm-4 col-md-4 col-xs-12">
				      		<div class="group">
				      			<img src="img/reserva-1.png" alt="" class="img-requisitos">
				      		</div>
				      		<div class="letra-req">
				        		<p>Buscar y comparar cuidadores <br>cerca de tu ubicaci&oacute;n.</p>
				      		</div>
						</div>
						<div class="col-sm-4 col-md-4 col-xs-12">
				      		<div class="group">
				      			<img src="img/reserva-2.png" alt="" class="img-requisitos">
				      		</div>
				      		<div class="letra-req">
				        		<p>Reservas la estad&iacute;a <br> de tu mascota.</p>
				      		</div>
						</div>
						<div class="col-sm-4 col-md-4 col-xs-12">
				      		<div class="group">
					      		<img src="img/reserva-3.png" alt="" class="img-requisitos">
					      	</div>
				      		<div class="letra-req">
				        		<p>Tu mascota regresa FELIZ</p>
				      		</div>
						</div>
				</article>
			</article>
		</section>
	<!-- ya tenemos presencia en varios paises -->
		<section id="section-7">
			<article class="container">
				<div>
					<h2 class="h2-foot">Ya tenemos presencia en <br>varios pa&iacute;ses</h2>
				</div>
				<div class="col-sm-12 col-xs-12">
					<p>
						<h3 class="h3-foot">¡En Kmimos, llegan como hu&eacute;sped <br>y consiguen a un nuevo amigo!</h3>
					</p>
				</div>
			<article  class="">
				<div class="col-sm-12" style="margin-top: -5%;">
					<div class="col-sm-12 col-xs-4">
						<img src="img/mexico.png" alt="Mexico" class="img-pais mx">
					</div>
					<div class="col-sm-12 col-xs-4">
						<img src="img/peru.png" alt="Peru" class="img-pais pr">
					</div>
					<div class="col-sm-12 col-xs-4">
						<img src="img/colombia.png" alt="Colombia" class="img-pais co">
					</div>
				</div>
			</article>
			</article>
				<a href="https://www.kmimos.com.mx/?"  ><img src="img/quiero-conocer-cuidador.png" alt="quiero-ser-cuidador" class="img-responsive" ></a>
			<br><br><br>
		</section>
		<footer>
			<header>
	 			<img src="img/LogoKmimos.png" class="logo">
			</header>
		</footer>	
		
	<script
	  src="https://code.jquery.com/jquery-2.2.4.min.js"
	  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
	  crossorigin="anonymous"></script>
	<!-- <script src="js/wow.js  "></script> -->
    <!-- <script src="js/main.js"></script> -->
    <script>
    	$(document).ready(function(){
		    $("#close").click(function(){
		        $('#cerrar').hide();
		        $('footer').addClass("logo-foot1");
		    });
		});
    </script>
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
	