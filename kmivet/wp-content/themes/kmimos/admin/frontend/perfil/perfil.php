<?php
    global $wpdb;

    $userdata = get_user_meta($user_id);

    $referred = $userdata['user_referred'][0];

    $opciones = get_referred_list_options(); $ref_str = "";
    foreach($opciones as $key=>$value){
        $selected = ($referred==$key)? ' selected':'';
        $ref_str .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
    }

    echo '
        <form id="form_perfil" autocomplete="off" enctype="multipart/form-data">
            <input type="hidden" name="accion" value="perfil" />
            <input type="hidden" name="user_id" value="'.$user_id.'" />
            <input type="hidden" name="core" value="SI" />
            <input type="hidden" id="sub_path" name="sub_path" value="'.$img_perfil["sub_path"].'" />
            <input type="hidden" class="vlz_img_portada_valor vlz_rotar_valor" name="portada" data-valid="requerid" />

            <h1>Mi Perfil</h1>
    
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

            <div class="container_btn">
                <input type="submit" id="btn_actualizar" class="km-btn-primary" value="Actualizar">
                <div class="perfil_cargando" style="background-image: url('.getTema().'/images/cargando.gif);" ></div>
            </div>
        </form>
    ';
?> 
