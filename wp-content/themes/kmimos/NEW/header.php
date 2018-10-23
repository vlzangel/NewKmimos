<?php
	wp_enqueue_style( 'kmimos_style', getTema()."/css/kmimos_style.css", array(), "1.0.0" );
	wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'fontello', getTema()."/css/fontello.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'old_generales_css', getTema()."/css/generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'old_generales_responsive_css', getTema()."/css/responsive/generales_responsive.css", array(), "1.0.0" );

	wp_enqueue_style( 'generales_css', get_recurso("css")."generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'generales_responsive_css', get_recurso("css")."responsive/generales.css", array(), "1.0.0" );

	wp_head();

	$HTML .= '</head><body class="'.join( ' ', get_body_class( $class ) ).' '.$reserrvacion_page.' '.$class_iOS.'">';
	echo comprimir($HTML);

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
		include_once(dirname(__DIR__).'/partes/modal_login.php');
		include_once(dirname(__DIR__).'/partes/modal_register.php');
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
			<img class="icono" src="'.get_recurso("img").'HOME/RESPONSIVE/PNG/Perfil.png" id="login" href="#" data-target="#popup-iniciar-sesion" role="button" data-toggle="modal" />
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

	$HTML .= '
    	<script>
    		var HOME = "'.getTema().'";
    	</script>
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
							<a href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros" id="" class="boton boton_morado"> <img src="'.get_recurso("img").'HOME/PNG/Ser_cuidador.png" /> Quiero ser Cuidador</a>
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