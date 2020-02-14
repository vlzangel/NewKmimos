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
            $base = path_base();
            $img = get_user_meta($user_id, 'name_photo', true);
            if( $img != '' && file_exists( $base.'/uploads/avatares/'.$user_id.'/'.$img ) ){
                $img = get_home_url().'/wp-content/uploads/avatares/'.$user_id.'/'.$img;
            }else{
                $img = get_home_url().'/wp-content/themes/kmimos/images/image.png';
            }
            return $img;
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