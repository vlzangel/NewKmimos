<?php
    global $wpdb;

    $userdata = get_user_meta($user_id);
    $_banco = $wpdb->get_var("select banco from cuidadores where user_id = ".$user_id);
    $datos_banco = unserialize($_banco);

    $referred = $userdata['user_referred'][0];

    $opciones = get_referred_list_options(); $ref_str = "";
    foreach($opciones as $key=>$value){
        $selected = ($referred==$key)? ' selected':'';
        $ref_str .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
    }

    $user_recibir_fotos = $userdata['user_recibir_fotos'][0];

    $opciones = array(
        "1" => "Si, 2 veces al d&iacute;a",
        "2" => "Si, 1 veces al d&iacute;a",
        "3" => "Si, 1 veces cada 2 d&iacute;as",
        "4" => "No, no deseo recibir fotos"
    );
    $recibir_fotos = "";
    foreach($opciones as $key => $value){
        $selected = ($user_recibir_fotos == $key) ? ' selected':'';
        $recibir_fotos .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
    }

    if (!isset($_SESSION)) { session_start(); }
    if( $_SESSION["nuevo_registro"] == "YES" ){
        $pixel = "
            <script> fbq ('track','CompleteRegistration'); </script>
        ";
        $_SESSION["nuevo_registro"] = "";
        unset($_SESSION["nuevo_registro"]);
    }else{
        $pixel = "";
    }

    $tipoUsuario = unserialize($userdata['wp_capabilities'][0]);

/*    echo "<pre>";
        print_r($tipoUsuario);
    echo "</pre>";*/

    $validacionesDescripcion = '';
    if( isset($tipoUsuario['vendor']) ){
        $validacionesDescripcion = '
            data-valid="min:200"
            data-title="La descripci&oacute;n debe tener al menos 200 caracteres" 
            data-toggle="tooltip"
            title="Cuentanos sobre ti, tus cualidades y porque deberían permitirte cuidar sus perritos"
        ';
    }

    $CONTENIDO .=  $pixel.'
        <input type="hidden" name="accion" value="perfil" />
        <input type="hidden" name="user_id" value="'.$user_id.'" />
        <input type="hidden" name="core" value="SI" />
        <input type="hidden" id="sub_path" name="sub_path" value="'.$img_perfil["sub_path"].'" />

        <h1 style="margin: 0px; padding: 0px;">Mi Perfil</h1><hr style="margin: 5px 0px 10px;">
        <section>
            <div class="vlz_img_portada_perfil">
                <div class="vlz_img_portada_fondo vlz_rotar" style="background-image: url('.$avatar.');"></div>
                <div class="vlz_img_portada_normal vlz_rotar" style="background-image: url('.$avatar.');"></div>
                <div class="vlz_img_portada_cargando vlz_cargando" style="background-image: url('.getTema().'/images/cargando.gif);"></div>
                <div class="vlz_cambiar_portada">
                    <i class="fa fa-camera" aria-hidden="true"></i>
                    Cargar Foto
                    <input type="file" id="portada" name="xportada" accept="image/*" />
                </div>
                <div id="rotar_i" class="btn_rotar" style="display: none;" data-orientacion="left"> <i class="fa fa-undo" aria-hidden="true"></i> </div>
                <div id="rotar_d" class="btn_rotar" style="display: none;" data-orientacion="right"> <i class="fa fa-repeat" aria-hidden="true"></i> </div>
            </div>
            <input type="hidden" class="vlz_img_portada_valor vlz_rotar_valor" name="portada" data-valid="requerid" />

            <div class="btn_aplicar_rotar" style="display: none;"> Aplicar Cambio </div>
        </section>

        <div class="inputs_containers">

            <section>
                <label for="firstname" class="lbl-text">'.esc_html__('Nombres','kmimos').':</label>
                <label class="lbl-ui">
                    <input type="text" id="first_name" name="first_name" value="'.$userdata['first_name'][0].'" data-valid="requerid" autocomplete="off" />
                </label>
            </section>

            <section>
                <label for="lastname" class="lbl-text">'.esc_html__('Apellidos','kmimos').':</label>
                <label class="lbl-ui">
                    <input type="text" id="last_name" name="last_name" value="'.$userdata['last_name'][0].'" data-valid="requerid" autocomplete="off" />
                </label>
            </section>

            <section style="display: none;">
                <label for="nickname" class="lbl-text">'.esc_html__('Apodo (Nombre a mostrar)','kmimos').':</label>
                <label class="lbl-ui">
                    <input  type="text" id="nickname" name="nickname" value="'.$userdata['nickname'][0].'" data-valid="requerid" autocomplete="off" />
                </label>
            </section>

            <section>
                <label for="phone" class="lbl-text">'.esc_html__('Teléfono','kmimos').':</label>
                <label class="lbl-ui">
                    <input type="number" id="phone" name="phone" data-title="El teléfono es requerido y debe tener al menos 10 digitos" value="'.$userdata['user_phone'][0].'" data-valid="requerid,min:10" autocomplete="off" />
                </label>
            </section>

            <section>
                <label for="mobile" class="lbl-text">'.esc_html__('Móvil','kmimos').':</label>
                <label class="lbl-ui">
                    <input type="number" id="mobile" name="mobile" data-title="El teléfono es requerido y debe tener al menos 10 digitos" value="'.$userdata['user_mobile'][0].'" data-valid="requerid,min:10" autocomplete="off" />
                </label>
            </section>

            <section>
                <label for="referred" class="lbl-text">'.esc_html__('¿Como nos conoció?','kmimos').':</label>
                <label class="lbl-ui">
                    <select id="referred" name="referred" data-valid="requerid" >
                        <option value="">Por favor seleccione</option>
                        '.$ref_str.'
                    </select>
                </label>
            </section>

            <section>
                <label for="user_recibir_fotos" class="lbl-text">'.esc_html__('¿Deseas recibir fotos durante tus reservas?','kmimos').':</label>
                <label class="lbl-ui">
                    <select id="user_recibir_fotos" name="user_recibir_fotos" data-valid="requerid" >
                        <option value="">Por favor seleccione</option>
                        '.$recibir_fotos.'
                    </select>
                </label>
            </section>
';
if( is_petsitters() ){
    $CONTENIDO .='
                <div>
                    <label for="banco" class="lbl-text">'.esc_html__('Banco','kmimos').':</label>
                    <label class="lbl-ui">
                        <!-- input type="text" id="banco" name="banco" value="'.$userdata['banco'][0].'" data-valid="requerid" autocomplete="off" / -->
                        <select id="banco" name="banco" data-valid="requerid" >
                            <option value="">SELECCIONE UN BANCO</option>
                            <option values="BBVA BANCOMER">BBVA BANCOMER </option> 
                            <option values="BANAMEX">BANAMEX </option> 
                            <option values="SANTANDER">SANTANDER </option> 
                            <option values="SCOTIABANK">SCOTIABANK </option> 
                            <option values="BANCO DEL BAJIO">BANCO DEL BAJIO </option> 
                            <option values="BANORTE">BANORTE </option> 
                            <option values="BANCO HSBC">BANCO HSBC </option> 
                            <option values="BANCO INBURSA">BANCO INBURSA </option> 
                            <option values="BANCO AZTECA">BANCO AZTECA </option> 
                            <option values="BANCA MIFEL">BANCA MIFEL </option> 
                            <option values="BANCA AFIRME">BANCA AFIRME </option> 
                            <option values="BANSI">BANSI </option> 
                            <option values="BANK OF AMERICA">BANK OF AMERICA </option> 
                            <option values="BANREGIO">BANREGIO </option> 
                            <option values="BANJERCITO">BANJERCITO </option> 
                            <option values="BANCO INTERACCIONES">BANCO INTERACCIONES </option> 
                            <option values="AMERICAN EXPRESS">AMERICAN EXPRESS </option> 
                            <option values="BANCO INVEX">BANCO INVEX </option> 
                            <option values="BANCO VE POR MAS">BANCO VE POR MAS </option> 
                            <option values="ING BANCO">ING BANCO </option> 
                            <option values="COMPARTAMOS">COMPARTAMOS </option> 
                            <option values="BANCO MULTIVA">BANCO MULTIVA </option> 
                            <option values="BANCOPPEL">BANCOPPEL </option> 
                            <option values="AHORRO FAMSA">AHORRO FAMSA </option> 
                            <option values="AUTOFIN">AUTOFIN </option> 
                            <option values="MONEX">MONEX </option> 
                            <option values="JP MORGAN">JP MORGAN </option> 
                            <option values="PRUDENTIAL BANK">PRUDENTIAL BANK </option> 
                            <option values="BANCO VOLKSWAGEN">BANCO VOLKSWAGEN </option> 
                            <option values="BANCO DE MEXICO">BANCO DE MEXICO </option> 
                            <option values="ABC CAPITAL">ABC CAPITAL </option> 
                            <option values="ACTINVER">ACTINVER </option> 
                            <option values="BANCO BASE">BANCO BASE </option> 
                            <option values="BANCO CREDIT SUISSE">BANCO CREDIT SUISSE </option> 
                            <option values="BANCO FINTERRA">BANCO FINTERRA </option> 
                            <option values="BANCO FORJADORES">BANCO FORJADORES </option> 
                            <option values="BANCO INMOBILIARIO MEXICANO">BANCO INMOBILIARIO MEXICANO </option> 
                            <option values="BANCO PAGATODO">BANCO PAGATODO </option> 
                            <option values="UNIÓN PROGRESO">UNIÓN PROGRESO </option> 
                            <option values="BANCO SABADELL">BANCO SABADELL </option> 
                            <option values="BANCREA">BANCREA </option> 
                            <option values="BANK OF CHINA MEXICO">BANK OF CHINA MEXICO </option> 
                            <option values="BANK OF TOKYO MITSUBISHI">BANK OF TOKYO MITSUBISHI </option> 
                            <option values="BANKAOOL">BANKAOOL </option> 
                            <option values="BARCLAYS BANK MEXICO">BARCLAYS BANK MEXICO </option> 
                            <option values="CIBANCO">CIBANCO </option> 
                            <option values="CONSUBANCO">CONSUBANCO </option> 
                            <option values="DEUTSCHE BANK MEXICO">DEUTSCHE BANK MEXICO </option> 
                            <option values="FUNDACION DONDE BANCO">FUNDACION DONDE BANCO </option> 
                            <option values="ICBC MEXICO">ICBC MEXICO </option> 
                            <option values="INTERCAM BANCO">INTERCAM BANCO </option> 
                            <option values="INVESTA BANCO">INVESTA BANCO </option> 
                            <option values="MIZUHO BANCO">MIZUHO BANCO </option> 
                            <option values="SHINHAN BANCO">SHINHAN BANCO </option> 
                            <option values="UBS BANCO MEXICO">UBS BANCO MEXICO </option> 
                            <option values="IXE BANCO">IXE BANCO </option>
                        </select>
                    </label>
                </div>
                <section>
                    <label for="titular" class="lbl-text">'.esc_html__('Nombre del titular','kmimos').':</label>
                    <label class="lbl-ui">
                        <input type="text" id="titular" name="titular" value="'.$datos_banco['titular'].'" data-valid="requerid" autocomplete="off" />
                    </label>
                </section>
                <section>
                    <label for="banco_cuenta" class="lbl-text">'.esc_html__('No. de Cuenta bancaria','kmimos').':</label>
                    <label class="lbl-ui">
                        <input type="text" id="banco_cuenta" name="banco_cuenta" value="'.$datos_banco['cuenta'].'" data-valid="requerid" autocomplete="off" />
                    </label>
                </section>
    ';
}
$CONTENIDO .='
            <section class="container_full">
                <label for="descr" class="lbl-text">'.esc_html__('Información biográfica','kmimos').':</label>
                <label class="lbl-ui">
                    <textarea 
                        id="descr" 
                        name="descr"
                        '.$validacionesDescripcion.'
                    >'.$userdata['description'][0].'</textarea>
                </label>
            </section>

            <section>
                <label for="username" class="lbl-text">'.esc_html__('Usuario','kmimos').':</label>
                <label class="lbl-ui">
                    <input type="text" id="username" value="'.$current_user->user_login.'" disabled />
                    <input type="hidden" name="username" value="'.$current_user->user_login.'" />
                </label>
            </section>

            <section>
                <label for="email" class="lbl-text">'.esc_html__('Correo Electrónico','kmimos').':</label>
                <label class="lbl-ui">
                    <input  type="email" id="email" value="'.$current_user->user_email.'" disabled />
                </label>
            </section>

            <section>
                <label for="password" class="lbl-text">'.esc_html__('Nueva contraseña','kmimos').':</label>
                <label class="lbl-ui">
                    <input 
                        type="password"
                        placeholder="Contraseña"
                        data-title="Las contraseñas deben ser iguales"
                        autocomplete="off" 
                        name="password" 
                        id="password" 
                        class="clv" 
                        data-valid="equalTo:password2"
                    />
                </label>
            </section>

            <section>
                <label for="password2" class="lbl-text">'.esc_html__('Repita la nueva contraseña','kmimos').':</label>
                <label class="lbl-ui">
                    <input 
                        type="password"
                        placeholder="Contraseña"
                        data-title="Las contraseñas deben ser iguales"
                        autocomplete="off" 
                        name="password2" 
                        id="password2" 
                        class="clv" 
                        data-valid="equalTo:password" 
                    />
                </label>
            </section>

            

        </div>
    ';
?> 