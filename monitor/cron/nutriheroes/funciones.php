<?php

require_once ( dirname(dirname(__DIR__)).'/conf/database.php' );

function get_fetch_assoc($sql){
	$db = new db();
	$rows = $db->query($sql);
	
	$data = ['info'=>[], 'rows'=>[]];
	if(isset($rows->num_rows)){
		if( $rows->num_rows > 0){
			$data['info'] = $rows;
			$data['rows'] = mysqli_fetch_all( $rows,MYSQLI_ASSOC);
		}
	}
	return $data;
}

function save( $tipo, $fecha, $param ){

	$id = 0;
	$sql = "select * from monitor_diario where fecha = '{$fecha}'";
	$data = get_fetch_assoc($sql);	
	$param = json_encode($param);

	// Actualizar registros
	if( isset($data['info']->num_rows) && $data['info']->num_rows > 0 ){	
		$id = $data['rows'][0]['id'];
	}
	switch ($tipo) {
		case 'ventas':
			if( $id > 0 ){
				$sql = "update monitor_diario set reserva = '{$param}' where id = {$id}";	
			}else{
				$sql = "insert into monitor_diario (reserva, fecha) VALUES ('{$param}','{$fecha}')";	
			}
			break;
		case 'usuario':
			if( $id > 0 ){
				$sql = "update monitor_diario set cliente = '{$param}' where id = {$id}";	
			}else{
				$sql = "insert into monitor_diario (cliente, fecha) VALUES ('{$param}','{$fecha}')";	
			}
			break;
	}
	get_fetch_assoc( $sql );
	return $data;
}

// ***************************************
// Cargar listados de Reservas
// ***************************************

function getRecompras( $desde, $hasta ){
	$sql = "SELECT count(id), concat(MONTH(fecha_creacion), '/', year(fecha_creacion)) as mes
		FROM ordenes 
		WHERE 
			`status` = 'Activa'
			AND fecha_creacion >= '{$desde}'
			AND fecha_creacion <= '{$hasta}'
		GROUP BY concat(MONTH(fecha_creacion), '/', year(fecha_creacion))";
	return get_fetch_assoc($sql);
}

function getOrdenes( $desde, $hasta ){
	$sql = "
		SELECT *
		FROM ordenes 
		WHERE fecha_creacion >= '{$desde}'
			AND fecha_creacion <= '{$hasta}'
	";
	return get_fetch_assoc($sql);
}

function getItems( $orden_id ){
	$sql = "
		SELECT * 
		FROM items_ordenes
		WHERE id_orden = {$orden_id}";
	return get_fetch_assoc($sql);		
}

function getUsuarios($desde="", $hasta=""){
	$sql = "
		SELECT 
			u.ID,
			u.user_email,
			u.user_registered, 
			NULL as cuidador_id
		FROM wp_users as u
		WHERE DATE_FORMAT(u.user_registered, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
		ORDER BY DATE_FORMAT(u.user_registered,'%d-%m-%Y') DESC;
	";
	return get_fetch_assoc($sql);
}

function getMetaUsuario( $user_id ){ 
	$condicion = " AND meta_key IN ( 'sexo', 'edad', 'dondo_conociste' )";
	$result = get_metaUser($user_id, $condicion);
	$data = [
		'first_name' =>'', 
		'last_name' =>'', 
		'user_referred' =>'', 
	];
	if( !empty($result) ){
		foreach ($result['rows'] as $row) {
			$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
		}
	}
	return $data;
}

function get_metaUser($user_id=0, $condicion=''){
	$sql = "
		SELECT u.user_email, m.*
		FROM wp_users as u 
			INNER JOIN wp_usermeta as m ON m.user_id = u.ID
		WHERE 
			m.user_id = {$user_id} 
			{$condicion}
	";
	return get_fetch_assoc($sql);
}