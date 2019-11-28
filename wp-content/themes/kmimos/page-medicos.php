<?php 
    /*
        Template Name: Médicos
    */

	date_default_timezone_set('America/Mexico_City');

    wp_enqueue_style('home_kmimos', get_recurso("css")."medicos.css", array(), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/medicos.css", array(), '1.0.0');

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');

	$especialidades = json_decode( file_get_contents("https://api.mediqo.mx/medics/specialty/") );
            
    get_header();
    $user_id = get_current_user_id(); ?>


    <div class="medicos_container medico_ficha_no_select">

		<form>
			<div class="form_container">
				<div class="medicos_control">
					<select id="especialidad">
						<?php
							foreach ($especialidades->objects as $key => $especialidad) {
								echo '<option value="'.$especialidad->id.'">'.$especialidad->name.'</option>';
							}
						?>
					</select>
				</div>
				<div class="medicos_control">
					<input type="text" name="" placeholder="Buscar tu dirección" />
				</div>
				<div class="medicos_control">
					<input type="text" name="" placeholder="Buscar por Nombre" />
				</div>
			</div>
		</form>
    	
    	<div class="medicos_list"></div>

    	<div class="medicos_details">
    		
			<div class="medico_ficha_titulo">
				<div>ELIGE A TU MÉDICO</div>
				<span>*Los precios varían por hora y distancia</span>
			</div>

    		<div class="medico_ficha">

    			<div class="medico_ficha_no_select_container">
    				<img src="<?= get_recurso("img").'MEDICOS/logo_mediqo.png' ?>" />
    				<!-- <div class="lds-roller">
    					<div></div>
    					<div></div>
    					<div></div>
    					<div></div>
    					<div></div>
    					<div></div>
    					<div></div>
    					<div></div>
    				</div> -->
    				<!-- <div>
	    				<div class="lds-ripple">
	    					<div></div>
	    					<div></div>
	    				</div>
    				</div> -->
					<div>
    					<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
    				</div>
    			</div>

    			<div class="medico_ficha_img_container">
    				<div class="medico_ficha_img"></div>
    				<div class="medico_ficha_info_name">
    					<label></label>
    					<div></div>
    				</div>


    				<div class="medico_ficha_info_certificaciones">
    					<label>CERTIFICACIONES:</label>
    					<div></div>
    				</div>
    				<div class="medico_ficha_info_cursos">
    					<label>CURSOS:</label>
    					<div></div>
    				</div>
    			</div>
    			
    			<div class="medico_ficha_info_container">
    				<div class="medico_ficha_info_experiencia">
    					<label>EXPERIENCIA:</label>
    					<div></div>
    				</div>
    				<div class="medico_ficha_info_otros">
    					<label>OTROS ESTUDIOS:</label>
    					<div></div>
    				</div>
    			</div>
    			
    			<div class="medico_ficha_horario_container">
    				<label>Horario</label>
    				<div>
    					
    				</div>
    			</div>

    		</div>

    	</div>

    </div>

    <?php

    wp_enqueue_script('buscar_home', get_recurso("js")."medicos.js?v=".time(), array(), '1.0.0');

    get_footer(); 
?>


