<?php 
    /*
        Template Name: Testimonios
    */

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');
            
    get_header();

    $user_id = get_current_user_id();

	$HTML .= '
		<style>
			.pc_seccion_0 {
			    width: 100%;
			    height: 350px;
			    background-size: cover;
			    background-position: center;
			    position: relative;
			    z-index: -1;
			}
			h1 {
				padding: 30px;
				text-align: center;
				font-family: Gotham-Pro-Bold;
				text-transform: uppercase;
				font-size: 22px;
			}
			h3 {
				padding: 15px;
				text-align: left;
				font-family: Gotham-Pro-Bold;
    			vertical-align: middle;
		    	font-size: 16px;
			}
			h3 img {
				width: 20px;
    			vertical-align: middle;
			    padding-bottom: 5px;
		        margin-right: 10px;
			}

			.container{
				display: table;
				width: 100%;
			    max-width: 1360px;
			    margin: 70px auto 40px;
			}

			.izq, .der{
				display: table-cell;
			}

			.izq {
				width: calc( 100% - 250px );
				text-align: justify;
				font-size: 14px;
			}

			.izq a {
				color: #008bf3 !important;
			}

			.der {
				width: 350px;
				padding: 0px 35px;
			}

			.der a {
				display: block;
				margin-bottom: 15px;
				text-align: center;
			}

			.boton {
				border-radius: 3px;
			}

			.boton_morado {
				color: #FFF !important;
				padding: 15px 50px;
				display: inline-block;
				text-align: center;
				margin: 0px auto 70px;
				text-transform: none !important;
				text-decoration: none !important;
				font-family: Gotham-Pro-Bold;
			}

			.banner_cuidador{
				width: 100%;
			}

			.promesa{
				text-align: center;
				font-family: Gotham-Pro-Bold;
				font-size: 18px;
			}

			.promesa span {
				color: #7c169e;
			}

			.img_footer {
				position: relative; 
				padding: 50px;
			}

			.img_footer img {
				width: 100%;
			}

			.btns_container{
				position: absolute;
				left: 0px;
				bottom: 50px;
				width: 100%;
				box-sizing: border-box;
				padding: 25px 50px;
				text-align: center;
			}

			.btns_container .boton {
				width: 250px;
				padding: 15px 0px;
				text-decoration: none !important;
				font-family: Gotham-Pro-Bold;
				font-size: 16px;
			}

			.btns_container a.boton.boton_border_gris {
				background: #FFF;
			}

			.ir_home{
				text-align: center;
				font-family: Gotham-Pro-Bold;
				font-size: 16px;
				margin-bottom: 50px;
			}

			.ir_home a {
				color: #00d2c6;
				text-decoration: underline !important;
			}

		</style>
		<div class="pc_seccion_0" style="background-image:url('.get_recurso('img').'/TESTIMONIOS/Header.jpg);"></div>

		<h1>Miles y miles de comentarios de dueños de mascotas felices</h1>

		<div class="container" style="margin: 0px auto 40px;">
			<h3>
				<img src="'.get_recurso('img').'/TESTIMONIOS/Estrella_1.svg">
				Mira estos perfiles de Cuidadores recomendados para ti
			</h3>
		</div>

		<img class="banner_cuidador" src="'.get_recurso('img').'/TESTIMONIOS/Maru-S.png" />
		<div class="container">
			<div class="izq">
				Mis dos hijos y yo vivimos en un departamento muy grande en compañía de nuestros perros Ortzi y Cholo, quienes son unos amables 
				y compartidos anfitriones. No uso jaulas así que los perros están libres, jugando por toda el área, siempre acompañados por 
				nosotros y sus nuevos camaradas perrunos. Salimos a pasear tres veces al día y los alimentos se les dan en forma separada. 
				Solamente acepto perros consentidos, sociables y nada agresivos... <a>Ver más</a><br>
			</div>
			<div class="der">
				<a class="boton boton_border_gris">Conocer cuidador</a>
				<a class="boton boton_verde">Reservar</a>
			</div>
		</div>
		<div style="text-align: center;">
			<a class="boton boton_morado">Ir al perfil de Maru S.</a>
		</div>







		<img class="banner_cuidador" src="'.get_recurso('img').'/TESTIMONIOS/Claudia-R.png" />
		<div class="container">
			<div class="izq">
				Horario de ingreso partir de 8:30am. ¡¡Listos para jugar?!! ¿Reservación para navidad? Hola! Soy Claudia Ramírez. Zona tlalpan por tec 
				de Monterrey campus sur He sido cuidadora de perrihijos , ya por varios años y todo 
				comenzó cuando ingrese a trabajar en una clínica veterinaria, allá observe que los perros permanecían encerrados por días, algo que 
				a mí nunca me gusto, por lo cual comencé a llevármelos para darles el calor de hog... <a>Ver más</a><br>
			</div>
			<div class="der">
				<a class="boton boton_border_gris">Conocer cuidador</a>
				<a class="boton boton_verde">Reservar</a>
			</div>
		</div>
		<div style="text-align: center;">
			<a class="boton boton_morado">Ir al perfil de Claudia R.</a>
		</div>




		<img class="banner_cuidador" src="'.get_recurso('img').'/TESTIMONIOS/Benjamin-G.png" />
		<div class="container">
			<div class="izq">
				Benjamín González les da la bienvenidas a todos ustedes a este hogar donde vivimos mi familia y mis mascotas, mi profesión es médico veterinario 
				con más de 36 años de experiencia en el manejo de animales y en particular perros, en esta tu casa colaboran en el cuidado y mantenimiento de los 
				huéspedes caninos, mi hijo Benjamín que desde que nació convive con animales participando en concursos de entrenamiento y manejo de perros, así 
				como en foros de Bienestar Animal y Laura asistente de... <a>Ver más</a><br>
			</div>
			<div class="der">
				<a class="boton boton_border_gris">Conocer cuidador</a>
				<a class="boton boton_verde">Reservar</a>
			</div>
		</div>
		<div style="text-align: center;">
			<a class="boton boton_morado">Ir al perfil de Benjamín G.</a>
		</div>

		<div class="promesa">
			<span>Nuestra promesa</span> ¡Tu mascota regresa feliz!
		</div>

		<div class="img_footer">
			<img src="'.get_recurso('img').'/TESTIMONIOS/Banner-1.png" />

			<div class="btns_container">
				<a class="boton boton_verde">Buscar cuidador</a>
				<a class="boton boton_border_gris">Quiero ser cuidador</a>
			</div>
		</div>

		<div class="ir_home">
			<a>Ir al home</a>
		</div>
	';

    echo comprimir($HTML);
    
    wp_enqueue_script('buscar_home', get_recurso("js")."home.js", array(), '1.0.0');

    get_footer(); 
?>


