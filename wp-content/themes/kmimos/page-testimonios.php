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
	';

    echo comprimir($HTML);
    
    wp_enqueue_script('buscar_home', get_recurso("js")."home.js", array(), '1.0.0');

    get_footer(); 
?>


