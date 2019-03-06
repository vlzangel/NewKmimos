<?php include 'pre-header.php'; ?><!doctype html>
<html lang="es-ES" class="no-js"><head>
	<title>Mucho mejor que una pensión para perros - Cuidadores Certificados - kmimos.com.mx</title>
	<meta charset="UTF-8"><?php 
	$HTML = '';	
	if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
		header('X-UA-Compatible: IE=edge,chrome=1');
	}
	if ( is_page() ){
		global $post;
		$descripcion = get_post_meta($post->ID, 'kmimos_descripcion', true);
		if( $descripcion != ""){
			$HTML .= "<meta name='description' content='{$descripcion}'>";
		}else{
			$HTML .= "<meta name='description' content='Por segunda vez dejé a mi perro con Gabriel y su familia, estoy muy agradecido y encantado con el cuidado que le ha dado a mi mascota'>";
		}
	}else{
		$HTML .= "<meta name='description' content='Por segunda vez dejé a mi perro con Gabriel y su familia, estoy muy agradecido y encantado con el cuidado que le ha dado a mi mascota'>";
	}

	$HTML .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';
	$HTML .= ' <script src="'.getTema().'/js/jquery.min.js"></script>'.
		'<style>'.
			'.modal p a { font-family: arial, sans-serif !important; color: #333 !important; } '.
			'pre { position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 99999999; display: none !important; }'.
		'</style>';

/*
	$HTML .= "
		<!-- Google Tag Manager -->
		<script>
			(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-5SG9NM');
		</script>
		<!-- End Google Tag Manager -->
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', 'UA-56422840-1');
		</script>
		<!-- Facebook Pixel Code --> <script> !function(f,b,e,v,n,t,s) {if(f.fbq)return;n=f.fbq=function(){n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)}; if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0'; n.queue=[];t=b.createElement(e);t.async=!0; t.src=v;s=b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s)}(window,document,'script', 'https://connect.facebook.net/en_US/fbevents.js');  fbq('init', '105485829783897');  fbq('track', 'PageView'); </script> <noscript>  <img height='1' width='1' src='https://www.facebook.com/tr?id=105485829783897&ev=PageView&noscript=1'/> </noscript>
		<!-- End Facebook Pixel Code -->
	";

	$HTML .= "
		<script>
	        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	        ga('create', 'UA-56422840-1', 'auto');
	        ga('send', 'pageview');
        </script>
	";
*/
	echo comprimir( $HTML );

    if( is_user_logged_in() && $_SESSION["save_uso_banner"] ){
		$current_user = wp_get_current_user();
	    $user_id = $current_user->ID;
	    set_uso_banner([ "user_id" => $user_id ]);
    	unset($_SESSION["save_uso_banner"]);
    }

	/* Solo para iOS - [ $is_iOS en pre-header.php ] */
	$class_iOS = ''; if( $is_iOS ){ $class_iOS = 'iOS'; wp_enqueue_style( 'modal_iOS', getTema()."/css/modal-iOS.css", array(), "1.0.0" ); }

	include_once("funciones.php");

	$pages_new = [
		"busqueda",
		"petsitters",
        "paseos",
        "testimonios",
        "product",
        "page-perfil.php",
        "page-recargar.php",
        "page-registro-cuidador.php",
        "page-personalizada.php",
        "page-home_2.php",

    ];

    $plantilla = get_post_meta($post->ID, '_wp_page_template', true);

    /* Recordatorio a cuidador para completar datos bancarios */
	wp_enqueue_style( 'style', getTema()."/css/popup-datos-bancarios.css", array(), "1.0.0" );

    if( true ){

		if( is_front_page() || in_array($post->post_name, $pages_new) || in_array($post->post_type, $pages_new)  || in_array($plantilla, $pages_new) ){
			include __DIR__.'/NEW/header.php';
		}else{
			
			wp_enqueue_style( 'style', getTema()."/style.css", array(), "1.0.0" );
			wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');
			wp_enqueue_style( 'jquery.bxslider', getTema()."/css/jquery.bxslider.css", array(), "1.0.0" );
			wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), "1.0.0" );
			wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );

			if( in_array("reservar", explode("/", $_SERVER["REQUEST_URI"])) ){
				wp_enqueue_style( 'kmimos_style', getTema()."/css/reserva_proceso.css", array(), "1.0.0" );
			}else{
				if( in_array("finalizar", explode("/", $_SERVER["REQUEST_URI"])) ){
					wp_enqueue_style( 'kmimos_style', getTema()."/css/finalizar_style.css", array(), "1.0.0" );
				}else{
					wp_enqueue_style( 'kmimos_style', getTema()."/css/kmimos_style.css", array(), "1.0.0" );
				}
			}

			wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );
			wp_enqueue_style( 'generales_css', getTema()."/css/generales.css", array(), "1.0.0" );
			wp_enqueue_style( 'generales_responsive_css', getTema()."/css/responsive/generales_responsive.css", array(), "1.0.0" );

			$wlabel = add_wlabel();
			wp_head();

			global $post;
			$reserrvacion_page = "";
			if( 
				$post->post_name == 'reservar' 			||
				$post->post_name == 'finalizar' 		
			){
				$reserrvacion_page = "page-reservation";
			}

			$HTML = '
				<script type="text/javascript"> 
					var pines = [], HOME = "'.getTema().'/"; 
					var RAIZ = "'.get_home_url().'/"; 
					var AVATAR = "";
		            var wlabel = "'.$wlabel.'";
				</script>
			</head>
			<body class="'.join( ' ', get_body_class( $class ) ).' '.$reserrvacion_page.' '.$class_iOS.'" onLoad="menu();">

		<!-- Load Facebook SDK for JavaScript -->
		<div id="fb-root"></div>
		<script>
			(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "https://connect.facebook.net/es_ES/sdk/xfbml.customerchat.js";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, \'script\', \'facebook-jssdk\'));
			
			window.fbAsyncInit = function() {
				FB.init({
					appId            : \'264829233920818\',
					autoLogAppEvents : true,
					xfbml            : true,
					version          : \'v3.2\'
				});
			};
		</script>
		<!-- Your customer chat code -->
		<div class="fb-customerchat"
			attribution=setup_tool
			page_id="361445711358167"
			theme_color="#00d2c6"
			logged_in_greeting="Hola! Cómo puedo ayudarte?"
			logged_out_greeting="Hola! Cómo puedo ayudarte?">
		</div>

				<script> 
					var RUTA_IMGS = "'.get_home_url().'/imgs"; 

			        var hizo_click = [];

			        hizo_click["paseos"] = false;
			        hizo_click["guarderia"] = false;
			        hizo_click["entrenamiento"] = false;

			        function evento_google(evento){
			        	if( wlabel == "petco" ){
				        	switch ( evento ) {

								case "boton_nueva_reserva_tarjeta":
									ga("send", "event", "wlabel", "click", "traking_code_boton_nueva_reserva_tarjeta", "1");
								break;
								
								case "boton_nueva_reserva_tienda":
									ga("send", "event", "wlabel", "click", "traking_code_boton_nueva_reserva_tienda", "1");
								break;

								case "paseos":
									if( !hizo_click["paseos"] ){
										ga("send", "event", "wlabel", "click", "traking_code_boton_paseos", "1");
										hizo_click["paseos"] = true;
									}
								break;

								case "guarderia":
									if( !hizo_click["guarderia"] ){
										ga("send", "event", "wlabel", "click", "traking_code_boton_guarderia", "1");
										hizo_click["guarderia"] = true;
									}
								break;
								
								case "entrenamiento":
									if( !hizo_click["entrenamiento"] ){
										ga("send", "event", "wlabel", "click", "traking_code_boton_entrenamiento", "1");
										hizo_click["entrenamiento"] = true;
									}
								break;

								case "conocer_cuidador":
									ga("send", "event", "wlabel", "click", "traking_code_conocer_cuidador", "1");
								break;

								case "nuevo_registro_cliente":
									ga("send", "event", "wlabel", "click", "traking_code_nuevo_registro_cliente", "1");
								break;

								case "nuevo_registro_cuidador":
									ga("send", "event", "wlabel", "click", "traking_code_nuevo_registro_cuidador", "1");
								break;

								case "nueva_reserva_tienda_completado":
									ga("send", "event", "wlabel", "click", "traking_code_nueva_reserva_tienda_completado", "1");
								break;

								case "nueva_reserva_tarjeta_completado":
									ga("send", "event", "wlabel", "click", "traking_code_nueva_reserva_tarjeta_completado", "1");
								break;

								case "nueva_reserva_descuento_saldo":
									ga("send", "event", "wlabel", "click", "traking_code_nueva_reserva_descuento_saldo", "1");
								break;

								case "llego_al_home":
									ga("send", "event", "wlabel", "click", "traking_code_llego_al_home", "1");
								break;

								case "dejo_el_correo":
									ga("send", "event", "wlabel", "click", "traking_code_dejo_el_correo", "1");
								break;
							}
						}
			        }

			        function evento_fbq(tipo, evento){
			        	if( wlabel == "petco" ){ 
			        		fbq(tipo, evento); 
			        	}
			        }

			        function evento_fbq_2(tipo, evento){
			        	fbq(tipo, evento); 
			        }



		 			function evento_fbq_kmimos(evento){

			        	switch ( evento ) {
							case "paseos":
								evento = "km_tracking_code_boton_paseos";
							break;
							case "buscar_home":
								evento = "km_tracking_code_boton_home_buscar";
							break;
							case "conocer_busqueda":
								evento = "km_tracking_code_boton_conocer_busqueda";
							break;
							case "reservar_busqueda":
								evento = "km_tracking_code_boton_reservar_busqueda";
							break;
							case "conocer_ficha":
								evento = "km_tracking_code_boton_conocer_ficha";
							break;
							case "reservar_ficha":
								evento = "km_tracking_code_boton_reservar_ficha";
							break;
							case "tienda":
								evento = "km_tracking_code_boton_tienda";
							break;
							case "tarjeta":
								evento = "km_tracking_code_boton_tarjeta";
							break;
							case "confirmacion_reserva":
								evento = "km_tracking_code_confirmacion_reserva";
							break;
							case "banner_home":
								evento = "km_tracking_code_banner_home";
							break;
						}

			        	fbq("track", evento); 
			        }

			        function evento_google_kmimos(evento){
			        	switch ( evento ) {
			        		case "CPF_Compartir_pdf":
								ga("send", "event", "wlabel", "click", "km_tracking_cpf_compartir_pdf", "1");
								break;
							case "CPF_Registro":
								ga("send", "event", "wlabel", "click", "km_tracking_cpf_registro", "1");
								break;
							case "CPF_Compartir_twitter":
								ga("send", "event", "wlabel", "click", "km_tracking_cpf_compartir_twitter", "1");
								break;
							case "CPF_Compartir_facebook":
								ga("send", "event", "wlabel", "click", "km_tracking_cpf_compartir_facebook", "1");
								break;
							case "CPF_Compartir_whatsapp":
								ga("send", "event", "wlabel", "click", "km_tracking_cpf_compartir_whatsapp", "1");
								break;
							case "paseos":
								ga("send", "event", "wlabel", "click", "km_tracking_code_boton_paseos", "1");
							break;
							case "buscar_home":
								ga("send", "event", "wlabel", "click", "km_tracking_code_boton_home_buscar", "1");
							break;
							case "conocer_busqueda":
								ga("send", "event", "wlabel", "click", "km_tracking_code_boton_conocer_busqueda", "1");
							break;
							case "reservar_busqueda":
								ga("send", "event", "wlabel", "click", "km_tracking_code_boton_reservar_busqueda", "1");
							break;
							case "conocer_ficha":
								ga("send", "event", "wlabel", "click", "km_tracking_code_boton_conocer_ficha", "1");
							break;
							case "reservar_ficha":
								ga("send", "event", "wlabel", "click", "km_tracking_code_boton_reservar_ficha", "1");
							break;
							case "tienda":
								ga("send", "event", "wlabel", "click", "km_tracking_code_boton_tienda", "1");
							break;
							case "tarjeta":
								ga("send", "event", "wlabel", "click", "km_tracking_code_boton_tarjeta", "1");
							break;
							case "confirmacion_reserva":
								ga("send", "event", "wlabel", "click", "km_tracking_code_confirmacion_reserva", "1");
							break;
							case "banner_home":
								ga("send", "event", "wlabel", "click", "km_tracking_code_banner_home", "1");
							break;
						}
			        }
				</script>
			';

		 	$_user_wlabel = false;
		 	if( $_SESSION["wlabel"] == "petco" ){
		 		$_user_wlabel = true;
		 	}
		 	$data = $wpdb->get_var("SELECT count(*) FROM wp_usermeta WHERE user_id = '{$user_id}' AND ( meta_key = '_wlabel' OR meta_key = 'user_referred' ) AND meta_value LIKE '%Petco%' ");
		 	if( $data > 0 ){
		 		$_user_wlabel = true;
		 	}

			if( $_user_wlabel ){
				$HTML .= '
					<!-- Adform Tracking Code BEGIN -->
					<script type="text/javascript">
					    window._adftrack = Array.isArray(window._adftrack) ? window._adftrack : (window._adftrack ? [window._adftrack] : []);
					    window._adftrack.push({
					        pm: 1453019,
					        divider: encodeURIComponent("|"),
					        pagename: encodeURIComponent("MX_Kmimos_AllPages_180907")
					    });
					    (function () { var s = document.createElement("script"); s.type = "text/javascript"; s.async = true; s.src = "https://a2.adform.net/serving/scripts/trackpoint/async/"; var x = document.getElementsByTagName("script")[0]; x.parentNode.insertBefore(s, x); })();
					</script>
					<noscript>
					    <p style="margin:0;padding:0;border:0;">
					        <img src="https://a2.adform.net/Serving/TrackPoint/?pm=1453019&ADFPageName=MX_Kmimos_AllPages_180907&ADFdivider=|" width="1" height="1" alt="" />
					    </p>
					</noscript>
					<!-- Adform Tracking Code END -->
				';
			}

			$MENU = get_menu_header(true);

			if( !isset($MENU["head"]) ){
				$menus_normal = '
					<li><a class="modal_show" style="padding-right: 15px" href="javascript:;" data-target="#popup-iniciar-sesion">INICIAR SESIÓN</a></li>
					<li><a class="modal_show" style="padding-left: 15px; border-left: 1px solid white;" data-target="#popup-registrarte">REGISTRARME</a></li>
					<!-- <li style="border-top:1px solid #e8e8e8;"><a href="'.get_home_url().'/ayuda" class="pd-tb11 menu-link"><i class="fa fa-question-circle-o" aria-hidden="true"></i> Ayuda</a></li> -->
				';
			}else{
				$menus_normal =  $MENU["body"].$MENU["footer"];
			}

			// Avatar default
			$avatar = getTema().'/images/new/km-navbar-mobile.svg';
			$avatar_circle = '';
			if( !is_user_logged_in() ){
				
			}else{
				$current_user = wp_get_current_user();
				$user_id = $current_user->ID;
				$avatar = kmimos_get_foto($user_id);
				$salir = wp_logout_url( home_url() );
				$HTML .= '<script> var AVATAR = "'.$avatar.'"; </script>';
				$avatar_circle = 'img-circle';
				
			}

			if($avatar== get_home_url()."/wp-content/themes/kmimos/images/noimg.png"){
				$avatar=get_home_url()."/wp-content/themes/kmimos/images/image.png";
			} 	

			global $no_top_menu;
			if( !isset($no_top_menu) ){
				if( !is_user_logged_in() ){
					$HTML .= '	
						<nav class="navbar navbar-fixed-top bg-transparent">
						<div class="container">
							<div class="navbar-header ">
								<button type="button" class="navbar-toggle sin_logear" id="ver_menu">
									<img src="'.$avatar.'" width="40px" height="40px" class="'.$avatar_circle.'">
								</button>
								<a class="navbar-brand" href="'.get_home_url().'">
									<img data-wlabel="logo" id="logo-white" src="'.getTema().'/images/new/km-logos/km-logo'.$wlabel.'.png" height="60px">
									<img data-wlabel="logo" id="logo-black" src="'.getTema().'/images/new/km-logos/km-logo-negro'.$wlabel.'.png" style="display:none;" height="60px">
								</a>
							</div>
							<ul class="hidden-xs nav-login">
								<li><a id="login" href="#" data-target="#popup-iniciar-sesion" style="padding-right: 15px" role="button" data-toggle="modal">INICIAR SESIÓN</a></li>
								<li><a href="#" style="padding-left: 15px; border-left: 1px solid white;" role="button" data-target="#popup-registrarte">REGISTRARME</a></li>
							</ul>	
							<ul class="nav navbar-nav navbar-right">
								<li><a href="'.get_home_url().'/busqueda" class="hidden-xs km-nav-link">BUSCAR CUIDADOR</a></li>
								<li><a href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros" class="hidden-xs km-btn-primary">QUIERO SER CUIDADOR</a></li>
					    	</ul>
							<div id="menu_movil" class="hidden-sm hidden-md hidden-lg">

								<div class="menu_movil_interno">
									<div class="cerrar_menu_movil clearfix initial_menu_movil">
										<button type="button" class="menu_movil_close" aria-hidden="true">×</button>
									</div>
									<div class="clearfix container_menu">
										<form class="barra_buscar_movil" method="POST" action="'.get_home_url().'/wp-content/themes/kmimos/procesos/busqueda/buscar.php">
											<i class="fa fa-search"></i>
											<input type="hidden" name="redireccionar" value="1" />
											<input type="text" id="txt_buscar" placeholder="Buscar cuidador" name="nombre"  />
										</form>

										<ul class="nav navbar-nav">
											<li><a href="javascript:;" data-target="#popup-iniciar-sesion" class="km-nav-link" role="button">Iniciar sesión</a></li>
											<li><a href="javascript:;" data-target="#popup-registrarte" class="km-nav-link" role="button" >Registrarme</a></li>
											<li><a href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros" class="km-nav-link">Quiero ser cuidador</a></li>
											<!-- <li style="border-top:1px solid #e8e8e8;"><a href="'.get_home_url().'/ayuda" class="pd-tb11 menu-link"><i class="fa fa-question-circle-o" aria-hidden="true"></i> Ayuda</a></li> -->
								    	</ul>
								    </div>
							    </div>
						    </div>
						</div>
					</nav>
					';
				}else{
					$HTML .= '	
						<nav class="navbar navbar-fixed-top bg-transparent">
							<div class="container">
								<button type="button" class="navbar-toggle" id="ver_menu">
									<img src="'.$avatar.'" width="40px" height="40px" class="'.$avatar_circle.'">
								</button>
								<div class="navbar-header ">
									<a class="navbar-brand" href="'.get_home_url().'">
										<img data-wlabel="logo" id="logo-white" src="'.getTema().'/images/new/km-logos/km-logo'.$wlabel.'.png" height="60px">
										<img data-wlabel="logo" id="logo-black" src="'.getTema().'/images/new/km-logos/km-logo-negro'.$wlabel.'.png" style="display:none;" height="60px">
									</a>
								</div>
								<ul class="nav navbar-nav navbar-right hidden-xs">
									<li class="dropdown" data-obj="avatar">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
											<img src="'.$avatar.'" width="60px" height="60px" class="img-circle"> 
										</a>
										<ul class="dropdown-menu"  style="background: #fff;">
											'.$menus_normal.'
										</ul>
						        	</li>
						    	</ul>

								<div id="menu_movil" class="hidden-sm hidden-md hidden-lg">

									<div class="menu_movil_interno">
										<div class="cerrar_menu_movil initial_menu_movil clearfix">
											<button type="button" class="menu_movil_close" aria-hidden="true">×</button>
										</div>

										<div class="clearfix container_menu">
											<form class="barra_buscar_movil" method="POST" action="'.get_home_url().'/wp-content/themes/kmimos/procesos/busqueda/buscar.php">
												<i class="fa fa-search"></i>
												<input type="hidden" name="USER_ID" value="'.$user_id.'" />
												<input type="hidden" name="redireccionar" value="1" />
												<input type="text" id="txt_buscar" placeholder="Buscar cuidador" name="nombre"  />
											</form>
											<ul class="nav navbar-nav">
												'.$menus_normal.'
									    	</ul>
									    </div>
								    </div>
						    	</div>
						</nav>
					';
				}
			}
			echo comprimir_styles($HTML);
		}
	}