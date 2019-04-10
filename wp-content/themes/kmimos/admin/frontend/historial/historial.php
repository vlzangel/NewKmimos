<?php
 
	global $wpdb;
	$sql = "SELECT * FROM $wpdb->posts WHERE post_type = 'wc_booking' AND post_author = {$user_id} AND post_status NOT LIKE '%cart%' ORDER BY id DESC";
	$reservas = $wpdb->get_results($sql);

	if( count($reservas) > 0 ){

		$reservas_array = array(
			"pendientes_tienda" => array(
				//"titulo" => 'Reservas pendientes por pagar en tienda por conveniencia',
				"titulo" => '',
				"reservas" => array()
			),
			"pendientes_confirmar" => array(
				//"titulo" => 'Reservas Pendientes por Confirmar',
				"titulo" => '',
				"reservas" => array()
			),
			"confirmadas" => array(
				//"titulo" => 'Reservas Confirmadas',
				"titulo" => '',
				"reservas" => array()
			),
			"completadas" => array(
				//"titulo" => 'Reservas Completadas',
				"titulo" => '',
				"reservas" => array()
			),
			"canceladas" => array(
				//"titulo" => 'Reservas Canceladas',
				"titulo" => '',
				"reservas" => array()
			),
			"modificadas" => array(
				//"titulo" => 'Reservas Modificadas',
				"titulo" => '',
				"reservas" => array()
			),
			"error" => array(
				//"titulo" => 'Reservas con error en tarjetas de credito',
				"titulo" => '',
				"reservas" => array()
			),
			"otros" => array(
				//"titulo" => 'Otras Reservas',
				"titulo" => '',
				"reservas" => array()
			)
		);

		//PENDIENTE POR PAGO EN TIENDA DE CONVENINCIA
		foreach($reservas as $key => $reserva){

			$_metas_reserva = get_post_meta($reserva->ID);
			$_metas_orden = get_post_meta($reserva->post_parent);

			$servicio = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = ".$_metas_reserva['_booking_product_id'][0]);

			$reserva_status = $reserva->post_status;
			$orden_status = $wpdb->get_var("SELECT post_status FROM $wpdb->posts WHERE ID = ".$reserva->post_parent);

			$creada = strtotime( $reserva->post_date );
			$inicio = strtotime( $_metas_reserva['_booking_start'][0] );
			$fin    = strtotime( $_metas_reserva['_booking_end'][0] );

			$cuidador_user_id = $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = ".$_metas_reserva['_booking_product_id'][0]);

			$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = ".$cuidador_user_id);

			$foto = kmimos_get_foto( $cuidador_user_id ) ;

			$pdf = $_metas_orden['_openpay_pdf'][0];
			$ver = $reserva->post_parent;
			$cancelar = $reserva->post_parent;
			$modificar = md5($reserva->ID)."_".md5($user_id)."_".md5($servicio->ID);
			$valorar = $reserva->ID;
			$facturar = $reserva->post_parent;

			$xitems = $wpdb->get_results( "SELECT meta_key, meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = ".$_metas_reserva["_booking_order_item_id"][0] );
			$items = array();
			foreach ($xitems as $item) {
				$items[ $item->meta_key ] = $item->meta_value;
			}

			$fee = $_metas_orden["_order_fee"][0];

			$pago = unserialize($items["_wc_deposit_meta"]);

			$items["_line_subtotal"] += $fee;
			$_metas_orden["_cart_discount"][0] += $fee;

			$desglose = $pago;
			if( $pago["enable"] == "yes" ){
				$desglose["descuento"] = $_metas_orden["_cart_discount"][0];
				$desglose["tipo"] = "DEPÓSITO DEL 20%";
			}else{
				$desglose["subtotal"] = $items["_line_subtotal"]-$_metas_orden["_cart_discount"][0];
				$desglose["total"] = $items["_line_subtotal"];
				$desglose["descuento"] = $_metas_orden["_cart_discount"][0];
				$desglose["tipo"] = "PAGO TOTAL";
			}

			//RESERVAS PENDIENTES POR ERROR DE PAGOS DE TARJETAS
			if($orden_status == 'wc-pending') {

			}else if($orden_status == 'wc-on-hold' && ( $_metas_orden['_payment_method'][0] == 'openpay_stores' || $_metas_orden['_payment_method'][0] == 'tienda' ) ){

				$reservas_array["pendientes_tienda"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver,
						"modificar" => $modificar,
						"cancelar" => $cancelar,
						"pdf" => $pdf,
						"noFacturar" => 'info_facturacion',
					),
					"desglose" => $desglose
				);

				//RESERVAS CONFIRMADAS
			}else if($reserva->post_status=='confirmed' && strtotime($_metas_reserva['_booking_end'][0])>time()){
				
				$reservas_array["confirmadas"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver,
						"modificar" => $modificar,
						"cancelar" => $cancelar,
						"noFacturar" => 'info_facturacion',
					),
					"desglose" => $desglose
				);

				//RESERVAS COMPLETADAS
			}else if($reserva->post_status=='complete' || ($reserva->post_status=='confirmed' && strtotime($_metas_reserva['_booking_end'][0])<time())){

				$reservas_array["completadas"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver,
						"valorar" => $valorar,
						"facturar" => $facturar
					),
					"desglose" => $desglose,
					"ayuda" => "factura"
				);

				//RESERVAS CANCELADAS
			}else if($reserva->post_status=='cancelled' || $reserva->post_status=='wc_cancelled'){

				$reservas_array["canceladas"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver,
						"noFacturar" => 'disabled',
					),
					"desglose" => $desglose
				);

			//RESERVAS MODIFICADAS
			}else if($reserva->post_status=='modified'){

				$reservas_array["modificadas"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver,
						"noFacturar" => 'disabled',
					),
					"desglose" => $desglose
				);

			//RESERVAS PNDIENTES POR CONFIRMAR
			}else if($reserva->post_status!='confirmed'){

				$data_conocer = [
					'id' => $cuidador->id_post,
					'url' => 'petsitters/'.$cuidador->url,
					'name' => $cuidador->titulo,
				];

				if( $_metas_reserva['_booking_test_conocer'][0] == 'c' ){
					$uso_cupon = $wpdb->get_row("SELECT * FROM wp_woocommerce_order_items WHERE order_id = '{$reserva->post_parent}' AND order_item_name = 'cpc10%' ");
					
					if( $uso_cupon == null ){
						$_metas_reserva['_booking_test_conocer'][0] = '';
						$data_conocer = [];
					}
				}

				$reservas_array["pendientes_confirmar"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'conocer' => $_metas_reserva['_booking_test_conocer'][0], 
					'data_conocer' => $data_conocer, 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver,
						"modificar" => $modificar,
						"cancelar" => $cancelar,
						"noFacturar" => 'info_facturacion',						
					),
					"desglose" => $desglose
				);

			}else{

				$reservas_array["otros"]["reservas"][] = array(
					'id' => $reserva->ID, 
					'servicio_id' => $servicio->ID, 
					'servicio' => $servicio->post_title, 
					'inicio' => date('d/m/Y', $inicio), 
					'fin' => date('d/m/Y', $fin), 
					'foto' => $foto,
					'acciones' => array(
						"ver" => $ver,
						"noFacturar" => 'disabled',						
					),
					"desglose" => $desglose
				);

			}
		}

		$pendientes = construir_listado(['pendientes_tienda'=>$reservas_array['pendientes_tienda']]);
		$por_confirmar = construir_listado(['pendientes_confirmar'=>$reservas_array['pendientes_confirmar']]);
		$confirmadas = construir_listado(['confirmadas'=>$reservas_array['confirmadas']]);
		$completadas = construir_listado(['completadas'=>$reservas_array['completadas']]);
		$canceladas = construir_listado(['canceladas'=>$reservas_array['canceladas']]);
		$modificadas = construir_listado(['modificadas'=>$reservas_array['modificadas']]);
		$error = construir_listado(['error'=>$reservas_array['error']]);
		$otros = construir_listado(['otros'=>$reservas_array['otros']]);
 
		//BUILD TABLE
		$CONTENIDO .= '
			<h1>Mis Reservas</h1>
			
			<div>
			
				<div class="kmisaldo alert alert-info" role="alert">
					<strong>'.kmimos_saldo_titulo().':</strong> MXN $'.kmimos_get_kmisaldo(true).'
				</div>

			  	<!-- Nav tabs -->
			  	<ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active">
				    	<a href="#pendiente" aria-controls="pendiente" role="tab" data-toggle="tab">
				    		Pendiente de Pago
				    	</a>
				    </li>
				    <li role="presentation">
				    	<a href="#por_confirmar" aria-controls="por_confirmar" role="tab" data-toggle="tab">
				    		Por Confirmar 
				    	</a>
				    </li>
				    <li role="presentation">
				    	<a href="#confirmadas" aria-controls="confirmadas" role="tab" data-toggle="tab">
				    		Confirmadas 
				    	</a>
				    </li>
				    <li role="presentation">
				    	<a href="#completadas" aria-controls="completadas" role="tab" data-toggle="tab">
				    		Completadas 
				    	</a>
				    </li>
				    <li role="presentation">
				    	<a href="#canceladas" aria-controls="canceladas" role="tab" data-toggle="tab">
				    		Canceladas 
				    	</a>
				    </li>
				    <li role="presentation">
				    	<a href="#modificadas" aria-controls="modificadas" role="tab" data-toggle="tab">
				    		Modificadas 
				    	</a>
				    </li>
				    <li role="presentation">
				    	<a href="#error" aria-controls="error" role="tab" data-toggle="tab">
				    		Error
				    	</a>
				    </li>
				    <li role="presentation">
				    	<a href="#otros" aria-controls="otros" role="tab" data-toggle="tab">
				    		Otros
				    	</a>
				    </li>
			  	</ul>

			  <!-- Tab panes -->
			  <div class="tab-content">
			    <div role="tabpanel" class="tab-pane active" id="pendiente">'.$pendientes.'</div>
			    <div role="tabpanel" class="tab-pane " id="por_confirmar">'.$por_confirmar.'</div>
			    <div role="tabpanel" class="tab-pane " id="confirmadas">'.$confirmadas.'</div>
			    <div role="tabpanel" class="tab-pane " id="completadas">'.$completadas.'</div>
			    <div role="tabpanel" class="tab-pane " id="canceladas">'.$canceladas.'</div>
			    <div role="tabpanel" class="tab-pane " id="modificadas">'.$modificadas.'</div>
			    <div role="tabpanel" class="tab-pane " id="error">'.$error.'</div>
			    <div role="tabpanel" class="tab-pane " id="otros">'.$otros.'</div>
			  </div>

			</div>';

	}else{
		$CONTENIDO .= "<h1 style='line-height: normal;'>Usted aún no tiene reservas.</h1><hr>";
	}

?>