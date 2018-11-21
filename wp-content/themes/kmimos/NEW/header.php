<?php
	wp_enqueue_style( 'kmimos_style', getTema()."/css/kmimos_style.css", array(), "1.0.0" );
	wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'fontello', getTema()."/css/fontello.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'old_generales_css', getTema()."/css/generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'old_generales_responsive_css', getTema()."/css/responsive/generales_responsive.css", array(), "1.0.0" );

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');

	wp_enqueue_style( 'generales_css', get_recurso("css")."generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'generales_responsive_css', get_recurso("css")."responsive/generales.css", array(), "1.0.0" );

	wp_head();

	$HTML = '';

	// $HTML .= '</head><body class="'.join( ' ', get_body_class( $class ) ).' '.$reserrvacion_page.' '.$class_iOS.'">';
	// echo comprimir($HTML);

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
		$btn_quiero = '<a href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros" id="" class="boton boton_morado"> <img src="'.get_recurso("img").'HOME/PNG/Ser_cuidador.png" /> Quiero ser Cuidador</a>';
		wp_enqueue_script('modales', getTema()."/js/registro_cliente.js", array("jquery"), '1.0.0');
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

	$menu_str = "";
	if( !is_user_logged_in() ){
		$menu_str = '
			<a href="#" data-target="#popup-iniciar-sesion" role="button" data-toggle="modal"> Iniciar Sesión </a><span style="color: #000 !important;">|</span>
			<a href="#" role="button" data-target="#popup-registrarte"> Registrarme </a>
		';
		$menu_movil_str = '
			<a href="#" role="button" data-target="#popup-registrarte"> Registrarme </a>
			<img class="icono" src="'.get_recurso("img").'HOME/SVG/Perfil.svg" id="login" href="#" data-target="#popup-iniciar-sesion" role="button" data-toggle="modal" />
			<img class="icono" src="'.get_recurso("img").'HOME/RESPONSIVE/PNG/Menu.png" id="ver_menu" />
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
						<li><a href="#" data-target="#popup-iniciar-sesion" class="km-nav-link" role="button" data-toggle="modal">Iniciar sesión</a></li>
						<li><a href="#" data-target="#popup-registrarte" class="km-nav-link" role="button" >Registrarme</a></li>
						<li><a href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros" class="km-nav-link">Quiero ser cuidador</a></li>
						<!-- <li style="border-top:1px solid #e8e8e8;"><a href="'.get_home_url().'/ayuda" class="pd-tb11 menu-link"><i class="fa fa-question-circle-o" aria-hidden="true"></i> Ayuda</a></li> -->
			    	</ul>
			    </div>
		    </div>
		';
	}else{
		$menu_str = '
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
		';
		$menu_movil_str = '
			<img class="icono" src="'.get_recurso("img").'GENERALES/RESPONSIVE/PNG/Menu.png" id="ver_menu" />
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
		';
	}

	$pages_white = [
		"busqueda"
	];

	$clase_white = "";
	if( in_array($post->post_name, $pages_white) ){
		$clase_white = "nav_white";
	}

	$wlabel = add_wlabel();

	$HTML .= '
    	<script>
    		var HOME = "'.getTema().'/";
    		var RAIZ = "'.get_home_url().'/";
    		var RUTA_IMGS = "'.get_home_url().'/imgs/";
    		var wlabel = "'.$wlabel.'";
    	</script>
	';

	$HTML .= '
	</head>
	<body class="' . join(' ', get_body_class($class)) . ' ' . $reserrvacion_page . '"><script> 
			var RUTA_IMGS = "'.get_home_url().'/imgs"; 

	        var hizo_click = [];

	        hizo_click["paseos"] = false;
	        hizo_click["guarderia"] = false;
	        hizo_click["entrenamiento"] = false;

 			function evento_fbq(tipo, evento){
	        	if( wlabel == "petco" ){ 
	        		fbq(tipo, evento); 
	        	}
	        }

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
		</script>
	';

	if( $_SESSION["wlabel"] == "petco" ){
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

	$HTML .= '
		<nav class="'.$clase_white.'">

			<div class="solo_pc">
				<table class="nav_table">
					<tr>
						<td class="nav_left">
							<a href="'.get_home_url().'">
								<img class="logo" src="'.get_recurso("img").'HOME/PNG/logo.png" />
								<img class="logo logo_negro" src="'.get_recurso("img").'HOME/PNG/logo-negro.png" />
							</a>
							<a href="'.get_home_url().'#buscar" id="" onclick="ancla_form()" class="boton">
								<img class="lupa" src="'.get_recurso("img").'HOME/PNG/Buscar.png" /> 
								<img class="lupa_negra" src="'.get_recurso("img").'HOME/PNG/Buscar_negro.png" /> 
								Buscar Cuidador
							</a>
							'.$btn_quiero.'
						</td>
						<td class="nav_right">
							'.$menu_str.'
						</td>
					</tr>
				</table>
			</div>

			<div class="solo_movil">
				<table class="nav_table">
					<tr>
						<td class="nav_left">
							<a href="'.get_home_url().'">
								<img class="logo" src="'.get_recurso("img").'HOME/PNG/logo-verde.png" />
								<img class="logo logo_negro" src="'.get_recurso("img").'HOME/PNG/logo-verde.png" />
							</a>
						</td>
						<td class="nav_right">
							'.$menu_movil_str.'
						</td>
					</tr>
				</table>
			</div>
		</nav>
	';

	echo comprimir($HTML);
?>