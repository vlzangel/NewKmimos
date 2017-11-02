<?php 
    /*
        Template Name: Contactanos
    */
    get_header();

    wp_enqueue_style('beneficios_kmimos', $home."/wp-content/themes/pointfinder/css/contactanos.css", array(), '1.0.0');
	wp_enqueue_style('contactanos_responsive', $home."/wp-content/themes/pointfinder/css/responsive/contactanos_responsive.css", array(), '1.0.0');
        
?>

    <form id='contactanos'>
        <div class="km-box-form">

            <div class="content-placeholder">
                <div class="label-placeholder">
                    <label>Nombre</label>
                    <input type="text" data-charset="xlf" id="rc_nombres" name="rc_nombres" value="" class="input-label-placeholder social_firstname solo_letras" maxlength="20">
                    <small data-error="rc_nombres" style="visibility: hidden;"></small>
                </div>
            </div>

            <div class="content-placeholder">
                <div class="label-placeholder">
                    <label>Asunto</label>
                    <input type="text" data-charset="xlfalfnum" name="ct_asunto" value="" class="input-label-placeholder" maxlength="50">
                    <small data-error="ct_asunto" style="visibility: hidden;"></small>
                </div>
            </div>

            <div class="content-placeholder">
                <div class="label-placeholder">
                    <label>Nombre</label>
                    <input type="text" data-charset="xlf" id="rc_nombres" name="rc_nombres" value="" class="input-label-placeholder social_firstname solo_letras" maxlength="20">
                    <small data-error="rc_nombres" style="visibility: hidden;"></small>
                </div>
            </div>

        </div>
	</form>


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
<?php	  

	echo comprimir_styles($HTML);

    get_footer(); 
?>