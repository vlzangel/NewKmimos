<?php
	wp_enqueue_style( 'generales_css', get_recurso("css")."generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'generales_responsive_css', get_recurso("css")."responsive/generales.css", array(), "1.0.0" );
	
	wp_enqueue_script('global_js', getTema()."/js/global.js", array("jquery"), '1.0.0');
	wp_enqueue_script('global_new_js', get_recurso("js")."global.js", array("jquery"), '1.0.0');
    wp_enqueue_script('boostrap.min.js', getTema()."/js/bootstrap.min.js", array("jquery"), '1.0.0');

    wp_enqueue_script('favorites', getTema()."/js/favoritos.js", array("jquery"), '1.0.0');

    if( $plantilla == 'page-registro-cuidador.php' ){
        wp_enqueue_script('Old_global_js', getTema()."/js/global.js", array("jquery"), '1.0.0');
        wp_enqueue_script('Old_main', getTema()."/js/main.js", array("jquery"), '1.0.0');

    	wp_enqueue_script('Old_jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    	wp_enqueue_script('Old_jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

	}
	$seccion = ( $_SESSION["wlabel"] != "" && strtolower($_SESSION["wlabel"]) != "quitar" ) ? $_SESSION["wlabel"] : "home";

	$HTML = '
		<footer>
			<div class="footer_alto">
				<div>
					<div class="footer_alto_col_1">
						<h2>Entérate de los últimos ciudados para tu mascota</h2>
						<ul>
							<li style="font-size: 13px;">¡Inscribete a nuestro blog y conócelas!</li>
						</ul>
					
						<form id="suscribirse" onsubmit="form_subscribe(this); return false;" class="subscribe" data-subscribe="'.get_home_url().'/wp-content/plugins/kmimos">
                            <input type="hidden" name="section" value="'.$seccion.'" class="form-control" placeholder="Ingresa tu correo">
                            <input type="hidden" id="wlabelSubscribeFooter" name="wlabelSubscribeFooter" value="'.$_SESSION["wlabel"].'" class="form-control" placeholder="Ingresa tu correo">
							<input type="email" id="email" name="mail" placeholder="Ingresa tu correo" />
							<input type="submit" value="Inscribirme al blog" />
                            <div class="message message-especial"></div>
						</form>

						<div class="siguenos_alto">
							<span>Siguenos en</span>
							<a href="https://www.facebook.com/Kmimosmx/" target="_blank">
								<img src="'.get_recurso("img").'HOME/SVG/Facebook.svg" />
							</a>
							<a href="https://www.instagram.com/kmimosmx/" target="_blank">
								<img src="'.get_recurso("img").'HOME/SVG/Instagram.svg" />
							</a>
							<a href="https://www.youtube.com/channel/UCZuzqWCgGdboK-w5yGQjACQ" target="_blank">
								<img src="'.get_recurso("img").'HOME/SVG/Youtube.svg" />
							</a>
						</div>
					</div>
					<div>
						<h2>Servicios</h2>
						<ul>
                            <p><a href="'.get_home_url().'/quiero-ser-cuidador-certificado-de-perros">Quiero ser cuidador</a></p>
                            <p><a href="'.get_home_url().'/busqueda">Buscar cuidador certificado</a></p>
                            <p><a target="blank" href="https://kmimos.com.mx/blog">Blog</a></p>
						</ul>
					</div>
					<div>
						<h2>Navega</h2>
						<ul>
                            <p><a href="'.get_home_url().'">Nosotros</a></p>
                            <p><a href="javascript:;">Preguntas y Respuestas</a></p>
                            <p><a href="'.get_home_url().'/coberturas-de-servicios-veterinarios/">Cobertura Veterinaria</a></p>
                            <p><a href="'.get_home_url().'">Comunicados de prensa</a></p>
                            <p><a href="'.get_home_url().'/terminos-y-condiciones/">Términos y Condiciones</a></p>
                            <p><a href="javascript:;">Nuestros Aliados</a></p>
                            <p><a href="'.get_home_url().'/contacta-con-nosotros/">Contáctanos</a></p> 
						</ul>
					</div>
					<div>
						<h2>Contáctanos</h2>
						<ul class="info_contacto">
							<li> <a href="tel:018009564667" > <i class="fa fa-phone" aria-hidden="true"></i> <span>Teléfono:</span> 01 (800) 9 564667 </a> </li>
							<li> <a href="tel:+52015585261162" > <i class="fa fa-phone" aria-hidden="true"></i> <span>Telef. Local:</span> 01 (55) 85261162 </a> </li>
							<li> <a href="whatsapp://send/?phone=+5213312614186" > <i class="fa fa-whatsapp" aria-hidden="true"></i> <span>WhatsApp:</span> +52 1 (33) 12614186 </a> </li>
							<li> <a href="whatsapp://send/?phone=+5215568922182" > <i class="fa fa-whatsapp" aria-hidden="true"></i> <span>WhatsApp:</span> +52 1 (55) 68922182 </a> </li>
							<li> <a href="whatsapp://send/?phone=+5215531374829" > <i class="fa fa-whatsapp" aria-hidden="true"></i> <span>WhatsApp:</span> +52 1 (55) 31374829 </a> </li>
							<li> <a href="mailto:contactomex@kmimos.la" > <i class="fa fa-envelope-o" aria-hidden="true"></i> <span>Email:</span> contactomex@kmimos.la </a> </li>
						</ul>
					</div>
					<div class="siguenos_bajo">
						<span style="display: block;">Siguenos en</span>
						<a href="https://www.facebook.com/Kmimosmx/" target="_blank">
							<img src="'.get_recurso("img").'HOME/SVG/Facebook.svg" />
						</a>
						<a href="https://www.instagram.com/kmimosmx/" target="_blank">
							<img src="'.get_recurso("img").'HOME/SVG/Instagram.svg" />
						</a>
						<a href="https://www.youtube.com/channel/UCZuzqWCgGdboK-w5yGQjACQ" target="_blank">
							<img src="'.get_recurso("img").'HOME/SVG/Youtube.svg" />
						</a>
					</div>
				</div>
			</div>
			<div class="footer_bajo">
				<a href="'.get_home_url().'">Kmimos.com.mx</a> Todos los derechos reservados.
			</div>
		</footer>
	';

	if( is_front_page() || $plantilla == 'page-paseos.php' || ( isset($_GET["g"]) && $plantilla == 'page-busqueda.php' ) ) {
		if( $_SERVER["HTTP_REFERER"] != "https://www.kmimos.com.mx/google-adwords/" && $_SERVER["HTTP_REFERER"] != "https://kmimos.com.mx/google-adwords/" ){
	     	include_once( dirname(__DIR__).'/partes/footer/SubscribeSite.php' );
	     	$_SESSION["POPUP_HOME"] = "YES"; 
	     	wp_enqueue_style( 'banner_suscribir_css', get_recurso("css")."responsive/banner_suscribir.css", array(), "1.0.0" );
	    }
    }

    if( !isset($_SESSION[ "llego_al_home" ]) ){
        $HTML .= '
            <script>
                evento_google("llego_al_home");  
                evento_fbq("track", "traking_code_llego_al_home");   
            </script>
        ';
        $_SESSION[ "llego_al_home" ] = "YA_ENTRO";
    }

	echo comprimir($HTML);

	wp_footer();

	$HTML = '';
    if( $_GET['r'] == 'cli' ){
        $HTML = '
            <script>
            	jQuery( document ).ready(function() {
            		jQuery("[data-target=#popup-registrarte]").click();
            		jQuery(".km-btn-popup-registrarte-1").click();
            	});
            </script>
        ';
    }
            
    if(  $_SESSION['admin_sub_login'] == 'YES' ){
        $HTML .= "
            <a href='".get_home_url()."/?i=".md5($_SESSION['id_admin'])."&admin=YES' class='theme_button' style='
			    position: fixed;
			    display: inline-block;
			    left: 50px;
			    bottom: 50px;
			    padding: 10px 25px;
			    font-size: 40px;
			    font-family: Roboto;
			    z-index: 999999999999999999;
			    text-decoration: none;
			    color: #FFF;
			    border-radius: 50%;
			    font-weight: 600;
		        background-color: #00d2c6;
    			border-color: #00d2c6;
            '>
                X
            </a>
        ";
    }

	echo comprimir($HTML);


	echo  "</body></html>";
?>