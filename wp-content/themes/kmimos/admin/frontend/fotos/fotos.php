<?php
	$CONTENIDO .= '<div class="lista_reservas" data-user="'.$user_id.'">';

		$sql = "
			SELECT 
				posts.post_status AS status,
				producto.post_title AS titulo,
				posts.post_parent AS orden,
				posts.ID AS ID
			FROM 
				wp_posts AS posts
			LEFT JOIN wp_postmeta AS metas_reserva ON ( posts.ID = metas_reserva.post_id AND metas_reserva.meta_key='_booking_product_id' )
			LEFT JOIN wp_postmeta AS inicio ON ( posts.ID = inicio.post_id AND inicio.meta_key='_booking_start' )
			LEFT JOIN wp_postmeta AS fin ON ( posts.ID = fin.post_id AND fin.meta_key='_booking_end' )
			LEFT JOIN wp_posts AS producto ON ( producto.ID = metas_reserva.meta_value )
			LEFT JOIN wp_posts AS orden ON ( orden.ID = posts.post_parent )
			WHERE 
				posts.post_type      = 'wc_booking' AND 
				posts.post_status    = 'confirmed'  AND
				(
					fin.meta_value       > NOW()  AND
					inicio.meta_value    <= NOW()
				) AND
				producto.post_author = '{$user_id}'
				AND inicio.meta_value > '20171222000000'
			ORDER BY posts.ID DESC
		";

		$reservas = $wpdb->get_results($sql);

		if( count($reservas) > 0 ){

			$reservas_array = array(
				"confirmadas" => array(
					"titulo" => 'Reservas Confirmadas',
					"reservas" => array()
				)
			);

			foreach($reservas as $key => $reserva){

				$_metas_reserva = get_post_meta($reserva->ID);
				$_metas_orden   = get_post_meta($reserva->orden);

				//Cliente
				$_metas_user = get_user_meta($_metas_reserva['_booking_customer_id'][0]);
				$_Cliente = $_metas_user['first_name'][0]." ".$_metas_user['last_name'][0];

				$foto = kmimos_get_foto( $_metas_reserva['_booking_customer_id'][0] ) ;

				$servicio = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = ".$_metas_reserva['_booking_product_id'][0]);

				$inicio = strtotime( $_metas_reserva['_booking_start'][0] );
				$fin    = strtotime( $_metas_reserva['_booking_end'][0] );

				$pdf = $_metas_orden['_openpay_pdf'][0];
				$ver = $reserva->orden;
				$cancelar = $reserva->orden;
				$confirmar = $reserva->orden;
				$valorar = $reserva->ID;

				$xitems = $wpdb->get_results( "SELECT meta_key, meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = ".$_metas_reserva["_booking_order_item_id"][0] );
				$items = array();
				foreach ($xitems as $item) {
					$items[ $item->meta_key ] = $item->meta_value;
				}

				$pago = unserialize($items["_wc_deposit_meta"]);

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

				//reservaS CONFIRMADAS
				if($reserva->status=='confirmed' && (strtotime($_metas_reserva['_booking_end'][0])>time())){

					$acciones = array();
					if( strtotime( $_metas_reserva['_booking_start'][0] ) <= time() && strtotime( $_metas_reserva['_booking_start'][0] ) >= strtotime("2017-12-23 00:00:00") ){
						$acciones = array(
							"ver" => "../../reservas/ver/".$ver,
							"subir_fotos" => $reserva->ID
						);
					}else{
						$acciones = array(
							"ver" => $ver
						);
					}

					$reservas_array["confirmadas"]["reservas"][] = array(
						'id' => $reserva->ID, 
						'cliente' => $_Cliente, 
						'servicio_id' => $servicio->ID, 
						'servicio' => $servicio->post_title, 
						'inicio' => date('d/m/Y', $inicio), 
						'fin' => date('d/m/Y', $fin), 
						'foto' => $foto,
						'acciones' => $acciones,
						"desglose" => $desglose,
						"desplegado" => true
					);

				//reservaS COMPLETADAS
				}else if($reserva->status=='complete' || ($reserva->status=='confirmed' && strtotime($_metas_reserva['_booking_end'][0])<=time())){
				//reservaS CANCELADAS
				}else if( $reserva->status=='cancelled' || $reserva->post_status=='wc_cancelled' ){
				}else if( $reserva->status == 'modified' ){
					//reservaS MODIFICADAS
				}else {
					//reservaS PENDIENTES
				}
			}

			//BUILD TABLE
			$CONTENIDO .= '<h1 style="margin: 0px; padding: 0px;">Mis Reservas Activas</h1><hr style="margin: 5px 0px 10px;">'.construir_listado($reservas_array);
			
		}else{
			$CONTENIDO .= "<h1 style='line-height: normal;' class='vlz_no_aun'>Usted no tiene reservas activas</h1>";
		}

	$CONTENIDO .= '</div>';
?>