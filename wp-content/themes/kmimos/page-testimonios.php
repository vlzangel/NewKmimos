<?php 
    /*
        Template Name: Testimonios
    */

    wp_enqueue_style('home_kmimos', get_recurso("css")."home.css", array(), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/home.css", array(), '1.0.0');

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');
            
    get_header();

    $user_id = get_current_user_id();

	$HTML .= '
		<style>
			.pc_seccion_0 {
			    width: 100%;
			    height: 230px;
			    background-size: cover;
			    background-position: center;
			    position: relative;
			    z-index: -1;
			}
			.testimonios_container .testimonios_item {
			    bottom: 25px;
			}
			.testimonios_container {
			    padding: 50px;
			    margin-bottom: 0px;
			}
			.info_home{
				border: solid 1px #CCC;
			    padding: 20px;
			    border-radius: 4px;
			    margin: 50px;
			    box-shadow: 1px 1px 1px #BBB;
			}
			.info_home a {
				padding: 6px 80px;
				text-align: center;
				font-size: 15px;
				font-weight: 600;
				color: #7c169e;
				transition: all .3s ease-in-out;
				text-decoration: none;
			}
			.info_home a:hover {
				background: #7c169e;
				color: #FFF;
			}
			.info{
			    font-size: 20px;
			    text-align: center;
			    padding: 0px 0px 15px;
			}
		</style>
		<div class="pc_seccion_0" style="background-image:url('.getTema().'/images/new/km-ficha/km-bg-ficha.jpg);">
			<div class="overlay"></div>
		</div>

		<div class="testimonios_container">
			<div class="testimonios_item">
				<p>Por segunda vez dejé a mi perro con Gabriel y su familia, estoy muy agradecido y encantado con el cuidado que le ha dado a mi mascota. Durante toda la estadía me envió fotos de mi perrito feliz mientras yo viajaba.</p>
				<span>- Alejandra R.</span>
			</div>
			<div class="testimonios_img"></div>
		</div>
		<div class="testimonios_container">
			<div class="testimonios_item">
				<p>Por segunda vez dejé a mi perro con Gabriel y su familia, estoy muy agradecido y encantado con el cuidado que le ha dado a mi mascota. Durante toda la estadía me envió fotos de mi perrito feliz mientras yo viajaba.</p>
				<span>- Alejandra R.</span>
			</div>
			<div class="testimonios_img"></div>
		</div>
		<div class="testimonios_container">
			<div class="testimonios_item">
				<p>Por segunda vez dejé a mi perro con Gabriel y su familia, estoy muy agradecido y encantado con el cuidado que le ha dado a mi mascota. Durante toda la estadía me envió fotos de mi perrito feliz mientras yo viajaba.</p>
				<span>- Alejandra R.</span>
			</div>
			<div class="testimonios_img"></div>
		</div>

		<div class="info_home">
			<div class="info">Para encontrar más testimonios como estos, busca en los perfiles de cientos de cuidadores certificados Kmimos.</div>
			<div style="text-align: center;">
				<a href="'.get_home_url().'/#buscar" class="boton boton_border_morado">Regresar</a>
			</div>
		</div>
	';

    echo comprimir($HTML);
    
    wp_enqueue_script('buscar_home', get_recurso("js")."home.js", array(), '1.0.0');

    get_footer(); 
?>


