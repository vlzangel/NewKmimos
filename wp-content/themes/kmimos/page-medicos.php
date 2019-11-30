<?php 
    /*
        Template Name: Médicos
    */

	date_default_timezone_set('America/Mexico_City');

    wp_enqueue_style('home_kmimos', get_recurso("css")."medicos.css", array('kmimos_style'), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/medicos.css", array(), '1.0.0');

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');

	$especialidades = json_decode( file_get_contents("https://api.mediqo.mx/medics/specialty/") );
            
    get_header();
    $user_id = get_current_user_id(); ?>
    <script type="text/javascript"> var USER_ID = "<?= $user_id ?>"; </script>
    <div class="medicos_container medico_ficha_no_select">
		<form>
            <input type="hidden" value="" />
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
					<input type="text" name="" placeholder="Buscar tu dirección" value="<?= $_SESSION['medicos_serch']['ubicacion_txt'] ?>" />
                    <input type="hidden" id="latitud" name="latitud" value="<?= $_SESSION['medicos_serch']['latitud'] ?>" />
                    <input type="hidden" id="longitud" name="longitud" value="<?= $_SESSION['medicos_serch']['longitud'] ?>" />
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

    <div class="pre">
        <pre>
            <?php
                print_r( $_SESSION['medicos_serch'] );
            ?>
        </pre>
    </div>
    <button class="pre-btn">PRE</button>

    <?php

    wp_enqueue_script('buscar_home', get_recurso("js")."medicos.js?v=".time(), array(), '1.0.0');

    echo '
    <div id="reservar_medico" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Cita</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="modal_container">

                        <div class="modal_img_container">

                            <div class="modal_img"></div>

                        </div>

                        <div class="modal_info">
                            
                            <div>
                                <h3>DULCE ROCIO RIOS PARADÁ</h3>
                                <div>A 2.012 km de tu ubicación</div>
                            </div>
                            
                            <div>
                                <h3>Dirección y Horario</h3>
                                <div>Jalisco</div>
                                <div>20 de Diciembre de 2019 a las 10:00 AM</div>
                            </div>
                            
                            <div>
                                <h3>Información de Consulta</h3>
                                <div>Precio <span class="modal_precio">$520</span></div>
                                <div>
                                    <label for="para_alguien_mas">
                                        ¿La cita es para alguien más? 
                                    </label>
                                    <span class="check_container"> 
                                        <input type="checkbox" id="para_alguien_mas" name="para_alguien_mas" /> 
                                        <label></label>
                                    </span> 
                                </div>
                            </div>

                        </div>

                        <div class="modal_pago">

                            <div>
                                <h3>Método de Pago</h3>
                                <div class="cont_tipos">
                                    <label for="tipo_tarjeta">
                                        <input type="radio" id="tipo_tarjeta" name="tipo_pago" value="tarjeta" checked />
                                        Pago con tarjeta
                                    </label>
                                    <label for="tipo_efectivo">
                                        <input type="radio" id="tipo_efectivo" name="tipo_pago" value="efectivo" />
                                        Pago en Efectivo
                                    </label>
                                </div>
                                <div class="form_tarjeta">
                                    <div class="cont_nombre">
                                        <input type="text" name="nombre" placeholder="Nombre" />
                                    </div>
                                    <div class="cont_apellido">
                                        <input type="text" name="apellido" placeholder="Apellido" />
                                    </div>
                                    <div class="cont_tarjeta">
                                        <input type="text" name="tarjeta" placeholder="Numero de tarjeta" />
                                    </div>
                                    <div class="cont_datos">
                                        <div class="cont_mes">
                                            <input type="text" name="mes" placeholder="Mes (MM)" />
                                        </div>
                                        <div class="cont_anio">
                                            <input type="text" name="anio" placeholder="Año (AA)" />
                                        </div>
                                        <div class="cont_cvv">
                                            <input type="text" name="cvv" placeholder="CVV" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Confirmar Cita</button>
                </div>
            </div>
        </div>
    </div>
    ';

    get_footer(); 
?>


