<?php
    $pet_id = vlz_get_page();
    $current_pet = kmimos_get_pet_info($pet_id);
    //var_dump($current_pet);
    $photo_pet = (!empty($current_pet['photo']))? "/".$current_pet['photo']: "/wp-content/themes/kmimos/images/noimg.png";

    $current_pet['type'] = explode(",", $current_pet['type']);
    $current_pet['type'] = $current_pet['type'][0];

    $tipos = kmimos_get_types_of_pets();
    $tipos_str = "";
    foreach ( $tipos as $tipo ) {
        $tipos_str .= '<option value="'.$tipo->ID.'"';
        if($tipo->ID == $current_pet['type']) $tipos_str .= ' selected';
        $tipos_str .= '>'.esc_html( $tipo->name ).'</option>';
    }

    global $wpdb;
    $razas = $wpdb->get_results("SELECT * FROM razas ORDER BY nombre ASC");
    $razas_str = "<option value=''>Favor Seleccione</option>";
    foreach ($razas as $value) {
        $razas_str .= '<option value="'.$value->id.'"';
        if($value->id == $current_pet['breed']) $razas_str .= ' selected';
        $razas_str .= '>'.esc_html( $value->nombre ).'</option>';
    }
    $razas_str_gatos = "<option value=1>Gato</option>";

    $generos = kmimos_get_genders_of_pets();
    $generos_str = "";
    foreach ( $generos as $genero ) {
        $generos_str .= '<option value="'.$genero['ID'].'"';
        if($genero['ID'] == $current_pet['gender']) $generos_str .= ' selected';
        $generos_str .= '>'.esc_html( $genero['singular'] ).'</option>';
    }

    
    $tamanos = kmimos_get_sizes_of_pets();
    $tamanos_str = "";
    foreach ( $tamanos as $tamano ) {
        $tamanos_str .= '<option value="'.$tamano['ID'].'"';
        if( $current_pet['type'] != 2608 ){ if($tamano['ID'] == $current_pet['size']) $tamanos_str .= ' selected'; }
        $tamanos_str .= '>'.esc_html( $tamano['name'].' ('.$tamano['desc'].')' ).'</option>';
    }

    $si_no = array('no','si');
    $esterilizado_str = "";
    for ( $i=0; $i<2; $i++ ) {
        $esterilizado_str .= '<option value="'.$i.'"';
        if( isset($current_pet['strerilized']) ){
            if($i == (int)$current_pet['strerilized']) $esterilizado_str .= ' selected';
        }else{
            if($i == (int)$current_pet['sterilized']) $esterilizado_str .= ' selected';
        }
        
        $esterilizado_str .= '>'.strtoupper($si_no[$i]).'</option>';
    }

    $sociable_str = "";
    for ( $i=0; $i<count($si_no); $i++ ) {
        $sociable_str .= '<option value="'.$i.'"';
        if($i == (int)$current_pet['sociable']) $sociable_str .= ' selected';
        $sociable_str .= '>'.strtoupper($si_no[$i]).'</option>';
    }

    $aggresive_humans_str = "";
    for ( $i=0; $i<count($si_no); $i++ ) {
        $aggresive_humans_str .= '<option value="'.$i.'"';
        if($i == (int)$current_pet['aggresive_humans']) $aggresive_humans_str .= ' selected';
        $aggresive_humans_str .= '>'.strtoupper($si_no[$i]).'</option>';
    }

    $aggresive_pets_str = "";
    for ( $i=0; $i<count($si_no); $i++ ) {
        $aggresive_pets_str .= '<option value="'.$i.'"';
        if($i == (int)$current_pet['aggresive_pets']) $aggresive_pets_str .= ' selected';
        $aggresive_pets_str .= '>'.strtoupper($si_no[$i]).'</option>';
    }

    $razas = $razas_str;
    if( $current_pet['type'] == 2608 ){
        $razas = $razas_str_gatos;
    }

    $_comportamiento_gatos = get_post_meta($pet_id, 'comportamiento_gatos', true);
    $_comportamiento_gatos = (array) json_decode($_comportamiento_gatos);

    $comportamientos_db = $wpdb->get_results("SELECT * FROM comportamientos_mascotas");
    foreach ($comportamientos_db as $value) {
        $comportamientos[ $value->slug ] =  $value->nombre;
    }

    $comportamientos_str = "";
    foreach ($comportamientos as $key => $value) {
        if($_comportamiento_gatos[ $key ] == 1){ $check = "vlz_check"; $valor = 1; }else{ $check = "vlz_no_check"; $valor = 0; }
        $comportamientos_str .= '<div class="vlz_input vlz_pin_check '.$check.'" style="padding: 8px 39px 8px 8px;"><input type="hidden" name="comportamiento_gatos_'.$key.'" value="'.$valor.'">'.$comportamientos[$key].'</div>';
    }

    $ocultar_tamanios = '';
    if( $current_pet['type'] == 2608 ){
        $ocultar_tamanios = ' style="display: none;" ';
    }

    $CONTENIDO .= '
    <input type="hidden" name="accion" value="update_mascota" />
    <input type="hidden" name="pet_id" value="'.$pet_id.'" />
    <input type="hidden" name="user_id" value="'.$user_id.'" />

    <div id="razas_perros" style="display: none;">'.$razas_str.'</div>
    <div id="razas_gatos" style="display: none;">'.$razas_str_gatos.'</div>

    <section>
        <div class="vlz_img_portada_perfil">
            <div class="vlz_img_portada_fondo vlz_rotar" style="background-image: url('.get_home_url().'/'.$photo_pet.');"></div>
            <div class="vlz_img_portada_normal vlz_rotar" style="background-image: url('.get_home_url().'/'.$photo_pet.');"></div>
            <div class="vlz_img_portada_cargando vlz_cargando" style="background-image: url('.getTema().'/images/cargando.gif);"></div>
            <div class="vlz_cambiar_portada">
                <i class="fa fa-camera" aria-hidden="true"></i>
                Cambiar Foto
                <input type="file" id="portada" name="xportada" accept="image/*" />
            </div>
            <div id="rotar_i" class="btn_rotar" style="display: none;" data-orientacion="left"> <i class="fa fa-undo" aria-hidden="true"></i> </div>
            <div id="rotar_d" class="btn_rotar" style="display: none;" data-orientacion="right"> <i class="fa fa-repeat" aria-hidden="true"></i> </div>
        </div>
        <input type="hidden" class="vlz_img_portada_valor vlz_rotar_valor" id="portada" name="portada" data-valid="requerid" />

        <div class="btn_aplicar_rotar" style="display: none;"> Aplicar Cambio </div>
    </section>

    <div class="inputs_containers" style="padding-bottom: 0px;">
        <section>
            <label for="pet_name" class="lbl-text">'.esc_html__('Nombre de la Mascota','kmimos').':
            <span id="spanpet_name" class="hidden">*</span></label>
            <label class="lbl-ui">
                <input type="hidden" name="pet_id" value="'.$current_pet['pet_id'].'" />
                <input type="text" name="pet_name" class="input" value="'.$current_pet['name'].'" />
            </label>
        </section>

        <section>
            <label for="pet_birthdate" class="lbl-text">'.esc_html__('Fecha de nacimiento','kmimos').':
            <span class="hidden" id="spanpet_birthdate">*</span></label>
            <label class="lbl-ui">
                <input 
                    type="text" 
                    id="pet_birthdate" 
                    name="pet_birthdate" 
                    placeholder="dd/mm/aaaa" 
                    value="'.date('d/m/Y', strtotime( str_replace("/", "-", $current_pet['birthdate']))).'" 
                    class="km-input-custom km-input-date date_from" 
                    readonly
                />
            </label>
        </section>
    </div>

    <div class="inputs_containers row_3" style="padding-bottom: 0px;">
   
        <section>
            <label for="pet_type" class="lbl-text">'.esc_html__('Tipo de Mascota','kmimos').':
            <span class="hidden" id="spanpet_type">*</span></label>
            <label class="lbl-ui">
                <select name="pet_type" class="input" id="pet_type" />
                    <option value="">Favor Seleccione</option>
                    '.$tipos_str.'
                </select>
            </label>
        </section>
    
        <section>
            <label for="pet_breed" class="lbl-text">'.esc_html__('Raza de la Mascota','kmimos').':
            <span class="hidden" id="spanpet_breed">*</span></label>
            <label class="lbl-ui">
                <select id="pet_breed" name="pet_breed" class="input" />
                    '.$razas.'
                </select>
            </label>
        </section>
   
        <section>
            <label for="pet_colors" class="lbl-text">'.esc_html__('Colores de la Mascota','kmimos').':
            <span class="hidden" id="spanpet_colors">*</span></label>
            <label class="lbl-ui">
                <input type="text" name="pet_colors" class="input" value="'.$current_pet['colors'].'" />
            </label>
        </section>

        <section>
            <label for="pet_gender" class="lbl-text">'.esc_html__('Género de la mascota','kmimos').':
            <span class="hidden" id="spanpet_gender">*</span></label>
            <label class="lbl-ui">
                <select name="pet_gender" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$generos_str.'
                </select>
            </label>
        </section>

        <section id="tamanio" '.$ocultar_tamanios.'>
            <label for="pet_size" class="lbl-text">'.esc_html__('Tamaño de la Mascota','kmimos').':
            <span class="hidden" id="spanpet_size">*</span></label>
            <label class="lbl-ui">
                <select name="pet_size" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$tamanos_str.'
                </select>
            </label>
        </section>

        <section>
            <label for="pet_sterilized" class="lbl-text">'.esc_html__('Mascota Esterilizada','kmimos').':
            <span class="hidden" id="spanpet_sterilized">*</span></label>
            <label class="lbl-ui">
                <select name="pet_sterilized" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$esterilizado_str.'
                </select>
            </label>
        </section>

        <section>
            <label for="pet_sociable" class="lbl-text">'.esc_html__('Mascota Sociable','kmimos').':
            <span class="hidden" id="spanpet_sociable">*</span></label>
            <label class="lbl-ui">
                <select name="pet_sociable" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$sociable_str.'
                </select>
            </label>
        </section>

        <section>
            <label for="aggresive_humans" class="lbl-text">'.esc_html__('Agresiva con Humanos','kmimos').':
            <span class="hidden" id="spanaggresive_humans">*</span></label>
            <label class="lbl-ui">
                <select name="aggresive_humans" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$aggresive_humans_str.'
                </select>
            </label>
        </section>

        <section>
            <label for="aggresive_pets" class="lbl-text">'.esc_html__('Agresiva c/otras Mascotas','kmimos').':<span class="hidden" id="spanaggresive_pets">*</span></label>
            <label class="lbl-ui">
                <select name="aggresive_pets" class="input" />
                    <option value="">Favor Seleccione</option>
                    '.$aggresive_pets_str.'
                </select>
            </label>
        </section>
    </div>

    <section id="otras_opciones" style="padding: 0px 5px 10px;">
        <label for="pet_observations" class="lbl-text">'.esc_html__('Opciones de comportamiento para gatos','kmimos').':</label>
        <label class="lbl-ui">
            '.$comportamientos_str.'
        </label>
        <div class="error_seleccionar_uno no_error">
            Debe seleccionar al menos un comportamiento
        </div>
    </section>

    <section style="padding: 0px 5px 10px;">
        <label for="pet_observations" class="lbl-text">'.esc_html__('Observaciones','kmimos').':</label>
        <label class="lbl-ui">
            <textarea name="pet_observations" class="textarea">'. $current_pet['observations'].'</textarea>
        </label>
    </section>';

    if( $current_pet['type'] == '2608' ){
        $CONTENIDO .= '
            <script>
                jQuery("#otras_opciones").css("display", "block");
            </script>
        ';
    }
?>