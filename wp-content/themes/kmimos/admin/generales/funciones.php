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
                array(
                    'title'         =>  __('Notas de Creditos'),
                    'short-title'   =>  __('Notas de Creditos'),
                    'parent'        =>  'reporte_facturas',
                    'slug'          =>  'reporte_notas_creditos',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_notas_creditos',
                    'icon'          =>  '',
                ),

                // Menu Pagos a Cuidador
                array(
                    'title'         =>  'Pagos a Cuidador',
                    'short-title'   =>  'Pagos a Cuidador',
                    'parent'        =>  '',
                    'slug'          =>  'reporte_pagos_cuidador',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_pagos_cuidador',
                    'icon'          =>  '',
                    'position'      =>  4,
                ),
                
                // Menu Pagos a Cuidador
                array(
                    'title'         =>  'Clientes',
                    'short-title'   =>  'Clientes',
                    'parent'        =>  '',
                    'slug'          =>  'reporte_clientes',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_clientes',
                    'icon'          =>  '',
                    'position'      =>  4,
                ),
 
                // Menu NPS
                array(
                    'title'         =>  'NPS',
                    'short-title'   =>  'NPS',
                    'parent'        =>  '',
                    'slug'          =>  'nps_preguntas',
                    'access'        =>  'manage_options',
                    'page'          =>  'nps_preguntas',
                    'icon'          =>  '',
                    'position'      =>  4,
                ),
 
                // Comentarios
                array(
                    'title'         =>  'Valoraciones',
                    'short-title'   =>  'Valoraciones',
                    'parent'        =>  '',
                    'slug'          =>  'valoraciones',
                    'access'        =>  'manage_options',
                    'page'          =>  'valoraciones',
                    'icon'          =>  '',
                    'position'      =>  4,
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
            $user_cupones = array(
                14720, // Alfredo
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
            }else{
                if( in_array($user_id, $user_cupones)  ){
                    $opciones_menu_reporte[] = array(
                        'title'         =>  __('Cupones'),
                        'short-title'   =>  __('Cupones'),
                        'parent'        =>  'reporte_fotos',
                        'slug'          =>  'reporte_cupones',
                        'access'        =>  'manage_options',
                        'page'          =>  'reporte_cupones',
                    );
                }
            }

            $opciones_menu_reporte[] = array(
                'title'         =>  __('Status Solicitudes'),
                'short-title'   =>  __('Status Solicitudes'),
                'parent'        =>  'reporte_fotos',
                'slug'          =>  'status_solicitudes',
                'access'        =>  'manage_options',
                'page'          =>  'status_solicitudes',
            );

            $opciones_menu_reporte[] = array(
                'title'         =>  __('Términos Aceptados'),
                'short-title'   =>  __('Términos Aceptados'),
                'parent'        =>  'reporte_fotos',
                'slug'          =>  'terminos',
                'access'        =>  'manage_options',
                'page'          =>  'terminos',
                'icon'          =>  '',
            );

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
                'title'         =>  __('Agregar cupon'),
                'short-title'   =>  __('Agregar cupon'),
                'parent'        =>  'reporte_fotos',
                'slug'          =>  'reporte_cupon',
                'access'        =>  'manage_options',
                'page'          =>  'reporte_cupon',
                'icon'          =>  '',
            );

            $opciones_menu_reporte[] = array(
                'title'         =>  __('Reporte Banner'),
                'short-title'   =>  __('Reporte Banner'),
                'parent'        =>  'reporte_fotos',
                'slug'          =>  'reporte_usos',
                'access'        =>  'manage_options',
                'page'          =>  'reporte_usos',
                'icon'          =>  '',
            );

            $opciones_menu_reporte[] = array(
                'title'         =>  __('Social Blue'),
                'short-title'   =>  __('Social Blue'),
                'parent'        =>  'reporte_fotos',
                'slug'          =>  'social_blue',
                'access'        =>  'manage_options',
                'page'          =>  'social_blue',
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
 
            $opciones_menu_reporte[] = array(
                'title'         =>  __('Openpay'),
                'short-title'   =>  __('Openpay'),
                'parent'        =>  'reporte_fotos',
                'slug'          =>  'openpay',
                'access'        =>  'manage_options',
                'page'          =>  'openpay',
            );

            $opciones_menu_reporte[] = array(
                'title'         =>  __('Detalle'),
                'short-title'   =>  __('Detalle'),
                'parent'        =>  'nps_preguntas',
                'slug'          =>  'nps_detalle',
                'access'        =>  'manage_options',
                'page'          =>  'nps_detalle',
            );

            $opciones_menu_reporte[] = array(
                'title'         =>  __('Feedback'),
                'short-title'   =>  __('Feedback'),
                'parent'        =>  'nps_preguntas',
                'slug'          =>  'nps_feedback',
                'access'        =>  'manage_options',
                'page'          =>  'nps_feedback',
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
    if(!function_exists('valoraciones')){
        function valoraciones(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/recursos/importador-botones.php');
            include_once(dirname(__DIR__).'/backend/comentarios/reporte_comentario.php');
        }
    }

    /* Inclucion de paginas */
    if(!function_exists('nps_feedback')){
        function nps_feedback(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/recursos/importador-botones.php');
            include_once(dirname(__DIR__).'/backend/nps/feedback/reporte.php');
        }
    }

    if(!function_exists('nps_preguntas')){
        function nps_preguntas(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/recursos/importador-botones.php');
            include_once(dirname(__DIR__).'/backend/nps/preguntas/reporte.php');
        }
    }

    if(!function_exists('nps_detalle')){
        function nps_detalle(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/recursos/importador-botones.php');
            include_once(dirname(__DIR__).'/recursos/importador-chart.php');
            include_once(dirname(__DIR__).'/backend/nps/detalle/reporte.php');
        }
    }

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

    if(!function_exists('reporte_cupon')){
        function reporte_cupon(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/backend/agregar_cupon/agregar_cupon.php');
        }
    }

    if(!function_exists('status_solicitudes')){
        function status_solicitudes(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/backend/status_solicitudes/panel.php');
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

    if(!function_exists('terminos')){
        function terminos(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/backend/terminos/page.php');
        }
    }

    if(!function_exists('reporte_usos')){
        function reporte_usos(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/recursos/importador-botones.php');
            include_once(dirname(__DIR__).'/backend/seguimiento/page.php');
        }
    }

    if(!function_exists('social_blue')){
        function social_blue(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            // include_once(dirname(__DIR__).'/backend/importar/page_.php');
            include_once(dirname(__DIR__).'/backend/social_blue/page.php');
        }
    }

    if(!function_exists('openpay')){
        function openpay(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/backend/openpay/page.php');
        }
    }

    if(!function_exists('reporte_clientes')){
        function reporte_clientes(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/recursos/importador-botones.php');
            include_once(dirname(__DIR__).'/backend/clientes/reporte_clientes.php');
        }
    }

    if(!function_exists('reporte_notas_creditos')){
        function reporte_notas_creditos(){
            include_once(dirname(__DIR__).'/recursos/importador.php');
            include_once(dirname(__DIR__).'/recursos/importador-botones.php');
            include_once(dirname(__DIR__).'/backend/notas_creditos/reporte.php');
        }
    }
