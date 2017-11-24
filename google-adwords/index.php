<?php include_once(dirname(__DIR__).'/wp-load.php'); ?>
<DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Kmimos | Guardería</title>

  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css?family=Lato:700,900" rel="stylesheet">
  <link rel="stylesheet" href="css/kmimos.css">
  <style type="text/css">
        #PageSubscribe{position:relative; max-width: 700px;  margin: 0 auto;  padding: 25px;  top: 75px; border-radius: 20px;  background: #ba2287;  overflow: hidden;}
        #PageSubscribe .exit{float: right; cursor: pointer;}
        #PageSubscribe .section{ width: 47%; padding: 10px; float: left; font-size: 17px; text-align: left;}
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
                '<div id="msg-register" class="span-email-hide">El email no es valido</div>'+
                '<div id="msg-error" class="span-email-hide">Este correo ya estaba registrado. Por favor intentar con uno nuevo</div>';
                
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
                    var datos = {'source': 'lan-cl-med', 'email': mail}
                    var result = getGlobalData("../../../landing/newsletter.php?source=guarderia-perro&email="+mail,'GET', null);
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
                        var datos = {'campo':'cm-vydldy-vydldy',
                                     'email': mail,
                                     'lista': 'http://kmimos.intaface.com/t/j/s/vydldy/'}
                        var resp = getGlobalData('https://www.kmimos.com.mx/landing-volaris/suscribir_lista.php','POST', datos);
                    }else if (result == 2){
                        jQuery("#msg-vacio").removeClass('span-email-show');
                        jQuery('#msg-error').removeClass('span-email-show');
                        jQuery('#msg').removeClass('span-email-show');
                        jQuery('#msg-register').addClass('span-email-show');
                        jQuery('#msg-register').removeClass('span-email-hide');
                        jQuery("#msg-vacio").addClass('span-email-hide');
                        jQuery('#msg-error').addClass('span-email-hide');
                        jQuery('#msg').addClass('span-email-hide');
                    }else if (result == 3){
                        jQuery("#msg-vacio").removeClass('span-email-show');
                        jQuery('#msg-error').removeClass('span-email-hide');
                        jQuery('#msg-register').removeClass('span-email-show');
                        jQuery("#msg-vacio").addClass('span-email-hide');
                        jQuery('#msg-error').addClass('span-email-show');
                        jQuery('#msg-register').addClass('span-email-hide');
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
                }, 7400);
            });

            function form_subscribe(element){
                var subscribe = jQuery(element).closest('#subscribe');
                var message = subscribe.find('#message');
                var email = subscribe.find('input[name="mail"]').val();
                var url = '../landing/newsletter.php?source=guarderia-perro&email='+email;
                if(email!=''){
                    jQuery.post(url, jQuery(element).serialize(),function(data){
                        //console.log(data);
                        var textmessage="Error al guardar los datos";

                        if( data == 1){
                            textmessage="Datos guardados";
                            var datos = {'campo':'cm-vydldy-vydldy',
                                         'email': email,
                                         'lista': 'http://kmimos.intaface.com/t/j/s/vydldy/'}
                            var resp = getGlobalData('https://www.kmimos.com.mx/landing-volaris/suscribir_lista.php','POST', datos);
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
          <!-- Google Tag Manager -->
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-5SG9NM');
        </script>
    <!-- End Google Tag Manager -->
  <?php wp_head(); ?>
</head>

<body>
  <!-- CABECERA -->
  <div class="container-fluid">
    <section id="section-1">
      <div class="cabecera">
        <img src="img/Kmimos-logo.png" alt="Kmimos Logo" class="cabecera-img">
      </div>
    </section>

  <!-- HEADER -->
    <section id="section-2">
      <div class="header"></div>
    </section>

  <!-- HEADER TEXT-->
    <section id="section-3">
      <div class="header-text">
        <center>
          <p class="simple-black hidden-xs">Con Kmimos, tu mascota se queda durmiendo<br> dentro de la casa de un Cuidador Certificado</p>
          <p class="simple-black visible-xs">Con Kmimos, tu mascota se<br> queda durmiendo dentro<br> de la casa de un<br> Cuidador Certificado</p>
          <h1 class="simple-purple">Feliz, libre de jaulas y encierros</h1>
          <a href="https://www.kmimos.com.mx" target="_blank"><p class="button">Si, quiero buscar un cuidador</p></a>
        </center>
      </div>
    </section>

  <!-- IMAGE GRID -->
    <section id="section-4">
      <div class="top-row">
        <div class="info">
          <center>
            <img src="img/info-img-1.png" alt="Info Imagen" class="info-img-1">
            <p class="simple-black info-text hidden-xs">Tu perro será un huesped<br> dentro de la casa del Cuidador<br> Certificado que escojas</p>
            <p class="simple-black info-text visible-xs">Tu perro será un huesped<br> dentro de la casa del<br> Cuidador Certificado<br> que escojas</p>
          </center>
        </div>
        <div class="row-border visible-xs visible-sm visible-md">
          <img src="img/cabecera-fondo.png" alt="separador" class="separador">
        </div>
        <div class="info">
          <center>
            <img src="img/info-img-2.png" alt="Info Imagen" class="info-img-2">
            <p class="simple-black info-text hidden-xs">¡Tu amigo estará protegido por una<br> cobertura de servicios veterinarios<br> durante su estadia!</p>
            <p class="simple-black info-text visible-xs">¡Tu amigo estará protegido<br> por una cobertura de<br> servicios veterinarios<br> durante su estadia!</p>
          </center>
        </div>
      </div>
      <div class="row-border">
        <img src="img/cabecera-fondo.png" alt="separador" class="separador">
      </div>
      <div class="bot-row">
        <div class="info">
          <center>
            <img src="img/info-img-3.png" alt="Info Imagen" class="info-img-3">
            <p class="simple-black info-text hidden-xs">Dormirá como un rey en salas, sofás<br> y a veces ¡hasta en la cama del<br> Cuidador!</p>
            <p class="simple-black info-text visible-xs">Dormirá como un rey en<br> salas, sofás y a veces<br> ¡hasta en la cama del<br> Cuidador!</p>
          </center>
        </div>
        <div class="row-border visible-xs visible-sm visible-md">
          <img src="img/cabecera-fondo.png" alt="separador" class="separador">
        </div>
        <div class="info">
          <center>
            <img src="img/info-img-4.png" alt="Info Imagen" class="info-img-4">
            <p class="simple-black info-text hidden-xs">El tamaño de tu perro y el cuidador<br> que elijas determinará el costo del<br> servicio. Ejemplo: para perro chico la<br> estadia costará entre $100 a<br> $200 MXP por noche</p>
            <p class="simple-black info-text visible-xs">El tamaño de tu perro y el<br> cuidador que elijas determi-<br>nará el costo del servicio<br> Ejemplo: para perro chico la<br> estadia costará entre $100<br> a $200 MXP por noche</p>
          </center>
        </div>
      </div>
    </section>

    <!-- PROCESO DE RESERVA SEPARADOR-->
    <section id="section-5">
      <div class="reserva-separador">
        <center>
          <p class="simple-white separador-text hidden-xs">¿Cómo es el proceso para reservar?</p>
          <p class="simple-white separador-text visible-xs">¿Cómo es el proceso<br> para reservar?</p>
        </center>
      </div>
    </section>

    <!-- PROCESO DE RESERVA -->
    <section id="section-6">
      <div class="reserva">
        <div class="reserva-info">
          <img src="img/reserva-info-num-3.png" alt="Número Uno" class="reserva-img-num-1">
          <center>
            <img src="img/reserva-info-2.png" alt="Reserva Info Imagen" class="reserva-img-1">
            <p class="simple-black reserva-text">Busca y compara<br> cuidadores cerca<br> de tu ubicación</p>
          </center>
        </div>
      </div>
      <div class="reserva">
        <div class="reserva-info">
          <img src="img/reserva-info-num-1.png" alt="Número Uno" class="reserva-img-num-2">
          <center>
            <img src="img/reserva-info-1.png" alt="Reserva Info Imagen" class="reserva-img-2">
            <p class="simple-black reserva-text">Reserva la estadía<br> de tu mascota</p>
          </center>
        </div>
      </div>
      <div class="reserva">
        <div class="reserva-info">
          <img src="img/reserva-info-num-2.png" alt="Número Uno" class="reserva-img-num-3">
          <center>
            <img src="img/reserva-info-3.png" alt="Reserva Info Imagen" class="reserva-img-3">
            <p class="simple-black reserva-text">Tu mascota<br> regresa feliz</p>
          </center>
        </div>
      </div>
    </section>

    <!-- VIDEO KMIMOS -->
    <section id="section-7">
      <div class="embed-responsive">
        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/xjyAXaTzEhM?rel=0" frameborder="0" allowfullscreen></iframe>
      </div>
    </section>

    <!-- PROMESA -->
    <section id="section-8">
      <div class="promesa">
        <center>
          <p class="simple-white promesa-text hidden-xs hidden-sm">Si necesitas que cuiden a tu perro, esta es<br> nuestra promesa:</p>
          <p class="simple-white promesa-text visible-xs visible-sm">Si necesitas que cuiden<br> a tu perro, esta es<br> nuestra promesa:</p>
          <p class="bold-white promesa-bold hidden-xs hidden-sm">Con Kmimos tu perrhijo regresará Feliz</p>
          <p class="bold-white promesa-bold visible-xs visible-sm">Con Kmimos tu perrhijo<br> regresará Feliz</p>
          <a href="https://www.kmimos.com.mx" target="_blank" class="visible-xs visible-sm"><p class="button">Si, quiero buscar un cuidador</p></a>
        </center>
      </div>
    </section>

    <!-- FOOTER -->
    <section id="section-1">
      <div class="cabecera">
        <img src="img/Kmimos-logo.png" alt="Kmimos Logo" class="cabecera-img">
      </div>
    </section>
  </div>
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
