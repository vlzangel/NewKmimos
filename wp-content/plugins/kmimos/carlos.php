<?php

	include_once('wlabel/wlabel.php');
	include_once('subscribe/subscribe.php');

	include_once('includes/class/class_kmimos_booking.php');
	include_once('includes/class/class_kmimos_tables.php');
	include_once('includes/class/class_kmimos_script.php');
	// include_once('plugins/woocommerce.php');

	if(!function_exists('carlos_include_script')){
	    function carlos_include_script(){
	        
	    }
	}

	if(!function_exists('carlos_include_admin_script')){
	    function carlos_include_admin_script(){
	        
	    }
	}

	if(!function_exists('carlos_menus')){
	    function carlos_menus($menus){
	        


	        return $menus;
	    }
	}



	if(!function_exists('date_boooking')){
	    function date_boooking($date=''){
	        $date=strtotime($date);
	        $date=date('d/m/Y',$date);
	        //$date=substr($date, 6, 2)."/".substr($date, 4, 2)."/".substr($date, 0, 4);
	        return $date;
	    }
	}

	if(!function_exists('build_table')){
	    function build_table($args=array()){
	        $table='';
	        foreach($args as $data){
	            if(count($data['tr'])>0){
	                $table.='<h1 class="theme_tite theme_table_title">'.$data['title'].'</h1>';
	                $table.='<table class="vlz_tabla jj_tabla table table-striped table-responsive">';
	                $table.='<tr>';
	                foreach($data['th'] as $th){
	                    $table.='<th class="theme_table_th '.$th['class'].'">'.$th['data'].'</th>';
	                }
	                $table.='</tr>';


	                foreach($data['tr'] as $tr){
	                    $table.='<tr>';
	                    foreach($tr as $td){
	                        $table.='<td class="'.$td['class'].'">'.$td['data'].'</th>';
	                    }
	                    $table.='</tr>';
	                }

	                $table.='</table>';
	            }
	        }
	        return $table;
	    }
	}

	if(!function_exists('build_select')){
	    function build_select($args=array()){
	        $select='';
	        if(count($args)>0){
	            $select.='<select class="redirect theme_btn">';
	            $select.='<option value="" selected="selected">Seleccionar Acción</option>';
	            foreach($args as $option){
	                if(array_key_exists('text',$option)){
	                    $class='';
	                    if(array_key_exists('class',$option)){
	                        $class=$option['class'];
	                    }
	                    $select.='<option class="'.$class.'" value="'.$option['value'].'">'.$option['text'].'</option>';
	                }
	            }
	            $select.='</select>';
	        }
	        return $select;
	    }
	}




/*	if(!function_exists('get_metaUser')){
	    function get_metaUser($user_id=0, $condicion=''){
	        global $wpdb;
	        $sql = "
	            SELECT u.user_email, m.*
	            FROM wp_users as u
	                INNER JOIN wp_usermeta as m ON m.user_id = u.ID
	            WHERE
	                m.user_id = {$user_id}
	                {$condicion}
	        ";
	        $result = $wpdb->get_results($sql);
	        //$result = execute($sql);
	        return $result;
	    }
	}*/

	if(!function_exists('GETmetaUSER')){
	    function GETmetaUSER($user_id=0){
	        $condicion = " AND m.meta_key IN ('first_name', 'last_name', 'user_phone', 'user_mobile')";
	        $result = get_metaUser($user_id, $condicion);
	        $data = [
	            'id' =>'',
	            'email' =>'',
	            'first_name' =>'',
	            'last_name' =>'',
	            'user_phone' =>'',
	            'user_mobile' =>'',
	        ];
	        if( !empty($result) ){
	            if( $result->num_rows > 0){
	                while ($row = $result->fetch_assoc()) {
	                    $data['email'] = utf8_encode( $row['user_email'] );
	                    $data['id'] = $row['user_id'];
	                    $data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
	                }
	            }
	        }
	        return $data;
	    }
	}

?>