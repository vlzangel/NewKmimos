<?php 
    /*
        Template Name: Perfil
    */

    if( !is_user_logged_in() ){
    	header( "location: ".get_home_url() );
    }

    wp_enqueue_style('perfil', getTema()."/css/perfil.css", array(), '1.0.0');
	wp_enqueue_style('perfil_responsive', getTema()."/css/responsive/perfil_responsive.css", array(), '1.0.0');
	wp_enqueue_script('perfil_global', getTema()."/js/perfil_global.js", array("jquery", "global_js"), '1.0.0');

	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );

    wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

	$btn_txt = "Actualizar";

	echo '<script> var URL_PROCESOS_PERFIL = "'.getTema().'/procesos/perfil/"; </script>';

	$mostrar_btn = true;
	switch ( $post->post_name ) {
		case 'perfil-usuario':
			wp_enqueue_script('perfil', getTema()."/js/perfil.js", array(), '1.0.0');
		break;
	}

	get_header();

		global $post;
		global $wpdb;

		$MENU = get_menu_header();

		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;

		$img_perfil = kmimos_get_foto($user_id, true);
		$avatar = $img_perfil["img"];

		include( "procesos/funciones/funciones_perfil.php");

		switch ( $post->post_name ) {
			case 'perfil-usuario':
				include("admin/frontend/perfil/perfil.php");
			break;
			case 'historial':
				$mostrar_btn = false;
				include("admin/frontend/perfil/historial.php");
			break;
		}

		/*$HTML_BTN = '';
		if( $mostrar_btn ){
			$type_btn = ( $btn_txt == '+' ) ? '<img src="'.get_recurso('img/PERFILES').'BOTON.svg" onclick="press_btn(jQuery(this))" data-id="#btn_actualizar" /> <input type="submit" id="btn_actualizar" class="km-btn-primary" value="'.$btn_txt.'" style="display: none;">' : '<input type="submit" id="btn_actualizar" class="km-btn-primary" value="'.$btn_txt.'">';
			$HTML_BTN = '
			<div class="container_btn">
				'.$type_btn.'
				<div class="perfil_cargando" style="background-image: url('.getTema().'/images/cargando.gif);" ></div>
			</div>';
		}*/

		$tipo = 'avatares_clientes/'.$user_id;

		$HTML = '
			<script> 
				var USER_ID = "'.$user_id.'";
				var TIPO_USER = "'.$tipo.'";
			</script>
	 		<div class="km-ficha-bg"></div>
			<div class="body container km-content-reservation">
				<div class="menu_perfil">
					<div class="vlz_img_portada">
			            <div class="vlz_img_portada_perfil" data-id="perfil">
			                <div class="vlz_img_portada_fondo vlz_rotar" style="background-image: url('.$avatar.');"></div>
			                <div class="vlz_img_portada_normal vlz_rotar" style="background-image: url('.$avatar.');"></div>
			                <div class="vlz_img_portada_cargando vlz_cargando" style="background-image: url('.getTema().'/images/cargando.gif);"></div>
			                <div class="vlz_cambiar_portada">
			                    <i class="fa fa-camera" aria-hidden="true"></i>
			                    <input type="file" id="portada_2" name="xportada" accept="image/*" />
			                </div>
			                <div id="rotar_i" class="btn_rotar" style="display: none;" data-orientacion="left"> <i class="fa fa-undo" aria-hidden="true"></i> </div>
			                <div id="rotar_d" class="btn_rotar" style="display: none;" data-orientacion="right"> <i class="fa fa-repeat" aria-hidden="true"></i> </div>
			            </div>
			            <div class="btn_aplicar_rotar" style="display: none;"> Aplicar Cambio </div>
					</div>
					<ul>
						'.$MENU["body"].'
					</ul>
				</div>
				<div class="main" >
					'.$CONTENIDO.'
				</div>
	    	</div>
		';
		echo comprimir_styles($HTML);

	get_footer();
?>