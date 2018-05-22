<?php
    if(!function_exists('kmimos_menu_monitor')){
        function kmimos_menu_monitor(){

            $opciones_menu_reporte = array(
 
                array(
                    'title'         =>  'Monitor',
                    'short-title'   =>  'Monitor',
                    'parent'        =>  '',
                    'slug'          =>  'resumen',
                    'access'        =>  'manage_options',
                    'page'          =>  'resumen',
                    'icon'          =>  '',
                    'position'      =>  3,
                ),
                array(
                    'title'         =>  __('Resumen'),
                    'short-title'   =>  __('Resumen'),
                    'parent'        =>  'resumen',
                    'slug'          =>  'resumen',
                    'access'        =>  'manage_options',
                    'page'          =>  'resumen',
                    'icon'          =>  '',
                ),
                array(
                    'title'         =>  __('Clientes'),
                    'short-title'   =>  __('Clientes'),
                    'parent'        =>  'resumen',
                    'slug'          =>  'reporte_ventas',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_ventas',
                    'icon'          =>  '',
                ),
                array(
                    'title'         =>  __('Cuidadores'),
                    'short-title'   =>  __('Cuidadores'),
                    'parent'        =>  'resumen',
                    'slug'          =>  'reporte_cuidadores',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_cuidadores',
                    'icon'          =>  '',
                ),
                array(
                    'title'         =>  __('Marketing Clientes'),
                    'short-title'   =>  __('Marketing Clientes'),
                    'parent'        =>  'resumen',
                    'slug'          =>  'reporte_marketing_clientes',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_marketing_clientes',
                    'icon'          =>  '',
                ),





                array(
                    'title'         =>  __('Marketing'),
                    'short-title'   =>  __('Marketing'),
                    'parent'        =>  'resumen',
                    'slug'          =>  'reporte_marketing',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_marketing',
                    'icon'          =>  '',
                )
            );

            $user_especiales = get_option( "superadmin" );
            $user_especiales = explode(",", $user_especiales);

            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;

            // dev
            $user_especiales[$user_id] = $user_id;

            if( in_array($user_id, $user_especiales)  ){

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
        }

        add_action('admin_menu','kmimos_menu_monitor');
    }

    /* Inclucion de paginas */

    if(!function_exists('reporte_marketing_clientes')){
        function reporte_marketing_clientes(){
            include_once('importador.php');
            include_once('graficos.php');
            include_once(dirname(__DIR__).'/reportes/marketing_gastos/marketing.php');
        }
    }

    if(!function_exists('reporte_cuidadores')){
        function reporte_cuidadores(){
            include_once('importador.php');
            include_once('graficos.php');
            include_once(dirname(__DIR__).'/reportes/cuidadores/cuidadores.php');
        }
    }
    if(!function_exists('reporte_marketing')){
        function reporte_marketing(){
            include_once('importador.php');
            include_once('graficos.php');
            include_once(dirname(__DIR__).'/reportes/marketing/marketing.php');
        }
    }
    if(!function_exists('reporte_ventas')){
        function reporte_ventas(){
            include_once('importador.php');
            include_once('graficos.php');
            include_once(dirname(__DIR__).'/reportes/ventas/ventas.php');
        }
    }
    if(!function_exists('resumen')){
        function resumen(){
            include_once('importador.php');
            include_once('graficos.php');
            include_once(dirname(__DIR__).'/reportes/resumen/resumen.php');
        }
    }
