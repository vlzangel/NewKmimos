<?php
require_once('base_db.php');
require_once('GlobalFunction.php');
// error_reporting(0);

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



    $__adicionales = array(
		"bano" => "BAÑO Y SECADO",
		"corte" => "CORTE DE UÑAS Y PELO",
		"limpieza_dental" => "LIMPIEZA DENTAL",
		"acupuntura" => "ACUPUNTURA",
		"visita_al_veterinario" => "VISITA AL VETERINARIO"
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

	// Añadir servicios adicionales
	foreach ($__adicionales as $key => $servicio) {
		$temp[ 'adicionales' ][ $key ] = [
			'costo' => $precios_adicionales_cuidador[$key],
			'descripcion' => $servicio,
		];
	}

    $__transporte = array(
		"transportacion_sencilla" => "Transportación Sencilla",
		"transportacion_redonda" => "Transportación Redonda"
	);

    $__transporte_tipo = array(
		"corto" => "Corto",
		"medio" => "Medio",
		"largo" => "Largo"
	);

	// Añadir servicios de transporte
	foreach ($precios_adicionales_cuidador as $key => $servicio) {
		if( isset($__transporte[ $key ]) ){
			$temp[ 'transporte' ][ $key ] = [];
			foreach ($servicio as $key_2 => $value_2) {
				$temp[ 'transporte' ][ $key ][] = [
					'costo' => $precios_adicionales_cuidador[$key][$key_2],
					'descripcion' => $__transporte_tipo[ $key_2 ]
				];
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
		SELECT u.*, c.activo as 'estatus', c.*, p.post_title as 'cuidador_title', p.ID as 'cuidador_post' 
		FROM wp_users as u
			INNER JOIN cuidadores as c ON c.user_id = u.ID
			INNER JOIN wp_posts as p ON p.post_author = u.ID AND p.post_type = 'petsitters'
		{$filtro_adicional}
		ORDER BY DATE_FORMAT(u.user_registered,'%d-%m-%Y') DESC;
	";
	
	$result = get_fetch_assoc($sql);
	return $result;
}

function getReservasByCuidador( $cuidador_id ){


}