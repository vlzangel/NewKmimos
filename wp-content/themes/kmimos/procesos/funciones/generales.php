<?php
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
    
    if(!function_exists('update_cupos')){
        function update_cupos($data, $accion){
            global $db;
            extract($data);
            for ($i=$inicio; $i < $fin; $i+=86400) { 
                $fecha = date("Y-m-d", $i);
                $full = 0;
                $existe = $db->get_var("SELECT * FROM cupos WHERE servicio = '{$servicio}' AND fecha = '{$fecha}'");
                if( $existe !== false ){
                    $db->query("UPDATE cupos SET cupos = cupos {$accion} {$cantidad} WHERE servicio = '{$servicio}' AND fecha = '{$fecha}' ");
                    $db->query("UPDATE cupos SET full = 1 WHERE servicio = '{$servicio}' AND ( fecha = '{$fecha}' AND cupos >= acepta )");
                    $db->query("UPDATE cupos SET full = 0 WHERE servicio = '{$servicio}' AND ( fecha = '{$fecha}' AND cupos < acepta )");
                }else{
                    $acepta = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = '{$servicio}' AND meta_key = '_wc_booking_qty'");
                    if( $cantidad >= $acepta ){ $full = 1; }
                    $sql = "
                        INSERT INTO cupos VALUES (
                            NULL,
                            '{$autor}',
                            '{$servicio}',
                            '{$tipo}',
                            '{$fecha}',
                            '{$cantidad}',
                            '{$acepta}',
                            '{$full}',        
                            '0'        
                        );
                    ";
                    $db->query($sql);
                }
            }
        }
    }
?>