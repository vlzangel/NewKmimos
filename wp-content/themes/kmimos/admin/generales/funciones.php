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
                array(
                    'title'         =>  __('Reporte Otro'),
                    'short-title'   =>  __('Reporte Otro'),
                    'parent'        =>  'reporte_fotos',
                    'slug'          =>  'reporte_otro',
                    'access'        =>  'manage_options',
                    'page'          =>  'reporte_otro',
                    'icon'          =>  '',
                )
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




    function kmimos_getImgCreate($path){
        $sExt = @mime_content_type( $path );
        switch( $sExt ) {
            case 'image/jpeg':
                return @imageCreateFromJpeg( $path );
            break;
            case 'image/gif':
                return @imageCreateFromGif( $path );
            break;
            case 'image/png':
                return @imageCreateFromPng( $path );
            break;
            case 'image/wbmp':
                return @imageCreateFromWbmp( $path );
            break;
        }
    }

    function kmimos_agregarFondo($path_perro, $path_fondo, $destino){
        $fondo      = kmimos_getImgCreate($path_fondo);
        $imgPerro   = kmimos_getImgCreate($path_perro);
        $OrigenSize  = @getImageSize( $path_fondo );
        $DestinoSize = @getImageSize( $path_perro );
        $x = ($OrigenSize[0]-$DestinoSize[0])/2;
        $y = ($OrigenSize[1]-$DestinoSize[1])/2;
        imagecopyresampled(
            $fondo,
            $imgPerro,
            $x, $y, 0, 0,
            imagesx($imgPerro),
            imagesy($imgPerro),
            imagesx($imgPerro),
            imagesy($imgPerro)
        );
        imagepng($fondo, $destino);
        imagedestroy($fondo);
    }
?> 