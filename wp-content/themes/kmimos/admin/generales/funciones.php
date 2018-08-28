<?php
    if(!function_exists('kmimos_menu_reportes')){
        function kmimos_menu_reportes(){

            $opciones_menu_reporte = array(
                array(
                    'title'         =>  'Reportes',
                    'short-title'   =>  'Reportes',
                    'parent'        =>  '',
                    'slug'          =>  'reporte_fotos',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_fotos',
                    'icon'          =>  '',
                    'position'      =>  4,
                ),
                array(
                    'title'         =>  __('Reporte Fotos'),
                    'short-title'   =>  __('Reporte Fotos'),
                    'parent'        =>  'reporte_fotos',
                    'slug'          =>  'reporte_fotos',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_fotos',
                    'icon'          =>  '',
                ),

                // Menu Facturas
                array(
                    'title'         =>  'Facturas',
                    'short-title'   =>  'Facturas',
                    'parent'        =>  '',
                    'slug'          =>  'reporte_facturas',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_facturas',
                    'icon'          =>  '',
                    'position'      =>  4,
                ),
                array(
                    'title'         =>  __('Facturas'),
                    'short-title'   =>  __('Facturas'),
                    'parent'        =>  'reporte_facturas',
                    'slug'          =>  'reporte_facturas',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_facturas',
                    'icon'          =>  '',
                ),

                // Menu Pagos a Cuidador
                array(
                    'title'         =>  'Pagos a Cuidador',
                    'short-title'   =>  'Pagos a Cuidador',
                    'parent'        =>  '',
                    'slug'          =>  'reporte_autorizacion_pagos',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_autorizacion_pagos',
                    'icon'          =>  '',
                    'position'      =>  4,
                ),

                array(
                    'title'         =>  __('Autorizaciones'),
                    'short-title'   =>  __('Autorizaciones'),
                    'parent'        =>  'reporte_autorizacion_pagos',
                    'slug'          =>  'reporte_autorizacion_pagos',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_autorizacion_pagos',
                    'icon'          =>  '',
                ),
 
            );

            $user_especiales = get_option( "especiales" );
            $user_especiales = explode(",", $user_especiales);

            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;

            $permitidos = array(
                367, // Kmimos
                8604, // Rob
                14720, // Alfredo
                9726, // Roberto
            );

            if( in_array($user_id, $permitidos ) ){
                
                $opciones_menu_reporte[] = array(
                    'title'         =>  __('Saldos'),
                    'short-title'   =>  __('Saldos'),
                    'parent'        =>  'reporte_fotos',
                    'slug'          =>  'reporte_saldos',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_saldos',
                );
            
            }


            if( in_array($user_id, $user_especiales)  ){

                $opciones_menu_reporte[] = array(
                    'title'         =>  __('Cupones'),
                    'short-title'   =>  __('Cupones'),
                    'parent'        =>  'reporte_fotos',
                    'slug'          =>  'reporte_cupones',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_cupones',
                );

                $opciones_menu_reporte[] = array(
                    'title'         =>  __('Otros'),
                    'short-title'   =>  __('Otros'),
                    'parent'        =>  'reporte_fotos',
                    'slug'          =>  'reporte_otros',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_otros',
                );
            }

            $opciones_menu_reporte[] = array(
                'title'         =>  __('Reporte Otro'),
                'short-title'   =>  __('Reporte Otro'),
                'parent'        =>  'reporte_fotos',
                'slug'          =>  'reporte_otro',
                'access'        =>  'manage_options',
                'page'          =>  'reporte_otro',
                'icon'          =>  '',
            );

            $opciones_menu_reporte[] = array(
                'title'         =>  __('Nuevos'),
                'short-title'   =>  __('Nuevos'),
                'parent'        =>  'reporte_autorizacion_pagos',
                'slug'          =>  'reporte_pagos_cuidador',
                'access'        =>  'manage_options',
                'page'          =>  'reporte_pagos_cuidador',
                'icon'          =>  '',
            );

            $opciones_menu_reporte[] = array(
                'title'         =>  __('Configuracion'),
                'short-title'   =>  __('Configuracion'),
                'parent'        =>  'reporte_facturas',
                'slug'          =>  'reporte_configuracion',
                'access'        =>  'manage_options',
                'page'          =>  'reporte_configuracion',
            );

            foreach($opciones_menu_reporte as $opcion){
                if( $opcion['parent'] == '' ){
                    add_menu_page(
                        $opcion['title'],
                        $opcion['short-title'],
                        $opcion['access'],
                        $opcion['slug'],
                        $opcion['page'],
                        $opcion['icon'],
                        $opcion['position']
                    );
                } else{
                    add_submenu_page(
                        $opcion['parent'],
                        $opcion['title'],
                        $opcion['short-title'],
                        $opcion['access'],
                        $opcion['slug'],
                        $opcion['page']
                    );
                }
            }
        }

        add_action('admin_menu','kmimos_menu_reportes');
    }

    /* Inclucion de paginas */

    if(!function_exists('reporte_fotos')){
        function reporte_fotos(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/backend/fotos/reporte_fotos.php');
        }
    }

    if(!function_exists('reporte_saldos')){
        function reporte_saldos(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/backend/saldos/reporte_saldos.php');
        }
    }

    if(!function_exists('reporte_cupones')){
        function reporte_cupones(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/backend/cupones/reporte.php');
        }
    }

    if(!function_exists('reporte_otros')){
        function reporte_otros(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/backend/otros/reporte_otros.php');
        }
    }

    if(!function_exists('reporte_facturas')){
        function reporte_facturas(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/recursos/importador-botones.php');
            include_once(dirname(__DIR__).'/backend/facturas/reporte_facturas.php');
        }
    }
    if(!function_exists('reporte_configuracion')){
        function reporte_configuracion(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/backend/facturas_configuracion/reporte_configuracion.php');
        }
    }

    if(!function_exists('reporte_pagos_cuidador')){
        function reporte_pagos_cuidador(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/recursos/importador-botones.php');
            include_once(dirname(__DIR__).'/backend/pagos/reporte_pagos.php');
        }
    }
    if(!function_exists('reporte_autorizacion_pagos')){
        function reporte_autorizacion_pagos(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/recursos/importador-botones.php');
            include_once(dirname(__DIR__).'/backend/pagos_autorizacion/reporte_pagos_autorizacion.php');
        }
    }
