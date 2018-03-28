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
                )
            );

            $user_especiales = get_option( "especiales" );
            $user_especiales = explode(",", $user_especiales);

            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;

            if( in_array($user_id, $user_especiales)  ){
                $opciones_menu_reporte[] = array(
                    'title'         =>  __('Saldos'),
                    'short-title'   =>  __('Saldos'),
                    'parent'        =>  'reporte_fotos',
                    'slug'          =>  'reporte_saldos',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_saldos',
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
?> 