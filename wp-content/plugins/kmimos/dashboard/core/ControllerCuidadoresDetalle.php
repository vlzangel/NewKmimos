<?php
require_once('base_db.php');
require_once('GlobalFunction.php');

function getmetaUser($user_id=0){
	$condicion = " AND m.meta_key IN ( 'nickname', 'first_name', 'last_name', 'user_phone', 'user_mobile', 'user_referred', 'user_address')";
	$result = get_metaUser($user_id, $condicion);
	$data = [
		'first_name' =>'', 
		'last_name' =>'', 
		'user_phone' =>'', 
		'user_mobile' =>'',
		'user_referred' =>'',
		'nickname' =>'',
		'user_address' =>'',
	];
	if( !empty($result) ){
		foreach ( $result['rows'] as $row ) {
			$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
		}
	}
	$data = merge_phone($data);
	return $data;
}

function getEstadoMunicipio($estados, $municipios){
	// buscar estados
	$resultado['estado'] = '';
	$resultado['municipio'] = '';
	if(!empty($estados)){
		$e = explode('=', $estados);
		$estado = '';
		foreach ($e as $value) {
			if( $value > 0 ){
				$sql_e = 'select * from states where country_id = 1 and id = '.$value;
				$result = get_fetch_assoc($sql_e);
				$resultado['estado'] = (isset($result['rows'][0]['name'])) ? $result['rows'][0]['name'] : '';
				break;
			}
		}
	}
	//buscar municipios
	if(!empty($municipios)){
		$e = explode('=', $municipios);
		$muni = '';
		foreach ($e as $value) {
			if( !empty( trim( $value) ) ){
				$sql_m = 'select * from locations where id = '.$value;
				$result = get_fetch_assoc($sql_m);
				$resultado['municipio'] = (isset($result['rows'][0]['name'])) ? $result['rows'][0]['name'] : '';
				break;
			}
		}
	}
	return $resultado;
}


function getServicios( $cuidador ){
	global $wpdb;
    $tam = array(
		"pequenos" => "Peque&ntilde;os",
		"medianos" => "Medianos",
		"grandes"  => "Grandes",
		"gigantes" => "Gigantes",
	);
    $rutas = array(
        "corto" => "Cortas",
        "medio" => "Medias",
        "largo" => "Largas"
    );
    $adicionales = array(
    	"guarderia"						=> "Guardería",
    	"paseos"						=> "Paseos",
    	"adiestramiento_basico"			=> "Entrenamiento Básico",
    	"adiestramiento_intermedio"		=> "Entrenamiento Intermedio",
    	"adiestramiento_avanzado"		=> "Entrenamiento Avanzado"
    );


	$sql = "SELECT * FROM wp_posts WHERE post_author = ".$cuidador['user_id']." AND post_type = 'product'";
    $productos = $wpdb->get_results($sql);
    foreach ($productos as $producto) {
    	$servicio = explode("-", $producto->post_name);
    	$status_servicios[ $servicio[0] ] = $producto->post_status;
    }

    $precios_adicionales_cuidador = unserialize($cuidador['adicionales']);

	$temp = "";
    $precios_adicionales = "";
    foreach ($adicionales as $key => $value) {
    	foreach ($tam as $key2 => $value2) {
    		if( isset($precios_adicionales_cuidador[$key] ) ){
    			$precio = $precios_adicionales_cuidador[$key][$key2];
	    		$temp[$key][$key2] = $precio;
    		} 
    	}
	}

	return $temp;
}


function getUsers($param = array(), $desde="", $hasta=""){
	$filtro_adicional = " c.activo = 1 ";
	if( !empty($desde) && !empty($hasta) ){
		$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
		$filtro_adicional .= " 
			DATE_FORMAT(u.user_registered, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
		";
	}

	foreach ($param as $key => $value) {
		$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
		$filtro_adicional .= " {$key} = {$value} " ;		
	}

	$filtro_adicional = (!empty($filtro_adicional))? ' WHERE '.$filtro_adicional : $filtro_adicional ;
	$sql = "
		SELECT u.*, b.*, c.activo as 'estatus', c.*, p.post_title as 'cuidador_title', p.ID as 'cuidador_post' 
		FROM wp_users as u
			INNER JOIN cuidadores as c ON c.user_id = u.ID
			INNER JOIN ubicaciones as b ON b.cuidador = c.id
			INNER JOIN wp_posts as p ON p.post_author = u.ID AND p.post_type = 'petsitters'
		{$filtro_adicional}
		ORDER BY DATE_FORMAT(u.user_registered,'%d-%m-%Y') DESC;
	";
	
	$result = get_fetch_assoc($sql);
	return $result;
}

function getReservasByCuidador( $cuidador_id ){


}