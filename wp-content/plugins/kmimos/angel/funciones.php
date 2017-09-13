<?php

	if(!function_exists('vlz_get_paginacion')){
        function vlz_get_paginacion($t, $pagina){
            $paginacion = ""; $h = 12; $inicio = $pagina*$h; 
            $fin = $inicio+$h; if( $fin > $t){ $fin = $t; }
            if($t > $h){

                $ps = ceil($t/$h);

                if( $ps < 5 ){
                    for( $i=0; $i<=2; $i++){
                        $active = ( $pagina == $i ) ? " class='active'" : "";
                        $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/".($i)."'> ".($i+1)." </a> </li>";
                    }
                }else{
                    if( $pagina < ($ps-3)){
                        if( $pagina > 0){
                            $in = $pagina-1;
                            $fi = $pagina+1;
                        }else{
                            $in = $pagina;
                            $fi = $pagina+2;
                        }

                        if( $pagina > 1){
                            $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/0'> 1 </a> </li>";
                        }

                        for( $i=$in; $i<=$fi; $i++){
                            $active = ( $pagina == $i ) ? " class='active'" : "";
                            $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/".($i)."'> ".($i+1)." </a> </li>";
                        }
                        $paginacion .= "<li> <a href='#'> ... </a> </li>";
                        $active = ( $pagina == ($ps-1) ) ? " class='active'" : "";
                        $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/".($ps-1)."'> ".($ps)." </a> </li>";

                        if( $pagina > 0 ){
                            $atras = '
                                <li>
                                    <a href="'.$home.'/busqueda/'.($in).'">
                                        <img src="'.getTema().'/images/new/arrow-left-nav.png" width="8">
                                    </a>
                                </li>
                            ';
                        }else{
                            $fi -= 1;
                        }

                        $paginacion = '
                            '.$atras.'
                            '.$paginacion.'
                            <li>
                                <a href="'.$home.'/busqueda/'.($fi).'">
                                    <img src="'.getTema().'/images/new/arrow-right-nav.png" width="8">
                                </a>
                            </li>
                        ';
                    }else{

                        $in = $pagina-2;
                        $fi = ($ps-1);
                        $active = ( $pagina == ($in) ) ? " class='active'" : "";
                        $paginacion = "
                            <li> <a href='".$home."/busqueda/".($in)."'> <img src='".getTema()."/images/new/arrow-left-nav.png' width='8'> </a> </li>
                            <li ".$active."> <a href='".$home."/busqueda/0'> 1 </a> </li>
                            <li> <a href='#'> ... </a> </li>
                        ";
                        for( $i=$in; $i<=$fi; $i++){
                            $active = ( $pagina == $i ) ? " class='active'" : "";
                            $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/".($i)."'> ".($i+1)." </a> </li>";
                        }
                        $paginacion .= '<li> <a href="'.$home.'/busqueda/'.($fi).'"> <img src="'.getTema().'/images/new/arrow-right-nav.png" width="8"> </a> </li>';

                    }
                }

            }
            return array(
                "inicio" => $inicio,
                "fin" => $fin,
                "html" => $paginacion,
            );
        }
    }

    if(!function_exists('get_destacados')){
        function get_destacados(){
            if( !isset($_SESSION) ){ session_start(); }
            $_POST = unserialize( $_SESSION['busqueda'] );
            $ubicacion = explode("_", $_POST["ubicacion"]);
            if( count($ubicacion) > 0 ){ $estado = $ubicacion[0]; }
            global $wpdb;
            $estado_des = $wpdb->get_var("SELECT name FROM states WHERE id = ".$estado);
            $sql_top = "SELECT * FROM destacados WHERE estado = '{$estado}'";
            $tops = $wpdb->get_results($sql_top);
            $top_destacados = ""; $cont = 0;
            foreach ($tops as $value) {
                $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = {$value->cuidador}");
                $data = $wpdb->get_row("SELECT post_title AS nom, post_name AS url FROM wp_posts WHERE ID = {$cuidador->id_post}");
                $nombre = $data->nom;
                $img_url = kmimos_get_foto($cuidador->user_id);
                $url = get_home_url() . "/petsitters/" . $data->url;
                $anios_exp = $cuidador->experiencia;
                if( $anios_exp > 1900 ){
                    $anios_exp = date("Y")-$anios_exp;
                }
                $top_destacados .= '
                    <div class="slide">
                        <div class="item-slide" style="background-image: url('.getTema().'/images/new/km-buscador/slide-01.jpg);">
                            <div class="slide-mask"></div>
                            <div class="slide-content">
                                <div class="slide-price-distance">
                                    <div class="slide-price">
                                        Desde <span>MXN $'.($cuidador->hospedaje_desde*1.2).'</span>
                                    </div>
                                    <!--
                                    <div class="slide-distance">
                                        A 96 km de tu búsqueda
                                    </div>
                                    -->
                                </div>

                                <div class="slide-profile">
                                    <div class="slide-profile-image" style="background-image: url('.$img_url.');"></div>
                                </div>

                                <div class="slide-name">
                                    '.$nombre.'
                                </div>

                                <div class="slide-expertice">
                                    '.$anios_exp.' año(s) de experiencia
                                </div>

                                <div class="slide-ranking">
                                    <div class="km-ranking">
                                        '.kmimos_petsitter_rating($cuidador->id_post).'
                                    </div>
                                </div>

                                <div class="slide-buttons">
                                    <a href="'.$url.'">CONÓCELO +</a>
                                    <a href="'.$url.'">RESERVAR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            }
            return comprimir_styles($top_destacados);
        }
    }

    if(!function_exists('vlz_get_page')){
        function vlz_get_page(){
            $valores = explode("/", $_SERVER['REDIRECT_URL']);
            return $valores[ count($valores)-2 ]+0;
        }
    }

    if(!function_exists('vlz_num_resultados')){
        function vlz_num_resultados(){
            if( !isset($_SESSION)){ session_start(); }
            if( $_SESSION['resultado_busqueda'] ){
                return count($_SESSION['resultado_busqueda'])+0;
            }else{
                return 0;
            }
        }
    }

    if(!function_exists('comprimir_styles')){
        function comprimir_styles($styles){
            $styles = str_replace("\t", "", $styles);
            $styles = str_replace("      ", " ", $styles);
            $styles = str_replace("     ", " ", $styles);
            $styles = str_replace("    ", " ", $styles);
            $styles = str_replace("   ", " ", $styles);
            $styles = str_replace("  ", " ", $styles);
            return $styles = str_replace("\n", " ", $styles);

            // return $styles;
        }
    }
    
    if(!function_exists('get_favoritos')){
        function get_favoritos(){
            $favoritos = array();
            global $wpdb;
            $id_user = get_current_user_id()+0;
            if( $id_user > 0 ){
                $rf = $wpdb->get_row("SELECT * FROM wp_usermeta WHERE ( user_id = $id_user AND meta_key = 'user_favorites'");
                preg_match_all('#"(.*?)"#i', $rf->favoritos, $favoritos);
                if( isset($favoritos[1]) ){
                    $favoritos = $favoritos[1];
                }
            }
            return $favoritos;
        }
    }
    
    if(!function_exists('get_menu_header')){
        function get_menu_header(){

            if( is_user_logged_in() ){

                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;
                $user = new WP_User( $user_id );
                $salir = wp_logout_url( home_url() );

                $MENUS = array(
                    "vendor" => array(
                        array(
                            "url"   => get_home_url()."/perfil-usuario/",
                            "name"  => "Mi Perfil",
                            "icono" => "460"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/mascotas",
                            "name"  => "Mis Mascotas",
                            "icono" => "871"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/favoritos",
                            "name"  => "Cuidadores Favoritos",
                            "icono" => "375"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/historial",
                            "name"  => "Historial",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/descripcion",
                            "name"  => "Descripción",
                            "icono" => "664"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/servicios",
                            "name"  => "Mis Servicios",
                            "icono" => "453"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/disponibilidad",
                            "name"  => "Disponibilidad",
                            "icono_2" => "fa fa-calendar"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/galeria",
                            "name"  => "Mis Fotos",
                            "icono" => "82"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/reservas",
                            "name"  => "Mis Reservas",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/solicitudes",
                            "name"  => "Mis Solicitudes",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => $salir,
                            "name"  => "Cerrar Sesión",
                            "icono" => "476"
                        )
                    ),
                    "subscriber" => array(
                        array(
                            "url"   => get_home_url()."/perfil-usuario/",
                            "name"  => "Mi Perfil",
                            "icono" => "460"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/mascotas",
                            "name"  => "Mis Mascotas",
                            "icono" => "871"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/favoritos",
                            "name"  => "Cuidadores Favoritos",
                            "icono" => "375"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/historial",
                            "name"  => "Historial",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/solicitudes",
                            "name"  => "Mis Solicitudes",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => $salir,
                            "name"  => "Cerrar Sesión",
                            "icono" => "476"
                        )
                    ),
                    "administrator" => array(
                        array(
                            "url"   => get_home_url()."/perfil-usuario/",
                            "name"  => "Mi Perfil",
                            "icono" => "460"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/mascotas",
                            "name"  => "Mis Mascotas",
                            "icono" => "871"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/favoritos",
                            "name"  => "Cuidadores Favoritos",
                            "icono" => "375"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/historial",
                            "name"  => "Historial",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => get_home_url()."/perfil-usuario/solicitudes",
                            "name"  => "Mis Solicitudes",
                            "icono" => "33"
                        ),
                        array(
                            "url"   => get_home_url()."/wp-admin/",
                            "name"  => "Panel de Control",
                            "icono" => "421"
                        ),
                        array(
                            "url"   => $salir,
                            "name"  => "Cerrar Sesión",
                            "icono" => "476"
                        )
                    )
                );

                $MENU["head"] = '<li><a href="#" class="km-nav-link"> <i class="pfadmicon-glyph-632"></i> '.$user->data->display_name.' </a></li>';
                $MENU["head_movil"] = '<li><a href="#" class="km-nav-link"> <i class="pfadmicon-glyph-632"></i> '.$user->data->display_name.' </a></li>';
                $MENU["body"] = "";

                if( $MENUS[ $user->roles[0] ] != "" ){
                    foreach ($MENUS[ $user->roles[0] ] as $key => $value) {
                        if( isset($value["icono"]) ){ $icono = '<i class="pfadmicon-glyph-'.$value["icono"].'"></i> '; }
                        if( isset($value["icono_2"]) ){ $icono = '<i class="'.$value["icono_2"].'"></i> '; }
                        $MENU["body"] .=
                            '<li>
                                <a href="'.$value["url"].'" class="">
                                    '.$icono.'
                                    '.$value["name"].'
                                </a>
                            </li>';
                    }
                }

                $MENU["footer"] = '';

            }else{
                $MENU["body"] = 
                '<li id="separador"></li>'.
                '<li id="login"><a><i class="pfadmicon-glyph-584"></i> Iniciar Sesión</a></li>'.
                '<li id="registrar"><a><i class="pfadmicon-glyph-365"></i> Registrarse</a></li>'.
                '<li id="recuperar"><a><i class="pfadmicon-glyph-889"></i> Contraseña Olvidada</a></li>';
            }

            return $MENU;
        }
    }

    if(!function_exists('kmimos_petsitter_rating')){

        function kmimos_petsitter_rating($post_id, $is_data = false){
            $valoracion = kmimos_petsitter_rating_and_votes($post_id);
            $votes = $valoracion['votes'];
            $rating = $valoracion['rating'];
            if( $is_data ){
                $data = array();
                if($votes =='' || $votes == 0 || $rating ==''){ 
                    for ($i=0; $i<5; $i++){ 
                        $data[] = 0;
                    }
                } else { 
                    $n = ceil($rating);
                    for ($i=0; $i<$n; $i++){ 
                        $data[] = 1;
                    }
                    if($n <= 4){
                        for ($i=$n; $i<5; $i++){ 
                            $data[] = 0;
                        }
                    }
                }
                return $data;
            }else{
                $html = '<div class="rating">';
                if($votes =='' || $votes == 0 || $rating ==''){ 
                    for ($i=0; $i<5; $i++){ 
                        $html .= "<a href='#'></a>";
                    }
                } else { 
                    $n = ceil($rating);
                    for ($i=0; $i<$n; $i++){ 
                        $html .= "<a href='#' class='active'></a>";
                    }
                    if($n <= 4){
                        for ($i=$n; $i<5; $i++){ 
                            $html .= "<a href='#'></a>";
                        }
                    }
                }
                $html .= '</div>';
                return $html;
            }
            return "";
        }
    }

    function get_ficha_cuidador($cuidador, $i, $favoritos, $disenio){
        $img        = kmimos_get_foto($cuidador->user_id);
        $anios_exp  = $cuidador->experiencia; if( $anios_exp > 1900 ){ $anios_exp = date("Y")-$anios_exp; }
        $url        = get_home_url()."/petsitters/".$cuidador->slug;

        if( isset($cuidador->DISTANCIA) ){ $distancia   = 'A '.floor($cuidador->DISTANCIA).' km de tu busqueda'; }

        $anios_exp = $cuidador->experiencia;
        if( $anios_exp > 1900 ){
            $anios_exp = date("Y")-$anios_exp;
        }

        $fav_check = 'false';
        if (in_array($cuidador->id_post, $favoritos)) {
            $fav_check = 'true'; $favtitle_text = esc_html__('Quitar de mis favoritos','kmimos');
        }

        switch ($disenio) {
            case 'list':
                $ficha = '
                    <div class="km-item-resultado active">
                        <div class="km-foto">
                            <div class="km-img" style="background-image: url('.$img.');"></div>
                            <span class="km-contenedor-favorito">
                                <a href="#" class="km-link-favorito active"></a>
                            </span>
                        </div>

                        <div class="km-contenedor-descripcion-opciones">
                            <div class="km-descripcion">
                                <h1><a href="'.$url.'">'.utf8_encode($cuidador->titulo).'</a></h1>

                                <p>'.$anios_exp.' año(s) años de experiencia</p>

                                <div class="km-ranking">
                                    '.kmimos_petsitter_rating($cuidador->id_post).'
                                </div>

                                <div class="km-sellos">
                                    '.vlz_servicios($cuidador->adicionales).'
                                </div>
                            </div>

                            <div class="km-opciones">
                                <div class="precio">Desde MXN $ '.$cuidador->precio.'</div>
                                <a href="'.get_home_url()."/petsitters/".$cuidador->slug.'" class="km-btn-primary-new stroke">CONÓCELO +</a>
                                <a href="'.get_home_url()."/petsitters/".$cuidador->slug.'" class="km-btn-primary-new basic">RESERVA</a>
                            </div>
                        </div>
                    </div>
                ';
            break;
            case 'grid':
                $ficha = '
                    <div class="km-item-resultado">
                        <div class="km-foto">
                            <div class="km-img" style="background-image: url('.$img.');"></div>
                            <span class="km-contenedor-favorito">
                                <a href="#" class="km-link-favorito active"></a>
                            </span>
                        </div>
                        <div class="km-descripcion">
                            <h1><a href="'.$url.'">'.utf8_encode($cuidador->titulo).'</a></h1>
                            <p>'.$anios_exp.' año(s) de experiencia</p>
                            <div class="km-ranking">
                                '.kmimos_petsitter_rating($cuidador->id_post).'
                            </div>
                            <div class="km-sellos">
                                '.vlz_servicios($cuidador->adicionales).'
                            </div>
                            <div class="km-buttons">
                                <a href="'.get_home_url()."/petsitters/".$cuidador->slug.'" >CONÓCELO +</a> <!-- role="button" data-toggle="modal" -->
                                <a href="'.get_home_url()."/petsitters/".$cuidador->slug.'" class="active">RESERVAR</a>
                            </div>
                        </div>
                    </div>
                ';
            break;
        }
    
        return $ficha;
    }

    function get_formulario($POST){
            $servicios = array(
                "hospedaje"      => '<p>Hospedaje<br><sup>Cuidado día y noche</sup></p>', 
                "guarderia"      => '<p>Guardería<br><sup>Cuidado durante el día</sup></p>', 
                "paseos"         => '<p>Paseos<br><sup></sup></p>',
                "adiestramiento" => '<p>Entrenamiento<br><sup></sup></p>'
            );

            $lista_servicios = '<div class="vlz_checkbox_contenedor">';
                foreach($servicios as $key => $value){
                    if( substr($key, 0, 14) == "adiestramiento"){ $xkey = "adiestramiento"; }else{ $xkey = $key; }
                    $lista_servicios .= '
                        <div id="servicio_'.$key.'">
                            <i class="icon-'.$xkey.'"></i>
                            <input type="checkbox" name="servicios[]" value="'.$key.'">
                            '.$value.'
                        </div>';
                }
            $lista_servicios .= '</div>';

            $servicios_adicionales = array(
                'transportacion_sencilla' => array( 
                    'label'=>'Transporte Sencillo',
                    'icon' => 'transporte'
                ),
                'transportacion_redonda' => array( 
                    'label'=>'Transporte Redondo',
                    'icon' => 'transporte2'
                ),
                'bano' => array( 
                    'label'=>'Baño y Secado',
                    'icon' => 'bano'
                ),
                'corte' => array( 
                    'label'=>'Corte de Pelo y Uñas',
                    'icon' => 'peluqueria'
                ),
                'visita_al_veterinario' => array( 
                    'label'=>'Visita al Veterinario',
                    'icon' => 'veterinario'
                ),
                'limpieza_dental' => array( 
                    'label'=>'Limpieza Dental',
                    'icon' => 'limpieza'
                ),
                'acupuntura' => array( 
                    'label'=>'Acupuntura',
                    'icon' => 'acupuntura'
                )
            );

            $lista_servicios_adicionales = '<div class="vlz_checkbox_contenedor">';
                foreach($servicios_adicionales as $key => $value){
                    $lista_servicios_adicionales .= '
                        <div id="servicio_'.$key.'">
                            <i class="icon-'.$value['icon'].'"></i>
                            <input type="checkbox" name="servicios[]" value="'.$key.'">
                            <p>'.$value['label'].'</p>
                        </div>';
                }
            $lista_servicios_adicionales .= '</div>';

            $tamanos = array(
                'pequenos' => '<p>Pequeño<br><sup>0.0cm - 25.0cm</sup></p>', 
                'medianos' => '<p>Mediano<br><sup>25.0cm - 58.0cm</sup></p>', 
                'grandes'  => '<p>Grande<br><sup>58.0cm - 73.0cm</sup></p>', 
                'gigantes' => '<p>Gigante<br><sup>73.0cm - 200.0cm</sup></p>'
            );

            $tamanos_mascotas_form = '<div class="vlz_checkbox_contenedor">';
                foreach($tamanos as $key => $value){
                    $tamanos_mascotas_form .= '
                        <div id="tamanos_'.$key.'">
                            <input type="checkbox" name="tamanos[]" value="'.$key.'">
                            '.$value.'
                        </div>';
                }
            $tamanos_mascotas_form .= '</div>';

            global $wpdb;
            $estados_array = $wpdb->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY name ASC");

            $estados = "<option value=''>Seleccione un estado</option>";
            foreach($estados_array as $estado) { 
                if( $POST['estados'] == $estado->id ){ 
                    $sel = "selected"; 
                }else{ $sel = ""; }
                $estados .= "<option value='".$estado->id."' $sel>".$estado->name."</option>";
            } 

            $estados = utf8_decode($estados);

            if($POST['estados'] != ""){
                $municipios_array = $wpdb->get_results("SELECT * FROM locations WHERE state_id = {$POST['estados']} ORDER BY name ASC");
                $muni = "<option value=''>Seleccione un municipio</option>"; $xxx = 0;
                foreach($municipios_array as $municipio) { 
                    if( $POST['municipios'] == $municipio->id ){
                        $sel = "selected"; 
                    }else{ $sel = ""; }
                    $muni .= "<option value='".$municipio->id."' data-id='".$xxx."' $sel>".$municipio->name."</option>";
                    $xxx++;
                } 
                $muni = utf8_decode($muni);
            }else{
                $mun = "<option value='' selected>Seleccione un estado primero</option>";
            }

            $selects_estados = "
                <div class='vlz_sub_seccion'>
                    <SELECT class='vlz_input' id='estados' name='estados' style='border: solid 1px #CCC;'>
                        {$estados}
                    </SELECT>
                </div>
                <div class='vlz_sub_seccion'>
                    <SELECT class='vlz_input' id='municipios' name='municipios' style='border: solid 1px #CCC;'>
                        {$muni}
                    </SELECT>
                </div>
            ";

            $valoraciones_rangos_1 .= "<option value='' ".selected(0, $POST['rangos'][4], false).">Min</option>";
            $valoraciones_rangos_2 .= "<option value='' ".selected(0, $POST['rangos'][5], false).">Max</option>";
            for ($i=1; $i < 6; $i++) { 
                $valoraciones_rangos_1 .= "<option value='$i' ".selected($i, $POST['rangos'][4], false).">$i</option>";
                $valoraciones_rangos_2 .= "<option value='$i' ".selected($i, $POST['rangos'][5], false).">$i</option>";
            }

            $FORMULARIO = "
            <script> var orderby = '".$POST['orderby']."'; var tipo_busqueda = '".$POST['tipo_busqueda']."'; </script>
            <div id='filtros'></div>
            <form action='".get_home_url()."/wp-content/themes/pointfinder/procesos/busqueda/buscar.php' method='POST' class='vlz_form' id='vlz_form_buscar' style='margin-top: 20px;'>

                <input type='submit' value='Aplicar Filtros' class='theme_button vlz_boton'>

                <div class='vlz_sub_seccion'>
                    <div class='vlz_sub_seccion_titulo'>Ordenar por:</div>
                    <div class='vlz_sub_seccion_interno'>

                        <div class='vlz_contenedor'>
                            <select id='orderby' name='orderby' class='vlz_input'>
                                <option value=''>Seleccione una opción</option>
                                <option value='rating_desc'>Valoración de mayor a menor</option>
                                <option value='rating_asc'>Valoración de menor a mayor</option>
                                <option value='distance_asc'>Distancia al cuidador de cerca a lejos</option>
                                <option value='distance_desc'>Distancia al cuidador de lejos a cerca</option>
                                <option value='price_asc'>Precio del Servicio de menor a mayor</option>
                                <option value='price_desc'>Precio del Servicio de mayor a menor</option>
                                <option value='experience_asc'>Experiencia de menos a más años</option>
                                <option value='experience_desc'>Experiencia de más a menos años</option>
                                <!-- option value='name_asc'>Nombre del Cuidador de la A a la Z</option -->
                                <!-- option value='name_desc'>Nombre del Cuidador de la Z a la A</option -->
                            </select>
                        </div>

                    </div>
                </div>

                <div class='vlz_sub_seccion'>
                    <div class='vlz_sub_seccion_titulo'>Por Nombre</div>
                    <div class='vlz_sub_seccion_interno'>

                        <div class='vlz_contenedor'>
                            <input type='text' name='nombre' value='".$POST['nombre']."' class='vlz_input' placeholder='Buscar por Nombre'>
                        </div>

                    </div>
                </div>
                
                <div class='vlz_sub_seccion'>
                    <div class='vlz_sub_seccion_titulo'>Por Ubicaci&oacute;n</div>
                    <div class='vlz_sub_seccion_interno'>

                        <div class='vlz_contenedor'>
                            <SELECT id='tipo_busqueda' name='tipo_busqueda' class='vlz_input' onchange='vlz_tipo_ubicacion()'>
                                <option value='mi-ubicacion'>Mi Ubicaci&oacute;n</option>
                                <option value='otra-localidad'>Otra Localidad</option>
                            </SELECT>   
                        </div>

                        <div class='vlz_contenedor' id='vlz_estados' style='border: 0;'>
                            {$selects_estados}
                        </div>

                        <div id='vlz_inputs_coordenadas_x' style='display: none;'>

                            <input type='text' id='otra_latitud' name='otra_latitud' value='".$POST['otra_latitud']."' class='vlz_input vlz_medio' placeholder='Latitud'>
                            <input type='text' id='otra_longitud' name='otra_longitud' value='".$POST['otra_longitud']."' class='vlz_input vlz_medio' placeholder='Longitud'>
                            <input type='text' id='otra_distancia' name='otra_distancia' value='".$POST['otra_distancia']."' class='vlz_input' placeholder='Distancia KM'>
            
                            <input type='text' id='latitud'   name='latitud'   value='".$POST['latitud']."'   >
                            <input type='text' id='longitud'  name='longitud'  value='".$POST['longitud']."'  >
                            <input type='text' id='distancia' name='distancia' value='".$POST['distancia']."' >

                        </div>

                    </div>
                </div>
                
                <div class='vlz_sub_seccion'>
                    <div class='vlz_sub_seccion_titulo'>Por Rangos</div>
                    <div class='vlz_sub_seccion_interno'>

                        <label>Rango Hospedaje</label>
                        <div class='vlz_contenedor'>
                            <input type='text' name='rangos[]' value='".$POST['rangos'][0]."' class='vlz_input vlz_medio' placeholder='Min'>
                            <input type='text' name='rangos[]' value='".$POST['rangos'][1]."' class='vlz_input vlz_medio' placeholder='Max'>
                        </div>

                        <label>A&ntilde;os de Experiencia</label>
                        <div class='vlz_contenedor'>
                            <input type='text' name='rangos[]' value='".$POST['rangos'][2]."' class='vlz_input vlz_medio' placeholder='Min'>
                            <input type='text' name='rangos[]' value='".$POST['rangos'][3]."' class='vlz_input vlz_medio' placeholder='Max'>
                        </div>

                        <label>Valoraci&oacute;n de Clientes</label>
                        <div class='vlz_contenedor' style='border-right: 0;'>
                            <select class='vlz_input vlz_medio' name='rangos[]' style='border: solid 1px #CCC;width: calc( 50% - 2px ); margin-right: 1px;'>".$valoraciones_rangos_1."</select>
                            <select class='vlz_input vlz_medio' name='rangos[]' style='border: solid 1px #CCC;width: calc( 50% - 2px ); margin-left: 1px;'>".$valoraciones_rangos_2."</select>
                        </div>
                        
                    </div>
                </div>

                <div class='vlz_sub_seccion'>
                    <div class='vlz_sub_seccion_titulo'>Por Servicios</div>
                    <div class='vlz_sub_seccion_interno'>

                        {$lista_servicios}
                        
                    </div>
                </div>

                <div class='vlz_sub_seccion'>
                    <div class='vlz_sub_seccion_titulo'>Por Servicios Adicionales</div>
                    <div class='vlz_sub_seccion_interno'>

                        {$lista_servicios_adicionales}
                        
                    </div>
                </div>

                <div class='vlz_sub_seccion'>
                    <div class='vlz_sub_seccion_titulo'>Por Tama&ntilde;os</div>
                    <div class='vlz_sub_seccion_interno'>

                        {$tamanos_mascotas_form}
                        
                    </div>
                </div>

                <input type='submit' value='Aplicar Filtros' class='theme_button vlz_boton'>
            </form>";

            return ($FORMULARIO);
    }

    function get_data_home(){
        $HOY = date("Y-m-d");

        $ESTADOS = "
            <option value=''>Seleccione un estado</option>
            <option value='7'>Aguascalientes</option>
            <option value='8'>Baja California</option>
            <option value='9'>Baja California Sur</option>
            <option value='10'>Campeche</option>
            <option value='13'>Chiapas</option>
            <option value='14'>Chihuahua</option>
            <option value='1'>Ciudad de México</option>
            <option value='11'>Coahuila de Zaragoza</option>
            <option value='12'>Colima</option>
            <option value='15'>Durango</option>
            <option value='2'>Estado de México</option>
            <option value='16'>Guanajuato</option>
            <option value='17'>Guerrero</option>
            <option value='18'>Hidalgo</option>
            <option value='3'>Jalisco</option>
            <option value='19'>Michoac&aacute;n de Ocampo</option>
            <option value='20'>Morelos</option>
            <option value='21'>Nayarit</option>
            <option value='4'>Nuevo León</option>
            <option value='22'>Oaxaca</option>
            <option value='23'>Puebla</option>
            <option value='24'>Queretaro</option>
            <option value='25'>Quintana Roo</option>
            <option value='26'>San Luis Potosi</option>
            <option value='27'>Sinaloa</option>
            <option value='28'>Sonora</option>
            <option value='29'>Tabasco</option>
            <option value='30'>Tamaulipas</option>
            <option value='31'>Tlaxcala</option>
            <option value='32'>Veracruz de Ignacio de la Llave</option>
            <option value='33'>Yucatan</option>
            <option value='34'>Zacatecas</option>
        ";
        $servicios = array(
            'hospedaje'      => '<p><span>Hospedaje</span><sup>cuidado día y noche</sup></p>', 
            'guarderia'      => '<p><span>Guardería</span><sup>cuidado durante el día</sup></p>', 
            'paseos'         => '<p><span>Paseos</span></p>',
            'adiestramiento' => '<p><span>Entrenamiento</span></p>'
        ); 
        $SERVICIOS = "";
        foreach($servicios as $key => $value){
            if( substr($key, 0, 14) == 'adiestramiento'){ $xkey = 'adiestramiento'; }else{ $xkey = $key; }
            $SERVICIOS .= "
            <div class='contenedor_servicio'>
                <div class='boton_servicio'>
                    <input type='checkbox' name='servicios[]' id='servicio_cuidador_{$key}' class='servicio_cuidador_{$key}' value='{$key}' data-key='{$key}'>
                    <label for='servicio_cuidador_{$key}'>
                        <i class='icon-{$key}'></i>
                        {$value}
                    </label>
                </div>
            </div>";
        }

        $extras = servicios_adicionales(); $MAS_SERVICIOS = "";
        foreach($extras as $key => $value){
            $MAS_SERVICIOS .= "
            <div class='boton_servicio'>
                <input type='checkbox' name='servicios[]' id='servicio_cuidador_{$key}' class='servicio_cuidador_{$key}' value='{$key}' data-key='{$key}'>
                <label for='servicio_cuidador_{$key}'>
                    <i class='icon-{$value['icon']}'></i>
                    <p>{$value['label']}</p>
                </label>
            </div>";
        }

        $tamanos = array(
            'pequenos' => 'Peque&ntilde;os <br><sub>0.0 cm - 25.0cm</sub>',
            'medianos' => 'Medianos <br><sub>25.0 cm - 58.0 cm</sub>',
            'grandes'  => 'Grandes <br><sub>58.0 cm - 73.0 cm</sub>',
            'gigantes' => 'Gigantes <br><sub>73.0 cm - 200.0 cm</sub>',
        );
        $TAMANOS = "";
        foreach($tamanos as $key => $value){
            $TAMANOS .= "
            <div class='contenedor_servicio contenedor_tamanos'>
                <div class='boton_servicio'>
                    <input type='checkbox' name='tamanos[]' id='tamano_mascota_{$key}' value='{$key}' class='servicio_cuidador_{$key}' data-key='{$key}'>
                    <label for='tamano_mascota_{$key}'>
                        <p>{$value}</p>
                    </label>
                </div>
            </div>";
        }

        global $wpdb; $TESTIMONIOS = ""; $WIDTH_SLIDER = 0;
        $resultado = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_type='testimonials'");
        foreach($resultado as $key => $testimonio){
            $TESTIMONIOS .= "
                <li>
                    <div class='testimonio'>
                        <div class='testimonio_cont_data'>
                            <div class='testimonio_titulo'>{$testimonio->post_title}</div>
                            <div class='testimonio_texto'>{$testimonio->post_content}</div>
                        </div>
                    </div>
                </li>
            ";
            $WIDTH_SLIDER += 100;
        }

        return array(
            "WIDTH_SLIDER" => $WIDTH_SLIDER,
            "TESTIMONIOS" => $TESTIMONIOS,
            "TAMANOS" => $TAMANOS,
            "MAS_SERVICIOS" => $MAS_SERVICIOS,
            "SERVICIOS" => $SERVICIOS,
            "HOY" => $HOY,
            "ESTADOS" => $ESTADOS
        );
    }
?>