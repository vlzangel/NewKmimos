<?php
	// Modificacion Ángel Veloz
	session_start();

	if( isset($data) ){
		$param = explode("_", $data);

		$data = $db->get_row("SELECT * FROM wp_posts WHERE md5(ID) = '{$param[2]}'");


		$metas_reserva = $db->get_results("SELECT * FROM wp_postmeta WHERE md5(post_id) = '{$param[0]}'"); 
		$id_reserva = $metas_reserva[0]->post_id;
		$metas_reservas = array();
		foreach ($metas_reserva as $key => $value) { $metas_reservas[ $value->meta_key ] = $value->meta_value; }
		
		$orden_id = $db->get_var("SELECT post_parent FROM wp_posts WHERE ID = '{$id_reserva}'", "post_parent"); 

		$order_status = $db->get_var("SELECT post_status FROM wp_posts WHERE ID = '{$orden_id}'", "post_status");

		$m_orden = $db->get_results("SELECT * FROM wp_postmeta WHERE post_id = '{$orden_id}'"); 
		$metas_orden = array();
		foreach ($m_orden as $key => $value) { $metas_orden[ $value->meta_key ] = $value->meta_value; }
		
		$descuento = 0;
		if( isset( $metas_orden[ "_cart_discount" ] ) ){
			$descuento = $metas_orden[ "_cart_discount" ];
		}

		$r3 = $db->get_results("SELECT * FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '{$metas_reservas['_booking_order_item_id']}'"); 
		if( count($r3) > 1 ){
			$items = array();
			foreach ($r3 as $key => $value) { $items[ $value->meta_key ] = $value->meta_value; }
		}

		if( $order_status == 'wc-on-hold' && $metas_orden['_payment_method'] == 'openpay_stores'){ }else{
			$deposito = unserialize( $items['_wc_deposit_meta'] );
			$saldo = 0;
			if( $deposito['enable'] == 'yes' ){
				$saldo = $deposito['deposit'];
			}else{
				$saldo = $items['_line_total'];
			}
		}

		$variaciones = unserialize( $metas_reservas['_booking_persons'] );

		$fechas = array(
			"inicio" => date('d-m-Y', strtotime( $metas_reservas['_booking_start'] ) ),
			"fin" 	 => date('d-m-Y', strtotime( $metas_reservas['_booking_end']   ) )
		);

		$trans = array(
            "transp-sencillo-rutas-cortas" => 'Transp. Sencillo - Rutas Cortas',
            "transp-sencillo-rutas-medias" => 'Transp. Sencillo - Rutas Medias',
            "transp-sencillo-rutas-largas" => 'Transp. Sencillo - Rutas Largas',
            "transp-redondo-rutas-cortas" => 'Transp. Redondo - Rutas Cortas',
            "transp-redondo-rutas-medias" => 'Transp. Redondo - Rutas Medias',
            "transp-redondo-rutas-largas" => 'Transp. Redondo - Rutas Largas'
        );

		$adic = array(
            "bano" => 'Baño (precio por mascota)',
            "corte" => 'Corte de Pelo y Uñas (precio por mascota)',
            "visita" => 'Visita al Veterinario (precio por mascota)',
            "limpieza" => 'Limpieza Dental (precio por mascota)',
            "acupuntura" => 'Acupuntura (precio por mascota)'
        );

		$transporte = array();
		$adicionales = array();

		if( count($r3) > 1 ){
			foreach ($items as $key => $value) {
				$retorno = array_search(utf8_encode($value), $trans);

				if( $retorno ){
					$transporte[] = $retorno;
				}
				$retorno = array_search(utf8_encode($value), $adic);
				if( $retorno ){
					$adicionales[] = $retorno;
				}
			}
		}

		$sql = "SELECT meta_value FROM wp_usermeta WHERE md5(user_id) = '{$param[1]}' AND meta_key = 'kmisaldo'";
		$kmisaldo = $db->get_var($sql, "meta_value");

		$parametros = array( 
			"reserva"         => $id_reserva,
			"servicio"        => $data->ID,
			"saldo"	          => $saldo+$descuento+$kmisaldo,
			"saldo_temporal"  => $saldo+$descuento,
			"variaciones"     => $variaciones,
			"fechas"          => $fechas,
			"transporte"      => $transporte,
			"adicionales"     => $adicionales
		);

		$_SESSION["MR_".$param[1]] = $parametros;

		$respuesta = array(
			"url" => "producto/".$data->post_name."/",
			"prm" => $parametros			
		);
	}else{
		extract($_GET);

		if( isset($b) ){
			include("../../../../../vlz_config.php");
			$conn = new mysqli($host, $user, $pass, $db);

			$home = $conn->query("SELECT option_value AS server FROM wp_options WHERE option_name = 'siteurl'"); 
			$home = $home->fetch_assoc();
			foreach ($_SESSION as $key => $value) {
				if(	substr($key, 0, 3) == "MR_" ){
					unset($_SESSION[$key]);
				}
			}

			header("location: ".$home['server']."/perfil-usuario/historial/");
		}

	}

?>