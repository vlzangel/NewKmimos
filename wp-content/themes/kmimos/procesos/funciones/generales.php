<?php
    
    if(!function_exists('is_petsitters')){
        function is_petsitters( $user_id ){
            global $db;
            $cuidador = $db->get_row("SELECT * FROM cuidadores WHERE user_id = {$user_id}");
             
            if( isset($cuidador->id) && $cuidador->id > 0 ){
                return $cuidador;
            }
            return false;
        }
    }

	if(!function_exists('get_home_url')){
        function get_home_url(){
        	global $db;
        	return $db->get_var("SELECT option_value FROM wp_options WHERE option_name = 'siteurl'");
        }
    }

	if(!function_exists('path_base')){
        function path_base(){
            return dirname(dirname(dirname(dirname(dirname(__DIR__)))));
        }
    }

    if(!function_exists('kmimos_get_foto')){
        function kmimos_get_foto($user_id, $get_sub_path = false){
            global $db;

            $HOME = get_home_url();

            $tipo = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'wp_capabilities' ");
            
            if( strpos($tipo, "vendor") === false ){
                $sub_path = "avatares_clientes/miniatura/{$user_id}_";
                $sub_path = "avatares_clientes/{$user_id}/";
            }else{
                $id = $db->get_var("SELECT id FROM cuidadores WHERE user_id = {$user_id}");
                $sub_path = "cuidadores/avatares/miniatura/{$id}_";
                $sub_path = "cuidadores/avatares/{$id}/";
            }
            
            $name_photo = $db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = 'name_photo' ");
            if( empty($name_photo)  ){
                $name_photo = "0";
            }
            /*
            if( count(explode(".", $name_photo)) == 1 ){
                $name_photo .= "jpg";
            }
            */
            $base = path_base();

            //echo $base."/wp-content/uploads/{$sub_path}{$name_photo}\n";

            if( file_exists($base."/wp-content/uploads/{$sub_path}{$name_photo}") ){
                $img = $HOME."/wp-content/uploads/{$sub_path}{$name_photo}";
            }else{
                if( file_exists($base."/wp-content/uploads/{$sub_path}0.jpg") ){
                    $img = $HOME."/wp-content/uploads/{$sub_path}0.jpg";
                }else{
                    $img = $HOME."/wp-content/themes/kmimos/images/noimg.png";
                }
            }

            return $img;
        }
    }

    if(!function_exists('ya_aplicado')){
        function ya_aplicado($cupon, $cupones){
            foreach ($cupones as $key => $valor) {
                if( $cupon == $valor[0] ){
                    return true;
                }
            }
            return false;
        }
    }

    function kmimos_get_user_meta($user_id, $key = ''){
        global $db;
        if( $key != '' ){
            return $db->get_var("SELECT meta_key FROM wp_usermeta WHERE user_id = {$user_id} AND meta_key = '{$key}';");
        }else{
            $resultado = array();
            $metas = $db->get_results("SELECT * FROM wp_usermeta WHERE user_id = {$user_id}");
            foreach ($metas as $key => $value) {
                $resultado[ $value->meta_key ] = $value->meta_value;
            }
            return $resultado;
        }
    }
    
    function kmimos_update_user_meta($user_id, $key, $valor){
        global $db;
        if( kmimos_get_user_meta($user_id, $key) !== false ){
            $db->query("UPDATE wp_usermeta SET meta_value = '{$valor}' WHERE user_id = {$user_id} AND meta_key = '{$key}';");
        }else{
            $db->query("INSERT INTO wp_usermeta VALUES ( NULL, {$user_id}, '{$key}', '{$valor}');");
        }
    }

    function kmimos_get_post_meta($post_id, $key = ''){
        global $db;
        if( $key != '' ){
            return $db->get_var("SELECT meta_key FROM wp_postmeta WHERE post_id = {$post_id} AND meta_key = '{$key}';");
        }else{
            $resultado = array();
            $metas = $db->get_results("SELECT * FROM wp_postmeta WHERE post_id = {$post_id}");
            foreach ($metas as $key => $value) {
                $resultado[ $value->meta_key ] = $value->meta_value;
            }
            return $resultado;
        }
    }
    
    function kmimos_update_post_meta($post_id, $key, $valor){
        global $db;
        if( kmimos_get_post_meta($post_id, $key) !== false ){
            $db->query("UPDATE wp_postmeta SET meta_value = '{$valor}' WHERE post_id = {$post_id} AND meta_key = '{$key}';");
        }else{
            $db->query("INSERT INTO wp_postmeta VALUES ( NULL, {$post_id}, '{$key}', '{$valor}');");
        }
    }

    function kmimos_get_relationship($object_id){
        global $db;
        return $db->get_var("SELECT term_taxonomy_id FROM wp_term_relationships WHERE object_id = {$object_id};");
    }
    
    function kmimos_update_relationship($object_id, $valor){
        global $db;
        if( kmimos_get_relationship($object_id) !== false ){
            $db->query("UPDATE wp_term_relationships SET term_taxonomy_id = '{$valor}' WHERE object_id = {$object_id};");
        }else{
            $db->query("INSERT INTO wp_term_relationships VALUES ( {$object_id}, '$valor', '0');");
        }
    }

    function listar_archivos($carpeta){
        $fotos = array();
        if(is_dir($carpeta)){
            if($dir = opendir($carpeta)){
                while(($archivo = readdir($dir)) !== false){
                    if($archivo != '.' && $archivo != '..' && $archivo != 'collage.png'){
                        $fotos[] = $archivo;
                    }
                }
                closedir($dir);
            }
        }
        return $fotos;
    }
    
    function procesar_img($id, $periodo, $dir, $sImagen, $es_collage = false){
        $name = $id.".png";
        $path = $dir.$name;
        @file_put_contents($path, $sImagen);
        $sExt = @mime_content_type( $path );
        switch( $sExt ) {
            case 'image/jpeg':
                $aImage = @imageCreateFromJpeg( $path );
            break;
            case 'image/gif':
                $aImage = @imageCreateFromGif( $path );
            break;
            case 'image/png':
                $aImage = @imageCreateFromPng( $path );
            break;
            case 'image/wbmp':
                $aImage = @imageCreateFromWbmp( $path );
            break;
        }
        if( $es_collage ){
            $nWidth  = 600; $nHeight = 495;
        }else{
            $nWidth  = 270; $nHeight = 190;
        }
        $aSize = @getImageSize( $path );
        if( $aSize[0] > $aSize[1] ){
            $nHeight = round( ( $aSize[1] * $nWidth ) / $aSize[0] );
        }else{
            $nWidth = round( ( $aSize[0] * $nHeight ) / $aSize[1] );
        }
        $aThumb = @imageCreateTrueColor( $nWidth, $nHeight );
        @imageCopyResampled( $aThumb, $aImage, 0, 0, 0, 0, $nWidth, $nHeight, $aSize[0], $aSize[1] );
        @imagepng( $aThumb, $path ); 
        @imageDestroy( $aImage ); @imageDestroy( $aThumb );
        return $name;
    }

    if(!function_exists('kmimos_fotos')){
        function kmimos_fotos($PATH, $MODERADAS, $URL_BASE){
            $FOTOS = listar_archivos( $PATH );
            $i = 1; $moderar_imgs = "";
            foreach ($FOTOS as $foto) {
                $check = "";
                if( $MODERADAS != false ){
                    $check = "";
                    if( in_array($foto, $MODERADAS)){
                        $check = "checked";
                    }
                }
                $moderar_imgs .= "
                    <div style='background-image: url(".$URL_BASE.$foto.");'>
                        <span onclick='ver_foto( jQuery(this) )' data-img='".$URL_BASE.$foto."'>Ver</span>
                        <input type='checkbox' class='input_check' value='{$foto}' {$check} id='foto_{$i}' data-index='{$i}' data-url=\"".$URL_BASE.$foto."\"  />
                    </div>
                ";
                $i++;
            }

            return $moderar_imgs;
        }
    }

    function kmimos_dateFormat($fecha, $format = "d/m/Y"){
        return date( $format, strtotime( str_replace("/", "-", $fecha) ) );
    }
    
?>