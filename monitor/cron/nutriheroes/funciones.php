<?php

require_once ( dirname(dirname(__DIR__)).'/conf/database.php' );

	function get_fetch_assoc($sql){
		$db = new db();
		$data['rows'] = $db->select($sql);
		
		/*$data = ['info'=>[], 'rows'=>[]];
		if(isset($rows->num_rows)){
			if( $rows->num_rows > 0){
				$data['info'] = $rows;
				$data['rows'] = mysqli_fetch_all( $rows,MYSQLI_ASSOC);
			}
		}*/
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
	function save_ventas( $fecha, $data ){

		$id = 0;
		$sql = "select * from monitor_diario_ventas where fecha = '{$fecha}'";
		$_data = $this->select($sql);

		// Actualizar registros
		if( isset($_data[0]['id']) && $_data[0]['id'] > 0 ){	
			$id = $_data[0]['id'];
		}
		if( $id > 0 ){
			$sql = "UPDATE monitor_diario_ventas SET
		 			clientes_total = {$data['clientes']['total']},
		 			clientes_nuevos = {$data['clientes']['nuevos']},
		 			mascotas_total = {$data['mascotas_total']},
		 			noches_numero = {$data['noches']['numero']},
		 			noches_total = {$data['noches']['total']},
		 			ventas_cantidad = {$data['ventas']['cant']},
		 			ventas_estatus = '".json_encode($data['ventas']['estatus'],JSON_UNESCAPED_UNICODE)."',
		 			ventas_tipo = '".json_encode($data['ventas']['tipo'],JSON_UNESCAPED_UNICODE)."',
		 			ventas_tipopago = '".json_encode($data['ventas']['tipo_pago'],JSON_UNESCAPED_UNICODE)."',
		 			ventas_formapago = '".json_encode($data['ventas']['forma_pago'],JSON_UNESCAPED_UNICODE)."',
		 			ventas_costo = '".json_encode($data['ventas']['costo'],JSON_UNESCAPED_UNICODE)."',
		 			ventas_costo_total = {$data['ventas']['costo_total']},
		 			ventas_descuento = {$data['ventas']['descuento']}
				WHERE id = {$id}
			";
		}else{
			$sql = "INSERT INTO monitor_diario_ventas (
	 			fecha,
	 			clientes_total,
	 			clientes_nuevos,
	 			mascotas_total,
	 			noches_numero,
	 			noches_total,
	 			ventas_cantidad,
	 			ventas_estatus,
	 			ventas_tipo,
	 			ventas_tipopago,
	 			ventas_formapago,
	 			ventas_costo,
	 			ventas_costo_total,
	 			ventas_descuento
	 		) VALUES (
	 			'{$fecha}',
	 			{$data['clientes']['total']},
	 			{$data['clientes']['nuevos']},
	 			{$data['mascotas_total']},
	 			{$data['noches']['numero']},
	 			{$data['noches']['total']},
	 			{$data['ventas']['cant']},
	 			'".json_encode($data['ventas']['estatus'],JSON_UNESCAPED_UNICODE)."',
	 			'".json_encode($data['ventas']['tipo'],JSON_UNESCAPED_UNICODE)."',
	 			'".json_encode($data['ventas']['tipo_pago'],JSON_UNESCAPED_UNICODE)."',
	 			'".json_encode($data['ventas']['forma_pago'],JSON_UNESCAPED_UNICODE)."',
	 			'".json_encode($data['ventas']['costo'],JSON_UNESCAPED_UNICODE)."',
	 			{$data['ventas']['costo_total']},
	 			{$data['ventas']['descuento']}
	 		)";
		}
		
		echo $sql;
		
		$this->query( $sql );
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