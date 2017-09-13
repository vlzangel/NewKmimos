<?php 
    /*
        Template Name: Contactanos
    */
    get_header();

    wp_enqueue_style('beneficios_kmimos', $home."/wp-content/themes/pointfinder/css/contactanos.css", array(), '1.0.0');
	wp_enqueue_style('contactanos_responsive', $home."/wp-content/themes/pointfinder/css/responsive/contactanos_responsive.css", array(), '1.0.0');
        
        $HTML = "
        	<form id='contactanos'>
                <h1>Cont&aacute;ctanos</h1>
        		<div id='campos'>
	        		<input type='text' id='nombre' placeholder='Nombre'>
	        		<input type='email' id='email' placeholder='Email'>
	        		<input type='telf' id='telf' placeholder='Teléfono'>
	        		<textarea id='mensaje' placeholder='Mensaje'></textarea>
	        	</div>
        		<div id='botones'>
        			<input type='submit' value='Enviar'>
	        	</div>
        	</form>
	    ";

	echo comprimir_styles($HTML);

    get_footer(); 
?>