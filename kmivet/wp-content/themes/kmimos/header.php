<?php include 'pre-header.php'; ?><!doctype html>
<html lang="es-ES" class="no-js"><head>
	<title>Kmivet</title>
	<meta charset="UTF-8">
	<link rel="icon" href="<?= get_home_url() ?>/favicon.ico" type="image/x-icon" /><?php 
	$HTML = '';	
	if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
		header('X-UA-Compatible: IE=edge,chrome=1');
	}

	$HTML .= "<meta name='description' content='Kmivet'>";
	$HTML .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';

	$HTML .= ' <script src="'.getTema().'/js/jquery.min.js"></script>'.
		'<style>'.
			'.modal p a { font-family: arial, sans-serif !important; color: #333 !important; } '.
			'/* pre { position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 99999999; display: none !important; } */'.
		'</style>';


	echo comprimir( $HTML );

	/* Solo para iOS - [ $is_iOS en pre-header.php ] */
	$class_iOS = ''; if( $is_iOS ){ $class_iOS = 'iOS'; wp_enqueue_style( 'modal_iOS', getTema()."/css/modal-iOS.css", array(), "1.0.0" ); }

	include_once("funciones.php");

	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	echo "
		<script> 
			var KEY_MAPS = '".KEY_MAPS."'; 
			var HEADER = 'kmivet'; 
			var AJAX = '".admin_url("admin-ajax.php")."'; 
			var UID = '".$user_id."'; 
		</script>";

	wp_enqueue_style( 'kmimos_style', getTema()."/css/kmimos_style.css", array(), "1.0.0" );

	echo "<script src='https://kit.fontawesome.com/9e9cd60cbd.js' crossorigin='anonymous'></script>";

	wp_enqueue_style( 'fontello', getTema()."/css/fontello.min.css", array(), "1.0.0" );

	wp_enqueue_style( 'old_generales_css', getTema()."/css/generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'old_generales_responsive_css', getTema()."/css/responsive/generales_responsive.css", array(), "1.0.0" );

	wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), '1.0.0');
	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.min.css", array(), '1.0.0');

	wp_enqueue_style( 'generales_css', get_recurso("css")."generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'generales_responsive_css', get_recurso("css")."responsive/generales.css", array(), "1.0.0" );

	wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );

	// wp_enqueue_style( 'bootstrap_css', "https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css", array(), "1.0.0" );

	

	wp_head();

	$HTML = '';

	$MENU = get_menu_header(true);

	if( !isset($MENU["head"]) ){
		$menus_normal = '
			<li><a class="modal_show" style="padding-right: 15px" href="javascript:;" data-target="#popup-iniciar-sesion">INICIAR SESIÓN</a></li>
			<li><a class="modal_show" style="padding-left: 15px; border-left: 1px solid white;" data-target="#popup-registrarte">REGISTRARME</a></li>
		';
	}else{
		$menus_normal =  $MENU["body"].$MENU["footer"];
	}

	$link_home = ( $_SESSION['landing_paseos'] == 'yes' ) ? get_home_url().'/paseos' : get_home_url();
	$link_home = get_home_url().'/kmivet/';
	$quiero_ser = 'Unirme como Veterinario';
	$quiero_ser_link = 'quiero-ser-veterinario';
	$buscar_ = 'Buscar Veterinario';
	$logo = '<img class="logo logo_kmivet" src="'.get_recurso("img").'KMIVET/logo_2.png" />';
	$link_buscar = get_home_url().'/busqueda/';
	$ico_perfil = get_recurso("img").'KMIVET/GENERALES/Perfil.svg';

	// Avatar default
	$avatar = getTema().'/images/new/km-navbar-mobile.svg';
	$avatar_circle = '';
	if( !is_user_logged_in() ){
		$btn_quiero = '<a href="'.get_home_url().'/'.$quiero_ser_link.'" id="quiero_ser_menu" class="boton boton_morado"> <img src="'.get_recurso("img").'HOME/PNG/Ser_cuidador.png" /> '.$quiero_ser.'</a>';
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
			<img class="icono" src="'.$ico_perfil.'" id="login" href="#" data-target="#popup-iniciar-sesion" role="button" data-toggle="modal" />
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
						<input type="text" id="txt_buscar" placeholder="'.$buscar_.'" name="nombre"  />
					</form>

					<ul class="nav navbar-nav">
						<li><a href="#" data-target="#popup-iniciar-sesion" class="km-nav-link" role="button" data-toggle="modal">Iniciar sesión</a></li>
						<li><a href="#" data-target="#popup-registrarte" class="km-nav-link" role="button" >Registrarme</a></li>
						<li><a href="'.get_home_url().'/'.$quiero_ser_link.'" class="km-nav-link">'.$quiero_ser.'</a></li>
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
							<input type="text" id="txt_buscar" placeholder="'.$buscar_.'" name="nombre"  />
						</form>
						<ul class="nav navbar-nav">
							'.$menus_normal.'
				    	</ul>
				    </div>
			    </div>
	    	</div>
		';
	}

	$clase_white = "nav_mediqo";

	$HTML .= '
    	<script>
    		var HOME = "'.getTema().'/";
    		var RAIZ = "'.get_home_url().'/";
    		var RUTA_IMGS = "'.get_home_url().'/imgs/";
    		var wlabel = "'.$wlabel.'";
    	</script>

	</head>
	<body class="' . join(' ', get_body_class($class)) . ' '.$class_iOS.'">
		<script> 
			var RUTA_IMGS = "'.get_home_url().'/imgs"; 
		</script>

		<nav class="'.$clase_white.'">

			<div class="solo_pc">
				<table class="nav_table">
					<tr>
						<td class="nav_left">
							<a id="link_home" href="'.$link_home.'">
								'.$logo.'
							</a>
							<a href="'.$link_buscar.'" id="buscar_cuidador_btn_nav" onclick="ancla_form()" class="boton">
								<img class="lupa" src="'.get_recurso("img").'HOME/PNG/Buscar.png" /> 
								<img class="lupa_negra" src="'.get_recurso("img").'HOME/PNG/Buscar_negro.png" /> 
								<span>'.$buscar_.'</span>
							</a>
							'.$btn_quiero.'
						</td>
						<td class="nav_right">
							'.$menu_home_2.'
							'.$menu_str.'
						</td>
					</tr>
				</table>
			</div>

			<div class="solo_movil">
				<table class="nav_table">
					<tr>
						<td class="nav_left">
							<a href="'.$link_home.'">
								'.$logo.'
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