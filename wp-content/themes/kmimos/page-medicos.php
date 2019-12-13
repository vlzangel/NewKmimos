<?php 
    /*
        Template Name: Médicos
    */

    $HEADER = 'kmivet';
    $NAV = 'kmivet';

	date_default_timezone_set('America/Mexico_City');
    
    wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
    wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );
    wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

    wp_enqueue_style('home_kmimos', get_recurso("css")."medicos.css", array('kmimos_style'), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/medicos.css", array(), '1.0.0');

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');

	$especialidades = json_decode( file_get_contents("https://api.mediqo.mx/medics/specialty/") );
            
    get_header();
    $user_id = get_current_user_id(); ?>
    <script type="text/javascript"> var USER_ID = "<?= $user_id ?>"; </script>
    <div class="medicos_container medico_ficha_no_select">

    	<div class="medicos_list_container">
                
            <form class="medicos_list_form">
                <div class="medicos_control">
                    <label>Especialidad</label>
                    <select id="especialidad">
                        <?php
                            foreach ($especialidades->objects as $key => $especialidad) {
                                echo '<option value="'.$especialidad->id.'">'.$especialidad->name.'</option>';
                            }
                        ?>
                    </select>
                </div>
                <input type="hidden" id="latitud" name="latitud" value="<?= $_SESSION['medicos_serch']['latitud'] ?>" />
                <input type="hidden" id="longitud" name="longitud" value="<?= $_SESSION['medicos_serch']['longitud'] ?>" />
                <!-- <div class="medicos_control">
                    <input type="text" name="" placeholder="Buscar tu dirección" value="<?= $_SESSION['medicos_serch']['ubicacion_txt'] ?>" />
                    <input type="hidden" id="latitud" name="latitud" value="<?= $_SESSION['medicos_serch']['latitud'] ?>" />
                    <input type="hidden" id="longitud" name="longitud" value="<?= $_SESSION['medicos_serch']['longitud'] ?>" />
                </div> -->
                <div class="medicos_control">
                    <label>Busca un médico por nombre</label>
                    <input type="text" id="medico_nombre" name="nombre" placeholder="Buscar por Nombre" />
                    <label>Elije a tu especialista de preferencia:</label>
                </div>
                

            </form>

            <div class="medicos_list">

                <!-- <div class="medico_item" data-id="id">
                   <div class="medico_img_container"> <div class="medico_img" style="background-image: url( http://tusimagenesde.com/wp-content/uploads/2017/09/fotos-de-perfil-para-facebook-4.jpg )"></div> </div>
                   <div class="medico_info">
                       <div class="medico_nombre">
                            <?= strtolower("FABIANA PATRICIA ORTEGA HERNÁNDEZ") ?>
                        </div>
                        <div class="medico_ranking"></div>
                       <div class="medico_precio">
                            <div>Servicios desde</div>
                            <span> <span>MXN$</span> <strong>520,</strong><span>00</span> </span>
                        </div>
                   </div>
                </div>

                <div class="medico_item active" data-id="id">
                   <div class="medico_img_container"> <div class="medico_img" style="background-image: url( http://tusimagenesde.com/wp-content/uploads/2017/09/fotos-de-perfil-para-facebook-4.jpg )"></div> </div>
                   <div class="medico_info">
                       <div class="medico_nombre">
                            <?= strtolower("FABIANA PATRICIA ORTEGA HERNÁNDEZ") ?>
                        </div>
                        <div class="medico_ranking">
                            <span class="active"></span>
                            <span class="active"></span>
                            <span class="active"></span>
                            <span class="active"></span>
                            <span></span>
                        </div>
                       <div class="medico_precio">
                            <div>Servicios desde</div>
                            <span> <span>MXN$</span> <strong>520,</strong><span>00</span> </span>
                        </div>
                   </div>
                </div> -->

            </div>

        </div>

    	<div class="medicos_details">
			<div class="medico_ficha_titulo">
				<!-- <div></div>
				<span></span>
                <strong></strong> -->
			</div>
    		<div class="medico_ficha">
    			<div class="medico_ficha_no_select_container">
    				<img src="<?= get_recurso("img").'MEDICOS/logo_mediqo.png' ?>" />
					<div>
    					<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
    				</div>
    			</div>

    			<div class="medico_ficha_img_container">
    				<div class="medico_ficha_img"></div>
    			</div>

    			<div class="medico_ficha_info_container">
                    <div class="medico_ficha_info_name">
                        <label></label>
                        <!-- <div></div>
                        <span></span> -->
                    </div>
                    <div class="medico_ficha_info_certificaciones">
                        <label for="certificaciones">Certificaciones</label>
                        <div></div>
                    </div>
                    <div class="medico_ficha_info_cursos">
                        <label for="cursos">Cursos realizados</label>
                        <div></div>
                    </div>
    				<div class="medico_ficha_info_experiencia">
    					<label for="experiencia">Experiencia</label>
    					<div></div>
    				</div>
    				<div class="medico_ficha_info_otros">
    					<label for="otros">Otros estudios</label>
    					<div></div>
    				</div>
    			</div>

    			<div class="medico_ficha_info_box">
    				<div></div>
    			</div>

                <div class="medico_ficha_horario_container">
                    <label>Horario</label>
                    <div></div>
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

    $mascota_tipo = ( $_SESSION['medicos_serch']['otro'] != '' ) ? $_SESSION['medicos_serch']['otro'] : $_SESSION['medicos_serch']['mascotas'][0];

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
                            <span>Médico General</span>
                        </div>
                        <div class="modal_info">
                            <div>
                                <h2></h2>
                            </div>
                            <div>
                                <h3>Dirección y Horario</h3>
                                <div>'.$_SESSION['medicos_serch']['ubicacion_txt'].'</div>
                                <div class="modal_fecha"></div>
                            </div>
                            <div>
                                <h3>Información de Consulta</h3>
                                <div class="modal_precio_container">Precio <span class="modal_precio">$520</span></div>
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
                            <form>
                                <input type="hidden" name="cita_latitud" value="'.$_SESSION['medicos_serch']['latitud'].'" />
                                <input type="hidden" name="cita_longitud" value="'.$_SESSION['medicos_serch']['longitud'].'" />
                                <input type="hidden" name="cita_mascota_tipo" value="'.$mascota_tipo.'" />
                                <input type="hidden" name="cita_motivo" value="'.$_SESSION['medicos_serch']['motivo'].'" />
                                <h3>Método de Pago</h3>
                                <div class="cont_tipos">
                                    <label for="tipo_tarjeta">
                                        <input type="radio" id="tipo_tarjeta" name="cita_tipo_pago" value="tarjeta" checked />
                                        Pago con tarjeta
                                    </label>
                                    <label for="tipo_efectivo">
                                        <input type="radio" id="tipo_efectivo" name="cita_tipo_pago" value="efectivo" />
                                        Pago en Efectivo
                                    </label>
                                </div>
                                <div class="form_tarjeta">
                                    <input type="hidden" id="input_modal_precio" name="cita_precio" />
                                    <div class="cont_nombre">
                                        <input type="text" name="cita_nombre" placeholder="Nombre" />
                                    </div>
                                    <div class="cont_apellido">
                                        <input type="text" name="cita_apellido" placeholder="Apellido" />
                                    </div>
                                    <div class="cont_tarjeta">
                                        <input type="text" name="cita_tarjeta" placeholder="Número de tarjeta" />
                                    </div>
                                    <div class="cont_datos">
                                        <div class="cont_mes">
                                            <input type="text" name="cita_mes" placeholder="Mes (MM)" />
                                        </div>
                                        <div class="cont_anio">
                                            <input type="text" name="cita_anio" placeholder="Año (AA)" />
                                        </div>
                                        <div class="cont_cvv">
                                            <input type="text" name="cita_cvv" placeholder="CVV" />
                                        </div>
                                    </div>
                                </div>
                            </form>
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


