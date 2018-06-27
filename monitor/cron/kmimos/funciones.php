<?php

require_once ( dirname(dirname(__DIR__)).'/reportes/class/general.php' );

class funciones extends general{

	public function save_reservas( $data ){

		$id = 0;
		$sql = "select * from monitor_reservas where numero_reserva = '{$data['numero_reserva']}'";
		$_data = $this->select($sql);

		// Actualizar registros
		if( isset($_data[0]['id']) && $_data[0]['id'] > 0 ){	
			$id = $_data[0]['id'];
		}
		if( $id > 0 ){
			$sql = "UPDATE monitor_reservas SET 
				numero_pedido = {$data['numero_pedido']},
				numero_reserva = {$data['numero_reserva']},
				flash = {$data['flash']},
				estatus = '{$data['estatus']}',
				fecha = '{$data['fecha']}',
				reserva_inicio = '{$data['reserva_inicio']}',
				reserva_fin = '{$data['reserva_fin']}',
				noches = {$data['noches']},
				mascotas_cantidad = {$data['mascotas_cantidad']},
				noches_total = {$data['noches_total']},
				cliente_email = '{$data['cliente_email']}',
				cuidador_email = '{$data['cuidador_email']}',
				user_referred = '{$data['user_referred']}',
				producto_id = {$data['producto_id']},
				servicio_principal = '{$data['servicio_principal']}',
				servicio_adicional = '{$data['servicio_adicional']}',
				pago_estatus = '{$data['pago_estatus']}',
				tipo_pago = '{$data['tipo_pago']}',
				forma_pago = '{$data['forma_pago']}',
				total_pago = {$data['total_pago']},
				monto_pagado = {$data['monto_pagado']},
				monto_remanente = {$data['monto_remanente']},
				descuento = {$data['descuento']},
				descuento_porcentaje = {$data['descuento_porcentaje']}
			WHERE id = {$id}";
		}else{
			$sql = "INSERT INTO monitor_reservas (
				numero_pedido,
				numero_reserva,
				flash,
				estatus,
				fecha,
				reserva_inicio,
				reserva_fin,
				noches,
				mascotas_cantidad,
				noches_total,
				cliente_email,
				cuidador_email,
				user_referred,
				producto_id,
				servicio_principal,
				servicio_adicional,
				pago_estatus,
				tipo_pago,
				forma_pago,
				total_pago,
				monto_pagado,
				monto_remanente,
				descuento,
				descuento_porcentaje
	 		) VALUES (
				{$data['numero_pedido']},
				{$data['numero_reserva']},
				{$data['flash']},
				'{$data['estatus']}',
				'{$data['fecha']}',
				'{$data['reserva_inicio']}',
				'{$data['reserva_fin']}',
				{$data['noches']},
				{$data['mascotas_cantidad']},
				{$data['noches_total']},
				'{$data['cliente_email']}',
				'{$data['cuidador_email']}',
				'{$data['user_referred']}',
				{$data['producto_id']},
				'{$data['servicio_principal']}',
				'{$data['servicio_adicional']}',
				'{$data['pago_estatus']}',
				'{$data['tipo_pago']}',
				'{$data['forma_pago']}',
				{$data['total_pago']},
				{$data['monto_pagado']},
				{$data['monto_remanente']},
				{$data['descuento']},
				{$data['descuento_porcentaje']}
	 		)";
		}
		
		echo $sql;
		
		$this->query( $sql );
		return $data;
	}

	public function save_ventas( $fecha, $data ){

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

	
	public function getRazas(){
		$_razas = $this->select('SELECT * from razas ');
		$razas=[];
		foreach ($_razas as $key => $value) {
			$razas[$value['id']] = $value['nombre'];
		}
		return $razas;
	}


	public function getMetaCuidador( $user_id ){
		$condicion = " AND m.meta_key IN ('first_name', 'last_name', 'user_referred')";
		$result = $this->get_metaUser($user_id, $condicion);
		$data = [
			'first_name' =>'', 
			'last_name' =>'', 
			'user_referred' =>'', 
		];
		if( !empty($result) ){
			foreach ($result as $row) {
				$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
			}
		}
		$data = $this->merge_phone($data);
		return $data;
	}

	public function getMetaCliente( $user_id ){
		$condicion = " AND m.meta_key IN ('first_name', 'last_name', 'user_referred')";
		$result = $this->get_metaUser($user_id, $condicion);
		$data = [
			'first_name' =>'', 
			'last_name' =>'', 
			'user_referred' =>'', 
		];
		if( !empty($result) ){
			foreach ($result as $row) {
				$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
				//$data['cliente_nombre'] = utf8_encode( $row['meta_value'] );
			}
		}
		$data = $this->merge_phone($data);
		return $data;
	}


	public function getReservas($desde="", $hasta=""){

		$filtro_adicional = "";

		if( !empty($desde) && !empty($hasta) ){
			$filtro_adicional = " 
				AND ( r.post_date_gmt >= '{$desde} 00:00:00' and  r.post_date_gmt <= '{$hasta} 23:59:59' )
			";
		}else{
			$filtro_adicional = " AND MONTH(r.post_date_gmt) = MONTH(NOW()) AND YEAR(r.post_date_gmt) = YEAR(NOW()) ";
		}

		$sql = "
			SELECT 
				r.ID as 'nro_reserva',
	 			DATE_FORMAT(r.post_date_gmt,'%Y-%m-%d') as 'fecha_solicitud',
	 			r.post_status as 'estatus_reserva',
	 			p.ID as 'nro_pedido',
	 			p.post_status as 'estatus_pago', 			
				pr.post_title as 'producto_title',
				pr.post_name as 'producto_name',	
	 			(IFNULL(mpe.meta_value,0) + IFNULL(mme.meta_value,0) + IFNULL(mgr.meta_value,0) + IFNULL(mgi.meta_value,0)) as nro_mascotas,
	 			

				pr.ID as producto_id,
				pr.post_name as post_name,
	 			us.user_id as cuidador_id,
	 			cl.ID as cliente_id

			from wp_posts as r
				LEFT JOIN wp_postmeta as rm ON rm.post_id = r.ID and rm.meta_key = '_booking_order_item_id' 
				LEFT JOIN wp_posts as p ON p.ID = r.post_parent

				LEFT JOIN wp_woocommerce_order_itemmeta as fe  ON (fe.order_item_id  = rm.meta_value and fe.meta_key  = 'Fecha de Reserva')
				
				LEFT JOIN wp_woocommerce_order_itemmeta as mpe ON mpe.order_item_id = rm.meta_value and (mpe.meta_key = 'Mascotas Pequeños' or mpe.meta_key = 'Mascotas Pequeñas')
				LEFT JOIN wp_woocommerce_order_itemmeta as mme ON mme.order_item_id = rm.meta_value and (mme.meta_key = 'Mascotas Medianos' or mme.meta_key = 'Mascotas Medianas')
				LEFT JOIN wp_woocommerce_order_itemmeta as mgr ON (mgr.order_item_id = rm.meta_value and mgr.meta_key = 'Mascotas Grandes')
				LEFT JOIN wp_woocommerce_order_itemmeta as mgi ON (mgi.order_item_id = rm.meta_value and mgi.meta_key = 'Mascotas Gigantes')
				LEFT JOIN wp_woocommerce_order_itemmeta as pri ON (pri.order_item_id = rm.meta_value and pri.meta_key = '_product_id')
				LEFT JOIN wp_posts as pr ON pr.ID = pri.meta_value
				LEFT JOIN cuidadores as us ON us.user_id = pr.post_author
				LEFT JOIN wp_users as cl ON cl.ID = r.post_author
			WHERE r.post_type = 'wc_booking' 
				and not r.post_status like '%cart%' 
				and cl.ID > 0 
				and p.ID > 0
				{$filtro_adicional}
			ORDER BY r.ID desc
			;";

		$reservas = $this->select($sql);
		return $reservas;
	}



	public function getMetaReserva( $post_id ){
		$condicion = " AND meta_key IN ( '_booking_start', '_booking_end', '_booking_cost', 'modificacion_de', '_booking_order_item_id', 'reserva_modificada', 'penalizado', '_booking_flash' )";
		$result = $this->get_metaPost($post_id, $condicion);

		$data = [
			'_booking_start' =>'', 
			'_booking_end' =>'', 
			'_booking_cost' =>'', 
			'_booking_order_item_id' =>'', 
			'_booking_flash'=>'',
			'penalizado' =>'', 
			'reserva_modificada'=>'',
			'modificacion_de' =>'', 
		];
		if( !empty($result) ){
			foreach ($result as $row) {
				$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
			}
		}
		return $data;	
	}

	public function getMetaPedido( $post_id ){
		$condicion = " AND meta_key IN ( '_payment_method','_payment_method_title','_order_total','_wc_deposits_remaining', '_cart_discount' )";
		$result = $this->get_metaPost($post_id, $condicion);
		$data = [
			'_payment_method' => '',
			'_payment_method_title' => '',
			'_order_total' => '',
			'_wc_deposits_remaining' => '',
			'_cart_discount' => '',
		];
		if( !empty($result) ){
			foreach ($result as $row) {
				$data[$row['meta_key']] = utf8_encode( $row['meta_value'] );
			}
		}
		return $data;	
	}

	public function getTipoPagoReserva( $meta_reserva ){
		$deposito = $this->select("
					SELECT meta_value 
					FROM wp_woocommerce_order_itemmeta 
					WHERE 
						order_item_id = {$meta_reserva['_booking_order_item_id']} 
						AND meta_key = '_wc_deposit_meta' 
				");

		foreach ($deposito as $key => $value) {
			$deposito = unserialize($value['meta_value']);
		}

		return $deposito;
	}

	public function getCountReservas( $author_id=0, $interval=12, $desde="", $hasta="" ){

		$filtro_adicional = "";
		if( !empty($landing) ){
			$filtro_adicional = " source = '{$landing}'";
		}
		if( !empty($desde) && !empty($hasta) ){
			$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
			$filtro_adicional .= " 
				DATE_FORMAT(post_date_gmt, '%m-%d-%Y') between DATE_FORMAT('{$desde}','%m-%d-%Y') and DATE_FORMAT('{$hasta}','%m-%d-%Y')
			";
		}else{
			$filtro_adicional .= (!empty($filtro_adicional))? ' AND ' : '' ;
			$filtro_adicional .= " MONTH(post_date_gmt) = MONTH(NOW()) AND YEAR(post_date_gmt) = YEAR(NOW()) ";
		}


		$filtro_adicional = ( !empty($filtro_adicional) )? " WHERE {$filtro_adicional}" : $filtro_adicional ;

		$result = [];
		$sql = "
			SELECT 
				count(ID) as cant
			FROM wp_posts
			WHERE post_type = 'wc_booking' 
				AND not post_status like '%cart%'
				AND post_status = 'confirmed' 
				AND post_author = {$author_id}
				AND post_date_gmt > DATE_SUB(CURDATE(), INTERVAL {$interval} MONTH)
		";

		$result = $this->select($sql);
		return $result;
	}

	public function get_status($sts_reserva, $sts_pedido, $forma_pago="", $meta=[]){
		
		// Cargar a totales
		$addTotal = 0;
		// Resultado
		$sts_corto = "---";
		$sts_largo = "Estatus Reserva: {$sts_reserva}  /  Estatus Pedido: {$sts_pedido}";
		//===============================================================
		// BEGIN PaymentMethod
		// Nota: Agregar la equivalencia de estatus de las pasarelas de pago
		//===============================================================
		$payment_method_cards = [ // pagos por TDC / TDD
			'openpay_cards',
			'tarjeta',
		]; 
		$payment_method_store = [ // pagos por Tienda por conveniencia
			'openpay_stores',
			'tienda',
		]; 
		//===============================================================
		// END PaymentMethod
		//===============================================================

		// Pedidos
		switch ($sts_reserva) {
			case 'unpaid':
				$sts_corto = "Pendiente";
				if( $sts_pedido == 'wc-on-hold'){
					if( in_array($forma_pago, $payment_method_cards) ){
						$sts_largo = "Pendiente por confirmar el cuidador"; // metodo de pago es por TDC / TDD ( parcial )
						$sts_corto = "Pago fallido";
					}elseif( in_array($forma_pago, $payment_method_store) ){
						$sts_largo = "Pendiente de pago en tienda"; // Tienda por conv
					}else{
						$sts_largo = "Estatus Pedido: {$sts_pedido}"; 
					}
				}
				if( $sts_pedido == 'wc-pending'){
					$sts_largo = 'Pendiente de pago';
					if( in_array($forma_pago, $payment_method_cards) ){
						$sts_corto = "Pago fallido";
					}
				}
			break;
			case 'wc-partially-paid':
				$sts_largo = "Estatus Reserva: Pago Parcial  /  Estatus Pedido: {$sts_pedido}";
				if( $sts_pedido == 'unpaid'){
					$sts_corto = 'Por confirmar (cuidador)';
					$sts_largo = 'Por confirmar (cuidador)';
				}
			break;		
			case 'confirmed':
				$sts_corto = 'Confirmado';
				$sts_largo = 'Confirmado';
				$addTotal  = 1;
			break;
			case 'paid':
				$sts_corto = 'Pagado';
				$sts_largo = 'Pagado';
			break;
			case 'cancelled':
				$sts_corto = 'Cancelado';
				$sts_largo = 'Cancelado';

				if( isset($meta['penalizado']) && $meta['penalizado'] == "YES" ){
					$sts_corto = 'Penalizado';
					$sts_largo = 'Cancelado con penalización';
				}
			break;
			// Modificacion Ángel Veloz
			case 'modified':
				if( isset($meta['reserva_modificada']) ){				
					$por = $meta['reserva_modificada'];
				}
				$sts_corto = 'Modificado';
				$sts_largo = 'Modificado por la reserva: '.$por;
			break;
		}

		return $sts_corto;
		/*
		return 	$result = [ 
			"reserva"  => $sts_reserva, 
			"pedido"   => $sts_pedido,
			"sts_corto"=> $sts_corto,
			"sts_largo"=> $sts_largo,
			"addTotal" => $addTotal,
		];
		*/
	}

	public function photo_exists($path=""){
		$photo = (file_exists('../'.$path) && !empty($path))? 
			get_option('siteurl').'/'.$path : 
			get_option('siteurl')."/wp-content/themes/kmimos/images/noimg.png";
		return $photo;
	}

	public function getMascotas($user_id){
		if(!$user_id>0){ return []; }

		$mascotas_cliente = $this->select("SELECT * FROM wp_posts WHERE post_author = '{$user_id}' AND post_type='pets' AND post_status = 'publish'");

	    $mascotas = array();
	    foreach ($mascotas_cliente as $key => $mascota) {
	        $_metas = $this->select( "SELECT * FROM wp_postmeta WHERE post_id = " . $mascota['ID'] );

	        $metas = [];
			foreach ($_metas as $key => $val) {
				$metas[ $val['meta_key'] ] = $val['meta_value'];
			}

	        $anio = $metas["birthdate_pet"][0];
	        $anio = str_replace("/", "-", $anio);
	        $anio = strtotime($anio);
	        $edad_time = time()-$anio;

	        $edad = '';
	        if( (date("Y", $edad_time)-1970) > 0 ){
		        $edad = (date("Y", $edad_time)-1970)." año(s) ";
	        }
	        $edad .= date("m", $edad_time)." mes(es)";
	 
	 		if( !isset($metas["breed_pet"][0]) ){
	 			$metas["breed_pet"][0] = '';
	 		}

	        $mascotas[] = array(
	            "nombre" => $mascota['post_title'],
	            "raza" => $metas["breed_pet"][0],
	            "edad" => $edad
	        );


	    }
		return $mascotas;
	}

	public function getProduct( $num_reserva = 0 ){
		$services = [];

		$sql = "	
			SELECT 
				i.meta_key as 'servicio',
				i.meta_value as 'descripcion'
			FROM wp_woocommerce_order_itemmeta as i
				-- Order_item_id
				LEFT JOIN wp_woocommerce_order_itemmeta as o ON ( o.meta_key = 'Reserva ID' and o.meta_value = $num_reserva )
				-- Reserva
				LEFT JOIN wp_posts as re ON re.ID = i.meta_value -- No. Reserva
			WHERE	
				i.meta_key like 'Servicios Adicionales%'
				and i.order_item_id = o.order_item_id
		";
		$services = $this->select($sql);

		return $services;	
	}

	public function getServices( $num_reserva = 0 ){
		$services = [];

		$sql = "	
			SELECT 
				i.meta_key as 'servicio',
				i.meta_value as 'descripcion'
			FROM wp_woocommerce_order_itemmeta as i
				-- Order_item_id
				LEFT JOIN wp_woocommerce_order_itemmeta as o ON ( o.meta_key = 'Reserva ID' and o.meta_value = $num_reserva )
				-- Reserva
				LEFT JOIN wp_posts as re ON re.ID = i.meta_value -- No. Reserva
			WHERE	
				i.meta_key like 'Servicios Adicionales%'
				and i.order_item_id = o.order_item_id
		";
		$_services = $this->select($sql);

		$services = [];
		if( !empty($_services) ){			
			foreach ($_services as $key => $value) {
				$services[ $value['servicio'] ] = $value['descripcion'];
			}
		}
		return $services;
	}

	public function get_ubicacion_cuidador( $user_id ){
		$sql = "
			SELECT ub.*
			from  ubicaciones as ub
				inner join cuidadores as u ON u.id = ub.cuidador  
	 	 	WHERE u.user_id = $user_id
	 	";
		$ubi = $this->select($sql);

		$ubicacion=$ubi;

		$data = [
			"estado" => '',
			"municipio" => '',
			"sql" => $sql,
		];
		if(count($ubi)>0){
			$ubicacion = $ubi[0];

			$estado = explode('=', $ubicacion['estado']);
			$munici = explode('=', $ubicacion['municipios']);

			$est = $this->select("select * from states as est where est.id = ".$estado[1]);


			if(count($est)>0){ 
				$est = $est[0];
				$data['estado'] = $est['name']; 
			}

			$mun = $this->select("select * from locations as mun where mun.id = ".$munici[1]);
			if(count($mun)>0){ 
				$mun = $mun[0];
				$data['municipio'] = $mun['name']; 
			}

		}

		return $data;
	}

	public function Get_CouponCode($order_id,$coupon_code) {
		$return = array();

		$query = "SELECT DISTINCT
	        wc_items.order_item_name AS coupon_name,
	        wc_itemmeta.meta_value AS coupon_discount_amount,
	        postmeta.*

	        FROM
	        {$wpdb->prefix}woocommerce_order_items AS wc_items
			LEFT JOIN
	        {$wpdb->prefix}woocommerce_order_itemmeta AS wc_itemmeta ON wc_items.order_item_id = wc_itemmeta.order_item_id
	        LEFT JOIN
	        {$wpdb->prefix}posts AS post ON post.post_title = wc_items.order_item_name
	        LEFT JOIN
	        {$wpdb->prefix}postmeta AS postmeta ON post.ID = postmeta.post_id

	        WHERE
	        wc_items.order_id = '{$order_id}' AND
	        wc_items.order_item_type = 'coupon' AND
	        wc_items.order_item_name LIKE '%{$coupon_code}%' AND
			wc_itemmeta.meta_key = 'discount_amount' ";

		$coupons = $this->select($query);

		if (!empty($coupons)) {
			foreach ($coupons as $key => $coupon) {
				//var_dump($coupon);
				$coupon_name = $coupon->coupon_name;

				if($coupon->meta_key=='coupon_amount'){
					if(!array_key_exists($coupon_name,$return)){
						$return[$coupon_name]=array();
					}
					$return[$coupon_name]['coupon_name'] = $coupon_name;
					$return[$coupon_name]['coupon_amount'] = $coupon->meta_value;
					//$return[$coupon_name]['coupon_amount'] = $coupon->meta_value;

				}else if($coupon->meta_key=='discount_type'){
					if(!array_key_exists($coupon_name,$return)){
						$return[$coupon_name]=array();
					}

					$return[$coupon_name]['discount_type'] = $coupon->meta_value;
				}

				//AMOUNT DISCOUNT
				$return[$coupon_name]['coupon_amount'] = $coupon->coupon_discount_amount;
			}
		}

		//var_dump($return);
		return $return;
	}

	public function Get_SumCouponCode($order_id,$coupon_code,$total=0) {
		$coupons = $this->Get_CouponCode($order_id,$coupon_code);
		$amount = 0;

		if(count($coupons)){
			foreach($coupons as $coupon){
				if($coupon['discount_type'] != 'percent'){
					$coupon_amount = $coupon['coupon_amount'];

				}else{
					$coupon_amount = $total*($coupon['coupon_amount']/100);
				}

				$coupon_amount = $coupon['coupon_amount'];
				$amount = $amount+$coupon_amount;
			}
		}
		return $amount;
	}

	public function Get_NameCouponCode($order_id,$coupon_code) {
		$coupons = $this->Get_CouponCode($order_id,$coupon_code);
		$name = array();

		if(count($coupons)){
			foreach($coupons as $coupon){
				if( $coupon['coupon_amount'] > 0 ){
					$name[] = $coupon['coupon_name'];
				}
			}
		}

		return implode(',',$name);
	}

	public function getUserBy($value="", $field='user_email' ){
		$sql = "
			SELECT *
			FROM wp_users
			WHERE {$field} = {$value}
		";
		$result = $this->select($sql);
		return $result;	
	}

	public function getEdad($fecha){
		$fecha = str_replace("/","-",$fecha);
		$hoy = date('Y/m/d');

		$diff = abs(strtotime($hoy) - strtotime($fecha) );
		$years = floor($diff / (365*60*60*24)); 
		$desc = " Años";
		$edad = $years;
		if($edad==0){
			$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
			$edad = $months;
			$desc = ($edad > 1) ? " Meses" : " Mes";
		}
		if($edad==0){
			$days  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			$edad = $days;
			$desc = " Días";
		}

		return $edad . $desc;
	}

	public function get_razas(){
		$sql = "SELECT * FROM razas ";
		$result = $this->select($sql);
		$razas = [];
		foreach ($result as $raza) {
			$razas[$raza->id] = $raza->nombre;
		}
		return $razas;
	}

	public function dias_transcurridos($fecha_i,$fecha_f){
		$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
		$dias 	= abs($dias); $dias = floor($dias);		
		return $dias;
	}


	public function date_convert( $str_date, $format = 'd-m-Y H:i:s', $totime=true ){
		$fecha = $str_date;
		if(!empty($str_date)){
			if($totime){
				$time = strtotime($str_date);
			}
			$fecha = date($format,$time);
		}
		return $fecha;
	}

	public function currency_format( $str, $signo="$ ", $miles=",", $decimal="." ){
		if(!empty($str)){
			$str = $signo.number_format($str, 2, $decimal, $miles);
		}else{
			$str = $signo."0";
		}
		return $str;
	}

	public function get_metaPost($post_id=0, $condicion=''){
		$sql = "
			SELECT u.meta_key, u.meta_value, u.post_id
			FROM wp_postmeta as u 
			WHERE 
				u.post_id = {$post_id} 
				{$condicion}
		";	
		$result = $this->select($sql);
		return $result;	
	}

	public function merge_phone($param, $separador=' / '){
		$param['phone'] = isset($param['user_phone']) ? 
			$param['user_phone'] : ''; 
		if(isset($param['user_mobile'])){ 
			$param['phone'] .= (!empty($param['phone']))? $separador : '' ;
			$param['phone'] .= $param['user_mobile'];
		}

		return $param;
	}

	public function getTotalClientes($desde="", $hasta=""){
		$sql = "
			SELECT 
				count(u.ID) as cant
			FROM wp_users as u
				LEFT JOIN cuidadores as c ON c.user_id = u.ID
			WHERE c.id is null 
				AND ( u.user_registered >= '{$desde} 00:00:00' 
					and  u.user_registered  <= '{$hasta} 23:59:59' )
		";

		$result = $this->select($sql);
		if( isset($result[0]['cant']) ){
			return $result[0]['cant'];
		}else{
			return 0;
		}
	}
}
