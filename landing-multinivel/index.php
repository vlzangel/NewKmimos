<?php include_once(dirname(__DIR__).'/wp-load.php'); ?>
<!DOCTYPE html>
<html> 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Kmimos | Multinivel</title>

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
                    var datos = {'source': 'landing-multinivel', 'email': mail}
                    var result = getGlobalData("../../../landing/newsletter.php?source=Multinivel&email="+mail,'GET', null);
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
<!-- CABECERA IMAGEN Y LOGO-->
   	<div class="container-fluid">
		<section id="section-1" class="col-xs-12">
			<div class="hidden-xs">
				<img src="img/Logo-black.png" alt="Logo-black" class="logo-negro">
			</div>
		</section>
	<!-- INVITA A TU COMUNIDAD	 -->
	<section id="section-2" class="color-azul">
   	 	<article class="col-xs-12 col-sm-12 col-md-12">
   	 		<h1 class="hidden-xs hidden-sm">Invita a tu comunidad a participar y gana dinero con Kmimos </h1>
   	 		<h1 class="hidden-lg hidden-md">Invita a tu comunidad a participar <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;y gana dinero con Kmimos </h1>
   	 	</article>
	</section>
	</div>

<!-- INSCRIBETE FOMULARIO -->
	<section id="section-3" class="col-sm-12 bg-color-morado">
		<div class="col-md-12 col-sm-12">
			<article class="title">
				¡Inscr&iacute;bete!
			</article>
		</div>
		<div class="container">
			<article class="title-label">
				<label class="hidden-xs">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Comparte el mensaje y gana 
					<br> dinero haciendo lo que más te gusta</label>
    			<label class="hidden-lg hidden-sm hidden-md">Comparte el mensaje y gana 
					<br> dinero haciendo lo que más te gusta</label>
			</article>
			
			<article class="centrar">
				<!-- <form method="POST" enctype="multipart/form-data"> -->
					<div class="col-sm-offset-4 col-sm-8">
						<input type="text" placeholder="Nombres y Apellidos" name="nomap" id="nomap" data-charset="alfxlf">
						<br>
						<span name="sp-name" class="span-name-hide">Ingrese su Nombre y su Apellido</span>
					</div>
					<div class="col-sm-offset-4 col-sm-8">
						<input type="email" placeholder="Correo electr&oacute;nico" name="email" id="email" data-charset="corxlfnum">
						<br>
						<span name="sp-email" class="span-email-hide">Ingrese su Email</span>
						<span name="sp-email-uso" class="span-email-hide">Este E-mail ya esta en uso</span>
						<span name="sp-name-inc" class="span-email-hide">Email incorrecto</span>
					</div>
					<div class="col-sm-offset-4 col-sm-8">
						<button class="btn-inscribirme" id="btn-inscribirme">Quiero Inscribirme</button>
					</div>
				<!-- </form> -->
				<br>
				<span id="guardando" class="text-center span-name-hide">¡Se registro exitosamente!</span>
				<span id="guardando-err" class="text-center span-name-hide">¡Problemas con el  registro!</span>
			</article>
		</div>
		<br>
	</section>
<!-- COMO HACERLOS -->
	<section id="section-4" class="col-xs-12">
   	 	<article class="col-xs-12 text-center">
			<h3>¿Cómo hacerlo?</h3>
		</article>
   	 	<article class="row lado">
   	 		<div class="col-xs-6 col-sm-6 col-md-6">
   	 			<img src="img/Icon-1.png" class="img-responsive">
   	 		</div>
   	 		<div class="col-xs-6 col-sm-6 col-md-6">
       	 		<h4 class="hidden-xs">1.&nbsp;&nbsp;&nbsp; Acumulas referidos</h4>
       	 		<h4 class="hidden-lg hidden-sm hidden-md text-center">1.&nbsp; Acumulas referidos</h4>
       	 		<p><br>Busca nuevos usuarios para los servicios de Kmimos.</p>
   	 		</div>
   	 	</article>
   	 	<article class="row lado">
   	 		<div class="col-xs-6 col-sm-6 col-md-6">
 	 			<img src="img/Icon-2.png" class="img-responsive baja-lg">
   	 		</div>
   	 		<div class="col-xs-6 col-sm-6 col-md-6">
   	 			<h4 class="hidden-xs">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Conviertes a tus referidos en embajadores de la filosof&iacute;a Kmimos</h4>
   	 			<h4 class="hidden-lg hidden-sm hidden-md">2.&nbsp;Conviertes a tus referidos en embajadores de la filosof&iacute;a Kmimos</h4>
   	 			<p><br>Como embajadores se encargar&aacute;n de masificar nuestra manera de cuidar <br> a las mascotas.</p>
   	 		</div>
   	 	</article>
	</section>
<!-- REFERIDOS -->
	<section id="section-5" class="col-xs-12">
   	 	<div class="row">
	   	 	<article class="col-md-12 col-xs-12">
				<h3 class="hidden-xs hidden-md hidden-sm">Cada referido directo te traer&aacute; ingresos en comisiones y cada referido de tus <br> referidos tambi&eacute;n</h3>
				<h3 class="hidden-lg">Cada referido directo te traer&aacute; ingresos en comisiones <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; y cada referido de tus referidos tambi&eacute;n</h3>
			</article>
			<div class="col-md-12 col-sm-12 sp">
				<article class="col-md-3 col-sm-3 move-lg">
		   	 		<div class="text-center">
		   	 			<img src="img/Icon-3.png" class="img-refe">
		   	 			<span>x5</span>
		   	 		</div>
		   	 		<div class="cuadrado">
		       	 		<p>Consolidate como el lider de tu manada</p>
		       	 		<span style="visibility: hidden;">xxxx</span>
		       	 		<span style="visibility: hidden;">xxxx</span>
		   	 		</div>
	   	 		</article>
		   	 	<article class="col-md-3 col-sm-3 move-lg">
		   	 		<div class="text-center">
		 	 			<img src="img/Icon-4.png" class="img-refe1">
		 	 			<span>x5</span>
		   	 		</div>
		   	 		<div class="cuadrado">
		   	 			<p>Consigue nuevos referidos y entr&eacute;nalos con la filosof&iacute;a Kmimos</p>
		   	 		</div>
		   	 	</article>
		   	 	<article class="col-md-3 col-sm-3 move-lg">
		   	 		<div class="text-center">
		 	 			<img src="img/Icon-5.png" class="img-refe2">
		 	 			<span>x5</span>
		   	 		</div>
		   	 		<div class="cuadrado">
		   	 			<p>Crea una comunidad de referidos</p>
		   	 		</div>
		   	 	</article>
		   	 	<article class="col-md-3 col-sm-3 move-lg">
		   	 		<div class="text-center">
		 	 			<img src="img/Icon-6.png" class="img-refe1">
		   	 		</div>
		   	 		<div class="cuadrado">
		   	 			<p>Recibe ingresos por cada nuevo referido de tu grupo</p>
		   	 		</div>
		   	 	</article>
			</div>
		</div>
	</section>
<!-- GRAFICA -->
	<section id="section-6">
		<article class="verde">
			<span class="circulo-verde"><img src="img/Icon-7.png" alt="Flecha" class="flecha"></span>
			<h1 class="hidden-xs">¡De igual manera por cada 5 referidos directos recibes un bono <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			y cada 5 referidos de tus referidos también!</h1>
    		<h1 class="hidden-lg hidden-md hidden-sm">¡De igual manera por cada 5 referidos directos recibes un bono
			y cada 5 referidos de tus referidos también!</h1>
		</article>
		<article class="bajando">
			<img src="img/Line-1.png" alt="Lineas" id="linea">
			<img src="img/Diagram-1.png" alt="Diagram-1" class="diagrama">
			<br><br><br>
			<img src="img/Line-1.png" alt="Lineas" id="linea">
		</article>
		<article>
			<p class="hidden-xs hidden-sm">Busca nuevos referidos, genera una comunidad y gana hasta $15.000 MXN* <br>mensuales sin sacrificios ni inversi&oacute;n propia</p>
			<p class="hidden-lg hidden-md">Busca nuevos referidos, genera una comunidad y gana hasta <br> $15.000 MXN* <br>mensuales sin sacrificios ni inversi&oacute;n propia</p>
			<p style="color: #c0c3b6; font-size: 13pt;">*El ingreso depender&aacute; de la cantidad y flujo de referidos</p>
		</article>
	</section>
<!-- INSCRIBETE -->
	<section id="section-7" class="col-sm-12 bg-color-morado">
		<div class="col-md-12 col-sm-12">
			<article>
				<h1>¡Inscr&iacute;bete ya y forma parte de nuestro equipo!</h1>
			</article>
		</div>
		<div class="container">
			<article class="">
				<a href="#section-3"><input type="submit" value="Inscr&iacute;bete aqui" class="btn-footer"></a>
			</article>
		</div>
		<br>
	</section>
<!-- FOOTER LOGO KMIMOS -->
	<section id="logo">
		<header>
			<img src="img/Logo-white.png" alt="logo-kmimos">
		</header>
	</section>

	<script
	  src="https://code.jquery.com/jquery-2.2.4.min.js"
	  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
	  crossorigin="anonymous"></script>
    <script src="js/wow.js  "></script>
    <script src="js/main.js?v=1.0.0"></script>
    <script>
    	
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
