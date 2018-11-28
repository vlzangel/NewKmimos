<?php
require_once('base_db.php');
require_once('GlobalFunction.php');

function getmetaUser($user_id=0){
	// $condicion = " AND m.meta_key IN ( 'nickname', 'first_name', 'last_name', 'user_phone', 'user_mobile', 'user_referred')";
	// $result = get_metaUser($user_id, $condicion);
	$result = get_metaUser($user_id);
	$data = [
		'first_name' =>'', 
		'last_name' =>'', 
		'user_phone' =>'', 
		'user_mobile' =>'',
		'user_referred' =>'',
		'nickname' => '',
	];
	if( !empty($result) ){
		foreach ( $result['rows'] as $row ) {
			$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
		}
	}
	$data = merge_phone($data);
	return $data;
}

function getUsers($desde="", $hasta=""){
	$filtro_adicional = "";
	if( !empty($desde) && !empty($hasta) ){
		$filtro_adicional .= " 
			AND DATE_FORMAT(u.user_registered, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
		";
	}
	$sql = "
		SELECT u.*
		FROM wp_users as u
			LEFT JOIN cuidadores as c ON c.user_id = u.ID
		WHERE c.id is NULL
		{$filtro_adicional}
		ORDER BY DATE_FORMAT(u.user_registered,'%d-%m-%Y') DESC;
	";	
	$result = get_fetch_assoc($sql);
	return $result;
}

function getCountReservas( $author_id=0 ){

	$result = [];
	$sql = "
		SELECT 
			count(ID) as cant
		FROM wp_posts
		WHERE post_type = 'wc_booking' 
			AND not post_status like '%cart%'
			AND post_status = 'confirmed' 
			AND post_author = {$author_id}
	";
	$result = get_fetch_assoc($sql);
	return $result;
}

function getMascotas($user_id){
	if(!$user_id>0){ return []; }

	global $wpdb;
	$mascotas_cliente = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_author = '{$user_id}' AND post_type='pets' AND post_status = 'publish'");
    $mascotas = array();
    foreach ($mascotas_cliente as $key => $mascota) {
        $_anio = get_post_meta($mascota->ID, "birthdate_pet", true);
        $_anio = str_replace("/", "-", $_anio);
        $anio = strtotime($_anio);
        $anio = ceil ( time()-$anio );
        $edad = (@date("Y", $anio)-1970);

        $mascotas[] = array(
            "nombre" => $mascota->post_title,
            "raza" => get_post_meta($mascota->ID, "breed_pet", true),
            "edad" => $edad
        );
    }
	return $mascotas;
}

function getRazaDescripcion($id, $razas){
	$nombre = "[{$id}]";
	if($id > 0){
		if( !empty($razas) ){
			if(array_key_exists($id, $razas)){
				$nombre = $razas[$id];
			}
		}
	}
	return $nombre;
}

function get_primera_reservas( $author_id=0 ){
	$result = [];
	$sql = "
		SELECT * 
		FROM wp_posts 
		WHERE post_type = 'wc_booking' 
			AND not post_status like '%cart%'
			AND post_status = 'confirmed' 
			AND post_author = {$author_id}
		ORDER BY post_date_gmt asc limit 1
	";
	$result = get_fetch_assoc($sql);
	return $result;
}
function get_primera_conocer( $author_id=0 ){
	$result = [];
	$sql = "
		SELECT 
			p.*
		FROM wp_postmeta as m
			LEFT JOIN wp_posts as p  ON p.ID = m.post_id 
		WHERE 
			m.meta_key = 'request_status'
			p.post_author = {$author_id}
		ORDER BY p.post_date_gmt asc limit 1
	";
	$result = get_fetch_assoc($sql);
	return $result;
}


function diferenciaDias( $inicio, $fin ){
	$fecha1 = new DateTime($inicio);
	$fecha2 = new DateTime($fin);
	$intervalo = $fecha1->diff($fecha2);
	return [
		'obj' => $intervalo,
		'anio' => $intervalo->format('%Y'),
		'mes' => $intervalo->format('%m'),
		'dia' => $intervalo->format('%d'),
		'hora' => $intervalo->format('%H'),
		'minuto' => $intervalo->format('%i'),
		'segundo' => $intervalo->format('%s'),
	];
    // $inicio = strtotime($inicio);
    // $fin = strtotime($fin);
    // $dif = $fin - $inicio;
    // $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
    // return ceil($diasFalt);
}