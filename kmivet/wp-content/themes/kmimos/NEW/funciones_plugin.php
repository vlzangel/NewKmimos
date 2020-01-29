<?php

    if(!function_exists('vlz_get_page')){
        function vlz_get_page(){
            $valores = explode("/", $_SERVER['REDIRECT_URL']);
            return $valores[ count($valores)-2 ]+0;
        }
    }

    if(!function_exists('kmimos_get_info_syte')){
        function kmimos_get_info_syte(){
            return array(
                "pais"      => "México",
                "titulo"    => "Kmimos México",
                "email"     => "contactomex@kmimos.la",

                "telefono" => "(01) 55 3137 4829",
                "telefono_sincosto" => "(01) 55 3137 4829",
                "whatsapp"=> "+52 (33) 1261 4186", 

                "twitter"   => "kmimosmx",
                "facebook"  => "Kmimosmx",
                "instagram" => "kmimosmx",
                "mon_izq" => "",
                "mon_der" => "$"
            );
        }
    }
    
	if(!function_exists('vlz_get_paginacion')){
        function vlz_get_paginacion($t, $pagina, $count_resultados=0){
            $pagina--;
            if($pagina < 0){
                $pagina = 0;
            }
            $home = get_home_url();
            $paginacion = ""; $h = 12; $inicio = $pagina*$h; 
            if( $inicio > $count_resultados && $inicio >= 0 ){
                $pagina = 0;
                $inicio = 0;
            }
            $fin = $inicio+$h; if( $fin > $t){ $fin = $t; }
            if($t > $h){

                $ps = ceil($t/$h);

                if( $ps < 5 ){
                    for( $i=0; $i<$ps; $i++){
                        $active = ( $pagina == $i ) ? " class='active'" : "";
                        $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/".($i+1)."'> ".($i+1)." </a> </li>";
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
                            $paginacion .= "<li ".$active."> <a href='".$home."/busqueda/".($i+1)."'> ".($i+1)." </a> </li>";
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

    if(!function_exists('update_cupos')){
        function update_cupos($data, $accion = ''){
            global $wpdb;
            $db = $wpdb;

            // $accion => No usado

            if( is_array($data) ){
                extract($data);
            }else{
                $id_orden = $data;
                $reserva = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_type = 'wc_booking' AND post_parent = '".$id_orden."'");
                $id_reserva = $reserva->ID;
                $metas_reserva = get_post_meta( $id_reserva );
                $servicio = $metas_reserva['_booking_product_id'][0];
            }

            $actual = date( 'YmdHis', time() );
            $hoy = date( 'Y-m-d', time() );

            $sql = "
                SELECT 
                    reserva.ID               AS id, 
                    servicio.post_author     AS autor, 
                    servicio.ID              AS servicio_id, 
                    tipo.slug                AS servicio_tipo, 
                    servicio.post_name       AS servicio, 
                    DATE_FORMAT(startmeta.meta_value,'%d-%m-%Y') AS inicio, 
                    DATE_FORMAT(endmeta.meta_value,'%d-%m-%Y') AS fin,
                    acepta.meta_value        AS acepta,
                    mascotas.meta_value      AS mascotas,
                    reserva.post_status      AS status

                FROM wp_posts AS reserva

                LEFT JOIN wp_postmeta as startmeta     ON ( reserva.ID      = startmeta.post_id         )
                LEFT JOIN wp_postmeta as endmeta       ON ( reserva.ID      = endmeta.post_id           )
                LEFT JOIN wp_postmeta as mascotas      ON ( reserva.ID      = mascotas.post_id          )
                LEFT JOIN wp_postmeta as servicio_id   ON ( reserva.ID      = servicio_id.post_id       )
                LEFT JOIN wp_posts    as servicio      ON ( servicio.ID     = servicio_id.meta_value    )
                LEFT JOIN wp_postmeta as acepta        ON ( acepta.post_id  = servicio.ID               )
                LEFT JOIN wp_term_relationships as relacion ON ( relacion.object_id = servicio.ID )
                LEFT JOIN wp_terms as tipo ON ( tipo.term_id = relacion.term_taxonomy_id )

                WHERE 
                    reserva.post_type       = 'wc_booking'              AND 
                    startmeta.meta_key      = '_booking_start'          AND 
                    endmeta.meta_key        = '_booking_end'            AND 
                    servicio_id.meta_key    = '_booking_product_id'     AND 
                    acepta.meta_key         = '_wc_booking_qty'         AND 
                    mascotas.meta_key       = '_booking_persons'        AND 
                    (
                        reserva.post_status NOT LIKE '%cancelled%'  AND
                        reserva.post_status NOT LIKE '%cart%'       AND
                        reserva.post_status NOT LIKE '%modified%'   AND
                        reserva.post_status NOT LIKE '%unpaid%' 
                    ) AND  (
                        startmeta.meta_value >= '{$actual}' OR
                        endmeta.meta_value >= '{$actual}'
                    ) AND
                    relacion.term_taxonomy_id != 28 AND 
                    servicio_id.meta_value = '{$servicio}'
            ";

            $resultados = $db->get_results($sql);

            $data_cupos = [];
            foreach ($resultados as $key => $reserva) {
                $mascotas = 0;
                $temp = unserialize( $reserva->mascotas);
                foreach ($temp as $cant) { $mascotas += $cant; }
                $ini = strtotime( $reserva->inicio );
                $fin = strtotime( $reserva->fin );
                for ($i=$ini; $i < $fin; $i += 86400 ) { 
                    $data_cupos[ $reserva->servicio_id ][ date("Y-m-d", $i) ] += $mascotas;
                }
            }

            $db->query("UPDATE cupos SET cupos = 0 WHERE servicio = ".$servicio);

            $sql = '';
            $temp_cupos = [];
            foreach ($data_cupos as $key => $fechas) {
                foreach ($fechas as $fecha => $cupos) {
                    $temp_cupos[ $cupos ][ $key ][] = $fecha;
                }
            }

            foreach ($temp_cupos as $cupos => $servicios) {

                foreach ($servicios as $servicio => $fechas ) {
                    
                    foreach ($fechas as $fecha) {

                        $existe = $db->get_var("SELECT * FROM cupos WHERE servicio = '{$servicio}' AND fecha = '{$fecha}'");
                        if( $existe !== null ){
                            $db->query("UPDATE cupos SET cupos = {$cupos} WHERE servicio = '{$servicio}' AND fecha = '{$fecha}' ");
                        }else{
                            $producto = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '{$servicio}'");
                            $tipo = explode("-", $producto->post_name); $tipo = $tipo[0];
                            $autor_user_id = $producto->post_author;
                            $acepta = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_qty'");
                            $sql = "
                                INSERT INTO cupos VALUES (
                                    NULL,
                                    '{$autor_user_id}',
                                    '{$servicio}',
                                    '{$tipo}',
                                    '{$fecha}',
                                    '{$cupos}',
                                    '{$acepta}',
                                    '0',        
                                    '0'        
                                );
                            ";
                            $db->query($sql);
                        }

                    }

                }

            }

            $wpdb->query("UPDATE cupos SET full = 1 WHERE cupos >= acepta");
            $wpdb->query("UPDATE cupos SET full = 0 WHERE cupos < acepta");

        }
    }

    if(!function_exists('update_cupos_user')){
        function update_cupos_user($user_id){
            global $wpdb;
            $db = $wpdb;

            $actual = date( 'YmdHis', time() );

            $sql = "
                SELECT 
                    reserva.ID               AS id, 
                    servicio.post_author     AS autor, 
                    servicio.ID              AS servicio_id, 
                    tipo.slug                AS servicio_tipo, 
                    servicio.post_name       AS servicio, 
                    DATE_FORMAT(startmeta.meta_value,'%d-%m-%Y') AS inicio, 
                    DATE_FORMAT(endmeta.meta_value,'%d-%m-%Y') AS fin,
                    acepta.meta_value        AS acepta,
                    mascotas.meta_value      AS mascotas,
                    reserva.post_status      AS status

                FROM wp_posts AS reserva

                LEFT JOIN wp_postmeta as startmeta     ON ( reserva.ID      = startmeta.post_id         )
                LEFT JOIN wp_postmeta as endmeta       ON ( reserva.ID      = endmeta.post_id           )
                LEFT JOIN wp_postmeta as mascotas      ON ( reserva.ID      = mascotas.post_id          )
                LEFT JOIN wp_postmeta as servicio_id   ON ( reserva.ID      = servicio_id.post_id       )
                LEFT JOIN wp_posts    as servicio      ON ( servicio.ID     = servicio_id.meta_value    )
                LEFT JOIN wp_postmeta as acepta        ON ( acepta.post_id  = servicio.ID               )

                LEFT JOIN wp_term_relationships as relacion ON ( relacion.object_id = servicio.ID )
                LEFT JOIN wp_terms as tipo ON ( tipo.term_id = relacion.term_taxonomy_id )

                WHERE 
                    reserva.post_type       = 'wc_booking'              AND 
                    startmeta.meta_key      = '_booking_start'          AND 
                    endmeta.meta_key        = '_booking_end'            AND 
                    servicio_id.meta_key    = '_booking_product_id'     AND 
                    acepta.meta_key         = '_wc_booking_qty'         AND 
                    mascotas.meta_key       = '_booking_persons'        AND 
                    (
                        reserva.post_status NOT LIKE '%cancelled%'  AND
                        reserva.post_status NOT LIKE '%cart%'       AND
                        reserva.post_status NOT LIKE '%modified%'   AND
                        reserva.post_status NOT LIKE '%unpaid%' 
                    ) AND  (
                        endmeta.meta_value >= '{$actual}'
                    ) AND
                    relacion.term_taxonomy_id != 28 AND 
                    servicio.post_author = '{$user_id}'
            ";

            $resultados = $db->get_results($sql);

            $data_cupos = [];
            foreach ($resultados as $key => $reserva) {
                $mascotas = 0;
                $temp = unserialize( $reserva->mascotas);
                foreach ($temp as $cant) { $mascotas += $cant; }
                $ini = strtotime( $reserva->inicio );
                $fin = strtotime( $reserva->fin );
                for ($i=$ini; $i < $fin; $i += 86400 ) { 
                    $data_cupos[ $reserva->servicio_id ][ date("Y-m-d", $i) ] += $mascotas;
                }
            }

            $db->query("UPDATE cupos SET cupos = 0 WHERE cuidador = ".$user_id);

            $sql = '';
            $temp_cupos = [];
            foreach ($data_cupos as $key => $fechas) {
                foreach ($fechas as $fecha => $cupos) {
                    $temp_cupos[ $cupos ][ $key ][] = $fecha;
                }
            }

            foreach ($temp_cupos as $cupos => $servicios) {

                foreach ($servicios as $servicio => $fechas ) {
                    
                    foreach ($fechas as $fecha) {

                        $existe = $db->get_var("SELECT * FROM cupos WHERE servicio = '{$servicio}' AND fecha = '{$fecha}'");
                        if( $existe !== null ){
                            $db->query("UPDATE cupos SET cupos = {$cupos} WHERE servicio = '{$servicio}' AND fecha = '{$fecha}' ");
                        }else{
                            $producto = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '{$servicio}'");
                            $tipo = explode("-", $producto->post_name); $tipo = $tipo[0];
                            $autor_user_id = $producto->post_author;
                            $acepta = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_qty'");
                            $sql = "
                                INSERT INTO cupos VALUES (
                                    NULL,
                                    '{$autor_user_id}',
                                    '{$servicio}',
                                    '{$tipo}',
                                    '{$fecha}',
                                    '{$cupos}',
                                    '{$acepta}',
                                    '0',        
                                    '0'        
                                );
                            ";
                            $db->query($sql);
                        }

                    }

                }

            }

            $wpdb->query("UPDATE cupos SET full = 1 WHERE cupos >= acepta");
            $wpdb->query("UPDATE cupos SET full = 0 WHERE cupos < acepta");

        }
    }

    if(!function_exists('comprimir')){
        function comprimir($styles){
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
    
    if(!function_exists('get_menu_header')){
        function get_menu_header( $menu_principal = false ){

            if( is_user_logged_in() ){

                global $wpdb;
                global $vlz;
                extract($vlz);

                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;
                $user = new WP_User( $user_id );
                $salir = wp_logout_url( home_url() );
                
                $tipo_usuario = strtolower( get_usermeta( $user_id, "tipo_usuario", true ) );
                $H = get_home_url();
                $M = get_query_var('modulo');

                /* VETERINARIOS */

                    $MENUS["veterinario"][] = array("name"  => "Mi Perfil",
                        "url"   => "/perfil/",
                        "img" => '<i class="far fa-user"></i>',
                    );

                    $MENUS["veterinario"][] = array(
                        "url"   => "/historial/",
                        "name"  => "Mis Citas",
                        "img" =>  '<i class="far fa-list-alt"></i>',
                    );

                    if( $tipo_usuario == "veterinario"){
                        $status = $wpdb->get_var("SELECT status FROM {$pf}veterinarios WHERE user_id = '{$user_id}' ");
                        if( $status ){
                            /*$MENUS["veterinario"][] = array(
                                "url"   => "/ajustes/",
                                "name"  => "Ajustes",
                                "img" =>  '<i class="fas fa-sliders-h"></i>',
                            );*/
                            $MENUS["veterinario"][] = array(
                                "url"   => "/horarios/",
                                "name"  => "Horarios",
                                "img" =>  '<i class="far fa-calendar-alt"></i>',
                            );
                        }
                    }
                    
                    $MENUS["veterinario"][] = array(
                        "url"   => $salir,
                        "name"  => "Cerrar Sesión",
                        "img" =>  '<i class="fas fa-sign-out-alt"></i>',
                    );


                /* PACIENTES */
                    $MENUS["paciente"][] = array(
                        "url"   => "/perfil/",
                        "name"  => "Mi Perfil",
                        "img" => '<i class="far fa-user"></i>',
                    );

                    $MENUS["paciente"][] =  array(
                        "url"   => "/historial/",
                        "name"  => "Mis Citas",
                        "img" =>  '<i class="far fa-calendar-alt"></i>',
                    );

                    $MENUS["paciente"][] = array(
                        "url"   => $salir,
                        "name"  => "Cerrar Sesión",
                        "img" =>  '<i class="fas fa-sign-out-alt"></i>',
                    );

                /* ADMINISTRADOR */

                    $MENUS["administrador"][] = array(
                        "url"   => "/perfil/",
                        "name"  => "Mi Perfil",
                        "img" => '<i class="far fa-user"></i>',
                    );

                    $MENUS["administrador"][] = array(
                        "url"   => "/historial/",
                        "name"  => "Mis Citas",
                        "img" =>  '<i class="far fa-calendar-alt"></i>',
                    );

                    $MENUS["administrador"][] = array(
                            "url"   => $H."/wp-admin/",
                        "name"  => "Panel de Control",
                        "img" =>  '<i class="fas fa-tachometer-alt"></i>',
                    );

                    $MENUS["administrador"][] = array(
                        "url"   => $salir,
                        "name"  => "Cerrar Sesión",
                        "img" =>  '<i class="fas fa-sign-out-alt"></i>',
                    );

                /* INVERSOR */

                    $MENUS["inversor"][] = array(
                            "url"   => $H."/wp-admin/",
                        "name"  => "Panel de Control",
                        "img" =>  '<i class="fas fa-tachometer-alt"></i>',
                    );

                    $MENUS["inversor"][] = array(
                        "url"   => $salir,
                        "name"  => "Cerrar Sesión",
                        "img" =>  '<i class="fas fa-sign-out-alt"></i>',
                    );


                $MENU["head"] = '<li><a href="#" class="km-nav-link"> <i class="pfadmicon-glyph-632"></i> '.$user->data->display_name.' </a></li>';
                $MENU["head_movil"] = '<li><a href="#" class="km-nav-link"> <i class="pfadmicon-glyph-632"></i> '.$user->data->display_name.' </a></li>';
                $MENU["body"] = "";

                $role = strtolower($tipo_usuario);
                if( $MENUS[ $role ] != "" ){
                    foreach ($MENUS[ $role ] as $key => $value) {
                        $MENU["body"] .=
                        '<li>
                            <a href="'.( ( substr($value["url"], 0, 4) == 'http' ) ? $value["url"] : $H.'/'.$role.$value["url"] ).'" class="pd-tb11 menu-link">
                                '.$value["img"].'
                                '.$value["name"].'
                            </a>
                        </li>';
                    }
                }

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

     if(!function_exists('path_base')){
        function path_base(){
            return dirname(dirname(dirname(__DIR__)));
        }
    }

    if(!function_exists('kmimos_get_foto')){
        function kmimos_get_foto($user_id){
            global $wpdb;
            $img = get_user_meta($user_id, 'name_photo', true);
            return get_home_url().'/wp-content/uploads/avatares/'.$user_id.'/'.$img;
        }
    }

    if(!function_exists('get_referred_list_options')){
        function get_referred_list_options(){
            $opciones = array(
                'Petco-Tienda'  =>  'Tienda Petco',
                'Petco'         =>  'Petco',

                /*'Venta de alimento' =>  'Venta de alimento',
                'Nutriheroes'       =>  'Nutriheroes',*/

                'Facebook'      =>  'Facebook',
                'FacebookSB'      =>  'FacebookSB',
                'Google'       =>  'Buscador de Google',
                'Instagram'     =>  'Instagram',
                'Twitter'       =>  'Twitter',
                'Booking.com'   =>  'Booking.com',
                'Cabify'        =>  'Cabify',
                'Bancomer'      =>  'Bancomer',
                'Mexcovery'     =>  'Mexcovery',
                'Totems'        =>  'Totems',
                'Groupon'       =>  'Groupon',
                'Agencia IQPR'  =>  'Agencia IQPR',
                'Revistas o periodicos' =>  'Revistas o periodicos',
                'Vintermex'             =>  'Viajes Intermex',
                'Amigo/Familiar'        =>  'Recomendación de amigo o familiar',
                'Youtube'               =>  'Youtube',
                'Otros'                 =>  'Otros',
                'CC-Petco'              =>  'Petco-CC',
                'Volaris'               =>  'Volaris'
            );
            return $opciones;
        }
    }
    
?>