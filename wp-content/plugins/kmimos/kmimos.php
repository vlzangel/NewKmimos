<?php
    /**
     * @package    WordPress
     * @subpackage KMIMOS
     * @author     Ing. Eduardo Allan D. <eallan@ingeredes.net>
     *
     *
     * Plugin Name: Kmimos - We consent your pets.
     * Plugin URI:  https://ingeredes.net/plugins/kmimos/
     * Description: <a href="https://ingeredes.net/plugins/kmimos/">Kmimos</a> is a full-featured system for Kmimos written in PHP by <a href="https://ingeredes.net" title="Business Engineering in the Net">Ingeredes, Inc.</a>. This plugin include this tool in WordPress for a fast management of all operations of the enterprise.
     * Author:      Eng. Eduardo Allan D. <eallan@ingeredes.net>
     * Author URI:  https://ingeredes.net/
     * Text Domain: kmimos  
     * Version:     1.0.0
     * License:     GPL2
     */

    function remove_menus() {
        global $current_user;
        if( $current_user->ID == 23617 ){

            $page = strtolower( $_GET["page"] );
            $partes = explode("-", $page);
            if( !in_array("pushnotifications", $partes ) ){
                @header( 'location: https://www.kmimos.com.mx/wp-admin/admin.php?page=all-Pushnotifications-wp' );
            }

            remove_menu_page( 'index.php' );
            remove_menu_page( 'jetpack' );
            remove_menu_page( 'edit.php' );
            remove_menu_page( 'upload.php' );
            remove_menu_page( 'edit.php?post_type=page' );
            remove_menu_page( 'edit-comments.php' );
            remove_menu_page( 'themes.php' );
            remove_menu_page( 'plugins.php' );
            remove_menu_page( 'users.php' );
            remove_menu_page( 'tools.php' );
            remove_menu_page( 'options-general.php' );

            remove_menu_page( 'edit.php?post_type=wc_booking' );
            remove_menu_page( 'edit.php?post_type=product' );
            remove_menu_page( 'woocommerce' );
            remove_menu_page( 'reporte_facturas' );
            remove_menu_page( 'reporte_clientes' );
            remove_menu_page( 'reporte_fotos' );
            remove_menu_page( 'reporte_pagos_cuidador' );
            remove_menu_page( 'kmimos' );
            remove_menu_page( 'valoraciones' );
            remove_menu_page( 'vlz-bootstrap-campaing' );
            remove_menu_page( 'nps_preguntas' );
            remove_menu_page( 'resumen' );
            remove_menu_page( 'edit.php?post_type=faq' );
            
            remove_menu_page( 'theme_editor_theme' );
            remove_menu_page( 'wp_file_manager' );
            remove_menu_page( 'zopim_account_config' );
        }
    }
    add_action( 'admin_menu', 'remove_menus', 999 );


    include_once('angel.php');
    include_once('carlos.php');
    include_once('italo.php');
    include_once('viejos.php');

    /** Incluye las funciones de javascript en la página WEB bajo Wordpress **/

    if(!function_exists('kmimos_include_scripts')){
        function kmimos_include_scripts(){
            angel_include_script();
            carlos_include_script();
            italo_include_script();
            viejos_include_script();
        }
    }

    if(!function_exists('kmimos_include_admin_scripts')){
        function kmimos_include_admin_scripts(){
            angel_include_admin_script();
            carlos_include_admin_script();
            italo_include_admin_script();
            viejos_include_admin_script();
        }
    }

    /** Define la estructura de los menúes en el área administrativa **/

    if(!function_exists('kmimos_admin_menu')){
        function kmimos_admin_menu(){
            $opciones_menu_admin = array(
                array(
                    'title'=>'Kmimos',
                    'short-title'=>'Kmimos',
                    'parent'=>'',
                    'slug'=>'kmimos',
                    'access'=>'manage_options',
                    'page'=>'petsitters',
                    'icon'=>get_home_url()."/wp-content/plugins/kmimos/".'/assets/images/icon.png',
                    'position'=>4,
                ),
            );
            $opciones_menu_admin = angel_menus ( $opciones_menu_admin );
            $opciones_menu_admin = carlos_menus( $opciones_menu_admin );
            $opciones_menu_admin = italo_menus ( $opciones_menu_admin );
            $opciones_menu_admin[] = array(
                'title'=> __('Settings'),
                'short-title'=> __('Settings'),
                'parent'=>'kmimos',
                'slug'=>'kmimos-setup',
                'access'=>'manage_options',
                'page'=>'kmimos_setup',
                'icon'=>'',
            );
            // Crea los links en el menú del panel de control
            foreach($opciones_menu_admin as $opcion){
                if($opcion['parent']==''){
                    add_menu_page($opcion['title'],$opcion['short-title'],$opcion['access'],$opcion['slug'],$opcion['page'],$opcion['icon'],$opcion['position']);
                } else{
                    add_submenu_page($opcion['parent'],$opcion['title'],$opcion['short-title'],$opcion['access'],$opcion['slug'],$opcion['page']);
                }
            }
        }
    }

?>

