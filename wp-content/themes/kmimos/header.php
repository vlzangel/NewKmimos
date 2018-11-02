<?php include 'pre-header.php'; ?><!doctype html>
<html lang="es-ES" class="no-js"><head>
	<title> <?php bloginfo('title'); ?> </title>
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
		}
	}

	$HTML .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';
	$HTML .= ' <script src="'.getTema().'/js/jquery.min.js"></script>';

	echo comprimir( $HTML );

	/* Solo para iOS - [ $is_iOS en pre-header.php ] */
	$class_iOS = '';
	if( $is_iOS ){
		$class_iOS = 'iOS';
		wp_enqueue_style( 'modal_iOS', getTema()."/css/modal-iOS.css", array(), "1.0.0" );
	}

	include_once("funciones.php");

	$pages_new = [
		"busqueda",
		"petsitters",
        "paseos",
    ];

    if( true ){

		if( is_front_page() || in_array($post->post_name, $pages_new) || in_array($post->post_type, $pages_new) ){
			include __DIR__.'/NEW/header.php';
		}else{
			
			wp_enqueue_style( 'style', getTema()."/style.css", array(), "1.0.0" );
			wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');
			wp_enqueue_style( 'jquery.bxslider', getTema()."/css/jquery.bxslider.css", array(), "1.0.0" );
			wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), "1.0.0" );
			wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
			wp_enqueue_style( 'kmimos_style', getTema()."/css/kmimos_style.css", array(), "1.0.0" );
			wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );
			wp_enqueue_style( 'generales_css', getTema()."/css/generales.css", array(), "1.0.0" );
			wp_enqueue_style( 'generales_responsive_css', getTema()."/css/responsive/generales_responsive.css", array(), "1.0.0" );

			wp_head();

			global $post;
			$reserrvacion_page = "";
			if( 
				$post->post_name == 'reservar' 			||
				$post->post_name == 'finalizar' 		
			){
				$reserrvacion_page = "page-reservation";
			}

			$wlabel = add_wlabel();
			$HTML .= '
				<script type="text/javascript"> 
					var pines = [], HOME = "'.getTema().'/"; 
					var RAIZ = "'.get_home_url().'/"; 
					var AVATAR = "";
		            var wlabel = "'.$wlabel.'";
				</script>
			</head>
			<body class="'.join( ' ', get_body_class( $class ) ).' '.$reserrvacion_page.' '.$class_iOS.'" onLoad="menu();">
				<script> 
					var RUTA_IMGS = "'.get_home_url().'/imgs"; 

					/*
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

								case "nueva_reserva_tienda":
									ga("send", "event", "wlabel", "click", "traking_code_nueva_reserva_tienda", "1");
								break;

								case "nueva_reserva_tarjeta":
									ga("send", "event", "wlabel", "click", "traking_code_nueva_reserva_tarjeta", "1");
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
			        */
				</script>
			';

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
				include_once('partes/modal_login.php');
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

			if( !is_user_logged_in() ){
				include_once('partes/modal_register.php');
			}
			
			echo comprimir_styles($HTML);

		}
	}