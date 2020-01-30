<?php 
    /*
        Template Name: Médicos
    */

	date_default_timezone_set('America/Mexico_City');
    
    wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
    wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );
    wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

    wp_enqueue_script('conekta', "https://cdn.conekta.io/js/latest/conekta.js", array("jquery"), '1.0.0');

    wp_enqueue_script('touchSwipe', getTema()."/lib/jquery.touchSwipe.min.js", array("jquery"), '1.0.0');

    wp_enqueue_style('home_kmimos', get_recurso("css")."medicos.css", array(), '1.0.0');
    wp_enqueue_style('home_responsive', get_recurso("css")."responsive/medicos.css", array(), '1.0.0');

	wp_enqueue_style( 'fontawesome4', getTema()."/css/font-awesome.css", array(), '1.0.0');

	$especialidades = get_specialties();
            
    get_header();
    $user_id = get_current_user_id(); ?>
    <script type="text/javascript"> 
        var USER_ID = "<?= $user_id ?>"; 
        var KEY_CONEKTA = "<?= KEY_CONEKTA ?>"; 
    </script>
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

            <div class="medicos_list"> </div>

        </div>

    	<div class="medicos_details">
			<div class="medico_ficha_titulo"> </div>
    		<div class="medico_ficha">
                
                <img class="atras_ficha" src="<?= get_recurso("img").'MEDICOS/atras.png' ?>" />

    			<div class="medico_ficha_no_select_container">
    				<img src="<?= get_recurso("img").'KMIVET/logo_2.png' ?>" />
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
        <?php
            /*echo '<pre>';
                print_r( $_SESSION['medicos_serch'] );
            echo '</pre>';*/
        ?>
    </div>
    <!-- <button class="pre-btn">PRE</button> -->

    <?php

    // wp_enqueue_script('openpay-v1', getTema()."/js/openpay.v1.min.js", array("jquery"), '1.0.0');
    // wp_enqueue_script('openpay-data', getTema()."/js/openpay-data.v1.min.js", array("jquery", "openpay-v1"), '1.0.0');

    wp_enqueue_script('medico_js', get_recurso("js")."busqueda.js?v=".time(), array(), '1.0.0');
    // wp_enqueue_script('openpay_lib', get_recurso("js")."openpay_lib.js?v=".time(), array('medico_js'), '1.0.0');

    $mascota_tipo = ( $_SESSION['medicos_serch']['otro'] != '' ) ? $_SESSION['medicos_serch']['otro'] : $_SESSION['medicos_serch']['mascotas'][0];

    include( dirname(__FILE__)."/procesos/funciones/config.php" );

    echo "
    <script> 
        var OPENPAY_TOKEN = '".$MERCHANT_ID."';
        var OPENPAY_PK = '".$OPENPAY_KEY_PUBLIC."';
        var OPENPAY_PRUEBAS = ".$OPENPAY_PRUEBAS.";
    </script>";

    /*global $wpdb;
    $_estados = $wpdb->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY `order` ASC, name ASC");
    $estados = '';
    foreach ($_estados as $key => $estado) {
        $estados .= '<option value="'.$estado->id.'" >'.utf8_decode($estado->name).'</option>';
    }*/

    $estados = '';

    echo '
    <div id="reservar_medico" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <img src="'.get_recurso("img").'MEDICOS/atras.png" data-dismiss="modal" aria-label="Close" />
                        <span>Confirmar Cita</span>
                    </h5>
                </div>
                <div class="modal-body">

                    <div id="modal_step_0" class="modal_container">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Estado</label>
                                <select name="state">
                                    <option value="">Seleccione...</option>
                                    '.$estados.'
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Provincia</label>
                                <select name="provincia">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Colonia</label>
                                <select name="colonia">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Dirección</label>
                                <input type="text"  />
                            </div>
                        </div>
                    </div>

                    <div id="modal_step_2" class="modal_container">
                        <h2>Felicidades, tu cita ha sido creada exitosamente!</h2>
                        <table>
                            <tr> <th>Médico:</th> <td id="modal_final_medico"></td> </tr>
                            <tr> <th>Horario:</th> <td id="modal_final_horario"></td> </tr>
                            <tr> <th>Método de pago:</th> <td id="modal_final_metodo">tarjeta</td> </tr>
                            <tr> <th>Costo:</th> <td id="modal_final_costo"></td> </tr>
                        </table>
                    </div>

                    <div id="modal_step_1" class="modal_container">
                        <div class="modal_img_container">
                            <div class="modal_img"></div>
                            <span>Médico General</span>
                            <div class="medico_ranking ranking"></div>
                        </div>
                        <div class="modal_info">
                            <!-- <div>
                                <h2></h2>
                            </div> -->
                            <div>
                                <h3>Horario de consulta</h3>
                                <!-- <div>'.$_SESSION['medicos_serch']['ubicacion_txt'].'</div> -->
                                <div class="modal_fecha"></div>
                            </div>
                            <div>
                                <h3>Información de consulta</h3>
                                <div class="modal_precio_container">Precio: <span class="modal_precio"></span></div>
                                
                                <form id="reserva_form">
                                    <input type="hidden" name="cita_latitud" value="'.$_SESSION['medicos_serch']['latitud'].'" />
                                    <input type="hidden" name="cita_longitud" value="'.$_SESSION['medicos_serch']['longitud'].'" />
                                    <input type="hidden" name="cita_mascota_tipo" value="'.$mascota_tipo.'" />
                                    <input type="hidden" name="cita_motivo" value="'.$_SESSION['medicos_serch']['motivo'].'" />
                                    <h3>Método de Pago</h3>
                                    <div class="cont_tipos">
                                        <label for="tipo_tarjeta">
                                            <div class="check_container">
                                                <input type="radio" id="tipo_tarjeta" name="cita_tipo_pago" value="tarjeta" checked />
                                                <img class="check_on" src="'.get_recurso('img').'MEDICOS/check_on.png" />
                                                <img class="check_off" src="'.get_recurso('img').'MEDICOS/check_off.png" />
                                            </div>
                                            <span>Pago con tarjeta</span>
                                        </label>
                                        <label for="tipo_efectivo">
                                            <div class="check_container">
                                                <input type="radio" id="tipo_efectivo" name="cita_tipo_pago" value="efectivo" />
                                                <img class="check_on" src="'.get_recurso('img').'MEDICOS/check_on.png" />
                                                <img class="check_off" src="'.get_recurso('img').'MEDICOS/check_off.png" />
                                            </div>
                                            <span>Pago en Efectivo</span>
                                        </label>
                                    </div>
                                    <div class="form_tarjeta">
                                        <input type="hidden" id="input_modal_precio" name="cita_precio" />
                                        <input type="hidden" name="user_id" value="'.$user_id.'" />
                                        <input type="hidden" id="medico_id" name="medico_id" />
                                        <input type="hidden" id="specialty_id" name="specialty_id" />
                                        <input type="hidden" id="cita_fecha" name="cita_fecha" />

                                        <input type="hidden" id="cita_token" name="cita_token" />

                                        <div class="cont_nombre">
                                            <input type="text" class="vlz_limpiar" name="cita_nombre" placeholder="Nombre" data-openpay-card="holder_name" data-conekta="card[name]" />
                                        </div>

                                        <div class="cont_tarjeta">
                                            <input type="text" class="vlz_limpiar" name="cita_tarjeta" placeholder="Número de tarjeta" data-openpay-card="card_number" data-conekta="card[number]" />
                                        </div>

                                        <div class="cont_datos">
                                            <div class="cont_mes">
                                                <input type="text" class="vlz_limpiar" name="cita_mes" placeholder="Mes (MM)" data-openpay-card="expiration_month" data-conekta="card[exp_month]" />
                                            </div>
                                            <div class="cont_anio">
                                                <input type="text" class="vlz_limpiar" name="cita_anio" placeholder="Año (AA)" data-openpay-card="expiration_year" data-conekta="card[exp_year]" />
                                            </div>
                                            <div class="cont_cvv">
                                                <input type="text" class="vlz_limpiar" name="cita_cvv" placeholder="CVV" data-openpay-card="cvv2" data-conekta="card[cvc]" />
                                            </div>
                                        </div>

                                        <div class="cont_direccion">
                                            <label>Dirección del paciente</label>
                                            <input type="text" name="cita_direccion" placeholder="Dirección del paciente" />
                                        </div>

                                    </div>
                                    <div class="errores_box"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cerrar</button>
                    <button id="btn_reservar" type="button" class="btn btn-primary">Solicitar Consulta</button>
                </div>
            </div>
        </div>
    </div>';

    get_footer(); 
?>


