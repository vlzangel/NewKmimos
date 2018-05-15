<?php
    if(!function_exists('kmimos_menu_monitor')){
        function kmimos_menu_monitor(){

            $opciones_menu_reporte = array(
                array(
                    'title'         =>  'Monitor',
                    'short-title'   =>  'Monitor',
                    'parent'        =>  '',
                    'slug'          =>  'reporte_ventas',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_ventas',
                    'icon'          =>  '',
                    'position'      =>  3,
                ),
                array(
                    'title'         =>  __('Reporte Ventas'),
                    'short-title'   =>  __('Reporte Ventas'),
                    'parent'        =>  'reporte_ventas',
                    'slug'          =>  'reporte_ventas',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_ventas',
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

    if(!function_exists('reporte_ventas')){
        function reporte_ventas(){
            include_once('importador.php');
            include_once('graficos.php');
            include_once(dirname(__DIR__).'/reportes/ventas/ventas.php');
        }
    }
