<?php 
    /*
        Template Name: Home
    */

    $landing = ( isset($_GET["landing"]) ) ? $_GET["landing"] : $_SESSION['landing_test'];

    if( $landing == 'd' ){
    	header("location: ".get_home_url()."/home-2/" );
    }

	date_default_timezone_set('America/Mexico_City');

    wp_enqueue_style('home_club_responsive', getTema()."/css/responsive/club_patitas_home.css", array(), '1.0.0');
    wp_enqueue_style('home_kmimos', get_recurso("css")."home.css", array(), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/home.css", array(), '1.0.0');

	wp_enqueue_style( 'bootstrap.min', getTema()."/css/bootstrap.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );


    wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

    wp_enqueue_script('select_localidad', getTema()."/js/select_localidad.js", array(), '1.0.0');
    wp_enqueue_script('check_in_out', getTema()."/js/fecha_check_in_out.js", array(), '1.0.0');

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');
            
    get_header();

    $user_id = get_current_user_id();
    
    $HTML = '';
    $cuidadores_destacados = '';

	if( $_SESSION["wlabel"] == "petco" ){
		$HTML .= '
			<!-- Adform Tracking Code BEGIN 
			<script type="text/javascript">
			    window._adftrack.push({
			        pm: 1453019,
			        divider: encodeURIComponent("|"),
			        pagename: encodeURIComponent("MX_Kmimos_Home_180907")
			    });
			</script>
			<noscript>
			    <p style="margin:0;padding:0;border:0;">
			        <img src="https://a2.adform.net/Serving/TrackPoint/?pm=1453019&ADFPageName=MX_Kmimos_Home_180907&ADFdivider=|" width="1" height="1" alt="" />
			    </p>
			</noscript>
				
				Adform Tracking Code END -->
		';

	}


	if( !is_user_logged_in() ){
		$btn_registro = '<div data-target="#popup-registrarte" role="button" class="boton boton_border_morado">Regístrate</div>';
		$info_registro = '
			<div class="beneficios_registrar_container">
				<div data-target="#popup-registrarte" role="button" class="boton boton_border_morado">Regístrate</div>
				<span class="">
					Crea tu perfil, y comienza a disfrutar de los servicios que te trae Kmimos
				</span>
			</div>
		';
	}

	switch ( $landing ) {
		case 'b':
			include dirname(__FILE__).'/partes/HOMES/home_b.php';
		break;
		case 'c':
			include dirname(__FILE__).'/partes/HOMES/home_c.php';
		break;
		
		default:
			include dirname(__FILE__).'/partes/HOMES/home_a.php';
		break;
	}
			
	$HTML .= '
	<!-- CONECTATE -->

	<div class="conectate_container" style="display: none;" >
		<h2>Conéctate de donde quieras</h2>
		<img src="'.get_recurso("img").'HOME/PNG/Moviles.png" />
		<span>Disponible en la web, y en dispositivos iOS y Android</span>
		<div class="mensaje_movil">
			<span>Baja nuestra <strong>app</strong>, y conéctate desde donde quieras</span>
		</div>
		<div class="conectate_botones_tabla">
			<div class="conectate_botones_celda"><img src="'.get_recurso("img").'HOME/SVG/APP_STORE.svg" /></div>
			<div class="conectate_botones_celda"><img src="'.get_recurso("img").'HOME/SVG/GOOGLE_PLAY.svg" /></div>
		</div>
	</div>';
	
	$HTML .= '
	<!-- ALIADOS -->
	<div class="aliados_container">
		<img src="'.get_recurso("img").'HOME/PNG/Reforma.png" />
		<img src="'.get_recurso("img").'HOME/PNG/Mural.png" />
		<img src="'.get_recurso("img").'HOME/PNG/El-norte.png" />
		<img src="'.get_recurso("img").'HOME/PNG/Financiero.png" />
		<img src="'.get_recurso("img").'HOME/PNG/Universal.png" />
		<img src="'.get_recurso("img").'HOME/PNG/Petco.png" style="display: none;" />
	</div>';

    echo comprimir($HTML);
    
    wp_enqueue_script('buscar_home', get_recurso("js")."home.js", array(), '1.0.0');
    wp_enqueue_script('club_patitas', get_recurso("js")."club_patitas.js", array(), '1.0.0');

    get_footer(); 
?>


